<?php

namespace App\Http\Controllers;

use App\Events\Chat\GreetingSent;
use App\Events\Chat\MessageSent;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        return view('chat.show');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required',
        ]);
        broadcast(new MessageSent(auth()->user(), $validated['message']));
        return response()->noContent();
    }

    public function sendGreeting(Request $request, User $user)
    {
        broadcast(new GreetingSent($user, auth()->user()->name . " te ha saludado!!"));
        broadcast(new GreetingSent(auth()->user(), "Has saludado a {$user->name}"));

        return;
    }
}
