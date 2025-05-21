@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Demandes d'amitié reçues</h3>
                </div>
                <div class="card-body">
                    @if($receivedRequests->where('status', 'pending')->count() > 0)
                        <div class="list-group">
                            @foreach($receivedRequests->where('status', 'pending') as $request)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                                                                <img src="{{ $request->user->profile && $request->user->profile->profile_picture                                             ? asset('storage/' . $request->user->profile->profile_picture)                                             : asset('images/default-avatar.svg') }}"                                             class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;" alt="Profile picture">
                                        <div>
                                            <h5 class="mb-1">{{ $request->user->name }}</h5>
                                            <p class="text-muted mb-0">Demande envoyée le {{ $request->created_at->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <form action="{{ route('friends.accept', $request) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-primary btn-sm">Accepter</button>
                                        </form>
                                        <form action="{{ route('friends.reject', $request) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Refuser</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center">Vous n'avez pas de demandes d'amitié en attente.</p>
                    @endif
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Demandes d'amitié envoyées</h3>
                </div>
                <div class="card-body">
                    @if($sentRequests->where('status', 'pending')->count() > 0)
                        <div class="list-group">
                            @foreach($sentRequests->where('status', 'pending') as $request)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $request->friend->profile && $request->friend->profile->profile_picture 
                                                                                        ? asset('storage/' . $request->friend->profile->profile_picture)                                             : asset('images/default-avatar.svg') }}"  
                                            class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;" alt="Profile picture">
                                        <div>
                                            <h5 class="mb-1">{{ $request->friend->name }}</h5>
                                            <p class="text-muted mb-0">Demande envoyée le {{ $request->created_at->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <form action="{{ route('friends.remove', $request) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Annuler</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center">Vous n'avez pas de demandes d'amitié envoyées en attente.</p>
                    @endif
                </div>
            </div>
            
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Mes amis</h3>
                    <a href="{{ route('friends.suggestions') }}" class="btn btn-primary btn-sm">Découvrir des amis</a>
                </div>
                <div class="card-body">
                    @if($acceptedSentFriends->count() > 0 || $acceptedReceivedFriends->count() > 0)
                        <div class="row">
                            @foreach($acceptedSentFriends as $friendship)
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $friendship->friend->profile && $friendship->friend->profile->profile_picture 
                                                                                                        ? asset('storage/' . $friendship->friend->profile->profile_picture)                                                     : asset('images/default-avatar.svg') }}"  
                                                    class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;" alt="Profile picture">
                                                <div>
                                                    <h5 class="mb-1">{{ $friendship->friend->name }}</h5>
                                                    <a href="{{ route('profile.show', $friendship->friend) }}" class="text-decoration-none">Voir le profil</a>
                                                </div>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{ $friendship->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Options
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $friendship->id }}">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('messages.show', $friendship->friend) }}">
                                                            <i class="fas fa-comment me-2"></i> Message
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('friends.remove', $friendship) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-user-times me-2"></i> Supprimer
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            @foreach($acceptedReceivedFriends as $friendship)
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $friendship->user->profile && $friendship->user->profile->profile_picture 
                                                                                                        ? asset('storage/' . $friendship->user->profile->profile_picture)                                                     : asset('images/default-avatar.svg') }}"  
                                                    class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;" alt="Profile picture">
                                                <div>
                                                    <h5 class="mb-1">{{ $friendship->user->name }}</h5>
                                                    <a href="{{ route('profile.show', $friendship->user) }}" class="text-decoration-none">Voir le profil</a>
                                                </div>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{ $friendship->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Options
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $friendship->id }}">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('messages.show', $friendship->user) }}">
                                                            <i class="fas fa-comment me-2"></i> Message
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('friends.remove', $friendship) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-user-times me-2"></i> Supprimer
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center">Vous n'avez pas encore d'amis. <a href="{{ route('friends.suggestions') }}">Découvrez de nouveaux amis !</a></p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 