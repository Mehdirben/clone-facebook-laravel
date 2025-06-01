<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Welcome Banner -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-facebook-500 via-purple-600 to-pink-500 rounded-3xl p-6 text-white relative overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-32 translate-x-32"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-24 -translate-x-24"></div>
                
                <div class="relative z-10">
                    <h1 class="text-2xl md:text-3xl font-bold mb-2">
                        Bonjour, {{ Auth::user()->name }} ! 👋
                    </h1>
                    <p class="text-white/90 text-lg">
                        Que voulez-vous partager aujourd'hui ?
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Sidebar gauche -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Profil utilisateur -->
                <div class="sidebar-section animate-fade-in group hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center space-x-4 mb-4">
                        @if (Auth::user()->profile && Auth::user()->profile->profile_picture)
                            <img src="{{ Storage::url(Auth::user()->profile->profile_picture) }}" alt="{{ Auth::user()->name }}" class="avatar avatar-lg ring-4 ring-facebook-100 dark:ring-facebook-900/20">
                        @else
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-facebook-400 to-facebook-600 flex items-center justify-center animate-float shadow-lg">
                                <span class="text-white font-bold text-2xl">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div>
                            <a href="{{ route('profile.show', Auth::user()) }}" class="text-lg font-bold text-text-primary dark:text-text-primary-dark hover:text-facebook-500 transition-colors">{{ Auth::user()->name }}</a>
                            <p class="text-text-secondary dark:text-text-secondary-dark text-sm">Voir votre profil</p>
                            <div class="flex items-center mt-1 text-xs text-green-500">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                                En ligne
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation rapide -->
                <div class="sidebar-section animate-fade-in">
                    <h3 class="font-bold text-text-primary dark:text-text-primary-dark mb-4 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-facebook-500 to-purple-600 rounded-xl flex items-center justify-center mr-3 animate-pulse">
                            <i class="fas fa-compass text-white text-sm"></i>
                        </div>
                        Navigation
                    </h3>
                    <nav class="space-y-2">
                        <a href="{{ route('dashboard') }}" class="nav-item {{ Route::currentRouteName() === 'dashboard' ? 'active' : '' }} group relative overflow-hidden">
                            <div class="w-10 h-10 bg-gradient-to-br from-facebook-500 via-facebook-600 to-blue-600 rounded-2xl mr-3 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg group-hover:shadow-facebook-glow relative flex items-center justify-center">
                                <!-- Icon with animation -->
                                <i class="fas fa-home text-white text-sm group-hover:animate-bounce"></i>
                                <!-- Shine effect -->
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700 rounded-2xl"></div>
                            </div>
                            <span class="group-hover:text-facebook-500 transition-colors font-medium">Fil d'actualité</span>
                            @if(Route::currentRouteName() === 'dashboard')
                                <div class="ml-auto w-2 h-2 bg-facebook-500 rounded-full animate-pulse shadow-sm"></div>
                            @endif
                            <!-- Hover background effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-facebook-50/0 to-facebook-50/20 dark:from-facebook-900/0 dark:to-facebook-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-xl"></div>
                        </a>
                        
                        <a href="{{ route('friends.index') }}" class="nav-item group relative overflow-hidden">
                            <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 via-green-500 to-teal-600 rounded-2xl mr-3 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg group-hover:shadow-green-glow relative flex items-center justify-center">
                                <i class="fas fa-users text-white text-sm group-hover:animate-pulse"></i>
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700 rounded-2xl"></div>
                            </div>
                            <span class="group-hover:text-green-500 transition-colors font-medium">Amis</span>
                            <span class="ml-auto text-xs bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 px-2.5 py-1.5 rounded-full font-semibold shadow-sm group-hover:scale-110 transition-transform">
                                {{ Auth::user()->friends()->count() ?? 0 }}
                            </span>
                            <div class="absolute inset-0 bg-gradient-to-r from-green-50/0 to-green-50/20 dark:from-green-900/0 dark:to-green-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-xl"></div>
                        </a>
                        
                        <a href="{{ route('messages.index') }}" class="nav-item group relative overflow-hidden">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 via-violet-500 to-indigo-600 rounded-2xl mr-3 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg group-hover:shadow-purple-glow relative flex items-center justify-center">
                                <i class="fab fa-facebook-messenger text-white text-sm group-hover:animate-wiggle"></i>
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700 rounded-2xl"></div>
                            </div>
                            <span class="group-hover:text-purple-500 transition-colors font-medium">Messages</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-purple-50/0 to-purple-50/20 dark:from-purple-900/0 dark:to-purple-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-xl"></div>
                        </a>
                        
                        <a href="{{ route('notifications.index') }}" class="nav-item group relative overflow-hidden">
                            <div class="w-10 h-10 bg-gradient-to-br from-red-500 via-rose-500 to-pink-600 rounded-2xl mr-3 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg group-hover:shadow-red-glow relative flex items-center justify-center">
                                <i class="fas fa-bell text-white text-sm group-hover:animate-swing"></i>
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700 rounded-2xl"></div>
                            </div>
                            <span class="group-hover:text-red-500 transition-colors font-medium">Notifications</span>
                            @php
                                $unreadCount = 0;
                                try {
                                    if (Auth::user() && method_exists(Auth::user(), 'notifications')) {
                                        $unreadCount = Auth::user()->notifications()->whereNull('read_at')->count();
                                    }
                                } catch (Exception $e) {
                                    $unreadCount = 0;
                                }
                            @endphp
                            @if($unreadCount > 0)
                                <span class="ml-auto text-xs bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 px-2.5 py-1.5 rounded-full font-semibold shadow-sm animate-pulse group-hover:scale-110 transition-transform">
                                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                </span>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-r from-red-50/0 to-red-50/20 dark:from-red-900/0 dark:to-red-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-xl"></div>
                        </a>
                        
                        <a href="{{ route('posts.index') }}" class="nav-item group relative overflow-hidden">
                            <div class="w-10 h-10 bg-gradient-to-br from-orange-500 via-amber-500 to-yellow-600 rounded-2xl mr-3 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg group-hover:shadow-orange-glow relative flex items-center justify-center">
                                <i class="fas fa-bookmark text-white text-sm group-hover:animate-float"></i>
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700 rounded-2xl"></div>
                            </div>
                            <span class="group-hover:text-orange-500 transition-colors font-medium">Mes publications</span>
                            <span class="ml-auto text-xs bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 px-2.5 py-1.5 rounded-full font-semibold shadow-sm group-hover:scale-110 transition-transform">
                                {{ Auth::user()->posts()->count() ?? 0 }}
                            </span>
                            <div class="absolute inset-0 bg-gradient-to-r from-orange-50/0 to-orange-50/20 dark:from-orange-900/0 dark:to-orange-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-xl"></div>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Contenu principal -->
            <div class="lg:col-span-6 space-y-6">
                <!-- Formulaire de création de post -->
                <div class="animate-slide-up">
                    <x-post-form />
                </div>

                <!-- Publications -->
                <div class="space-y-6">
                    @forelse ($posts as $item)
                        <div class="animate-fade-in">
                            @if ($item instanceof \App\Models\Post)
                                <x-post-card :post="$item" />
                            @elseif ($item instanceof \App\Models\Share)
                                <x-shared-post-card :share="$item" />
                            @endif
                        </div>
                    @empty
                        <div class="card p-12 text-center animate-fade-in relative overflow-hidden">
                            <!-- Background decoration -->
                            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-facebook-500/10 to-purple-500/10 rounded-full -translate-y-16 translate-x-16"></div>
                            
                            <div class="relative z-10">
                                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-facebook-100 to-purple-100 dark:from-facebook-900/20 dark:to-purple-900/20 rounded-3xl flex items-center justify-center animate-bounce-slow">
                                    <i class="fas fa-newspaper text-facebook-500 text-3xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-text-primary dark:text-text-primary-dark mb-3">Votre fil d'actualité est vide</h3>
                                <p class="text-text-secondary dark:text-text-secondary-dark mb-6 max-w-md mx-auto">Commencez à suivre des amis ou créez votre première publication pour voir du contenu ici.</p>
                                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                    <button onclick="focusPostForm()" class="btn-facebook animate-wiggle">
                                        <i class="fas fa-plus mr-2"></i>
                                        Créer une publication
                                    </button>
                                    <a href="{{ route('friends.index') }}" class="btn-secondary">
                                        <i class="fas fa-user-plus mr-2"></i>
                                        Trouver des amis
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($posts->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $posts->links() }}
                    </div>
                @endif
            </div>

            <!-- Sidebar droite -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Suggestions d'amis -->
                @if(isset($friendSuggestions) && $friendSuggestions && $friendSuggestions->count() > 0)
                    <div class="sidebar-section animate-fade-in">
                        <h3 class="font-bold text-text-primary dark:text-text-primary-dark mb-4 flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-facebook-500 to-purple-600 rounded-xl flex items-center justify-center mr-3 animate-pulse">
                                <i class="fas fa-user-plus text-white text-sm"></i>
                            </div>
                            Suggestions d'amis
                        </h3>
                        <div class="space-y-4">
                            @foreach($friendSuggestions->take(3) as $suggestion)
                                <div class="flex items-center justify-between p-3 bg-gradient-to-r from-facebook-50/50 to-purple-50/50 dark:from-facebook-900/10 dark:to-purple-900/10 rounded-xl hover:shadow-md transition-all duration-200">
                                    <div class="flex items-center space-x-3">
                                        @if($suggestion->profile && $suggestion->profile->profile_picture)
                                            <img src="{{ Storage::url($suggestion->profile->profile_picture) }}" alt="{{ $suggestion->name }}" class="w-12 h-12 rounded-xl object-cover">
                                        @else
                                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                                <span class="text-white font-bold">{{ substr($suggestion->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-semibold text-text-primary dark:text-text-primary-dark text-sm">{{ $suggestion->name }}</div>
                                            <div class="text-text-secondary dark:text-text-secondary-dark text-xs">
                                                {{ $suggestion->mutual_friends_count ?? 0 }} ami{{ ($suggestion->mutual_friends_count ?? 0) > 1 ? 's' : '' }} en commun
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button class="p-2.5 bg-facebook-500 text-white rounded-xl hover:bg-facebook-600 transition-all duration-300 group hover:shadow-facebook-glow">
                                            <i class="fas fa-user-plus text-xs group-hover:scale-125 transition-transform"></i>
                                        </button>
                                        <button class="p-2.5 bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-500 transition-all duration-300 group">
                                            <i class="fas fa-times text-xs group-hover:scale-125 group-hover:text-red-500 transition-all"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Activité récente -->
                @if(isset($recentActivity) && $recentActivity && $recentActivity->count() > 0)
                    <div class="sidebar-section animate-fade-in">
                        <h3 class="font-bold text-text-primary dark:text-text-primary-dark mb-4 flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center mr-3 animate-pulse">
                                <i class="fas fa-chart-line text-white text-sm"></i>
                            </div>
                            Activité récente
                        </h3>
                        <div class="space-y-4">
                            @foreach($recentActivity->take(3) as $activity)
                                <div class="flex items-start space-x-3 p-3 bg-gradient-to-r from-gray-50/50 to-blue-50/50 dark:from-gray-800/50 dark:to-blue-900/10 rounded-xl hover:shadow-md transition-all duration-300 group">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-{{ $activity->color ?? 'blue' }}-500 to-{{ $activity->color ?? 'blue' }}-600 dark:from-{{ $activity->color ?? 'blue' }}-400 dark:to-{{ $activity->color ?? 'blue' }}-500 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                        <i class="fas fa-{{ $activity->icon ?? 'bell' }} text-white text-sm group-hover:animate-pulse"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-text-primary dark:text-text-primary-dark text-sm font-medium group-hover:text-facebook-500 transition-colors">{{ $activity->message }}</p>
                                        <p class="text-text-muted dark:text-text-muted-dark text-xs mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
    function focusPostForm() {
        // Scroll to the post form
        const postForm = document.querySelector('textarea[name="content"]');
        if (postForm) {
            postForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(() => {
                postForm.focus();
                // Trigger the Alpine.js data to show options
                const formComponent = postForm.closest('[x-data]');
                if (formComponent && formComponent.__x) {
                    formComponent.__x.$data.showOptions = true;
                }
            }, 500);
        }
    }
    </script>
</x-app-layout>
