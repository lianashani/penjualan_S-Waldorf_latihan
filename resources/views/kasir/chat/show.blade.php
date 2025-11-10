@extends('layouts.master')

@section('title', 'Chat dengan ' . $member->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="background-color: #000; color: #fff; padding: 15px 20px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-2">
                                <a href="{{ route('kasir.chat.index') }}" class="text-white me-3">
                                    <i class="mdi mdi-arrow-left"></i>
                                </a>
                                <div>
                                    <h5 class="mb-0"><strong>{{ $member->name }}</strong></h5>
                                </div>
                            </div>
                            <div class="ms-5 ps-2" style="font-size: 13px; opacity: 0.9;">
                                <div class="mb-1">
                                    <i class="mdi mdi-email"></i> {{ $member->email }}
                                </div>
                                @if($member->phone)
                                <div class="mb-1">
                                    <i class="mdi mdi-phone"></i> {{ $member->phone }}
                                </div>
                                @endif
                                @if($member->address)
                                <div>
                                    <i class="mdi mdi-map-marker"></i> {{ Str::limit($member->address, 80) }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="height: 500px; overflow-y: auto; padding: 20px;" id="chatMessages">
                    @foreach($messages as $message)
                    <div class="mb-3 d-flex {{ $message->sender_type === 'member' ? 'justify-content-start' : 'justify-content-end' }}">
                        <div style="max-width: 70%;">
                            <div class="p-3 rounded" style="background-color: {{ $message->sender_type === 'member' ? '#f1f1f1' : '#000' }}; color: {{ $message->sender_type === 'member' ? '#000' : '#fff' }}; {{ $message->sender_type === 'staff' ? 'margin-left: auto;' : '' }}">
                                {{ $message->message }}
                            </div>
                            <small class="text-muted d-block mt-1 {{ $message->sender_type === 'member' ? '' : 'text-end' }}">
                                {{ $message->created_at->format('H:i') }}
                            </small>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="card-footer">
                    <form id="chatForm">
                        @csrf
                        <input type="hidden" name="id_member" value="{{ $member->id_member }}">
                        <div class="input-group">
                            <input type="text" name="message" id="messageInput" class="form-control" placeholder="Ketik pesan..." required>
                            <button type="submit" class="btn" style="background-color: #000; color: #fff;">
                                <i class="mdi mdi-send"></i> Kirim
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto refresh messages every 5 seconds
setInterval(function() {
    loadMessages();
}, 5000);

// Scroll to bottom on load
$(document).ready(function() {
    scrollToBottom();
    loadMessages();
});

// Handle form submit
$('#chatForm').on('submit', function(e) {
    e.preventDefault();

    var message = $('#messageInput').val();
    if (!message.trim()) return;

    $.ajax({
        url: '{{ route("kasir.chat.send") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            id_member: {{ $member->id_member }},
            message: message
        },
        success: function(response) {
            $('#messageInput').val('');
            loadMessages();
        },
        error: function(xhr) {
            console.error('Error:', xhr);
            alert('Gagal mengirim pesan: ' + (xhr.responseJSON?.message || xhr.statusText));
        }
    });
});

// Load messages
function loadMessages() {
    $.ajax({
        url: '{{ route("kasir.chat.messages", $member->id_member) }}',
        method: 'GET',
        success: function(response) {
            var html = '';
            response.messages.forEach(function(message) {
                var isMember = message.sender_type === 'member';
                var time = new Date(message.created_at).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'});

                html += '<div class="mb-3 d-flex ' + (isMember ? 'justify-content-start' : 'justify-content-end') + '">';
                html += '<div style="max-width: 70%;">';
                html += '<div class="p-3 rounded" style="background-color: ' + (isMember ? '#f1f1f1' : '#000') + '; color: ' + (isMember ? '#000' : '#fff') + '; ' + (!isMember ? 'margin-left: auto;' : '') + '">';
                html += message.message;
                html += '</div>';
                html += '<small class="text-muted d-block mt-1 ' + (!isMember ? 'text-end' : '') + '">' + time + '</small>';
                html += '</div></div>';
            });

            $('#chatMessages').html(html);
            scrollToBottom();
        },
        error: function(xhr) {
            console.error('Error loading messages:', xhr);
        }
    });
}

// Scroll to bottom
function scrollToBottom() {
    var chatMessages = document.getElementById('chatMessages');
    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
}
</script>
@endpush
