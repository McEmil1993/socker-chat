<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use App\Models\User;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('index');
    }

    public function index()
    {
        $messages = Message::select('messages.*', 'users.name as user_name','users.image_name')
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
        
        return redirect()->route('home');
    }

    public function upload(Request $request)
    {
        $currentDateTime = now();
        $formattedDateTime = $currentDateTime->format('Ymd_His');
        $filename = $formattedDateTime . '_' . Str::random(10) . '.jpg';

        $imageData = $request->input('image');

        $imagePath = 'dist/img/'.$filename;

        file_put_contents(public_path($imagePath), base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData)));

        // Auth::user()->id
        $user = User::find(Auth::user()->id); 
        $user->image_name = $filename;
        $user->save();
        return response()->json(['message' => 'Image cropped and saved']);
    }
}
