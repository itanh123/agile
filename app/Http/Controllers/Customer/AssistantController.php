<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class AssistantController extends Controller
{
    public function index()
    {
        $messages = Message::where('user_id', auth()->id())->latest('id')->take(50)->get()->reverse()->values();

        return view('customer.assistant.index', compact('messages'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(['content' => 'required|string|max:1000']);

        Message::create([
            'user_id' => auth()->id(),
            'sender' => 'user',
            'content' => $data['content'],
            'sent_at' => now(),
            'is_read' => true,
        ]);

        Message::create([
            'user_id' => auth()->id(),
            'sender' => 'ai',
            'content' => 'Thanks! AI integration placeholder response. We will support external API soon.',
            'sent_at' => now(),
            'is_read' => true,
        ]);

        return back()->with('success', 'Message sent.');
    }
}
