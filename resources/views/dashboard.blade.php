<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Fil d\'actualité') }}
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
                            <a href="{{ route('dashboard') }}" class="flex items-center text-gray-800 hover:bg-gray-100 p-2 rounded-lg {{ Route::currentRouteName() === 'dashboard' ? 'bg-gray-100' : '' }}">
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

                <!-- Colonne centrale (Publications) -->
                <div class="md:col-span-2">
                    <!-- Formulaire de création de post -->
                    <x-post-form />

                    <!-- Liste des publications et partages -->
                    <div class="space-y-6">
                        @forelse ($posts as $item)
                            @if ($item instanceof \App\Models\Post)
                                <x-post-card :post="$item" />
                            @elseif ($item instanceof \App\Models\Share)
                                <x-shared-post-card :share="$item" />
                            @endif
                        @empty
                            <div class="bg-white rounded-lg shadow p-6 text-center">
                                <p class="text-gray-500">Aucune publication à afficher pour le moment.</p>
                                <p class="text-gray-500 text-sm mt-2">Ajoutez des amis ou créez une publication pour voir du contenu ici.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
