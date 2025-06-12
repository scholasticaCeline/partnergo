<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $messages = Message::where('ReceiverID', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('messages.index', compact('messages'));
    }

    public function show($id)
    {
        $message = Message::findOrFail($id);
        
        // Check if user has access to this message
        $user = Auth::user();
        if ($message->ReceiverID !== $user->id && $message->SenderID !== $user->id) {
            return redirect()->route('messages.index')->with('error', 'You do not have access to this message.');
        }
        
        // Mark as read if user is the receiver
        if ($message->receiver_id === $user->id && !$message->read_at) {
            $message->read_at = now();
            $message->save();
        }
        
        return view('messages.show', compact('message'));
    }
}
