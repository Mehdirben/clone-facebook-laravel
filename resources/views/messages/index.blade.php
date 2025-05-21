@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Mes conversations</h3>
                </div>
                <div class="card-body">
                    @if($users->count() > 0)
                        <div class="list-group">
                            @foreach($users as $user)
                                <a href="{{ route('messages.show', $user) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $user->profile && $user->profile->profile_picture 
                                                                                        ? asset('storage/' . $user->profile->profile_picture)                                             : asset('images/default-avatar.svg') }}"  
                                            class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;" alt="Profile picture">
                                        <div>
                                            <h5 class="mb-1">{{ $user->name }}</h5>
                                            <p class="text-muted mb-0">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    @if(isset($unreadCounts[$user->id]) && $unreadCounts[$user->id] > 0)
                                        <span class="badge bg-primary rounded-pill">{{ $unreadCounts[$user->id] }}</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center">
                            <p>Vous n'avez pas encore de conversations.</p>
                            <p>Commencez à échanger avec des amis via leur profil !</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 