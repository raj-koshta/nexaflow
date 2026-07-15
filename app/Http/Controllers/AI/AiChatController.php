<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use App\Models\AiConversation;
use App\Models\AiMessage;
use App\Services\AI\AiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\RealtimeMessageSent;

class AiChatController extends Controller
{
    protected $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index(Request $request, $id = null)
    {
        $conversations = AiConversation::where('user_id', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->get();

        $activeConversation = null;
        if ($id) {
            $activeConversation = AiConversation::where('user_id', Auth::id())
                ->with('messages')
                ->findOrFail($id);
        }

        return view('ai.chat.index', compact('conversations', 'activeConversation'));
    }

    public function storeMessage(Request $request, $id = null)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $conversation = null;

        if ($id) {
            $conversation = AiConversation::where('user_id', Auth::id())->findOrFail($id);
        } else {
            // Create a new conversation
            // Extract the first few words as a title
            $title = substr($request->message, 0, 40) . (strlen($request->message) > 40 ? '...' : '');
            $conversation = AiConversation::create([
                'user_id' => Auth::id(),
                'title' => $title
            ]);
        }

        // Save User Message
        $userMessage = $conversation->messages()->create([
            'role' => 'user',
            'content' => $request->message
        ]);

        $conversation->touch(); // Update timestamp

        // Get AI Response
        $aiResponseText = $this->aiService->generateResponse($request->message, 'AI Chat');
        $htmlResponse = \Illuminate\Support\Str::markdown($aiResponseText, ['html_input' => 'escape']);

        // Save AI Message
        $aiMessage = $conversation->messages()->create([
            'role' => 'assistant',
            'content' => $aiResponseText
        ]);

        // Broadcast to WebSockets
        broadcast(new RealtimeMessageSent($conversation->id, $htmlResponse))->toOthers();

        return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id,
            'user_message' => $userMessage,
            'ai_message' => $aiMessage,
            'ai_message_html' => $htmlResponse,
            'is_new' => !$id
        ]);
    }
    
    public function destroy($id)
    {
        $conversation = AiConversation::where('user_id', Auth::id())->findOrFail($id);
        $conversation->delete();
        
        return redirect()->route('ai.chat.index');
    }
}
