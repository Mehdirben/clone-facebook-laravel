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
                                <p class="text-text-secondary dark:text-text-secondary-dark text-sm">Mes conversations</p>
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
                            <div class="flex items-center text-facebook-500 bg-facebook-50 dark:bg-facebook-900/20 p-3 rounded-xl">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-comment-alt text-white"></i>
                                </div>
                                <span class="font-semibold">Messages</span>
                            </div>
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
                                <h1 class="text-3xl font-bold text-text-primary dark:text-text-primary-dark mb-2">Mes Messages</h1>
                                <p class="text-text-secondary dark:text-text-secondary-dark">Restez en contact avec vos amis et famille</p>
                            </div>
                            <div class="mt-4 sm:mt-0 relative" x-data="{ open: false }">
                                <button @click="open = !open" class="btn-facebook">
                                    <i class="fas fa-plus mr-2"></i>
                                    Nouvelle conversation
                                </button>
                                <!-- Future: Add new conversation modal -->
                            </div>
                        </div>
                    </div>

                    <!-- Messages List -->
                    <div class="card p-6">
                        @if($users->count() > 0)
                            <div class="space-y-4">
                                @foreach($users as $user)
                                    <a href="{{ route('messages.show', $user) }}" class="block group">
                                        <div class="flex items-center p-4 bg-background-hover dark:bg-background-hover-dark rounded-2xl hover:shadow-lg transition-all duration-300 border border-transparent hover:border-purple-200 dark:hover:border-purple-700 group-hover:scale-[1.02]">
                                            <div class="relative">
                                                @if($user->profile && $user->profile->profile_picture)
                                                    <img src="{{ Storage::url($user->profile->profile_picture) }}" class="w-16 h-16 rounded-xl object-cover shadow-md" alt="Profile picture">
                                                @else
                                                    <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center shadow-md">
                                                        <span class="text-white font-bold text-xl">{{ substr($user->name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                                <!-- Online indicator placeholder -->
                                                <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 border-2 border-white dark:border-background-card-dark rounded-full"></div>
                                            </div>
                                            
                                            <div class="ml-5 flex-1">
                                                <div class="flex items-center justify-between mb-2">
                                                    <h3 class="font-semibold text-text-primary dark:text-text-primary-dark text-lg group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                                                        {{ $user->name }}
                                                    </h3>
                                                    <span class="text-text-muted dark:text-text-muted-dark text-sm">
                                                        2h <!-- Placeholder for last message time -->
                                                    </span>
                                                </div>
                                                <div class="flex items-center justify-between">
                                                    <p class="text-text-secondary dark:text-text-secondary-dark text-sm truncate max-w-xs">
                                                        Dernière conversation... <!-- Placeholder for last message -->
                                                    </p>
                                                    @if(isset($unreadCounts[$user->id]) && $unreadCounts[$user->id] > 0)
                                                        <span class="ml-3 px-2 py-1 bg-purple-500 text-white text-xs font-semibold rounded-full min-w-[1.5rem] h-6 flex items-center justify-center">
                                                            {{ $unreadCounts[$user->id] }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="ml-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                <i class="fas fa-chevron-right text-purple-500"></i>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-16">
                                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/20 dark:to-pink-900/20 rounded-3xl flex items-center justify-center">
                                    <i class="fas fa-comment-alt text-purple-500 text-3xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-text-primary dark:text-text-primary-dark mb-3">Aucune conversation</h3>
                                <p class="text-text-secondary dark:text-text-secondary-dark mb-6 max-w-md mx-auto">
                                    Vous n'avez pas encore de conversations. Commencez à échanger avec vos amis via leur profil !
                                </p>
                                <a href="{{ route('friends.index') }}" class="btn-facebook">
                                    <i class="fas fa-user-friends mr-2"></i>
                                    Voir mes amis
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Quick Actions -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="card p-6 hover:shadow-lg transition-all duration-300 border border-transparent hover:border-purple-200 dark:hover:border-purple-700">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center mb-4">
                                <i class="fas fa-users text-white text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-text-primary dark:text-text-primary-dark mb-2">Conversations de groupe</h3>
                            <p class="text-text-secondary dark:text-text-secondary-dark text-sm mb-4">
                                Créez des conversations avec plusieurs amis en même temps
                            </p>
                            <button class="text-purple-500 hover:text-purple-600 dark:hover:text-purple-400 font-medium text-sm transition-colors">
                                Bientôt disponible →
                            </button>
                        </div>

                        <div class="card p-6 hover:shadow-lg transition-all duration-300 border border-transparent hover:border-blue-200 dark:hover:border-blue-700">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center mb-4">
                                <i class="fas fa-video text-white text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-text-primary dark:text-text-primary-dark mb-2">Appels vidéo</h3>
                            <p class="text-text-secondary dark:text-text-secondary-dark text-sm mb-4">
                                Passez des appels vidéo avec vos amis directement depuis les messages
                            </p>
                            <button class="text-blue-500 hover:text-blue-600 dark:hover:text-blue-400 font-medium text-sm transition-colors">
                                Bientôt disponible →
                            </button>
                        </div>

                        <div class="card p-6 hover:shadow-lg transition-all duration-300 border border-transparent hover:border-green-200 dark:hover:border-green-700">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center mb-4">
                                <i class="fas fa-share text-white text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-text-primary dark:text-text-primary-dark mb-2">Partage de fichiers</h3>
                            <p class="text-text-secondary dark:text-text-secondary-dark text-sm mb-4">
                                Partagez des photos, vidéos et documents facilement
                            </p>
                            <button class="text-green-500 hover:text-green-600 dark:hover:text-green-400 font-medium text-sm transition-colors">
                                Bientôt disponible →
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 