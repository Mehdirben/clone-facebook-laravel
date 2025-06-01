<x-app-layout>
    <div class="min-h-screen bg-background-main dark:bg-background-main-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="card p-6 sticky top-8">
                        <div class="flex items-center mb-6">
                            @if (Auth::user()->profile && Auth::user()->profile->profile_picture)
                                <img src="{{ Storage::url(Auth::user()->profile->profile_picture) }}" alt="{{ Auth::user()->name }}" class="w-12 h-12 rounded-xl object-cover shadow-md ring-2 ring-facebook-100 dark:ring-facebook-800">
                            @else
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-facebook-400 to-facebook-600 flex items-center justify-center shadow-md">
                                    <span class="text-white font-bold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div class="ml-4">
                                <a href="{{ route('profile.show', Auth::user()) }}" class="font-semibold text-text-primary dark:text-text-primary-dark hover:text-facebook-500 transition-colors">{{ Auth::user()->name }}</a>
                                <p class="text-text-secondary dark:text-text-secondary-dark text-sm">Modifier mon profil</p>
                            </div>
                        </div>

                        <nav class="space-y-3">
                            <a href="{{ route('dashboard') }}" class="flex items-center text-text-secondary dark:text-text-secondary-dark hover:text-facebook-500 hover:bg-background-hover dark:hover:bg-background-hover-dark p-3 rounded-xl transition-all duration-300 group">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-home text-white"></i>
                                </div>
                                <span class="font-medium">Fil d'actualité</span>
                            </a>
                            <a href="{{ route('friends.index') }}" class="flex items-center text-text-secondary dark:text-text-secondary-dark hover:text-facebook-500 hover:bg-background-hover dark:hover:bg-background-hover-dark p-3 rounded-xl transition-all duration-300 group">
                                <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-user-friends text-white"></i>
                                </div>
                                <span class="font-medium">Amis</span>
                            </a>
                            <a href="{{ route('messages.index') }}" class="flex items-center text-text-secondary dark:text-text-secondary-dark hover:text-facebook-500 hover:bg-background-hover dark:hover:bg-background-hover-dark p-3 rounded-xl transition-all duration-300 group">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-comment-alt text-white"></i>
                                </div>
                                <span class="font-medium">Messages</span>
                            </a>
                            <a href="{{ route('notifications.index') }}" class="flex items-center text-text-secondary dark:text-text-secondary-dark hover:text-facebook-500 hover:bg-background-hover dark:hover:bg-background-hover-dark p-3 rounded-xl transition-all duration-300 group">
                                <div class="w-10 h-10 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-bell text-white"></i>
                                </div>
                                <span class="font-medium">Notifications</span>
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-3 space-y-8">
                    <!-- Header -->
                    <div class="card p-6">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between">
                            <div>
                                <h1 class="text-3xl font-bold text-text-primary dark:text-text-primary-dark mb-2">Modifier mon Profil</h1>
                                <p class="text-text-secondary dark:text-text-secondary-dark">Personnalisez votre profil et gérez vos informations</p>
                            </div>
                            <a href="{{ route('profile.show', Auth::user()) }}" class="mt-4 sm:mt-0 btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Retour au profil
                            </a>
                        </div>
                    </div>

                    <!-- Success Messages -->
                    @if (session('status') === 'profile-updated')
                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-2xl p-4 flex items-center">
                            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-check text-white text-sm"></i>
                            </div>
                            <span class="text-green-800 dark:text-green-200 font-medium">Profil mis à jour avec succès !</span>
                        </div>
                    @endif

                    @if (session('status') === 'password-updated')
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-2xl p-4 flex items-center">
                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-key text-white text-sm"></i>
                            </div>
                            <span class="text-blue-800 dark:text-blue-200 font-medium">Mot de passe mis à jour avec succès !</span>
                        </div>
                    @endif

                    <!-- Profile Information Form -->
                    <div class="card p-8" x-data="{ 
                        profilePreview: '{{ $profile && $profile->profile_picture ? Storage::url($profile->profile_picture) : '/images/default-avatar.svg' }}',
                        coverPreview: '{{ $profile && $profile->cover_photo ? Storage::url($profile->cover_photo) : '' }}',
                        showCoverPlaceholder: {{ $profile && $profile->cover_photo ? 'false' : 'true' }}
                    }">
                        <div class="flex items-center mb-8">
                            <div class="w-8 h-8 bg-gradient-to-br from-facebook-400 to-facebook-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-user-edit text-white text-sm"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-text-primary dark:text-text-primary-dark">Informations du Profil</h2>
                        </div>

                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-8">
                            @csrf
                            @method('PATCH')

                            <!-- Personal Information -->
                            <div>
                                <h3 class="text-lg font-semibold text-text-primary dark:text-text-primary-dark mb-6 flex items-center">
                                    <div class="w-6 h-6 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-info-circle text-white text-xs"></i>
                                    </div>
                                    Informations personnelles
                                </h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="name" class="block text-text-primary dark:text-text-primary-dark font-semibold mb-2">Nom complet</label>
                                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                                               class="w-full border-0 bg-background-hover dark:bg-background-hover-dark rounded-xl px-4 py-3 focus:ring-2 focus:ring-facebook-500 text-text-primary dark:text-text-primary-dark placeholder-text-muted dark:placeholder-text-muted-dark transition-all duration-300 @error('name') ring-2 ring-red-500 @enderror"
                                               placeholder="Votre nom complet" required>
                                        @error('name')
                                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="email" class="block text-text-primary dark:text-text-primary-dark font-semibold mb-2">Adresse email</label>
                                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                                               class="w-full border-0 bg-background-hover dark:bg-background-hover-dark rounded-xl px-4 py-3 focus:ring-2 focus:ring-facebook-500 text-text-primary dark:text-text-primary-dark placeholder-text-muted dark:placeholder-text-muted-dark transition-all duration-300 @error('email') ring-2 ring-red-500 @enderror"
                                               placeholder="votre@email.com" required>
                                        @error('email')
                                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="bio" class="block text-text-primary dark:text-text-primary-dark font-semibold mb-2">Biographie</label>
                                        <textarea name="bio" id="bio" rows="4" 
                                                  class="w-full border-0 bg-background-hover dark:bg-background-hover-dark rounded-xl px-4 py-3 focus:ring-2 focus:ring-facebook-500 resize-none text-text-primary dark:text-text-primary-dark placeholder-text-muted dark:placeholder-text-muted-dark transition-all duration-300 @error('bio') ring-2 ring-red-500 @enderror"
                                                  placeholder="Parlez-nous de vous...">{{ old('bio', $profile->bio ?? '') }}</textarea>
                                        @error('bio')
                                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="location" class="block text-text-primary dark:text-text-primary-dark font-semibold mb-2">Localisation</label>
                                        <input type="text" name="location" id="location" value="{{ old('location', $profile->location ?? '') }}" 
                                               class="w-full border-0 bg-background-hover dark:bg-background-hover-dark rounded-xl px-4 py-3 focus:ring-2 focus:ring-facebook-500 text-text-primary dark:text-text-primary-dark placeholder-text-muted dark:placeholder-text-muted-dark transition-all duration-300 @error('location') ring-2 ring-red-500 @enderror"
                                               placeholder="Votre ville, pays">
                                        @error('location')
                                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="birthday" class="block text-text-primary dark:text-text-primary-dark font-semibold mb-2">Date de naissance</label>
                                        <input type="date" name="birthday" id="birthday" value="{{ old('birthday', $profile && $profile->birthday ? $profile->birthday->format('Y-m-d') : '') }}" 
                                               class="w-full border-0 bg-background-hover dark:bg-background-hover-dark rounded-xl px-4 py-3 focus:ring-2 focus:ring-facebook-500 text-text-primary dark:text-text-primary-dark transition-all duration-300 @error('birthday') ring-2 ring-red-500 @enderror">
                                        @error('birthday')
                                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Photo Management -->
                            <div>
                                <h3 class="text-lg font-semibold text-text-primary dark:text-text-primary-dark mb-6 flex items-center">
                                    <div class="w-6 h-6 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-camera text-white text-xs"></i>
                                    </div>
                                    Photos de profil
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <!-- Profile Picture -->
                                    <div>
                                        <label class="block text-text-primary dark:text-text-primary-dark font-semibold mb-4">Photo de profil</label>
                                        <div class="flex items-center space-x-6">
                                            <div class="relative">
                                                <img :src="profilePreview" class="w-24 h-24 rounded-2xl object-cover shadow-lg ring-2 ring-gray-200 dark:ring-gray-700" alt="Profile preview">
                                                <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-facebook-500 rounded-full flex items-center justify-center shadow-lg">
                                                    <i class="fas fa-camera text-white text-xs"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <label for="profile_picture" class="cursor-pointer block">
                                                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 hover:border-facebook-500 dark:hover:border-facebook-400 transition-colors group">
                                                        <div class="text-center">
                                                            <i class="fas fa-upload text-gray-400 group-hover:text-facebook-500 text-2xl mb-2"></i>
                                                            <p class="text-text-secondary dark:text-text-secondary-dark group-hover:text-facebook-500 font-medium">Cliquez pour changer</p>
                                                            <p class="text-text-muted dark:text-text-muted-dark text-sm">JPEG, PNG (max. 2MB)</p>
                                                        </div>
                                                    </div>
                                                </label>
                                                <input type="file" name="profile_picture" id="profile_picture" class="hidden" accept="image/*"
                                                       @change="if ($event.target.files[0]) { profilePreview = URL.createObjectURL($event.target.files[0]); }">
                                                @error('profile_picture')
                                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cover Photo -->
                                    <div>
                                        <label class="block text-text-primary dark:text-text-primary-dark font-semibold mb-4">Photo de couverture</label>
                                        <div class="relative">
                                            <div class="w-full h-32 rounded-xl overflow-hidden shadow-lg ring-2 ring-gray-200 dark:ring-gray-700" 
                                                 x-show="!showCoverPlaceholder">
                                                <img :src="coverPreview" class="w-full h-full object-cover" alt="Cover preview">
                                            </div>
                                            <div class="w-full h-32 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl flex items-center justify-center hover:border-facebook-500 dark:hover:border-facebook-400 transition-colors group cursor-pointer"
                                                 x-show="showCoverPlaceholder" @click="$refs.coverInput.click()">
                                                <div class="text-center">
                                                    <i class="fas fa-image text-gray-400 group-hover:text-facebook-500 text-2xl mb-2"></i>
                                                    <p class="text-text-secondary dark:text-text-secondary-dark group-hover:text-facebook-500 font-medium">Ajouter une couverture</p>
                                                </div>
                                            </div>
                                            <label for="cover_photo" class="absolute inset-0 cursor-pointer" x-show="!showCoverPlaceholder">
                                                <div class="w-full h-full bg-black/50 opacity-0 hover:opacity-100 transition-opacity rounded-xl flex items-center justify-center">
                                                    <div class="text-center text-white">
                                                        <i class="fas fa-camera text-2xl mb-2"></i>
                                                        <p class="font-medium">Changer la couverture</p>
                                                    </div>
                                                </div>
                                            </label>
                                            <input type="file" name="cover_photo" id="cover_photo" class="hidden" accept="image/*" x-ref="coverInput"
                                                   @change="if ($event.target.files[0]) { coverPreview = URL.createObjectURL($event.target.files[0]); showCoverPlaceholder = false; }">
                                        </div>
                                        @error('cover_photo')
                                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div>
                                <h3 class="text-lg font-semibold text-text-primary dark:text-text-primary-dark mb-6 flex items-center">
                                    <div class="w-6 h-6 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-address-book text-white text-xs"></i>
                                    </div>
                                    Informations de contact
                                </h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="phone" class="block text-text-primary dark:text-text-primary-dark font-semibold mb-2">Téléphone</label>
                                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $profile->phone ?? '') }}" 
                                               class="w-full border-0 bg-background-hover dark:bg-background-hover-dark rounded-xl px-4 py-3 focus:ring-2 focus:ring-facebook-500 text-text-primary dark:text-text-primary-dark placeholder-text-muted dark:placeholder-text-muted-dark transition-all duration-300 @error('phone') ring-2 ring-red-500 @enderror"
                                               placeholder="+33 1 23 45 67 89">
                                        @error('phone')
                                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="website" class="block text-text-primary dark:text-text-primary-dark font-semibold mb-2">Site web</label>
                                        <input type="url" name="website" id="website" value="{{ old('website', $profile->website ?? '') }}" 
                                               class="w-full border-0 bg-background-hover dark:bg-background-hover-dark rounded-xl px-4 py-3 focus:ring-2 focus:ring-facebook-500 text-text-primary dark:text-text-primary-dark placeholder-text-muted dark:placeholder-text-muted-dark transition-all duration-300 @error('website') ring-2 ring-red-500 @enderror"
                                               placeholder="https://monsite.com">
                                        @error('website')
                                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end pt-6 border-t border-gray-100 dark:border-border-dark">
                                <button type="submit" class="btn-facebook px-8 py-3">
                                    <i class="fas fa-save mr-2"></i>
                                    Enregistrer les modifications
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Password Change Section -->
                    <div class="card p-8">
                        <div class="flex items-center mb-8">
                            <div class="w-8 h-8 bg-gradient-to-br from-yellow-400 to-orange-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-shield-alt text-white text-sm"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-text-primary dark:text-text-primary-dark">Sécurité</h2>
                        </div>

                        <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div>
                                <label for="current_password" class="block text-text-primary dark:text-text-primary-dark font-semibold mb-2">Mot de passe actuel</label>
                                <input type="password" name="current_password" id="current_password" 
                                       class="w-full border-0 bg-background-hover dark:bg-background-hover-dark rounded-xl px-4 py-3 focus:ring-2 focus:ring-facebook-500 text-text-primary dark:text-text-primary-dark placeholder-text-muted dark:placeholder-text-muted-dark transition-all duration-300 @error('current_password') ring-2 ring-red-500 @enderror"
                                       placeholder="Entrez votre mot de passe actuel" autocomplete="current-password">
                                @error('current_password')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="password" class="block text-text-primary dark:text-text-primary-dark font-semibold mb-2">Nouveau mot de passe</label>
                                    <input type="password" name="password" id="password" 
                                           class="w-full border-0 bg-background-hover dark:bg-background-hover-dark rounded-xl px-4 py-3 focus:ring-2 focus:ring-facebook-500 text-text-primary dark:text-text-primary-dark placeholder-text-muted dark:placeholder-text-muted-dark transition-all duration-300 @error('password') ring-2 ring-red-500 @enderror"
                                           placeholder="Nouveau mot de passe" autocomplete="new-password">
                                    @error('password')
                                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-text-primary dark:text-text-primary-dark font-semibold mb-2">Confirmer le mot de passe</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" 
                                           class="w-full border-0 bg-background-hover dark:bg-background-hover-dark rounded-xl px-4 py-3 focus:ring-2 focus:ring-facebook-500 text-text-primary dark:text-text-primary-dark placeholder-text-muted dark:placeholder-text-muted-dark transition-all duration-300"
                                           placeholder="Confirmez le mot de passe" autocomplete="new-password">
                                </div>
                            </div>

                            <div class="flex justify-end pt-4">
                                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-xl hover:from-yellow-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                                    <i class="fas fa-key mr-2"></i>
                                    Modifier le mot de passe
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Danger Zone -->
                    <div class="card p-8 border-2 border-red-200 dark:border-red-800" x-data="{ showDeleteModal: false }">
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-red-600 dark:text-red-400">Zone de Danger</h2>
                        </div>

                        <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-red-800 dark:text-red-200 mb-3">Supprimer le compte</h3>
                            <p class="text-red-700 dark:text-red-300 mb-6">
                                Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées. Avant de supprimer votre compte, veuillez télécharger les données que vous souhaitez conserver.
                            </p>
                            <button @click="showDeleteModal = true" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                                <i class="fas fa-trash-alt mr-2"></i>
                                Supprimer mon compte
                            </button>
                        </div>

                        <!-- Delete Confirmation Modal -->
                        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click.away="showDeleteModal = false">
                            <div class="bg-white dark:bg-background-card-dark rounded-2xl shadow-2xl max-w-md w-full mx-4 p-8" @click.stop>
                                <div class="flex items-center mb-6">
                                    <div class="w-12 h-12 bg-red-500 rounded-2xl flex items-center justify-center mr-4">
                                        <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-text-primary dark:text-text-primary-dark">Confirmer la suppression</h3>
                                </div>
                                
                                <p class="text-text-secondary dark:text-text-secondary-dark mb-6">
                                    Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible et toutes vos données seront perdues.
                                </p>

                                <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4">
                                    @csrf
                                    @method('DELETE')
                                    
                                    <div>
                                        <label for="password_confirm_delete" class="block text-text-primary dark:text-text-primary-dark font-semibold mb-2">Mot de passe</label>
                                        <input type="password" name="password" id="password_confirm_delete" 
                                               class="w-full border-0 bg-background-hover dark:bg-background-hover-dark rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-500 text-text-primary dark:text-text-primary-dark placeholder-text-muted dark:placeholder-text-muted-dark transition-all duration-300"
                                               placeholder="Entrez votre mot de passe pour confirmer" required>
                                    </div>

                                    <div class="flex space-x-4 pt-4">
                                        <button type="button" @click="showDeleteModal = false" class="flex-1 px-4 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-300">
                                            Annuler
                                        </button>
                                        <button type="submit" class="flex-1 px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-all duration-300">
                                            Supprimer définitivement
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide success messages after 5 seconds
            const successMessages = document.querySelectorAll('.bg-green-50, .bg-blue-50');
            successMessages.forEach(message => {
                setTimeout(() => {
                    message.style.opacity = '0';
                    message.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        message.remove();
                    }, 300);
                }, 5000);
            });
        });
    </script>
    @endpush
</x-app-layout>
