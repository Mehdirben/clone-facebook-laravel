@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Trouver des amis</h3>
                    <a href="{{ route('friends.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Retour à mes amis
                    </a>
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
                    
                    <!-- Résultats de recherche ou suggestions -->
                    @if(isset($search) && !empty($search))
                        <h4 class="mb-3">Résultats pour "{{ $search }}"</h4>
                    @else
                        <h4 class="mb-3">Suggestions pour vous</h4>
                    @endif
                    
                    @if($suggestions->count() > 0)
                        <div class="row">
                            @foreach($suggestions as $user)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <img src="{{ $user->profile && $user->profile->profile_picture 
                                                    ? asset('storage/' . $user->profile->profile_picture) 
                                                    : asset('images/default-avatar.svg') }}" 
                                                    class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;" alt="Profile picture">
                                                <div>
                                                    <h5 class="mb-0">{{ $user->name }}</h5>
                                                    @if($user->profile && $user->profile->location)
                                                        <small class="text-muted">
                                                            <i class="fas fa-map-marker-alt me-1"></i> {{ $user->profile->location }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="d-flex justify-content-between">
                                                <a href="{{ route('profile.show', $user) }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-user me-1"></i> Profil
                                                </a>
                                                <form action="{{ route('friends.request', $user) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-user-plus me-1"></i> Ajouter
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-3">
                            {{ $suggestions->links() }}
                        </div>
                    @else
                        @if(isset($search) && !empty($search))
                            <div class="text-center py-4">
                                <i class="fas fa-search fa-3x mb-3 text-muted"></i>
                                <p class="text-muted">Aucun résultat trouvé pour "{{ $search }}".</p>
                                <a href="{{ route('friends.suggestions') }}" class="btn btn-outline-primary mt-2">
                                    Voir les suggestions
                                </a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-users fa-3x mb-3 text-muted"></i>
                                <p class="text-muted">Aucune suggestion d'ami pour le moment.</p>
                            </div>
                        @endif
                    @endif
                    
                    <!-- Suggestions aléatoires en bas de page -->
                    @if(isset($randomSuggestions) && $randomSuggestions->count() > 0)
                        <hr class="my-4">
                        <h4 class="mb-3">Découvrez également</h4>
                        <div class="row">
                            @foreach($randomSuggestions as $user)
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="text-center mb-3">
                                                <img src="{{ $user->profile && $user->profile->profile_picture 
                                                    ? asset('storage/' . $user->profile->profile_picture) 
                                                    : asset('images/default-avatar.svg') }}" 
                                                    class="rounded-circle mb-2" style="width: 60px; height: 60px; object-fit: cover;" alt="Profile picture">
                                                <h6 class="mb-0">{{ $user->name }}</h6>
                                            </div>
                                            
                                            <div class="d-flex justify-content-between">
                                                <a href="{{ route('profile.show', $user) }}" class="btn btn-sm btn-outline-secondary w-100 me-1">
                                                    <i class="fas fa-user me-1"></i> Profil
                                                </a>
                                                <form action="{{ route('friends.request', $user) }}" method="POST" class="w-100 ms-1">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary w-100">
                                                        <i class="fas fa-user-plus"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Bloc pour expliquer comment trouver des amis -->
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Comment trouver des amis ?</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            <i class="fas fa-search fa-2x mb-2 text-primary"></i>
                            <h5>Rechercher</h5>
                            <p class="text-muted">Utilisez la barre de recherche pour trouver des personnes par nom ou email.</p>
                        </div>
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            <i class="fas fa-user-plus fa-2x mb-2 text-primary"></i>
                            <h5>Envoyer</h5>
                            <p class="text-muted">Envoyez des demandes d'amitié aux personnes que vous connaissez.</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="fas fa-bell fa-2x mb-2 text-primary"></i>
                            <h5>Accepter</h5>
                            <p class="text-muted">Acceptez les demandes d'amitié que vous recevez dans vos notifications.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 