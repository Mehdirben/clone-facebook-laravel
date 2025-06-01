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
                                <p class="text-text-secondary dark:text-text-secondary-dark text-sm">Modifier ma publication</p>
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
                                <h1 class="text-3xl font-bold text-text-primary dark:text-text-primary-dark mb-2">Modifier la Publication</h1>
                                <p class="text-text-secondary dark:text-text-secondary-dark">Modifiez votre publication et partagez-la avec vos amis</p>
                            </div>
                            <a href="{{ route('posts.show', $post) }}" class="mt-4 sm:mt-0 btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Retour à la publication
                            </a>
                        </div>
                    </div>

                    <!-- Edit Form -->
                    <div class="card p-8">
                        @if ($errors->any())
                            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl">
                                <div class="flex items-center mb-3">
                                    <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                                    </div>
                                    <h3 class="text-red-800 dark:text-red-200 font-semibold">Erreurs de validation</h3>
                                </div>
                                <ul class="list-disc pl-8 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li class="text-red-700 dark:text-red-300 text-sm">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data" x-data="{ hasContent: true, mediaType: null }">
                            @csrf
                            @method('PATCH')

                            <!-- User Header -->
                            <div class="flex items-center mb-8">
                                @if (Auth::user()->profile && Auth::user()->profile->profile_picture)
                                    <img src="{{ Storage::url(Auth::user()->profile->profile_picture) }}" alt="{{ Auth::user()->name }}" class="w-12 h-12 rounded-xl object-cover shadow-md ring-2 ring-facebook-100 dark:ring-facebook-800">
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-facebook-400 to-facebook-600 flex items-center justify-center shadow-md">
                                        <span class="text-white font-bold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div class="ml-4">
                                    <h3 class="font-semibold text-text-primary dark:text-text-primary-dark">{{ Auth::user()->name }}</h3>
                                    <p class="text-text-secondary dark:text-text-secondary-dark text-sm">Modifie sa publication</p>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="mb-8">
                                <label for="content" class="block text-text-primary dark:text-text-primary-dark font-semibold mb-4 flex items-center">
                                    <div class="w-6 h-6 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-edit text-white text-xs"></i>
                                    </div>
                                    Contenu de la publication
                                </label>
                                <textarea id="content" name="content" rows="6" 
                                          class="w-full border-0 bg-background-hover dark:bg-background-hover-dark rounded-2xl px-6 py-4 focus:ring-2 focus:ring-facebook-500 resize-none text-text-primary dark:text-text-primary-dark placeholder-text-muted dark:placeholder-text-muted-dark transition-all duration-300 text-lg leading-relaxed"
                                          placeholder="Que voulez-vous partager ?"
                                          @input="hasContent = $event.target.value.length > 0">{{ old('content', $post->content) }}</textarea>
                            </div>

                            <!-- Current Media Display -->
                            @if($post->image || $post->video)
                                <div class="mb-8">
                                    <h3 class="text-text-primary dark:text-text-primary-dark font-semibold mb-4 flex items-center">
                                        <div class="w-6 h-6 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-image text-white text-xs"></i>
                                        </div>
                                        Média actuel
                                    </h3>
                                    
                                    @if ($post->image)
                                        <div class="bg-background-hover dark:bg-background-hover-dark rounded-2xl p-4 border-2 border-dashed border-gray-300 dark:border-gray-600">
                                            <img src="{{ Storage::url($post->image) }}" alt="Image actuelle" class="w-full h-auto max-h-96 object-cover rounded-xl shadow-md">
                                            <p class="text-text-muted dark:text-text-muted-dark text-sm mt-3 text-center">Image actuelle</p>
                                        </div>
                                    @endif

                                    @if ($post->video)
                                        <div class="bg-background-hover dark:bg-background-hover-dark rounded-2xl p-4 border-2 border-dashed border-gray-300 dark:border-gray-600">
                                            <video src="{{ Storage::url($post->video) }}" controls class="w-full h-auto max-h-96 rounded-xl shadow-md"></video>
                                            <p class="text-text-muted dark:text-text-muted-dark text-sm mt-3 text-center">Vidéo actuelle</p>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Media Upload -->
                            <div class="mb-8">
                                <h3 class="text-text-primary dark:text-text-primary-dark font-semibold mb-4 flex items-center">
                                    <div class="w-6 h-6 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-plus text-white text-xs"></i>
                                    </div>
                                    Remplacer le média (optionnel)
                                </h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Image Upload -->
                                    <div>
                                        <label for="image" class="flex flex-col items-center p-6 bg-background-hover dark:bg-background-hover-dark rounded-2xl hover:bg-green-50 dark:hover:bg-green-900/10 cursor-pointer transition-all duration-300 group border-2 border-transparent hover:border-green-200 dark:hover:border-green-800">
                                            <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-md">
                                                <i class="far fa-image text-white text-2xl"></i>
                                            </div>
                                            <span class="text-lg font-semibold text-text-primary dark:text-text-primary-dark group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors mb-2">Nouvelle Image</span>
                                            <span class="text-text-muted dark:text-text-muted-dark text-sm text-center">JPEG, PNG, GIF - Max 2MB</span>
                                            <input id="image" type="file" name="image" class="hidden" accept="image/*" @change="mediaType = 'image'">
                                        </label>
                                    </div>

                                    <!-- Video Upload -->
                                    <div>
                                        <label for="video" class="flex flex-col items-center p-6 bg-background-hover dark:bg-background-hover-dark rounded-2xl hover:bg-red-50 dark:hover:bg-red-900/10 cursor-pointer transition-all duration-300 group border-2 border-transparent hover:border-red-200 dark:hover:border-red-800">
                                            <div class="w-16 h-16 bg-gradient-to-br from-red-400 to-red-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-md">
                                                <i class="fas fa-video text-white text-2xl"></i>
                                            </div>
                                            <span class="text-lg font-semibold text-text-primary dark:text-text-primary-dark group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors mb-2">Nouvelle Vidéo</span>
                                            <span class="text-text-muted dark:text-text-muted-dark text-sm text-center">MP4, MOV, AVI - Max 20MB</span>
                                            <input id="video" type="file" name="video" class="hidden" accept="video/*" @change="mediaType = 'video'">
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Privacy Settings -->
                            <div class="mb-8">
                                <h3 class="text-text-primary dark:text-text-primary-dark font-semibold mb-4 flex items-center">
                                    <div class="w-6 h-6 bg-gradient-to-br from-gray-400 to-gray-600 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-shield-alt text-white text-xs"></i>
                                    </div>
                                    Confidentialité
                                </h3>
                                
                                <div class="bg-background-hover dark:bg-background-hover-dark rounded-2xl p-6">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_public" value="1" {{ old('is_public', $post->is_public) ? 'checked' : '' }} 
                                               class="sr-only peer">
                                        <div class="relative w-12 h-6 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-facebook-300 dark:peer-focus:ring-facebook-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-facebook-600"></div>
                                        <div class="ml-4">
                                            <span class="text-text-primary dark:text-text-primary-dark font-semibold">Publication publique</span>
                                            <p class="text-text-muted dark:text-text-muted-dark text-sm">Si désactivé, seuls vous et vos amis pourront voir cette publication</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-100 dark:border-border-dark">
                                <a href="{{ route('posts.show', $post) }}" class="flex-1 px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-300 text-center">
                                    <i class="fas fa-times mr-2"></i>
                                    Annuler
                                </a>
                                <button type="submit" 
                                        class="flex-1 px-6 py-3 bg-gradient-to-r from-facebook-500 to-facebook-600 text-white font-semibold rounded-xl hover:from-facebook-600 hover:to-facebook-700 focus:ring-4 focus:ring-facebook-300 dark:focus:ring-facebook-800 transition-all duration-300 shadow-lg hover:shadow-facebook-glow"
                                        :disabled="!hasContent">
                                    <i class="fas fa-save mr-2"></i>
                                    Mettre à jour
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 