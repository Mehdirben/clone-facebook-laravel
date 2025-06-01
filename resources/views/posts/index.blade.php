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
                                <p class="text-text-secondary dark:text-text-secondary-dark text-sm">Mes publications</p>
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
                            <div class="flex items-center text-facebook-500 bg-facebook-50 dark:bg-facebook-900/20 p-3 rounded-xl">
                                <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-file-alt text-white"></i>
                                </div>
                                <span class="font-semibold">Mes publications</span>
                            </div>
                        </nav>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-3 space-y-8">
                    <!-- Header -->
                    <div class="card p-6">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between">
                            <div>
                                <h1 class="text-3xl font-bold text-text-primary dark:text-text-primary-dark mb-2">Mes Publications</h1>
                                <p class="text-text-secondary dark:text-text-secondary-dark">Gérez et visualisez toutes vos publications</p>
                            </div>
                            <a href="{{ route('posts.create') }}" class="mt-4 sm:mt-0 btn-facebook">
                                <i class="fas fa-plus mr-2"></i>
                                Nouvelle publication
                            </a>
                        </div>
                    </div>

                    <!-- Posts Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="card p-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-file-alt text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-2xl font-bold text-text-primary dark:text-text-primary-dark">{{ $posts->total() }}</h3>
                                    <p class="text-text-secondary dark:text-text-secondary-dark text-sm">Publications totales</p>
                                </div>
                            </div>
                        </div>

                        <div class="card p-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-heart text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-2xl font-bold text-text-primary dark:text-text-primary-dark">
                                        {{ $posts->sum(function($post) { return $post->likes->count(); }) }}
                                    </h3>
                                    <p class="text-text-secondary dark:text-text-secondary-dark text-sm">Likes reçus</p>
                                </div>
                            </div>
                        </div>

                        <div class="card p-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-comment text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-2xl font-bold text-text-primary dark:text-text-primary-dark">
                                        {{ $posts->sum(function($post) { return $post->comments->count(); }) }}
                                    </h3>
                                    <p class="text-text-secondary dark:text-text-secondary-dark text-sm">Commentaires</p>
                                </div>
                            </div>
                        </div>

                        <div class="card p-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-share text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-2xl font-bold text-text-primary dark:text-text-primary-dark">
                                        {{ $posts->sum(function($post) { return $post->shares->count(); }) }}
                                    </h3>
                                    <p class="text-text-secondary dark:text-text-secondary-dark text-sm">Partages</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Posts List -->
                    <div class="card p-6">
                        @if($posts->count() > 0)
                            <div class="space-y-6">
                                @foreach ($posts as $post)
                                    <div class="group border border-gray-100 dark:border-border-dark rounded-2xl p-6 hover:shadow-lg transition-all duration-300 hover:border-facebook-200 dark:hover:border-facebook-700 bg-background-card dark:bg-background-card-dark relative">
                                        <!-- Post Header -->
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex items-center">
                                                @if (Auth::user()->profile && Auth::user()->profile->profile_picture)
                                                    <img src="{{ Storage::url(Auth::user()->profile->profile_picture) }}" alt="{{ Auth::user()->name }}" class="w-12 h-12 rounded-xl object-cover shadow-md">
                                                @else
                                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-facebook-400 to-facebook-600 flex items-center justify-center shadow-md">
                                                        <span class="text-white font-bold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                                <div class="ml-4">
                                                    <h3 class="font-semibold text-text-primary dark:text-text-primary-dark">{{ Auth::user()->name }}</h3>
                                                    <div class="flex items-center text-text-muted dark:text-text-muted-dark text-sm">
                                                        <span>{{ $post->created_at->diffForHumans() }}</span>
                                                        <span class="mx-2">•</span>
                                                        <i class="fas {{ $post->is_public ? 'fa-globe-americas text-green-500' : 'fa-lock text-gray-500' }} mr-1"></i>
                                                        <span>{{ $post->is_public ? 'Public' : 'Privé' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Post Actions -->
                                            <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                <a href="{{ route('posts.edit', $post) }}" class="p-2 text-facebook-500 hover:bg-facebook-50 dark:hover:bg-facebook-900/20 rounded-xl transition-colors">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette publication ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-colors">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Post Content -->
                                        <div class="mb-4">
                                            <p class="text-text-primary dark:text-text-primary-dark leading-relaxed">{{ $post->content }}</p>
                                        </div>

                                        <!-- Post Media -->
                                        @if($post->image)
                                            <div class="mb-4">
                                                <img src="{{ Storage::url($post->image) }}" alt="Image de la publication" class="w-full h-auto max-h-96 object-cover rounded-xl shadow-md">
                                            </div>
                                        @endif

                                        @if($post->video)
                                            <div class="mb-4">
                                                <video src="{{ Storage::url($post->video) }}" controls class="w-full h-auto max-h-96 rounded-xl shadow-md"></video>
                                            </div>
                                        @endif

                                        <!-- Post Engagement Stats -->
                                        @php
                                            $likesCount = $post->likes->count();
                                            $commentsCount = $post->comments->count();
                                            $sharesCount = $post->shares->count();
                                        @endphp

                                        @if($likesCount > 0 || $commentsCount > 0 || $sharesCount > 0)
                                            <div class="flex items-center justify-between py-3 border-t border-gray-100 dark:border-border-dark">
                                                <div class="flex items-center space-x-6 text-sm text-text-secondary dark:text-text-secondary-dark">
                                                    @if($likesCount > 0)
                                                        <div class="flex items-center">
                                                            <i class="fas fa-heart text-red-500 mr-1"></i>
                                                            <span>{{ $likesCount }} {{ $likesCount > 1 ? 'likes' : 'like' }}</span>
                                                        </div>
                                                    @endif
                                                    
                                                    @if($commentsCount > 0)
                                                        <div class="flex items-center">
                                                            <i class="fas fa-comment text-blue-500 mr-1"></i>
                                                            <span>{{ $commentsCount }} {{ $commentsCount > 1 ? 'commentaires' : 'commentaire' }}</span>
                                                        </div>
                                                    @endif
                                                    
                                                    @if($sharesCount > 0)
                                                        <div class="flex items-center">
                                                            <i class="fas fa-share text-green-500 mr-1"></i>
                                                            <span>{{ $sharesCount }} {{ $sharesCount > 1 ? 'partages' : 'partage' }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <a href="{{ route('posts.show', $post) }}" class="text-facebook-500 hover:text-facebook-600 text-sm font-medium transition-colors">
                                                    Voir les détails →
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            @if($posts->hasPages())
                                <div class="mt-8 flex justify-center">
                                    {{ $posts->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-16">
                                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-orange-100 to-red-100 dark:from-orange-900/20 dark:to-red-900/20 rounded-3xl flex items-center justify-center">
                                    <i class="fas fa-file-alt text-orange-500 text-3xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-text-primary dark:text-text-primary-dark mb-3">Aucune publication</h3>
                                <p class="text-text-secondary dark:text-text-secondary-dark mb-6 max-w-md mx-auto">
                                    Vous n'avez pas encore créé de publications. Commencez à partager vos pensées et moments avec vos amis.
                                </p>
                                <a href="{{ route('posts.create') }}" class="btn-facebook">
                                    <i class="fas fa-plus mr-2"></i>
                                    Créer ma première publication
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 