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
     */
    public function index()
    {
        $currentUser = Auth::user();

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

        return view('message', ['conversations' => $conversations]);
    }

    /**
     * Fetch the full message history for a specific conversation.
     * This will be called via AJAX (JavaScript).
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
                      ->where('ReceiverID', 'partner->UserID');
            })->orWhere(function ($query) use ($currentUser, $partner) {
                $query->where('SenderID', $partner->UserID)
                      ->where('ReceiverID', $currentUser->UserID);
            })->orderBy('CreatedAt', 'asc')->get();

        return response()->json([
            'partner' => $partner,
            'messages' => $messages
        ]);
    }

    public function store(Request $request, User $user)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

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