@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header p-0 position-relative">
                    <!-- Cover photo -->
                    <div class="cover-photo-container" style="height: 200px; background-color: #e9ecef; overflow: hidden;">
                        @if($user->profile && $user->profile->cover_photo)
                            <img src="{{ asset('storage/' . $user->profile->cover_photo) }}" 
                                style="width: 100%; height: 100%; object-fit: cover;" alt="Cover photo">
                        @endif
                    </div>
                    
                    <!-- Profile picture -->
                    <div class="profile-picture-container position-absolute" style="bottom: -50px; left: 20px;">
                        <img src="{{ $user->profile && $user->profile->profile_picture 
                                                        ? asset('storage/' . $user->profile->profile_picture)                             : asset('images/default-avatar.svg') }}"  
                            class="rounded-circle border border-3 border-white" 
                            style="width: 100px; height: 100px; object-fit: cover;" alt="Profile picture">
                    </div>
                    
                    <!-- Action buttons -->
                    <div class="position-absolute" style="bottom: 10px; right: 20px;">
                        @if(Auth::id() === $user->id)
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i> Modifier mon profil
                            </a>
                        @else
                            <div class="d-flex">
                                <a href="{{ route('messages.show', $user) }}" class="btn btn-outline-primary me-2">
                                    <i class="fas fa-comment me-1"></i> Message
                                </a>
                                
                                @if(!$friendship)
                                                                        <form action="{{ route('friends.request', $user) }}" method="POST">                                        @csrf                                        <button type="submit" class="btn btn-primary">                                            <i class="fas fa-user-plus me-1"></i> Ajouter                                        </button>                                    </form>
                                @elseif($friendship->status === 'pending')
                                    @if($friendship->user_id === Auth::id())
                                        <form action="{{ route('friends.remove', $friendship) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-secondary">
                                                <i class="fas fa-clock me-1"></i> Demande envoyée
                                            </button>
                                        </form>
                                    @else
                                        <div>
                                            <form action="{{ route('friends.accept', $friendship) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fas fa-check me-1"></i> Accepter
                                                </button>
                                            </form>
                                            <form action="{{ route('friends.reject', $friendship) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-times me-1"></i> Refuser
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @elseif($friendship->status === 'accepted')
                                    <form action="{{ route('friends.remove', $friendship) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-primary">
                                            <i class="fas fa-user-check me-1"></i> Amis
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="card-body pt-5 mt-4">
                    <h2 class="card-title">{{ $user->name }}</h2>
                    
                    @if($user->profile)
                        @if($user->profile->bio)
                            <p class="card-text mb-3">{{ $user->profile->bio }}</p>
                        @endif
                        
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex px-0">
                                        <i class="fas fa-envelope me-2 mt-1"></i>
                                        <span>{{ $user->email }}</span>
                                    </li>
                                    
                                    @if($user->profile->location)
                                        <li class="list-group-item d-flex px-0">
                                            <i class="fas fa-map-marker-alt me-2 mt-1"></i>
                                            <span>{{ $user->profile->location }}</span>
                                        </li>
                                    @endif
                                    
                                    @if($user->profile->birthday)
                                        <li class="list-group-item d-flex px-0">
                                            <i class="fas fa-birthday-cake me-2 mt-1"></i>
                                            <span>{{ \Carbon\Carbon::parse($user->profile->birthday)->format('d/m/Y') }}</span>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush">
                                    @if($user->profile->phone)
                                        <li class="list-group-item d-flex px-0">
                                            <i class="fas fa-phone me-2 mt-1"></i>
                                            <span>{{ $user->profile->phone }}</span>
                                        </li>
                                    @endif
                                    
                                    @if($user->profile->website)
                                        <li class="list-group-item d-flex px-0">
                                            <i class="fas fa-globe me-2 mt-1"></i>
                                            <a href="{{ $user->profile->website }}" target="_blank">{{ $user->profile->website }}</a>
                                        </li>
                                    @endif
                                    
                                    <li class="list-group-item d-flex px-0">
                                        <i class="fas fa-calendar-alt me-2 mt-1"></i>
                                        <span>Membre depuis {{ $user->created_at->format('d/m/Y') }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @else
                        <p class="card-text">Aucune information supplémentaire</p>
                    @endif
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3>Publications</h3>
                </div>
                <div class="card-body">
                    @if($posts->count() > 0)
                        @foreach($posts as $post)
                            <div class="card mb-3">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $post->user->profile && $post->user->profile->profile_picture 
                                                                                        ? asset('storage/' . $post->user->profile->profile_picture)                                             : asset('images/default-avatar.svg') }}"  
                                            class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;" alt="Profile picture">
                                        <div>
                                            <h5 class="mb-0">{{ $post->user->name }}</h5>
                                            <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="badge {{ $post->is_public ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $post->is_public ? 'Public' : 'Privé' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">{{ $post->content }}</p>
                                    
                                    @if($post->image)
                                        <div class="post-image mt-3">
                                            <img src="{{ asset('storage/' . $post->image) }}" class="img-fluid rounded" alt="Post image">
                                        </div>
                                    @endif
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div>
                                            <span class="me-3">
                                                <i class="fas fa-thumbs-up"></i> {{ $post->likes->count() }}
                                            </span>
                                            <span>
                                                <i class="fas fa-comment"></i> {{ $post->comments->count() }}
                                            </span>
                                        </div>
                                        <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-outline-primary">
                                            Voir le post
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        <div class="mt-3">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <p class="text-center">Aucune publication à afficher.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 