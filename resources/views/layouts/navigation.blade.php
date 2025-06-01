<nav x-data="{ open: false }" class="bg-white dark:bg-background-card-dark shadow-facebook dark:shadow-facebook-dark sticky top-0 z-50 border-b border-gray-200 dark:border-border-dark transition-colors duration-200">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 group">
                        <div class="w-10 h-10 bg-gradient-to-br from-facebook-500 to-facebook-600 rounded-full flex items-center justify-center group-hover:shadow-glow transition-all duration-300">
                            <i class="fab fa-facebook-f text-white text-lg group-hover:scale-110 transition-transform"></i>
                        </div>
                        <span class="text-xl font-bold text-gradient hidden sm:block group-hover:scale-105 transition-transform">SocialBook</span>
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="hidden md:block ml-6">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-text-muted dark:text-text-muted-dark"></i>
                        </div>
                        <input type="text" placeholder="Rechercher sur SocialBook" 
                               class="pl-10 pr-4 py-2 w-80 bg-background-hover dark:bg-background-hover-dark rounded-full border-0 focus:outline-none focus:ring-2 focus:ring-facebook-500 text-sm text-text-primary dark:text-text-primary-dark placeholder-text-muted dark:placeholder-text-muted-dark transition-colors duration-200">
                    </div>
                </div>
            </div>

            <!-- Center Navigation Links -->
            <div class="hidden md:flex items-center space-x-2">
                <a href="{{ route('dashboard') }}" 
                   class="nav-item group relative {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <div class="relative">
                        <i class="fas fa-home text-xl group-hover:scale-125 transition-all duration-300"></i>
                        @if(request()->routeIs('dashboard'))
                            <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-1.5 h-1.5 bg-facebook-500 rounded-full animate-pulse"></div>
                        @endif
                    </div>
                    <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-full opacity-0 group-hover:opacity-100 transition-opacity duration-200 bg-black text-white text-xs px-2 py-1 rounded whitespace-nowrap z-50">
                        Accueil
                    </div>
                </a>
                
                <a href="{{ route('friends.index') }}" 
                   class="nav-item group relative {{ request()->routeIs('friends.*') ? 'active' : '' }}">
                    <div class="relative">
                        <i class="fas fa-users text-xl group-hover:scale-125 transition-all duration-300"></i>
                        @if(request()->routeIs('friends.*'))
                            <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-1.5 h-1.5 bg-facebook-500 rounded-full animate-pulse"></div>
                        @endif
                    </div>
                    <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-full opacity-0 group-hover:opacity-100 transition-opacity duration-200 bg-black text-white text-xs px-2 py-1 rounded whitespace-nowrap z-50">
                        Amis
                    </div>
                </a>
                
                <a href="{{ route('messages.index') }}" 
                   class="nav-item group relative {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                    <div class="relative">
                        <i class="fab fa-facebook-messenger text-xl group-hover:scale-125 transition-all duration-300"></i>
                        @if(request()->routeIs('messages.*'))
                            <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-1.5 h-1.5 bg-facebook-500 rounded-full animate-pulse"></div>
                        @endif
                    </div>
                    <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-full opacity-0 group-hover:opacity-100 transition-opacity duration-200 bg-black text-white text-xs px-2 py-1 rounded whitespace-nowrap z-50">
                        Messenger
                    </div>
                </a>
                
                <a href="{{ route('notifications.index') }}" 
                   class="nav-item group relative {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                    <div class="relative">
                        <i class="fas fa-bell text-xl group-hover:scale-125 group-hover:animate-swing transition-all duration-300"></i>
                        @if(request()->routeIs('notifications.*'))
                            <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-1.5 h-1.5 bg-facebook-500 rounded-full animate-pulse"></div>
                        @endif
                        <!-- Notification badge -->
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
                            <div class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-xs font-bold animate-pulse">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </div>
                        @endif
                    </div>
                    <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-full opacity-0 group-hover:opacity-100 transition-opacity duration-200 bg-black text-white text-xs px-2 py-1 rounded whitespace-nowrap z-50">
                        Notifications
                    </div>
                </a>
            </div>

            <!-- Right Side Icons -->
            <div class="hidden sm:flex sm:items-center sm:space-x-4">
                <!-- Theme Toggle -->
                <x-theme-toggle />
                
                <!-- Enhanced Profile Dropdown -->
                <x-dropdown align="right" width="72">
                    <x-slot name="trigger">
                        <button class="flex items-center p-2 rounded-2xl hover:bg-gradient-to-r hover:from-facebook-50/50 hover:to-purple-50/50 dark:hover:from-facebook-900/10 dark:hover:to-purple-900/10 transition-all duration-300 group relative overflow-hidden border-2 border-transparent hover:border-facebook-200 dark:hover:border-facebook-700">
                            <!-- Profile picture with enhanced styling -->
                            <div class="relative">
                                @if(Auth::user()->profile && Auth::user()->profile->profile_picture)
                                    <img src="{{ Storage::url(Auth::user()->profile->profile_picture) }}" 
                                        class="w-10 h-10 rounded-xl object-cover ring-2 ring-transparent group-hover:ring-facebook-300 dark:group-hover:ring-facebook-600 group-hover:scale-105 transition-all duration-300 shadow-lg" 
                                        alt="{{ Auth::user()->name }}">
                                @else
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-facebook-400 to-facebook-600 flex items-center justify-center ring-2 ring-transparent group-hover:ring-facebook-300 dark:group-hover:ring-facebook-600 group-hover:scale-105 transition-all duration-300 shadow-lg">
                                        <span class="text-white font-bold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                
                                <!-- Shine effect -->
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700 rounded-xl"></div>
                            </div>
                            
                            <!-- User name and chevron -->
                            <div class="ml-3 hidden lg:block">
                                <div class="flex items-center space-x-2">
                                    <div>
                                        <div class="text-sm font-semibold text-text-primary dark:text-text-primary-dark group-hover:text-facebook-500 transition-colors">
                                            {{ Auth::user()->name }}
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-down text-text-secondary dark:text-text-secondary-dark text-xs group-hover:rotate-180 group-hover:text-facebook-500 transition-all duration-300"></i>
                                </div>
                            </div>
                            
                            <!-- Mobile chevron -->
                            <i class="fas fa-chevron-down lg:hidden ml-2 text-text-secondary dark:text-text-secondary-dark text-xs group-hover:rotate-180 group-hover:text-facebook-500 transition-all duration-300"></i>
                            
                            <!-- Hover background effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-facebook-50/0 to-facebook-50/20 dark:from-facebook-900/0 dark:to-facebook-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-2xl"></div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Enhanced profile header -->
                        <div class="px-6 py-4 border-b border-gray-100 dark:border-border-dark bg-gradient-to-r from-facebook-50/30 to-purple-50/30 dark:from-facebook-900/10 dark:to-purple-900/10">
                            <div class="flex items-center space-x-4">
                                @if(Auth::user()->profile && Auth::user()->profile->profile_picture)
                                    <img src="{{ Storage::url(Auth::user()->profile->profile_picture) }}" 
                                        class="w-12 h-12 rounded-xl object-cover shadow-lg ring-2 ring-facebook-200 dark:ring-facebook-700" 
                                        alt="{{ Auth::user()->name }}">
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-facebook-400 to-facebook-600 flex items-center justify-center shadow-lg ring-2 ring-facebook-200 dark:ring-facebook-700">
                                        <span class="text-white font-bold text-xl">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <div class="font-bold text-text-primary dark:text-text-primary-dark text-lg">{{ Auth::user()->name }}</div>
                                    <div class="text-sm text-text-secondary dark:text-text-secondary-dark">{{ Auth::user()->email }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Enhanced menu items -->
                        <div class="py-2">
                            <a href="{{ route('profile.show', Auth::user()) }}" 
                               class="flex items-center px-6 py-3 text-text-primary dark:text-text-primary-dark hover:bg-gradient-to-r hover:from-facebook-50 hover:to-purple-50 dark:hover:from-facebook-900/10 dark:hover:to-purple-900/10 transition-all duration-200 group">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform shadow-md">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium group-hover:text-facebook-500 transition-colors">{{ __('Voir votre profil') }}</div>
                                    <div class="text-xs text-text-muted dark:text-text-muted-dark">Gérer votre profil public</div>
                                </div>
                                <i class="fas fa-chevron-right text-text-muted dark:text-text-muted-dark text-xs group-hover:text-facebook-500 group-hover:translate-x-1 transition-all"></i>
                            </a>
                            
                            <a href="{{ route('profile.edit') }}" 
                               class="flex items-center px-6 py-3 text-text-primary dark:text-text-primary-dark hover:bg-gradient-to-r hover:from-gray-50 hover:to-blue-50 dark:hover:from-gray-800/50 dark:hover:to-blue-900/10 transition-all duration-200 group">
                                <div class="w-10 h-10 bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform shadow-md">
                                    <i class="fas fa-cog text-white text-sm group-hover:animate-spin"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium group-hover:text-gray-600 dark:group-hover:text-gray-400 transition-colors">{{ __('Paramètres') }}</div>
                                    <div class="text-xs text-text-muted dark:text-text-muted-dark">Gérer votre compte</div>
                                </div>
                                <i class="fas fa-chevron-right text-text-muted dark:text-text-muted-dark text-xs group-hover:text-gray-600 dark:group-hover:text-gray-400 group-hover:translate-x-1 transition-all"></i>
                            </a>
                            
                            <a href="{{ route('notifications.index') }}" 
                               class="flex items-center px-6 py-3 text-text-primary dark:text-text-primary-dark hover:bg-gradient-to-r hover:from-purple-50 hover:to-indigo-50 dark:hover:from-purple-900/10 dark:hover:to-indigo-900/10 transition-all duration-200 group relative">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform shadow-md relative">
                                    <i class="fas fa-bell text-white text-sm group-hover:animate-swing"></i>
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
                                        <div class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-xs font-bold animate-pulse shadow-sm">
                                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">{{ __('Notifications') }}</div>
                                    <div class="text-xs text-text-muted dark:text-text-muted-dark">
                                        @if($unreadCount > 0)
                                            {{ $unreadCount }} nouvelle{{ $unreadCount > 1 ? 's' : '' }} notification{{ $unreadCount > 1 ? 's' : '' }}
                                        @else
                                            Aucune nouvelle notification
                                        @endif
                                    </div>
                                </div>
                                <i class="fas fa-chevron-right text-text-muted dark:text-text-muted-dark text-xs group-hover:text-purple-600 dark:group-hover:text-purple-400 group-hover:translate-x-1 transition-all"></i>
                            </a>
                            
                            <a href="{{ route('messages.index') }}" 
                               class="flex items-center px-6 py-3 text-text-primary dark:text-text-primary-dark hover:bg-gradient-to-r hover:from-pink-50 hover:to-purple-50 dark:hover:from-pink-900/10 dark:hover:to-purple-900/10 transition-all duration-200 group">
                                <div class="w-10 h-10 bg-gradient-to-br from-pink-500 to-purple-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform shadow-md">
                                    <i class="fab fa-facebook-messenger text-white text-sm group-hover:animate-bounce"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium group-hover:text-pink-600 dark:group-hover:text-pink-400 transition-colors">{{ __('Messages') }}</div>
                                    <div class="text-xs text-text-muted dark:text-text-muted-dark">Vos conversations</div>
                                </div>
                                <i class="fas fa-chevron-right text-text-muted dark:text-text-muted-dark text-xs group-hover:text-pink-600 dark:group-hover:text-pink-400 group-hover:translate-x-1 transition-all"></i>
                            </a>
                        </div>
                        
                        <!-- Logout section -->
                        <div class="border-t border-gray-100 dark:border-border-dark">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="flex items-center w-full px-6 py-3 text-red-600 hover:bg-gradient-to-r hover:from-red-50 hover:to-pink-50 dark:hover:from-red-900/20 dark:hover:to-pink-900/20 transition-all duration-200 group">
                                    <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform shadow-md">
                                        <i class="fas fa-sign-out-alt text-white text-sm group-hover:translate-x-1 transition-transform"></i>
                                    </div>
                                    <div class="flex-1 text-left">
                                        <div class="font-medium group-hover:text-red-700 dark:group-hover:text-red-400 transition-colors">{{ __('Se déconnecter') }}</div>
                                        <div class="text-xs text-red-500/70 dark:text-red-400/70">Quitter votre session</div>
                                    </div>
                                    <i class="fas fa-chevron-right text-red-500/70 text-xs group-hover:text-red-600 dark:group-hover:text-red-400 group-hover:translate-x-1 transition-all"></i>
                                </button>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" class="p-2 rounded-md text-text-secondary dark:text-text-secondary-dark hover:text-text-primary dark:hover:text-text-primary-dark hover:bg-background-hover dark:hover:bg-background-hover-dark">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white dark:bg-background-card-dark border-t border-gray-200 dark:border-border-dark">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('dashboard') }}" 
               class="nav-item w-full flex items-center group hover:bg-facebook-50 dark:hover:bg-facebook-900/20 {{ request()->routeIs('dashboard') ? 'active bg-facebook-50 dark:bg-facebook-900/20' : '' }}">
                <div class="w-10 h-10 bg-gradient-to-br from-facebook-500 to-facebook-600 rounded-xl flex items-center justify-center mr-3 group-hover:scale-105 transition-transform shadow-md">
                    <i class="fas fa-home text-white text-sm"></i>
                </div>
                <span class="font-medium">{{ __('Accueil') }}</span>
            </a>
            <a href="{{ route('friends.index') }}" 
               class="nav-item w-full flex items-center group hover:bg-green-50 dark:hover:bg-green-900/20 {{ request()->routeIs('friends.*') ? 'active bg-green-50 dark:bg-green-900/20' : '' }}">
                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-3 group-hover:scale-105 transition-transform shadow-md">
                    <i class="fas fa-users text-white text-sm"></i>
                </div>
                <span class="font-medium">{{ __('Amis') }}</span>
            </a>
            <a href="{{ route('messages.index') }}" 
               class="nav-item w-full flex items-center group hover:bg-purple-50 dark:hover:bg-purple-900/20 {{ request()->routeIs('messages.*') ? 'active bg-purple-50 dark:bg-purple-900/20' : '' }}">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center mr-3 group-hover:scale-105 transition-transform shadow-md">
                    <i class="fab fa-facebook-messenger text-white text-sm"></i>
                </div>
                <span class="font-medium">{{ __('Messages') }}</span>
            </a>
            <a href="{{ route('notifications.index') }}" 
               class="nav-item w-full flex items-center group hover:bg-red-50 dark:hover:bg-red-900/20 {{ request()->routeIs('notifications.*') ? 'active bg-red-50 dark:bg-red-900/20' : '' }}">
                <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl flex items-center justify-center mr-3 group-hover:scale-105 transition-transform shadow-md relative">
                    <i class="fas fa-bell text-white text-sm"></i>
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
                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white rounded-full flex items-center justify-center text-xs font-bold">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </div>
                    @endif
                </div>
                <span class="font-medium">{{ __('Notifications') }}</span>
            </a>
        </div>

        <!-- Mobile User Menu -->
        <div class="px-4 py-3 border-t border-gray-200 dark:border-border-dark">
            <div class="flex items-center space-x-3 mb-3 p-3 rounded-xl bg-gradient-to-r from-facebook-50/50 to-purple-50/50 dark:from-facebook-900/10 dark:to-purple-900/10">
                <img src="{{ Auth::user()->profile && Auth::user()->profile->profile_picture 
                    ? asset('storage/' . Auth::user()->profile->profile_picture) 
                    : asset('images/default-avatar.svg') }}" 
                    class="avatar avatar-md ring-2 ring-facebook-200 dark:ring-facebook-700" alt="Avatar">
                <div>
                    <div class="font-semibold text-text-primary dark:text-text-primary-dark">{{ Auth::user()->name }}</div>
                    <div class="text-sm text-text-secondary dark:text-text-secondary-dark">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="space-y-1">
                <a href="{{ route('profile.show', Auth::user()) }}" class="nav-item w-full flex items-center group hover:bg-blue-50 dark:hover:bg-blue-900/20">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-3 group-hover:scale-105 transition-transform shadow-md">
                        <i class="fas fa-user text-white text-sm"></i>
                    </div>
                    <span class="font-medium">{{ __('Mon profil') }}</span>
                </a>
                <a href="{{ route('profile.edit') }}" class="nav-item w-full flex items-center group hover:bg-gray-50 dark:hover:bg-gray-800">
                    <div class="w-10 h-10 bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl flex items-center justify-center mr-3 group-hover:scale-105 transition-transform shadow-md">
                        <i class="fas fa-cog text-white text-sm"></i>
                    </div>
                    <span class="font-medium">{{ __('Paramètres') }}</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-item w-full text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center group">
                        <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center mr-3 group-hover:scale-105 transition-transform shadow-md">
                            <i class="fas fa-sign-out-alt text-white text-sm"></i>
                        </div>
                        <span class="font-medium">{{ __('Se déconnecter') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
