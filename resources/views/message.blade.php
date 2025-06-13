<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chats</title>
    <link href="{{ asset('css/message.css') }}" rel="stylesheet">
</head>
<body>
    <div class="chat-container" id="chat-container" data-auth-id="{{ Auth::id() }}" data-csrf-token="{{ csrf_token() }}">
    
    <aside class="chat-sidebar">
        <div class="sidebar-header">
            <a href="{{ route('user.home') }}" class="back-link">&lt; Back to Dashboard</a>
            <h2 style="margin-top: 1rem;">Chat</h2>
        </div>
        <div class="sidebar-search">
            <input type="text" placeholder="Search chats...">
        </div>
        <div class="conversations-list">
            @forelse ($conversations as $convo)
                @php
                    $isSender = $convo->SenderID === Auth::id();
                    $partnerId = $isSender ? $convo->receiver_user_id : $convo->sender_user_id;
                    $partnerName = $isSender ? $convo->receiver_name : $convo->sender_name;
                @endphp
                <div class="conversation-item" data-partner-id="{{ $partnerId }}" data-partner-name="{{ $partnerName }}">
                    <div class="avatar">{{ strtoupper(substr($partnerName, 0, 1)) }}</div>
                    <div class="convo-details">
                        <h4>{{ $partnerName }}</h4>
                        <p>{{ Str::limit($convo->Content, 25) }}</p>
                    </div>
                </div>
            @empty
                <p style="padding: 1.5rem; color: var(--text-light);">You have no active conversations.</p>
            @endforelse
        </div>
    </aside>

    <div class="chat-main-content" id="chat-main-content-area">
        <main class="chat-panel">
            <div id="chat-welcome-screen" style="display: flex; flex-direction:column; align-items:center; justify-content:center; height:100%; text-align:center; color: var(--text-light);">
                <h2 style="color: var(--text-dark);">Welcome to Your Inbox</h2>
                <p>Select a conversation from the left to start chatting.</p>
            </div>
            <div id="chat-view" style="display:none; height:100%; flex-direction:column;">
                <div class="chat-header" id="chat-header-clickable" title="Click to see details">
                    <div class="avatar" id="chat-header-avatar"></div>
                    <h3 id="chat-header-name" style="margin:0; font-size: 1.2rem; color: var(--text-dark);"></h3>
                </div>
                <div class="chat-messages" id="chat-messages-container"></div>
                <div class="message-input-area">
                    <form id="messageForm" method="POST" action="">
                        @csrf
                        <input type="text" name="content" placeholder="Type your message..." autocomplete="off">
                        <button type="submit" aria-label="Send">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3.4 20.4L20.85 12.02L3.4 3.6V10.1L15.35 12.02L3.4 13.95V20.4Z" fill="var(--primary-color)" /></svg>
                        </button>
                    </form>
                </div>
            </div>
        </main>
    
        <aside class="info-panel" id="info-panel">
            <div class="avatar" id="info-panel-avatar"></div>
            <h3 id="info-panel-name"></h3>
            <p id="info-panel-details">PartnerGo User</p>
            <div class="info-section">
                <h4>Shared Files</h4>
                <div class="placeholder"></div>
            </div>
        </aside>
    </div>
</div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Get variables passed from Blade
    const chatContainer = document.getElementById('chat-container');
    if (!chatContainer) return; // Don't run script if not on the chat page

    const authId = chatContainer.dataset.authId;
    const csrfToken = chatContainer.dataset.csrfToken;

    // Get all other elements
    const chatMainContentArea = document.getElementById('chat-main-content-area');
    const chatWelcomeScreen = document.getElementById('chat-welcome-screen');
    const chatView = document.getElementById('chat-view');
    const infoPanel = document.getElementById('info-panel');
    const conversationItems = document.querySelectorAll('.conversation-item');
    const chatMessagesContainer = document.getElementById('chat-messages-container');
    const messageForm = document.getElementById('messageForm');
    const chatHeaderClickable = document.getElementById('chat-header-clickable');
    let currentPartnerId = null;

    // Logic to toggle the info panel
    chatHeaderClickable.addEventListener('click', () => {
        infoPanel.classList.toggle('visible');
        chatMainContentArea.classList.toggle('info-visible');
    });

    // Handle clicking a conversation from the list
    conversationItems.forEach(item => {
        item.addEventListener('click', function() {
            // UI updates
            chatWelcomeScreen.style.display = 'none';
            chatView.style.display = 'flex';
            infoPanel.classList.remove('visible');
            chatMainContentArea.classList.remove('info-visible');
            conversationItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            
            // Get partner data from the clicked element
            const partnerId = this.dataset.partnerId;
            const partnerName = this.dataset.partnerName;
            currentPartnerId = partnerId;

            // Update UI with partner info
            document.getElementById('chat-header-name').textContent = partnerName;
            document.getElementById('chat-header-avatar').textContent = partnerName.charAt(0).toUpperCase();
            document.getElementById('info-panel-name').textContent = partnerName;
            document.getElementById('info-panel-avatar').textContent = partnerName.charAt(0).toUpperCase();
            
            // Fetch and display this conversation's messages
            fetch(`/message/${partnerId}`)
                .then(response => response.json())
                .then(data => {
                    chatMessagesContainer.innerHTML = '';
                    data.messages.forEach(message => appendMessage(message));
                    scrollToBottom();
                });
            
            messageForm.action = `/message/${partnerId}`;
        });
    });

    // Handle sending a new message
    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const contentInput = this.querySelector('input[name="content"]');
        const content = contentInput.value.trim();
        if (content === '' || !this.action) return;

        fetch(this.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken // Use variable from data attribute
            },
            body: JSON.stringify({ content: content })
        })
        .then(response => response.json())
        .then(newMessage => {
            appendMessage(newMessage);
            contentInput.value = '';
            scrollToBottom();
        });
    });

    function appendMessage(message) {
        const bubble = document.createElement('div');
        bubble.classList.add('message-bubble');
        bubble.classList.add(message.SenderID === authId ? 'sent' : 'received'); // Use variable
        bubble.textContent = message.Content;
        chatMessagesContainer.appendChild(bubble);
    }

    function scrollToBottom() {
        chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
    }

    // Automatically open a chat if a `?user=` URL parameter exists
    const urlParams = new URLSearchParams(window.location.search);
    const userToOpen = urlParams.get('user');
    if (userToOpen) {
        const convoItem = document.querySelector(`.conversation-item[data-partner-id="${userToOpen}"]`);
        if (convoItem) {
            convoItem.click();
        }
    }
});
</script>
@endpush    

</body>
</html>