@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Mes notifications</h3>
                    @if($notifications->where('is_read', false)->count() > 0)
                                            <form action="{{ route('notifications.read.all') }}" method="POST">                            @csrf                            @method('PATCH')                            <button type="submit" class="btn btn-sm btn-outline-primary">                                Tout marquer comme lu                            </button>                        </form>
                    @endif
                </div>
                <div class="card-body">
                    @if($notifications->count() > 0)
                        <div class="list-group">
                            @foreach($notifications as $notification)
                                <div class="list-group-item {{ $notification->is_read ? '' : 'list-group-item-primary' }} d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $notification->fromUser->profile && $notification->fromUser->profile->profile_picture 
                                                                                        ? asset('storage/' . $notification->fromUser->profile->profile_picture)                                             : asset('images/default-avatar.svg') }}"  
                                            class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;" alt="Profile picture">
                                        <div>
                                            <p class="mb-1">{{ $notification->content }}</p>
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        @if($notification->type == 'friend_request')
                                            <a href="{{ route('friends.index') }}" class="btn btn-sm btn-primary me-2">
                                                Voir
                                            </a>
                                        @elseif($notification->type == 'friend_accepted')
                                            <a href="{{ route('profile.show', $notification->fromUser) }}" class="btn btn-sm btn-primary me-2">
                                                Voir le profil
                                            </a>
                                        @elseif(in_array($notification->type, ['post_like', 'post_comment']))
                                            <a href="{{ route('posts.show', $notification->notifiable_id) }}" class="btn btn-sm btn-primary me-2">
                                                Voir le post
                                            </a>
                                        @endif
                                        
                                        @if(!$notification->is_read)
                                                                                        <form action="{{ route('notifications.read', $notification) }}" method="POST">                                                @csrf                                                @method('PATCH')                                                <button type="submit" class="btn btn-sm btn-outline-secondary">                                                    Marquer comme lu                                                </button>                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-3">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <p class="text-center">Vous n'avez pas de notifications.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 