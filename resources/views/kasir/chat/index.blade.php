@extends('layouts.master')

@section('title', 'Chat dengan Member')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Chat dengan Member</h4>
                    </div>

                    @if(count($chatList) > 0)
                    <div class="list-group">
                        @foreach($chatList as $chat)
                        <a href="{{ route('kasir.chat.show', $chat['member']->id_member) }}"
                           class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0"><strong>{{ $chat['member']->name }}</strong></h6>
                                        <small class="text-muted">{{ $chat['last_message']->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">
                                            <i class="mdi mdi-email"></i> {{ $chat['member']->email }}
                                        </small>
                                        @if($chat['member']->phone)
                                        <small class="text-muted ms-3">
                                            <i class="mdi mdi-phone"></i> {{ $chat['member']->phone }}
                                        </small>
                                        @endif
                                    </div>
                                    <p class="mb-0 text-muted">
                                        <i class="mdi mdi-message-text-outline"></i> {{ Str::limit($chat['last_message']->message, 50) }}
                                    </p>
                                </div>
                                @if($chat['unread_count'] > 0)
                                <span class="badge bg-info ms-3">{{ $chat['unread_count'] }}</span>
                                @endif
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="mdi mdi-chat-outline" style="font-size: 64px; color: #ccc;"></i>
                        <p class="text-muted mt-3">Belum ada percakapan dengan member</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
