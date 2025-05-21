@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Résultats de recherche</h3>
                    <div>
                        <a href="{{ route('friends.suggestions') }}" class="btn btn-outline-primary btn-sm me-2">
                            <i class="fas fa-users me-1"></i> Suggestions
                        </a>
                        <a href="{{ route('friends.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Retour à mes amis
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Barre de recherche -->
                    <form action="{{ route('friends.search') }}" method="GET" class="mb-4">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Rechercher des personnes..." value="{{ $search ?? '' }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i> Rechercher
                            </button>
                        </div>
                    </form>
                    
                    <h4 class="mb-3">Résultats pour "{{ $search }}"</h4>
                    
                    @if($users->count() > 0)
                        <div class="list-group">
                            @foreach($users as $user)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $user->profile && $user->profile->profile_picture 
                                            ? asset('storage/' . $user->profile->profile_picture) 
                                            : asset('images/default-avatar.svg') }}" 
                                            class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;" alt="Profile picture">
                                        <div>
                                            <h5 class="mb-1">{{ $user->name }}</h5>
                                            @if($user->profile && $user->profile->location)
                                                <small class="text-muted">
                                                    <i class="fas fa-map-marker-alt me-1"></i> {{ $user->profile->location }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <a href="{{ route('profile.show', $user) }}" class="btn btn-sm btn-outline-secondary me-2">
                                            <i class="fas fa-user me-1"></i> Profil
                                        </a>
                                        
                                        @if(!$user->friendship)
                                            <form action="{{ route('friends.request', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-user-plus me-1"></i> Ajouter
                                                </button>
                                            </form>
                                        @elseif($user->friendship->status === 'pending')
                                            @if($user->friendship->user_id === Auth::id())
                                                <button class="btn btn-sm btn-outline-warning" disabled>
                                                    <i class="fas fa-clock me-1"></i> Demande envoyée
                                                </button>
                                            @else
                                                <div class="btn-group">
                                                    <form action="{{ route('friends.accept', $user->friendship) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="fas fa-check me-1"></i> Accepter
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('friends.reject', $user->friendship) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-times me-1"></i> Refuser
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        @elseif($user->friendship->status === 'accepted')
                                            <a href="{{ route('messages.show', $user) }}" class="btn btn-sm btn-outline-primary me-2">
                                                <i class="fas fa-comment me-1"></i> Message
                                            </a>
                                            <form action="{{ route('friends.remove', $user->friendship) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-user-times me-1"></i> Supprimer
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-3">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-search fa-3x mb-3 text-muted"></i>
                            <p class="text-muted">Aucun résultat trouvé pour "{{ $search }}".</p>
                            <a href="{{ route('friends.suggestions') }}" class="btn btn-outline-primary mt-2">
                                <i class="fas fa-users me-1"></i> Voir les suggestions
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 