<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    /**
     * Display the main messaging page.
     * CORRECTED to handle starting new conversations from a URL parameter.
     */
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $userToOpenId = $request->query('user'); // Get the user ID from the ?user=... parameter

        // This complex query gets the latest message for each existing conversation.
        $subQuery = Message::select('SenderID', 'ReceiverID', DB::raw('MAX(CreatedAt) as last_message_at'))
            ->where('SenderID', $currentUser->UserID)
            ->orWhere('ReceiverID', $currentUser->UserID)
            ->groupBy('SenderID', 'ReceiverID');

        $conversations = DB::table(DB::raw("({$subQuery->toSql()}) as latest_messages"))
            ->mergeBindings($subQuery->getQuery())
            ->join('messages as m2', function ($join) {
                $join->on('latest_messages.SenderID', '=', 'm2.SenderID')
                    ->on('latest_messages.ReceiverID', '=', 'm2.ReceiverID')
                    ->on('latest_messages.last_message_at', '=', 'm2.CreatedAt');
            })
            ->join('users as u_sender', 'm2.SenderID', '=', 'u_sender.UserID')
            ->join('users as u_receiver', 'm2.ReceiverID', '=', 'u_receiver.UserID')
            ->select('m2.Content', 'm2.CreatedAt', 'm2.SenderID', 'm2.ReceiverID',
                    'u_sender.name as sender_name', 'u_sender.UserID as sender_user_id',
                    'u_receiver.name as receiver_name', 'u_receiver.UserID as receiver_user_id')
            ->orderBy('m2.CreatedAt', 'desc')
            ->get()
            ->unique(function ($item) use ($currentUser) {
                return $item->SenderID === $currentUser->UserID ? $item->ReceiverID : $item->SenderID;
            });

        // --- NEW LOGIC STARTS HERE ---
        // If a user ID was passed in the URL and that conversation doesn't already exist...
        if ($userToOpenId && !$conversations->contains(fn($c) => $c->sender_user_id == $userToOpenId || $c->receiver_user_id == $userToOpenId)) {
            $partner = User::find($userToOpenId);
            if ($partner) {
                // Create a "placeholder" conversation object to add to the top of the list
                $placeholderConvo = (object)[
                    'Content' => 'Start a new conversation...',
                    'CreatedAt' => now(),
                    'SenderID' => $currentUser->UserID,
                    'ReceiverID' => $partner->UserID,
                    'sender_name' => $currentUser->name,
                    'sender_user_id' => $currentUser->UserID,
                    'receiver_name' => $partner->name,
                    'receiver_user_id' => $partner->UserID,
                ];
                $conversations->prepend($placeholderConvo);
            }
        }

        return view('messages.index', [
            'conversations' => $conversations
        ]);
    }

    /**
     * Start a new conversation from a proposal. This logic is correct.
     */
    public function startFromUser(User $userToChatWith)
    {
        if ($userToChatWith->UserID === Auth::id()) {
            return redirect()->route('message')->with('error', "You cannot start a conversation with yourself.");
        }
        return redirect()->route('message', ['user' => $userToChatWith->UserID]);
    }


    /**
     * Fetch the full message history for a specific conversation.
     */
    public function show(User $user)
    {
        $currentUser = Auth::user();
        $partner = $user;

        Message::where('SenderID', $partner->UserID)
            ->where('ReceiverID', $currentUser->UserID)
            ->whereNull('ReadAt')
            ->update(['ReadAt' => now()]);

        $messages = Message::where(function ($query) use ($currentUser, $partner) {
                $query->where('SenderID', $currentUser->UserID)
                    ->where('ReceiverID', $partner->UserID); // Corrected a typo here
            })->orWhere(function ($query) use ($currentUser, $partner) {
                $query->where('SenderID', $partner->UserID)
                    ->where('ReceiverID', $currentUser->UserID);
            })->orderBy('CreatedAt', 'asc')->get();

        return response()->json([
            'partner' => $partner,
            'messages' => $messages
        ]);
    }

    /**
     * Store a new message sent from the chat window.
     */
    public function store(Request $request, User $user)
    {
        $validated = $request->validate(['content' => 'required|string|max:2000']);

        $message = new Message();
        $message->MessageID = Str::uuid();
        $message->SenderID = Auth::id();
        $message->ReceiverID = $user->UserID;
        $message->Content = $validated['content'];
        $message->CreatedAt = now();
        $message->save();

        return response()->json($message);
    }
}