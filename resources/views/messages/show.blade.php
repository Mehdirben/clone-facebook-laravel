@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary btn-sm me-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div class="d-flex align-items-center">
                            <img src="{{ $user->profile && $user->profile->profile_picture 
                                                                ? asset('storage/' . $user->profile->profile_picture)                                 : asset('images/default-avatar.svg') }}"  
                                class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;" alt="Profile picture">
                            <h3 class="mb-0">{{ $user->name }}</h3>
                        </div>
                    </div>
                    <a href="{{ route('profile.show', $user) }}" class="btn btn-outline-primary btn-sm">
                        Voir le profil
                    </a>
                </div>
                <div class="card-body" style="height: 400px; overflow-y: auto;" id="chat-messages">
                    @if($messages->count() > 0)
                        <div class="messages">
                            @foreach($messages as $message)
                                <div class="message-item mb-3 {{ $message->user_id === Auth::id() ? 'text-end' : 'text-start' }}">
                                    <div class="message-content d-inline-block {{ $message->user_id === Auth::id() ? 'bg-primary text-white' : 'bg-light' }} p-2 px-3 rounded" style="max-width: 75%;">
                                        @if($message->attachment)
                                            <div class="message-attachment mb-2">
                                                @php
                                                    $extension = pathinfo($message->attachment, PATHINFO_EXTENSION);
                                                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                                @endphp
                                                
                                                @if($isImage)
                                                    <img src="{{ asset('storage/' . $message->attachment) }}" class="img-fluid rounded" style="max-height: 200px;" alt="Attachment">
                                                @else
                                                    <a href="{{ asset('storage/' . $message->attachment) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                                                        <i class="fas fa-file me-1"></i> Télécharger le fichier
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                        <p class="mb-0">{{ $message->content }}</p>
                                        <small class="text-muted d-block mt-1" style="font-size: 0.7rem;">
                                            {{ $message->created_at->format('H:i') }}
                                            @if($message->is_read && $message->user_id === Auth::id())
                                                <i class="fas fa-check-double ms-1"></i>
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center my-5">
                            <p>Aucun message. Commencez la conversation !</p>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <form action="{{ route('messages.send', $user) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="content" class="form-control" placeholder="Votre message..." required>
                            <label class="input-group-text" for="attachment">
                                <i class="fas fa-paperclip"></i>
                            </label>
                            <input type="file" name="attachment" id="attachment" class="d-none">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Scroll to bottom of chat
        const chatMessages = document.getElementById('chat-messages');
        chatMessages.scrollTop = chatMessages.scrollHeight;
        
        // Display file name when selected
        const attachmentInput = document.getElementById('attachment');
        attachmentInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                alert('Fichier sélectionné : ' + this.files[0].name);
            }
        });
    });
</script>
@endpush
@endsection 