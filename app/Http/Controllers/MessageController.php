<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('index');
    }

    public function index()
    {
        $messages = Message::select('messages.*', 'users.name as user_name')
        ->join('users', 'users.id', '=', 'messages.user_id')
        ->orderBy('messages.created_at', 'asc')
        ->get();
        return view('socket-client', compact('messages'));
    }

    public function store(Request $request)
    {
        $message = $request->input('message');
        $user_id = Auth::id();
        Message::create([
            'user_id' => $user_id,
            'message' => $message,
        ]);
        
        // You can add Socket.IO logic here to emit the message to the server
        
        return redirect()->route('socket-io');
    }
}
