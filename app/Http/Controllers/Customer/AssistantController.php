<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Services\ChatBotService;
use Illuminate\Http\Request;

class AssistantController extends Controller
{
    public function __construct(private ChatBotService $chatBotService) {}

    public function index()
    {
        $messages = Message::where('user_id', auth()->id())->latest('id')->take(50)->get()->reverse()->values();
        $quickReplies = $this->chatBotService->getQuickReplies();

        return view('customer.assistant.index', compact('messages', 'quickReplies'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(['content' => 'required|string|max:500']);

        // Lưu tin nhắn user
        $userMessage = Message::create([
            'user_id' => auth()->id(),
            'sender' => 'user',
            'content' => $data['content'],
            'sent_at' => now(),
            'is_read' => true,
        ]);

        // Xử lý với chatbot AI (FAQ-based)
        $aiResponse = $this->chatBotService->getResponse($data['content']);

        // Lưu phản hồi AI
        Message::create([
            'user_id' => auth()->id(),
            'sender' => 'ai',
            'content' => $aiResponse['content'],
            'sent_at' => now(),
            'is_read' => true,
        ]);

        return back()->with('success', 'Tin nhắn đã được gửi.');
    }
}
