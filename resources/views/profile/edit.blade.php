@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">{{ __('Modifier mon profil') }}</h2>
                    <a href="{{ route('profile.show', $user) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Retour au profil
                    </a>
                </div>
                
                <div class="card-body">
                    @if (session('status') === 'profile-updated')
                        <div class="alert alert-success">
                            {{ __('Profil mis à jour avec succès !') }}
                        </div>
                    @endif

                    <form method="post" action="{{ route('profile.update') }}" class="mt-6" enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="mb-3">Informations personnelles</h4>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Nom complet') }}</label>
                                    <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('Adresse email') }}</label>
                                    <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="bio" class="form-label">{{ __('Biographie') }}</label>
                                    <textarea id="bio" name="bio" class="form-control @error('bio') is-invalid @enderror" rows="3">{{ old('bio', $profile->bio ?? '') }}</textarea>
                                    @error('bio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="location" class="form-label">{{ __('Localisation') }}</label>
                                    <input id="location" name="location" type="text" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', $profile->location ?? '') }}">
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h4 class="mb-3">Photos de profil</h4>
                                
                                <div class="mb-4">
                                    <label for="profile_picture" class="form-label">{{ __('Photo de profil') }}</label>
                                    <div class="d-flex mb-2 align-items-center">
                                        <div class="me-3">
                                            <img src="{{ $profile && $profile->profile_picture 
                                                ? asset('storage/' . $profile->profile_picture) 
                                                : asset('images/default-avatar.svg') }}" 
                                                class="rounded-circle border" style="width: 100px; height: 100px; object-fit: cover;" 
                                                id="profile_picture_preview" alt="Profile picture">
                                        </div>
                                        <div>
                                            <input id="profile_picture" name="profile_picture" type="file" class="form-control @error('profile_picture') is-invalid @enderror" accept="image/*">
                                            <small class="text-muted">Format JPEG, PNG ou GIF (max. 2Mo)</small>
                                        </div>
                                    </div>
                                    @error('profile_picture')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="cover_photo" class="form-label">{{ __('Photo de couverture') }}</label>
                                    <div class="mb-2">
                                        <img src="{{ $profile && $profile->cover_photo 
                                            ? asset('storage/' . $profile->cover_photo) 
                                            : 'https://placehold.co/1200x400/e9ecef/6c757d?text=Photo+de+couverture' }}" 
                                            class="img-fluid rounded border" style="width: 100%; height: 150px; object-fit: cover;" 
                                            id="cover_photo_preview" alt="Cover photo">
                                    </div>
                                    <input id="cover_photo" name="cover_photo" type="file" class="form-control @error('cover_photo') is-invalid @enderror" accept="image/*">
                                    <small class="text-muted">Format JPEG, PNG ou GIF (max. 4Mo)</small>
                                    @error('cover_photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h4 class="mb-3">Informations de contact</h4>
                                
                                <div class="mb-3">
                                    <label for="birthday" class="form-label">{{ __('Date de naissance') }}</label>
                                    <input id="birthday" name="birthday" type="date" class="form-control @error('birthday') is-invalid @enderror" value="{{ old('birthday', $profile && $profile->birthday ? $profile->birthday->format('Y-m-d') : '') }}">
                                    @error('birthday')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="phone" class="form-label">{{ __('Téléphone') }}</label>
                                    <input id="phone" name="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $profile->phone ?? '') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="website" class="form-label">{{ __('Site web') }}</label>
                                    <input id="website" name="website" type="url" class="form-control @error('website') is-invalid @enderror" value="{{ old('website', $profile->website ?? '') }}">
                                    @error('website')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6 d-flex align-items-end">
                                <div class="mb-3 mt-4 w-100">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-save me-1"></i> {{ __('Enregistrer les modifications') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h3>{{ __('Sécurité') }}</h3>
                </div>
                <div class="card-body">
                    <h4 class="mb-3">{{ __('Modifier le mot de passe') }}</h4>
                    
                    @if (session('status') === 'password-updated')
                        <div class="alert alert-success">
                            {{ __('Mot de passe mis à jour avec succès !') }}
                        </div>
                    @endif
                    
                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">{{ __('Mot de passe actuel') }}</label>
                            <input id="current_password" name="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" autocomplete="current-password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Nouveau mot de passe') }}</label>
                            <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">{{ __('Confirmer le mot de passe') }}</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-key me-1"></i> {{ __('Modifier le mot de passe') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h3 class="mb-0">{{ __('Supprimer le compte') }}</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées. Avant de supprimer votre compte, veuillez télécharger les données que vous souhaitez conserver.
                    </p>
                    
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                        <i class="fas fa-trash-alt me-1"></i> {{ __('Supprimer mon compte') }}
                    </button>
                    
                    <!-- Modal de confirmation -->
                    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmDeleteModalLabel">{{ __('Êtes-vous sûr de vouloir supprimer votre compte ?') }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>{{ __('Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées. Veuillez entrer votre mot de passe pour confirmer que vous souhaitez supprimer définitivement votre compte.') }}</p>
                                    
                                    <form method="post" action="{{ route('profile.destroy') }}" id="delete-account-form">
                                        @csrf
                                        @method('delete')
                                        
                                        <div class="mb-3">
                                            <label for="password_confirm_delete" class="form-label">{{ __('Mot de passe') }}</label>
                                            <input id="password_confirm_delete" name="password" type="password" class="form-control" placeholder="Entrez votre mot de passe pour confirmer" required>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Annuler') }}</button>
                                    <button type="button" class="btn btn-danger" onclick="document.getElementById('delete-account-form').submit();">
                                        {{ __('Supprimer définitivement') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview images before upload
    document.addEventListener('DOMContentLoaded', function() {
        const profilePictureInput = document.getElementById('profile_picture');
        const profilePicturePreview = document.getElementById('profile_picture_preview');
        
        const coverPhotoInput = document.getElementById('cover_photo');
        const coverPhotoPreview = document.getElementById('cover_photo_preview');
        
        // Preview profile picture
        profilePictureInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    profilePicturePreview.src = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        // Preview cover photo
        coverPhotoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    coverPhotoPreview.src = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
@endpush
@endsection
