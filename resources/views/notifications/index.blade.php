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
                                <p class="text-text-secondary dark:text-text-secondary-dark text-sm">Mes notifications</p>
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
                            <div class="flex items-center text-facebook-500 bg-facebook-50 dark:bg-facebook-900/20 p-3 rounded-xl">
                                <div class="w-10 h-10 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-bell text-white"></i>
                                </div>
                                <span class="font-semibold">Notifications</span>
                            </div>
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
                                <h1 class="text-3xl font-bold text-text-primary dark:text-text-primary-dark mb-2">Mes Notifications</h1>
                                <p class="text-text-secondary dark:text-text-secondary-dark">Restez au courant de toute l'activité sur votre compte</p>
                            </div>
                            @if($notifications->where('is_read', false)->count() > 0)
                                <form action="{{ route('notifications.read.all') }}" method="POST" class="mt-4 sm:mt-0">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-secondary">
                                        <i class="fas fa-check-double mr-2"></i>
                                        Tout marquer comme lu
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Notifications Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="card p-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-bell text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-2xl font-bold text-text-primary dark:text-text-primary-dark">{{ $notifications->count() }}</h3>
                                    <p class="text-text-secondary dark:text-text-secondary-dark text-sm">Notifications totales</p>
                                </div>
                            </div>
                        </div>

                        <div class="card p-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-bell-slash text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-2xl font-bold text-text-primary dark:text-text-primary-dark">{{ $notifications->where('is_read', false)->count() }}</h3>
                                    <p class="text-text-secondary dark:text-text-secondary-dark text-sm">Non lues</p>
                                </div>
                            </div>
                        </div>

                        <div class="card p-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-check text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-2xl font-bold text-text-primary dark:text-text-primary-dark">{{ $notifications->where('is_read', true)->count() }}</h3>
                                    <p class="text-text-secondary dark:text-text-secondary-dark text-sm">Lues</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications List -->
                    <div class="card p-6">
                        @if($notifications->count() > 0)
                            <div class="space-y-4">
                                @foreach($notifications as $notification)
                                    <div class="group relative {{ $notification->is_read ? 'opacity-75' : '' }}">
                                        <div class="flex items-start p-4 bg-background-hover dark:bg-background-hover-dark rounded-2xl hover:shadow-lg transition-all duration-300 border-2 {{ $notification->is_read ? 'border-transparent' : 'border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/10' }} hover:border-facebook-200 dark:hover:border-facebook-700">
                                            <!-- Notification Icon based on type -->
                                            <div class="flex-shrink-0 mr-4">
                                                <div class="relative">
                                                    @if($notification->fromUser->profile && $notification->fromUser->profile->profile_picture)
                                                        <img src="{{ Storage::url($notification->fromUser->profile->profile_picture) }}" class="w-14 h-14 rounded-xl object-cover shadow-md" alt="Profile picture">
                                                    @else
                                                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center shadow-md">
                                                            <span class="text-white font-bold text-lg">{{ substr($notification->fromUser->name, 0, 1) }}</span>
                                                        </div>
                                                    @endif
                                                    
                                                    <!-- Type indicator -->
                                                    <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full border-2 border-white dark:border-background-card-dark flex items-center justify-center
                                                        @if($notification->type == 'friend_request') bg-blue-500
                                                        @elseif($notification->type == 'friend_accepted') bg-green-500
                                                        @elseif($notification->type == 'like_post') bg-red-500
                                                        @elseif($notification->type == 'post_comment') bg-purple-500
                                                        @else bg-gray-500
                                                        @endif">
                                                        @if($notification->type == 'friend_request')
                                                            <i class="fas fa-user-plus text-white text-xs"></i>
                                                        @elseif($notification->type == 'friend_accepted')
                                                            <i class="fas fa-check text-white text-xs"></i>
                                                        @elseif($notification->type == 'like_post')
                                                            <i class="fas fa-heart text-white text-xs"></i>
                                                        @elseif($notification->type == 'post_comment')
                                                            <i class="fas fa-comment text-white text-xs"></i>
                                                        @else
                                                            <i class="fas fa-bell text-white text-xs"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Notification Content -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <p class="text-text-primary dark:text-text-primary-dark font-medium mb-1">
                                                            {{ $notification->content }}
                                                        </p>
                                                        <p class="text-text-muted dark:text-text-muted-dark text-sm">
                                                            {{ $notification->created_at->diffForHumans() }}
                                                        </p>
                                                    </div>
                                                    
                                                    @if(!$notification->is_read)
                                                        <div class="w-3 h-3 bg-red-500 rounded-full ml-4 flex-shrink-0"></div>
                                                    @endif
                                                </div>

                                                <!-- Action Buttons -->
                                                <div class="flex flex-wrap items-center gap-3 mt-4">
                                                    @if($notification->type == 'friend_request')
                                                        <a href="{{ route('friends.index') }}" class="inline-flex items-center px-4 py-2 bg-facebook-500 text-white text-sm font-medium rounded-xl hover:bg-facebook-600 transition-colors">
                                                            <i class="fas fa-eye mr-2"></i>Voir
                                                        </a>
                                                    @elseif($notification->type == 'friend_accepted')
                                                        <a href="{{ route('profile.show', $notification->fromUser) }}" class="inline-flex items-center px-4 py-2 bg-green-500 text-white text-sm font-medium rounded-xl hover:bg-green-600 transition-colors">
                                                            <i class="fas fa-user mr-2"></i>Voir le profil
                                                        </a>
                                                    @elseif(in_array($notification->type, ['post_like', 'post_comment']))
                                                        <a href="{{ route('posts.show', $notification->notifiable_id) }}" class="inline-flex items-center px-4 py-2 bg-purple-500 text-white text-sm font-medium rounded-xl hover:bg-purple-600 transition-colors">
                                                            <i class="fas fa-external-link-alt mr-2"></i>Voir le post
                                                        </a>
                                                    @endif
                                                    
                                                    @if(!$notification->is_read)
                                                        <form action="{{ route('notifications.read', $notification) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                                                <i class="fas fa-check mr-2"></i>Marquer comme lu
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            @if($notifications->hasPages())
                                <div class="mt-8 flex justify-center">
                                    {{ $notifications->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-16">
                                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-red-100 to-orange-100 dark:from-red-900/20 dark:to-orange-900/20 rounded-3xl flex items-center justify-center">
                                    <i class="fas fa-bell text-red-500 text-3xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-text-primary dark:text-text-primary-dark mb-3">Aucune notification</h3>
                                <p class="text-text-secondary dark:text-text-secondary-dark mb-6 max-w-md mx-auto">
                                    Vous n'avez pas de notifications pour le moment. L'activité sur votre compte apparaîtra ici.
                                </p>
                                <a href="{{ route('dashboard') }}" class="btn-facebook">
                                    <i class="fas fa-home mr-2"></i>
                                    Retour au fil d'actualité
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 