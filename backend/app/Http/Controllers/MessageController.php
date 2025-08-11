<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MessageController extends Controller
{
    public function store(Request $request): Response
    {
        $request->validate([
            'user' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        $message = Message::create([
            'user' => $request->string('user'),
            'message' => $request->string('message'),
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->noContent();
    }
}