@extends('layouts.master')

@section('title', 'AI Chat')

@push('custom-css')
<style>
    .chat-layout {
        display: flex;
        height: calc(100vh - 130px);
        background: var(--card-bg);
        border: var(--glass-border);
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .chat-sidebar {
        width: 260px;
        background: var(--card-bg);
        border-right: 1px solid var(--border-color);
        display: flex;
        flex-direction: column;
    }
    
    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        position: relative;
    }
    
    .chat-history {
        flex: 1;
        overflow-y: auto;
        padding: 1rem;
    }
    
    .chat-history-item {
        padding: 0.75rem 1rem;
        color: var(--text-main);
        text-decoration: none;
        display: flex;
        align-items: center;
        border-radius: 8px;
        margin-bottom: 0.25rem;
        transition: background 0.2s;
    }
    
    .chat-history-item:hover {
        background: var(--secondary-bg);
        color: var(--text-main);
    }
    
    .chat-history-item.active {
        background: var(--primary-bg);
        font-weight: 600;
    }
    
    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 2rem;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .chat-bubble-container {
        display: flex;
        gap: 1rem;
        max-width: 800px;
        margin: 0 auto;
        width: 100%;
    }
    
    .chat-bubble-container.user {
        flex-direction: row-reverse;
    }
    
    .chat-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        flex-shrink: 0;
    }
    
    .chat-avatar.user {
        background: var(--accent);
        color: white;
    }
    
    .chat-avatar.ai {
        background: var(--accent);
        background: linear-gradient(135deg, var(--accent) 0%, #a855f7 100%);
        color: white;
    }
    
    .chat-bubble {
        padding: 1rem 1.25rem;
        border-radius: 12px;
        line-height: 1.6;
    }
    
    .chat-bubble-container.user .chat-bubble {
        background: var(--secondary-bg);
        border: none;
        color: var(--text-main);
        border-radius: 18px;
        border-bottom-right-radius: 4px;
        padding: 0.75rem 1.25rem;
    }
    
    .chat-bubble-container.ai .chat-bubble {
        background: transparent;
        color: var(--text-main);
        padding: 0.5rem 0;
        font-size: 1.05rem;
    }

    .markdown-body p:last-child {
        margin-bottom: 0;
    }
    
    .markdown-body ul, .markdown-body ol {
        padding-left: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .markdown-body li {
        margin-bottom: 0.25rem;
    }
    
    .markdown-body pre {
        background: var(--secondary-bg);
        padding: 1rem;
        border-radius: 8px;
        overflow-x: auto;
    }
    
    .markdown-body code {
        background: var(--secondary-bg);
        padding: 0.2rem 0.4rem;
        border-radius: 4px;
        font-family: monospace;
    }
    
    .chat-input-area {
        padding: 1.5rem 2rem;
        background: transparent;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: center;
    }
    
    .chat-input-wrapper {
        max-width: 800px;
        width: 100%;
        position: relative;
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 24px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: flex-end;
        padding: 0.5rem 1rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    
    .chat-input-wrapper:focus-within {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
    }
    
    .chat-input {
        flex: 1;
        background: transparent;
        border: none;
        color: var(--text-main);
        padding: 0.5rem;
        resize: none;
        outline: none;
        max-height: 150px;
        line-height: 1.5;
    }
    
    .chat-send-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--accent);
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s, transform 0.1s;
        margin-left: 0.5rem;
        margin-bottom: 0.25rem;
        flex-shrink: 0;
    }
    
    .chat-send-btn:hover {
        background: #4f46e5;
    }
    
    .chat-send-btn:active {
        transform: scale(0.95);
    }

    .chat-send-btn:disabled {
        background: var(--border-color);
        cursor: not-allowed;
    }

    /* Typing indicator */
    .typing-indicator {
        display: flex;
        gap: 4px;
        padding: 8px;
    }
    
    .typing-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--text-muted);
        animation: typing 1.4s infinite ease-in-out both;
    }
    
    .typing-dot:nth-child(1) { animation-delay: -0.32s; }
    .typing-dot:nth-child(2) { animation-delay: -0.16s; }
    
    @keyframes typing {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1); }
    }
</style>
@endpush

@section('content')
<div class="chat-layout">
    <!-- Sidebar -->
    <div class="chat-sidebar">
        <div class="p-3 border-bottom border-secondary border-opacity-25">
            <a href="{{ route('ai.chat.index') }}" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center">
                <i class="bi bi-plus-lg me-2"></i> New Chat
            </a>
        </div>
        
        <div class="chat-history">
            <div class="text-uppercase text-muted fw-bold mb-2 ms-2" style="font-size: 0.7rem; letter-spacing: 1px;">Recent</div>
            @forelse($conversations as $conv)
                <div class="d-flex align-items-center position-relative">
                    <a href="{{ route('ai.chat.index', $conv->id) }}" class="chat-history-item w-100 {{ $activeConversation && $activeConversation->id === $conv->id ? 'active' : '' }}">
                        <i class="bi bi-chat-left-text me-3 text-muted"></i>
                        <span class="text-truncate" style="max-width: 160px;">{{ $conv->title }}</span>
                    </a>
                    
                    @if($activeConversation && $activeConversation->id === $conv->id)
                        <form id="deleteForm-{{ $conv->id }}" action="{{ route('ai.chat.destroy', $conv->id) }}" method="POST" class="position-absolute end-0 me-2">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmAction('Delete Chat', 'Are you sure you want to delete this conversation?', () => document.getElementById('deleteForm-{{ $conv->id }}').submit())" class="btn btn-sm btn-link text-danger p-1 shadow-none"><i class="bi bi-trash"></i></button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="text-muted small ms-2 fst-italic">No conversations yet.</div>
            @endforelse
        </div>
    </div>
    
    <!-- Main Chat Area -->
    <div class="chat-main">
        <div class="chat-messages" id="chatMessages">
            @if(!$activeConversation)
                <div class="d-flex flex-column align-items-center justify-content-center h-100 text-center text-muted">
                    <div class="mb-4" style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, var(--accent) 0%, #a855f7 100%); display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="bi bi-robot" style="font-size: 2.5rem;"></i>
                    </div>
                    <h3 class="fw-bold text-main mb-2">NexaFlow AI</h3>
                    <p class="mb-4" style="max-width: 400px;">I'm your intelligent assistant. I can help you draft emails, summarize tickets, generate task checklists, and query your CRM data.</p>
                    
                    <div class="d-flex flex-wrap gap-2 justify-content-center" style="max-width: 600px;">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-2 suggestion-btn">"Summarize my open tickets"</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-2 suggestion-btn">"Draft an apology email for a delay"</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-2 suggestion-btn">"Create a task checklist for website redesign"</button>
                    </div>
                </div>
            @else
                @foreach($activeConversation->messages as $msg)
                    <div class="chat-bubble-container {{ $msg->role }}">
                        <div class="chat-avatar {{ $msg->role }}">
                            @if($msg->role === 'user')
                                {{ substr(Auth::user()->name, 0, 1) }}
                            @else
                                <i class="bi bi-robot"></i>
                            @endif
                        </div>
                        <div class="chat-bubble shadow-sm {{ $msg->role === 'assistant' ? 'markdown-body' : '' }}">
                            @if($msg->role === 'assistant')
                                {!! \Illuminate\Support\Str::markdown($msg->content, ['html_input' => 'escape']) !!}
                            @else
                                {!! nl2br(e($msg->content)) !!}
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        
        <div class="chat-input-area">
            <form id="chatForm" class="chat-input-wrapper">
                <textarea id="chatInput" class="chat-input" rows="1" placeholder="Message NexaFlow AI..."></textarea>
                <button type="submit" id="sendBtn" class="chat-send-btn" disabled>
                    <i class="bi bi-send-fill fs-6"></i>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('custom-scripts')
<script>
$(document).ready(function() {
    const $chatInput = $('#chatInput');
    const $sendBtn = $('#sendBtn');
    const $chatMessages = $('#chatMessages');
    const conversationId = '{{ $activeConversation ? $activeConversation->id : "" }}';
    let isWaiting = false;

    // Auto-resize textarea
    $chatInput.off('input').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
        if(this.scrollHeight > 200) this.style.overflowY = 'auto';
        
        $sendBtn.prop('disabled', $(this).val().trim().length === 0 || isWaiting);
    });

    // Form submit to send
    $('#chatForm').off('submit').on('submit', function(e) {
        e.preventDefault();
        if(!$sendBtn.prop('disabled')) {
            sendMessage();
        }
    });

    // Enter to send
    $chatInput.off('keydown').on('keydown', function(e) {
        if(e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            $('#chatForm').submit();
        }
    });

    // Click suggestion
    $('.suggestion-btn').off('click').on('click', function(e) {
        e.preventDefault();
        if(isWaiting) return;
        let text = $(this).text().replace(/"/g, '');
        $chatInput.val(text).trigger('input');
        sendMessage();
    });

    function scrollToBottom() {
        $chatMessages.scrollTop($chatMessages[0].scrollHeight);
    }
    
    // Initial scroll
    scrollToBottom();

    function appendUserMessage(text) {
        const initialSate = `{{ !$activeConversation ? 'true' : 'false' }}`;
        if (initialSate === 'true' && $('.suggestion-btn').length > 0) {
            $chatMessages.empty(); // Clear welcome screen
        }

        const html = `
            <div class="chat-bubble-container user mb-4">
                <div class="chat-avatar user">{{ substr(Auth::user()->name, 0, 1) }}</div>
                <div class="chat-bubble shadow-sm">${text.replace(/\n/g, '<br>')}</div>
            </div>
        `;
        $chatMessages.append(html);
        scrollToBottom();
    }

    function appendTypingIndicator() {
        const html = `
            <div class="chat-bubble-container ai mb-4" id="typingIndicator">
                <div class="chat-avatar ai"><i class="bi bi-robot"></i></div>
                <div class="chat-bubble shadow-sm d-flex align-items-center">
                    <div class="typing-indicator">
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                    </div>
                </div>
            </div>
        `;
        $chatMessages.append(html);
        scrollToBottom();
    }

    function replaceTypingWithAiMessage(htmlContent) {
        $('#typingIndicator').remove();
        const html = `
            <div class="chat-bubble-container ai mb-4">
                <div class="chat-avatar ai"><i class="bi bi-robot"></i></div>
                <div class="chat-bubble shadow-sm markdown-body">${htmlContent}</div>
            </div>
        `;
        $chatMessages.append(html);
        scrollToBottom();
    }

    function sendMessage() {
        if(isWaiting) return;
        const message = $chatInput.val().trim();
        if(!message) return;

        isWaiting = true;
        $chatInput.val('').trigger('input');
        $chatInput.prop('disabled', true);
        
        appendUserMessage(message);
        appendTypingIndicator();

        const url = conversationId 
            ? '{{ route("ai.chat.store", "") }}/' + conversationId 
            : '{{ route("ai.chat.store") }}';

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                message: message
            },
            success: function(response) {
                replaceTypingWithAiMessage(response.ai_message_html || response.ai_message.content);
                isWaiting = false;
                $chatInput.prop('disabled', false).focus();
                
                // If this was a brand new conversation, redirect to its URL so the history updates
                if (response.is_new) {
                    window.location.href = '{{ url("ai/chat") }}/' + response.conversation_id;
                }
            },
            error: function() {
                replaceTypingWithAiMessage("<span class='text-danger'>Sorry, I encountered an error. Please try again.</span>");
                isWaiting = false;
                $chatInput.prop('disabled', false).focus();
            }
        });
    }
});
</script>
@endpush
