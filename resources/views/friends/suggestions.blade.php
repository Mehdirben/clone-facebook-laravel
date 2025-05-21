@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Suggestions d'amis</h3>
                    <a href="{{ route('friends.index') }}" class="btn btn-outline-primary btn-sm">Retour à mes amis</a>
                </div>
                <div class="card-body">
                    @if($suggestions->count() > 0)
                        <div class="row">
                            @foreach($suggestions as $user)
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $user->profile && $user->profile->profile_picture 
                                                                                                        ? asset('storage/' . $user->profile->profile_picture)                                                     : asset('images/default-avatar.svg') }}"  
                                                    class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;" alt="Profile picture">
                                                <div>
                                                    <h5 class="mb-1">{{ $user->name }}</h5>
                                                    <a href="{{ route('profile.show', $user) }}" class="text-decoration-none">Voir le profil</a>
                                                </div>
                                            </div>
                                                                                        <form action="{{ route('friends.request', $user) }}" method="POST">                                                @csrf                                                <button type="submit" class="btn btn-primary btn-sm">                                                    <i class="fas fa-user-plus me-1"></i> Ajouter                                                </button>                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center">Aucune suggestion d'ami pour le moment.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 