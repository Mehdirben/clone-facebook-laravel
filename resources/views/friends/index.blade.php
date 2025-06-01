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
                                <p class="text-text-secondary dark:text-text-secondary-dark text-sm">Gérer mes amis</p>
                            </div>
                        </div>

                        <nav class="space-y-3">
                            <a href="{{ route('dashboard') }}" class="flex items-center text-text-secondary dark:text-text-secondary-dark hover:text-facebook-500 hover:bg-background-hover dark:hover:bg-background-hover-dark p-3 rounded-xl transition-all duration-300 group">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-home text-white"></i>
                                </div>
                                <span class="font-medium">Fil d'actualité</span>
                            </a>
                            <div class="flex items-center text-facebook-500 bg-facebook-50 dark:bg-facebook-900/20 p-3 rounded-xl">
                                <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-user-friends text-white"></i>
                                </div>
                                <span class="font-semibold">Amis</span>
                            </div>
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
                            <a href="{{ route('posts.index') }}" class="flex items-center text-text-secondary dark:text-text-secondary-dark hover:text-facebook-500 hover:bg-background-hover dark:hover:bg-background-hover-dark p-3 rounded-xl transition-all duration-300 group">
                                <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-file-alt text-white"></i>
                                </div>
                                <span class="font-medium">Mes publications</span>
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
                                <h1 class="text-3xl font-bold text-text-primary dark:text-text-primary-dark mb-2">Mes Amis</h1>
                                <p class="text-text-secondary dark:text-text-secondary-dark">Gérez vos relations d'amitié et découvrez de nouvelles personnes</p>
                            </div>
                            <a href="{{ route('friends.suggestions') }}" class="mt-4 sm:mt-0 btn-facebook">
                                <i class="fas fa-user-plus mr-2"></i>
                                Découvrir des amis
                            </a>
                        </div>
                    </div>

                    <!-- Friend Requests Received -->
                    @if($receivedRequests->where('status', 'pending')->count() > 0)
                    <div class="card p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-text-primary dark:text-text-primary-dark flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-user-plus text-white text-sm"></i>
                                </div>
                                Demandes d'amitié reçues
                            </h2>
                            <span class="px-3 py-1 bg-facebook-100 dark:bg-facebook-900/30 text-facebook-600 dark:text-facebook-400 rounded-full text-sm font-semibold">
                                {{ $receivedRequests->where('status', 'pending')->count() }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($receivedRequests->where('status', 'pending') as $request)
                                <div class="bg-background-hover dark:bg-background-hover-dark p-4 rounded-2xl hover:shadow-lg transition-all duration-300 border border-transparent hover:border-facebook-200 dark:hover:border-facebook-700">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            @if($request->user->profile && $request->user->profile->profile_picture)
                                                <img src="{{ Storage::url($request->user->profile->profile_picture) }}" class="w-14 h-14 rounded-xl object-cover shadow-md" alt="Profile picture">
                                            @else
                                                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center shadow-md">
                                                    <span class="text-white font-bold text-lg">{{ substr($request->user->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <div class="ml-4">
                                                <h3 class="font-semibold text-text-primary dark:text-text-primary-dark">{{ $request->user->name }}</h3>
                                                <p class="text-text-muted dark:text-text-muted-dark text-sm">{{ $request->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex space-x-3 mt-4">
                                        <form action="{{ route('friends.accept', $request) }}" method="POST" class="flex-1">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-md hover:shadow-lg">
                                                <i class="fas fa-check mr-2"></i>Accepter
                                            </button>
                                        </form>
                                        <form action="{{ route('friends.reject', $request) }}" method="POST" class="flex-1">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="w-full px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-300">
                                                <i class="fas fa-times mr-2"></i>Refuser
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Friend Requests Sent -->
                    @if($sentRequests->where('status', 'pending')->count() > 0)
                    <div class="card p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-text-primary dark:text-text-primary-dark flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-paper-plane text-white text-sm"></i>
                                </div>
                                Demandes envoyées
                            </h2>
                            <span class="px-3 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 rounded-full text-sm font-semibold">
                                {{ $sentRequests->where('status', 'pending')->count() }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($sentRequests->where('status', 'pending') as $request)
                                <div class="bg-background-hover dark:bg-background-hover-dark p-4 rounded-2xl hover:shadow-lg transition-all duration-300 border border-transparent hover:border-orange-200 dark:hover:border-orange-700">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            @if($request->friend->profile && $request->friend->profile->profile_picture)
                                                <img src="{{ Storage::url($request->friend->profile->profile_picture) }}" class="w-14 h-14 rounded-xl object-cover shadow-md" alt="Profile picture">
                                            @else
                                                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center shadow-md">
                                                    <span class="text-white font-bold text-lg">{{ substr($request->friend->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <div class="ml-4">
                                                <h3 class="font-semibold text-text-primary dark:text-text-primary-dark">{{ $request->friend->name }}</h3>
                                                <p class="text-text-muted dark:text-text-muted-dark text-sm">{{ $request->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <form action="{{ route('friends.remove', $request) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-red-100 dark:hover:bg-red-900/20 hover:text-red-600 dark:hover:text-red-400 transition-all duration-300">
                                                <i class="fas fa-times mr-2"></i>Annuler la demande
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Friends List -->
                    <div class="card p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-text-primary dark:text-text-primary-dark flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-users text-white text-sm"></i>
                                </div>
                                Mes amis
                            </h2>
                            <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full text-sm font-semibold">
                                {{ $acceptedSentFriends->count() + $acceptedReceivedFriends->count() }}
                            </span>
                        </div>

                        @if($acceptedSentFriends->count() > 0 || $acceptedReceivedFriends->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($acceptedSentFriends as $friendship)
                                    <div class="group bg-background-hover dark:bg-background-hover-dark p-4 rounded-2xl hover:shadow-lg transition-all duration-300 border border-transparent hover:border-green-200 dark:hover:border-green-700">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex items-center">
                                                @if($friendship->friend->profile && $friendship->friend->profile->profile_picture)
                                                    <img src="{{ Storage::url($friendship->friend->profile->profile_picture) }}" class="w-14 h-14 rounded-xl object-cover shadow-md group-hover:scale-105 transition-transform" alt="Profile picture">
                                                @else
                                                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                                                        <span class="text-white font-bold text-lg">{{ substr($friendship->friend->name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                                <div class="ml-4">
                                                    <h3 class="font-semibold text-text-primary dark:text-text-primary-dark">{{ $friendship->friend->name }}</h3>
                                                    <a href="{{ route('profile.show', $friendship->friend) }}" class="text-facebook-500 hover:text-facebook-600 text-sm font-medium transition-colors">
                                                        Voir le profil
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex space-x-2">
                                            <a href="{{ route('messages.show', $friendship->friend) }}" class="flex-1 px-3 py-2 bg-facebook-50 dark:bg-facebook-900/20 text-facebook-600 dark:text-facebook-400 text-center rounded-xl hover:bg-facebook-100 dark:hover:bg-facebook-900/30 transition-all duration-300 text-sm font-medium">
                                                <i class="fas fa-comment mr-2"></i>Message
                                            </a>
                                            <div class="relative" x-data="{ open: false }">
                                                <button @click="open = !open" class="px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-300">
                                                    <i class="fas fa-ellipsis-h"></i>
                                                </button>
                                                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white dark:bg-background-card-dark rounded-xl shadow-2xl border border-gray-100 dark:border-border-dark py-2 z-10">
                                                    <form action="{{ route('friends.remove', $friendship) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                            <i class="fas fa-user-times mr-2"></i>Supprimer
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                
                                @foreach($acceptedReceivedFriends as $friendship)
                                    <div class="group bg-background-hover dark:bg-background-hover-dark p-4 rounded-2xl hover:shadow-lg transition-all duration-300 border border-transparent hover:border-green-200 dark:hover:border-green-700">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex items-center">
                                                @if($friendship->user->profile && $friendship->user->profile->profile_picture)
                                                    <img src="{{ Storage::url($friendship->user->profile->profile_picture) }}" class="w-14 h-14 rounded-xl object-cover shadow-md group-hover:scale-105 transition-transform" alt="Profile picture">
                                                @else
                                                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                                                        <span class="text-white font-bold text-lg">{{ substr($friendship->user->name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                                <div class="ml-4">
                                                    <h3 class="font-semibold text-text-primary dark:text-text-primary-dark">{{ $friendship->user->name }}</h3>
                                                    <a href="{{ route('profile.show', $friendship->user) }}" class="text-facebook-500 hover:text-facebook-600 text-sm font-medium transition-colors">
                                                        Voir le profil
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex space-x-2">
                                            <a href="{{ route('messages.show', $friendship->user) }}" class="flex-1 px-3 py-2 bg-facebook-50 dark:bg-facebook-900/20 text-facebook-600 dark:text-facebook-400 text-center rounded-xl hover:bg-facebook-100 dark:hover:bg-facebook-900/30 transition-all duration-300 text-sm font-medium">
                                                <i class="fas fa-comment mr-2"></i>Message
                                            </a>
                                            <div class="relative" x-data="{ open: false }">
                                                <button @click="open = !open" class="px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-300">
                                                    <i class="fas fa-ellipsis-h"></i>
                                                </button>
                                                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white dark:bg-background-card-dark rounded-xl shadow-2xl border border-gray-100 dark:border-border-dark py-2 z-10">
                                                    <form action="{{ route('friends.remove', $friendship) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                            <i class="fas fa-user-times mr-2"></i>Supprimer
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-green-100 to-blue-100 dark:from-green-900/20 dark:to-blue-900/20 rounded-3xl flex items-center justify-center">
                                    <i class="fas fa-user-friends text-green-500 text-3xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-text-primary dark:text-text-primary-dark mb-3">Aucun ami pour le moment</h3>
                                <p class="text-text-secondary dark:text-text-secondary-dark mb-6 max-w-md mx-auto">Commencez à vous connecter avec des personnes et construisez votre réseau social.</p>
                                <a href="{{ route('friends.suggestions') }}" class="btn-facebook">
                                    <i class="fas fa-user-plus mr-2"></i>
                                    Découvrir des amis
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 