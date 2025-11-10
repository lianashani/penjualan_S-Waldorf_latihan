@extends('member.layout')
@section('title','Chat dengan Kasir')
@push('styles')
<style>
.chat-container{max-width:1000px;margin:2rem auto;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,0.1);height:calc(100vh - 200px);display:flex;flex-direction:column}
.chat-header{background:#000;color:#fff;padding:1.5rem;display:flex;align-items:center;gap:1rem}
.chat-header-icon{width:50px;height:50px;background:rgba(255,255,255,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.5rem}
.chat-header-info h3{margin:0;font-size:1.25rem;font-weight:600}
.chat-header-info p{margin:0;font-size:0.875rem;opacity:0.8}
.chat-messages{flex:1;overflow-y:auto;padding:2rem;background:#f7f7f7}
.message-wrapper{margin-bottom:1.5rem;display:flex}
.message-wrapper.member{justify-content:flex-end}
.message-wrapper.staff{justify-content:flex-start}
.message-bubble{max-width:70%;padding:1rem 1.25rem;border-radius:12px;position:relative}
.message-wrapper.member .message-bubble{background:#000;color:#fff;border-bottom-right-radius:4px}
.message-wrapper.staff .message-bubble{background:#fff;color:#000;border:1px solid #e8e8e8;border-bottom-left-radius:4px}
.message-sender{font-size:0.75rem;font-weight:600;margin-bottom:0.25rem}
.message-wrapper.member .message-sender{color:rgba(255,255,255,0.7)}
.message-wrapper.staff .message-sender{color:#767676}
.message-text{margin:0;line-height:1.5}
.message-time{font-size:0.688rem;margin-top:0.5rem;opacity:0.7}
.chat-input-container{padding:1.5rem;background:#fff;border-top:1px solid #e8e8e8}
.chat-input-form{display:flex;gap:1rem}
.chat-input{flex:1;padding:0.875rem 1.25rem;border:1px solid #e8e8e8;border-radius:24px;font-size:0.938rem;transition:all 0.3s}
.chat-input:focus{outline:none;border-color:#000;box-shadow:0 0 0 3px rgba(0,0,0,0.1)}
.chat-send-btn{background:#000;color:#fff;border:none;padding:0.875rem 2rem;border-radius:24px;font-weight:600;cursor:pointer;transition:all 0.3s;display:flex;align-items:center;gap:0.5rem}
.chat-send-btn:hover{background:#333;transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,0.3)}
.chat-send-btn:disabled{background:#d1d5db;cursor:not-allowed;transform:none}
.empty-chat{text-align:center;padding:3rem 2rem;color:#767676}
.empty-chat i{font-size:4rem;margin-bottom:1rem;opacity:0.3}
.typing-indicator{display:none;padding:1rem;color:#767676;font-size:0.875rem;font-style:italic}
</style>
@endpush

@section('content')
<div class="chat-container">
    <div class="chat-header">
        <div class="chat-header-icon">
            <i class="mdi mdi-headset"></i>
        </div>
        <div class="chat-header-info">
            <h3>Customer Service</h3>
            <p>Tim S&Waldorf siap membantu Anda</p>
        </div>
    </div>

    <div class="chat-messages" id="chatMessages">
        @forelse($chats as $chat)
        <div class="message-wrapper {{ $chat->sender_type }}">
            <div class="message-bubble">
                <div class="message-sender">
                    @if($chat->sender_type === 'member')
                        Anda
                    @else
                        {{ $chat->user->name ?? 'Kasir' }}
                    @endif
                </div>
                <p class="message-text">{{ $chat->message }}</p>
                <div class="message-time">{{ $chat->created_at->format('H:i') }}</div>
            </div>
        </div>
        @empty
        <div class="empty-chat">
            <i class="mdi mdi-message-text-outline"></i>
            <h5>Belum ada percakapan</h5>
            <p>Mulai chat dengan mengirim pesan di bawah</p>
        </div>
        @endforelse
    </div>

    <div class="typing-indicator" id="typingIndicator">
        Kasir sedang mengetik...
    </div>

    <div class="chat-input-container">
        <form class="chat-input-form" id="chatForm" onsubmit="sendMessage(event)">
            @csrf
            <input type="text" class="chat-input" id="messageInput" name="message" placeholder="Ketik pesan Anda..." required autocomplete="off">
            <button type="submit" class="chat-send-btn" id="sendBtn">
                <i class="mdi mdi-send"></i> Kirim
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let isLoading = false;

function scrollToBottom() {
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function sendMessage(event) {
    event.preventDefault();

    if (isLoading) return;

    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();

    if (!message) return;

    isLoading = true;
    const sendBtn = document.getElementById('sendBtn');
    sendBtn.disabled = true;
    sendBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Mengirim...';

    fetch('{{ route("member.chat.send") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ message: message })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            messageInput.value = '';
            loadMessages();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal mengirim pesan. Silakan coba lagi.');
    })
    .finally(() => {
        isLoading = false;
        sendBtn.disabled = false;
        sendBtn.innerHTML = '<i class="mdi mdi-send"></i> Kirim';
    });
}

function loadMessages() {
    fetch('{{ route("member.chat.messages") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const chatMessages = document.getElementById('chatMessages');
                chatMessages.innerHTML = '';

                if (data.chats.length === 0) {
                    chatMessages.innerHTML = `
                        <div class="empty-chat">
                            <i class="mdi mdi-message-text-outline"></i>
                            <h5>Belum ada percakapan</h5>
                            <p>Mulai chat dengan mengirim pesan di bawah</p>
                        </div>
                    `;
                } else {
                    data.chats.forEach(chat => {
                        const time = new Date(chat.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                        const sender = chat.sender_type === 'member' ? 'Anda' : (chat.user ? chat.user.name : 'Kasir');

                        const messageHTML = `
                            <div class="message-wrapper ${chat.sender_type}">
                                <div class="message-bubble">
                                    <div class="message-sender">${sender}</div>
                                    <p class="message-text">${chat.message}</p>
                                    <div class="message-time">${time}</div>
                                </div>
                            </div>
                        `;
                        chatMessages.insertAdjacentHTML('beforeend', messageHTML);
                    });
                }

                scrollToBottom();
            }
        })
        .catch(error => console.error('Error loading messages:', error));
}

// Auto-refresh messages every 5 seconds
setInterval(loadMessages, 5000);

// Scroll to bottom on page load
document.addEventListener('DOMContentLoaded', () => {
    scrollToBottom();
});
</script>
@endpush
