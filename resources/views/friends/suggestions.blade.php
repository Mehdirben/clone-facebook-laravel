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
                                <p class="text-text-secondary dark:text-text-secondary-dark text-sm">Découvrir des amis</p>
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
                                <span class="font-medium">Mes amis</span>
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
                                <h1 class="text-3xl font-bold text-text-primary dark:text-text-primary-dark mb-2">Trouver des Amis</h1>
                                <p class="text-text-secondary dark:text-text-secondary-dark">Découvrez et connectez-vous avec de nouvelles personnes</p>
                            </div>
                            <a href="{{ route('friends.index') }}" class="mt-4 sm:mt-0 btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Retour à mes amis
                            </a>
                        </div>
                    </div>

                    <!-- Search Section -->
                    <div class="card p-6">
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-search text-white text-sm"></i>
                            </div>
                            <h2 class="text-xl font-bold text-text-primary dark:text-text-primary-dark">Rechercher des personnes</h2>
                        </div>

                        <form action="{{ route('friends.search') }}" method="GET" class="relative">
                            <div class="relative">
                                <input type="text" name="search" 
                                       class="w-full pl-12 pr-32 py-4 bg-background-hover dark:bg-background-hover-dark border-0 rounded-2xl focus:ring-2 focus:ring-facebook-500 text-text-primary dark:text-text-primary-dark placeholder-text-muted dark:placeholder-text-muted-dark transition-all duration-300" 
                                       placeholder="Rechercher par nom, email..." 
                                       value="{{ $search ?? '' }}">
                                <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                    <i class="fas fa-search text-text-muted dark:text-text-muted-dark"></i>
                                </div>
                                <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 px-6 py-2 bg-gradient-to-r from-facebook-500 to-facebook-600 text-white font-semibold rounded-xl hover:from-facebook-600 hover:to-facebook-700 transition-all duration-300 shadow-md hover:shadow-lg">
                                    <i class="fas fa-search mr-2"></i>Rechercher
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Results/Suggestions -->
                    <div class="card p-6">
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-users text-white text-sm"></i>
                            </div>
                            <h2 class="text-xl font-bold text-text-primary dark:text-text-primary-dark">
                                @if(isset($search) && !empty($search))
                                    Résultats pour "{{ $search }}"
                                @else
                                    Suggestions pour vous
                                @endif
                            </h2>
                        </div>

                        @if($suggestions->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($suggestions as $user)
                                    <div class="group bg-background-hover dark:bg-background-hover-dark p-6 rounded-2xl hover:shadow-lg transition-all duration-300 border border-transparent hover:border-facebook-200 dark:hover:border-facebook-700">
                                        <!-- User Info -->
                                        <div class="text-center mb-6">
                                            <div class="relative inline-block">
                                                @if($user->profile && $user->profile->profile_picture)
                                                    <img src="{{ Storage::url($user->profile->profile_picture) }}" class="w-20 h-20 rounded-xl object-cover shadow-md mx-auto group-hover:scale-105 transition-transform" alt="Profile picture">
                                                @else
                                                    <div class="w-20 h-20 rounded-xl bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center shadow-md mx-auto group-hover:scale-105 transition-transform">
                                                        <span class="text-white font-bold text-2xl">{{ substr($user->name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                                <!-- Online indicator -->
                                                <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 border-2 border-white dark:border-background-card-dark rounded-full"></div>
                                            </div>
                                            
                                            <h3 class="font-semibold text-text-primary dark:text-text-primary-dark text-lg mt-4">{{ $user->name }}</h3>
                                            
                                            @if($user->profile && $user->profile->location)
                                                <div class="flex items-center justify-center text-text-muted dark:text-text-muted-dark text-sm mt-2">
                                                    <i class="fas fa-map-marker-alt mr-2 text-pink-500"></i>
                                                    <span>{{ $user->profile->location }}</span>
                                                </div>
                                            @endif

                                            @if(isset($user->mutual_friends_count))
                                                <div class="flex items-center justify-center text-text-muted dark:text-text-muted-dark text-sm mt-1">
                                                    <i class="fas fa-user-friends mr-2 text-blue-500"></i>
                                                    <span>{{ $user->mutual_friends_count }} amis en commun</span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Actions -->
                                        <div class="flex space-x-3">
                                            <a href="{{ route('profile.show', $user) }}" class="flex-1 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-center rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-300 font-medium">
                                                <i class="fas fa-user mr-2"></i>Profil
                                            </a>
                                            <form action="{{ route('friends.request', $user) }}" method="POST" class="flex-1">
                                                @csrf
                                                <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-facebook-500 to-facebook-600 text-white font-semibold rounded-xl hover:from-facebook-600 hover:to-facebook-700 transition-all duration-300 shadow-md hover:shadow-lg">
                                                    <i class="fas fa-user-plus mr-2"></i>Ajouter
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            @if($suggestions->hasPages())
                                <div class="mt-8 flex justify-center">
                                    {{ $suggestions->links() }}
                                </div>
                            @endif
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-16">
                                @if(isset($search) && !empty($search))
                                    <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-purple-100 to-blue-100 dark:from-purple-900/20 dark:to-blue-900/20 rounded-3xl flex items-center justify-center">
                                        <i class="fas fa-search text-purple-500 text-3xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-text-primary dark:text-text-primary-dark mb-3">Aucun résultat trouvé</h3>
                                    <p class="text-text-secondary dark:text-text-secondary-dark mb-6 max-w-md mx-auto">
                                        Aucun résultat trouvé pour "{{ $search }}". Essayez avec d'autres mots-clés.
                                    </p>
                                    <a href="{{ route('friends.suggestions') }}" class="btn-facebook">
                                        <i class="fas fa-users mr-2"></i>
                                        Voir les suggestions
                                    </a>
                                @else
                                    <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-green-100 to-blue-100 dark:from-green-900/20 dark:to-blue-900/20 rounded-3xl flex items-center justify-center">
                                        <i class="fas fa-users text-green-500 text-3xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-text-primary dark:text-text-primary-dark mb-3">Aucune suggestion</h3>
                                    <p class="text-text-secondary dark:text-text-secondary-dark mb-6 max-w-md mx-auto">
                                        Aucune suggestion d'ami pour le moment. Utilisez la recherche pour trouver des personnes.
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Random Suggestions -->
                    @if(isset($randomSuggestions) && $randomSuggestions->count() > 0)
                    <div class="card p-6">
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 bg-gradient-to-br from-yellow-400 to-orange-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-magic text-white text-sm"></i>
                            </div>
                            <h2 class="text-xl font-bold text-text-primary dark:text-text-primary-dark">Découvrez également</h2>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($randomSuggestions as $user)
                                <div class="group bg-background-hover dark:bg-background-hover-dark p-4 rounded-2xl hover:shadow-lg transition-all duration-300 border border-transparent hover:border-yellow-200 dark:hover:border-yellow-700">
                                    <div class="text-center mb-4">
                                        @if($user->profile && $user->profile->profile_picture)
                                            <img src="{{ Storage::url($user->profile->profile_picture) }}" class="w-16 h-16 rounded-xl object-cover shadow-md mx-auto group-hover:scale-105 transition-transform" alt="Profile picture">
                                        @else
                                            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center shadow-md mx-auto group-hover:scale-105 transition-transform">
                                                <span class="text-white font-bold text-xl">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <h4 class="font-semibold text-text-primary dark:text-text-primary-dark text-sm mt-3 truncate">{{ $user->name }}</h4>
                                    </div>

                                    <div class="flex space-x-2">
                                        <a href="{{ route('profile.show', $user) }}" class="flex-1 px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-center rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-300 text-xs font-medium">
                                            <i class="fas fa-user"></i>
                                        </a>
                                        <form action="{{ route('friends.request', $user) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full px-2 py-1 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all duration-300 text-xs">
                                                <i class="fas fa-user-plus"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- How to find friends guide -->
                    <div class="card p-6">
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-question-circle text-white text-sm"></i>
                            </div>
                            <h2 class="text-xl font-bold text-text-primary dark:text-text-primary-dark">Comment trouver des amis ?</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center p-6 bg-background-hover dark:bg-background-hover-dark rounded-2xl">
                                <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-search text-white text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-text-primary dark:text-text-primary-dark mb-3">Rechercher</h3>
                                <p class="text-text-secondary dark:text-text-secondary-dark text-sm">
                                    Utilisez la barre de recherche pour trouver des personnes par nom ou email.
                                </p>
                            </div>
                            
                            <div class="text-center p-6 bg-background-hover dark:bg-background-hover-dark rounded-2xl">
                                <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-user-plus text-white text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-text-primary dark:text-text-primary-dark mb-3">Envoyer</h3>
                                <p class="text-text-secondary dark:text-text-secondary-dark text-sm">
                                    Envoyez des demandes d'amitié aux personnes que vous connaissez.
                                </p>
                            </div>
                            
                            <div class="text-center p-6 bg-background-hover dark:bg-background-hover-dark rounded-2xl">
                                <div class="w-16 h-16 bg-gradient-to-br from-red-400 to-red-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-bell text-white text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-text-primary dark:text-text-primary-dark mb-3">Accepter</h3>
                                <p class="text-text-secondary dark:text-text-secondary-dark text-sm">
                                    Acceptez les demandes d'amitié que vous recevez dans vos notifications.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 