<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier la publication') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Colonne gauche (Profil et liens) -->
                <div class="md:col-span-1">
                    <div class="bg-white rounded-lg shadow p-4 mb-6">
                        <div class="flex items-center mb-4">
                            @if (Auth::user()->profile && Auth::user()->profile->profile_picture)
                                <img src="{{ Storage::url(Auth::user()->profile->profile_picture) }}" alt="{{ Auth::user()->name }}" class="w-12 h-12 rounded-full mr-3">
                            @else
                                <div class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                                    <span class="text-gray-600 font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div>
                                <a href="{{ route('profile.show', Auth::user()) }}" class="font-semibold text-blue-600 hover:underline">{{ Auth::user()->name }}</a>
                                <p class="text-gray-500 text-sm">Voir mon profil</p>
                            </div>
                        </div>

                        <nav class="space-y-2">
                            <a href="{{ route('dashboard') }}" class="flex items-center text-gray-800 hover:bg-gray-100 p-2 rounded-lg">
                                <i class="fas fa-home w-6 text-blue-600"></i> Fil d'actualité
                            </a>
                            <a href="{{ route('friends.index') }}" class="flex items-center text-gray-800 hover:bg-gray-100 p-2 rounded-lg">
                                <i class="fas fa-user-friends w-6 text-green-600"></i> Amis
                            </a>
                            <a href="{{ route('messages.index') }}" class="flex items-center text-gray-800 hover:bg-gray-100 p-2 rounded-lg">
                                <i class="fas fa-comment-alt w-6 text-purple-600"></i> Messages
                            </a>
                            <a href="{{ route('notifications.index') }}" class="flex items-center text-gray-800 hover:bg-gray-100 p-2 rounded-lg">
                                <i class="fas fa-bell w-6 text-red-600"></i> Notifications
                            </a>
                            <a href="{{ route('posts.index') }}" class="flex items-center text-gray-800 hover:bg-gray-100 p-2 rounded-lg">
                                <i class="fas fa-file-alt w-6 text-orange-600"></i> Mes publications
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Colonne centrale (Formulaire de modification) -->
                <div class="md:col-span-2">
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Modifier la publication</h3>
                        
                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                <ul class="list-disc pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            
                            <div class="mb-4">
                                <label for="content" class="block text-gray-700 font-medium mb-2">Contenu</label>
                                <textarea id="content" name="content" rows="6" class="w-full rounded-lg border border-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" placeholder="Que voulez-vous partager?">{{ old('content', $post->content) }}</textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label for="image" class="block text-gray-700 font-medium mb-2">Image (optionnel)</label>
                                @if ($post->image)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($post->image) }}" alt="Image actuelle" class="max-h-40 rounded-lg">
                                        <p class="text-sm text-gray-500 mt-1">Image actuelle</p>
                                    </div>
                                @endif
                                <input type="file" id="image" name="image" class="w-full border border-gray-300 p-2 rounded-lg" accept="image/*">
                                <p class="text-gray-500 text-sm mt-1">Formats acceptés: JPEG, PNG, GIF (max 2MB)</p>
                            </div>
                            
                            <div class="mb-4">
                                <label for="video" class="block text-gray-700 font-medium mb-2">Vidéo (optionnel)</label>
                                @if ($post->video)
                                    <div class="mb-2">
                                        <video src="{{ Storage::url($post->video) }}" controls class="max-h-40 rounded-lg"></video>
                                        <p class="text-sm text-gray-500 mt-1">Vidéo actuelle</p>
                                    </div>
                                @endif
                                <input type="file" id="video" name="video" class="w-full border border-gray-300 p-2 rounded-lg" accept="video/*">
                                <p class="text-gray-500 text-sm mt-1">Formats acceptés: MP4, MOV, AVI (max 20MB)</p>
                            </div>
                            
                            <div class="mb-6">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_public" value="1" {{ old('is_public', $post->is_public) ? 'checked' : '' }} class="mr-2 focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                    <span class="text-gray-700">Publication publique</span>
                                </label>
                                <p class="text-gray-500 text-sm mt-1">Si désactivé, seuls vous et vos amis pourront voir cette publication</p>
                            </div>
                            
                            <div class="flex justify-end">
                                <a href="{{ route('posts.show', $post) }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400 mr-2">
                                    Annuler
                                </a>
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
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