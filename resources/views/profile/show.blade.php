<x-app-layout>
    <div class="min-h-screen bg-background-main dark:bg-background-main-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Profile Header -->
            <div class="card mb-8 overflow-hidden">
                <!-- Cover Photo -->
                <div class="relative h-80 bg-gradient-to-br from-facebook-400 to-facebook-600 overflow-hidden">
                    @if($user->profile && $user->profile->cover_photo)
                        <img src="{{ Storage::url($user->profile->cover_photo) }}" 
                             class="w-full h-full object-cover" alt="Cover photo">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-facebook-400 via-purple-500 to-pink-500 flex items-center justify-center">
                            <div class="text-center text-white">
                                <i class="fas fa-camera text-6xl mb-4 opacity-50"></i>
                                <p class="text-xl font-semibold opacity-75">Photo de couverture</p>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Edit Cover Photo (if own profile) -->
                    @if(Auth::id() === $user->id)
                        <div class="absolute bottom-4 right-4">
                            <button class="px-4 py-2 bg-black/50 text-white rounded-xl hover:bg-black/70 transition-all duration-300 backdrop-blur-sm">
                                <i class="fas fa-camera mr-2"></i>
                                Modifier la couverture
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Profile Info Section -->
                <div class="relative px-8 pb-8">
                    <!-- Profile Picture -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between -mt-20 relative z-10">
                        <div class="relative">
                            @if($user->profile && $user->profile->profile_picture)
                                <img src="{{ Storage::url($user->profile->profile_picture) }}" 
                                     class="w-40 h-40 rounded-3xl object-cover shadow-2xl border-4 border-white dark:border-background-card-dark" alt="Profile picture">
                            @else
                                <div class="w-40 h-40 rounded-3xl bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center shadow-2xl border-4 border-white dark:border-background-card-dark">
                                    <span class="text-white font-bold text-6xl">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                            @endif
                            
                            <!-- Online Indicator -->
                            <div class="absolute -bottom-2 -right-2 w-12 h-12 bg-green-500 border-4 border-white dark:border-background-card-dark rounded-full flex items-center justify-center">
                                <i class="fas fa-circle text-white text-sm"></i>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-3 mt-6 sm:mt-0">
                            @if(Auth::id() === $user->id)
                                <a href="{{ route('profile.edit') }}" class="btn-facebook">
                                    <i class="fas fa-edit mr-2"></i>
                                    Modifier mon profil
                                </a>
                            @else
                                <a href="{{ route('messages.show', $user) }}" class="btn-secondary">
                                    <i class="fas fa-comment mr-2"></i>
                                    Message
                                </a>
                                
                                @if(!$friendship)
                                    <form action="{{ route('friends.request', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="btn-facebook">
                                            <i class="fas fa-user-plus mr-2"></i>
                                            Ajouter ami
                                        </button>
                                    </form>
                                @elseif($friendship->status === 'pending')
                                    @if($friendship->user_id === Auth::id())
                                        <form action="{{ route('friends.remove', $friendship) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-300">
                                                <i class="fas fa-clock mr-2"></i>
                                                Demande envoyée
                                            </button>
                                        </form>
                                    @else
                                        <div class="flex gap-2">
                                            <form action="{{ route('friends.accept', $friendship) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-300">
                                                    <i class="fas fa-check mr-2"></i>
                                                    Accepter
                                                </button>
                                            </form>
                                            <form action="{{ route('friends.reject', $friendship) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-4 py-2 bg-red-500 text-white font-semibold rounded-xl hover:bg-red-600 transition-all duration-300">
                                                    <i class="fas fa-times mr-2"></i>
                                                    Refuser
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @elseif($friendship->status === 'accepted')
                                    <form action="{{ route('friends.remove', $friendship) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400 font-semibold rounded-xl hover:bg-green-200 dark:hover:bg-green-900/30 transition-all duration-300">
                                            <i class="fas fa-user-check mr-2"></i>
                                            Amis
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- User Info -->
                    <div class="mt-6">
                        <h1 class="text-4xl font-bold text-text-primary dark:text-text-primary-dark mb-3">{{ $user->name }}</h1>
                        
                        @if($user->profile && $user->profile->bio)
                            <p class="text-text-secondary dark:text-text-secondary-dark text-lg mb-4 max-w-2xl">{{ $user->profile->bio }}</p>
                        @endif

                        <!-- Quick Stats -->
                        <div class="flex flex-wrap gap-6 text-text-muted dark:text-text-muted-dark">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-facebook-500"></i>
                                <span>Membre depuis {{ $user->created_at->format('M Y') }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt mr-2 text-pink-500"></i>
                                <span>{{ $user->profile && $user->profile->location ? $user->profile->location : 'Emplacement non spécifié' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-users mr-2 text-green-500"></i>
                                <span>{{ $posts->total() }} {{ $posts->total() > 1 ? 'publications' : 'publication' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Section -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="card p-6 text-center hover:shadow-lg transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-text-primary dark:text-text-primary-dark mb-2">{{ $posts->total() }}</h3>
                    <p class="text-text-secondary dark:text-text-secondary-dark text-sm">Publications totales</p>
                </div>

                <div class="card p-6 text-center hover:shadow-lg transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-red-400 to-red-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-heart text-white text-2xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-text-primary dark:text-text-primary-dark mb-2">
                        {{ $posts->sum(function($post) { return $post->likes->count(); }) }}
                    </h3>
                    <p class="text-text-secondary dark:text-text-secondary-dark text-sm">Likes reçus</p>
                </div>

                <div class="card p-6 text-center hover:shadow-lg transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-comment text-white text-2xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-text-primary dark:text-text-primary-dark mb-2">
                        {{ $posts->sum(function($post) { return $post->comments->count(); }) }}
                    </h3>
                    <p class="text-text-secondary dark:text-text-secondary-dark text-sm">Commentaires</p>
                </div>

                <div class="card p-6 text-center hover:shadow-lg transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-share text-white text-2xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-text-primary dark:text-text-primary-dark mb-2">
                        {{ $posts->sum(function($post) { return $post->shares->count(); }) }}
                    </h3>
                    <p class="text-text-secondary dark:text-text-secondary-dark text-sm">Partages</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Sidebar Info -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- About Card -->
                    @if($user->profile)
                        <div class="card p-6">
                            <h3 class="text-xl font-bold text-text-primary dark:text-text-primary-dark mb-6 flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-info-circle text-white text-sm"></i>
                                </div>
                                À propos
                            </h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-start space-x-4">
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-envelope text-white"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-text-primary dark:text-text-primary-dark">Email</h4>
                                        <p class="text-text-secondary dark:text-text-secondary-dark">{{ $user->email }}</p>
                                    </div>
                                </div>

                                @if($user->profile->phone)
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-phone text-white"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-text-primary dark:text-text-primary-dark">Téléphone</h4>
                                            <p class="text-text-secondary dark:text-text-secondary-dark">{{ $user->profile->phone }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if($user->profile->birthday)
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-gradient-to-br from-pink-400 to-pink-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-birthday-cake text-white"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-text-primary dark:text-text-primary-dark">Anniversaire</h4>
                                            <p class="text-text-secondary dark:text-text-secondary-dark">{{ \Carbon\Carbon::parse($user->profile->birthday)->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if($user->profile->website)
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-globe text-white"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-text-primary dark:text-text-primary-dark">Site web</h4>
                                            <a href="{{ $user->profile->website }}" target="_blank" class="text-facebook-500 hover:text-facebook-600 transition-colors">{{ $user->profile->website }}</a>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if(Auth::id() === $user->id && (!$user->profile->phone || !$user->profile->birthday || !$user->profile->website))
                                <div class="mt-6 pt-6 border-t border-gray-100 dark:border-border-dark">
                                    <a href="{{ route('profile.edit') }}" class="flex items-center text-facebook-500 hover:text-facebook-600 transition-colors">
                                        <i class="fas fa-plus mr-2"></i>
                                        Compléter mon profil
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="card p-6 text-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-user text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-text-primary dark:text-text-primary-dark mb-2">Aucune information</h3>
                            <p class="text-text-secondary dark:text-text-secondary-dark mb-4">Ce profil n'a pas encore d'informations détaillées.</p>
                            @if(Auth::id() === $user->id)
                                <a href="{{ route('profile.edit') }}" class="btn-facebook">
                                    <i class="fas fa-user-edit mr-2"></i>
                                    Compléter mon profil
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Tabs Navigation -->
                    <div class="card p-6">
                        <div class="flex space-x-8 border-b border-gray-100 dark:border-border-dark">
                            <button class="pb-4 text-facebook-500 border-b-2 border-facebook-500 font-semibold" id="posts-tab" onclick="showTab('posts')">
                                <i class="fas fa-file-alt mr-2"></i>
                                Publications
                            </button>
                            <button class="pb-4 text-text-secondary dark:text-text-secondary-dark hover:text-facebook-500 transition-colors font-semibold" id="shares-tab" onclick="showTab('shares')">
                                <i class="fas fa-share-alt mr-2"></i>
                                Partages
                            </button>
                        </div>
                    </div>

                    <!-- Posts Tab -->
                    <div id="posts-content" class="space-y-6">
                        @if(Auth::id() === $user->id)
                            <div class="card p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold text-text-primary dark:text-text-primary-dark">Mes Publications</h3>
                                        <p class="text-text-secondary dark:text-text-secondary-dark">Partagez vos moments avec vos amis</p>
                                    </div>
                                    <a href="{{ route('posts.create') }}" class="btn-facebook">
                                        <i class="fas fa-plus mr-2"></i>
                                        Nouvelle publication
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($posts->count() > 0)
                            @foreach($posts as $post)
                                <div class="card p-6 hover:shadow-lg transition-all duration-300">
                                    <!-- Post Header -->
                                    <div class="flex items-center justify-between mb-6">
                                        <div class="flex items-center">
                                            @if($post->user->profile && $post->user->profile->profile_picture)
                                                <img src="{{ Storage::url($post->user->profile->profile_picture) }}" class="w-12 h-12 rounded-xl object-cover shadow-md" alt="Profile picture">
                                            @else
                                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-facebook-400 to-facebook-600 flex items-center justify-center shadow-md">
                                                    <span class="text-white font-bold text-lg">{{ substr($post->user->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <div class="ml-4">
                                                <h4 class="font-semibold text-text-primary dark:text-text-primary-dark">{{ $post->user->name }}</h4>
                                                <div class="flex items-center text-text-muted dark:text-text-muted-dark text-sm">
                                                    <span>{{ $post->created_at->diffForHumans() }}</span>
                                                    <span class="mx-2">•</span>
                                                    <i class="fas {{ $post->is_public ? 'fa-globe-americas text-green-500' : 'fa-lock text-gray-500' }} mr-1"></i>
                                                    <span>{{ $post->is_public ? 'Public' : 'Privé' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @if(Auth::id() === $post->user_id)
                                            <div class="relative" x-data="{ open: false }">
                                                <button @click="open = !open" class="p-2 text-text-secondary dark:text-text-secondary-dark hover:text-facebook-500 hover:bg-background-hover dark:hover:bg-background-hover-dark rounded-xl transition-all duration-300">
                                                    <i class="fas fa-ellipsis-h"></i>
                                                </button>
                                                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white dark:bg-background-card-dark rounded-xl shadow-2xl border border-gray-100 dark:border-border-dark py-2 z-10">
                                                    <a href="{{ route('posts.edit', $post) }}" class="flex items-center px-4 py-2 text-text-primary dark:text-text-primary-dark hover:bg-background-hover dark:hover:bg-background-hover-dark transition-colors">
                                                        <i class="fas fa-edit mr-3"></i>
                                                        Modifier
                                                    </a>
                                                    <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette publication ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-full flex items-center px-4 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                            <i class="fas fa-trash mr-3"></i>
                                                            Supprimer
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Post Content -->
                                    <div class="mb-6">
                                        <p class="text-text-primary dark:text-text-primary-dark leading-relaxed">{{ $post->content }}</p>
                                    </div>

                                    <!-- Post Media -->
                                    @if($post->image)
                                        <div class="mb-6">
                                            <img src="{{ Storage::url($post->image) }}" alt="Post image" class="w-full h-auto max-h-96 object-cover rounded-xl shadow-md">
                                        </div>
                                    @endif

                                    @if($post->video)
                                        <div class="mb-6">
                                            <video src="{{ Storage::url($post->video) }}" controls class="w-full h-auto max-h-96 rounded-xl shadow-md"></video>
                                        </div>
                                    @endif

                                    <!-- Post Engagement -->
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-border-dark">
                                        <div class="flex items-center space-x-6 text-text-secondary dark:text-text-secondary-dark">
                                            <div class="flex items-center">
                                                <i class="fas fa-heart text-red-500 mr-2"></i>
                                                <span>{{ $post->likes->count() }}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-comment text-blue-500 mr-2"></i>
                                                <span>{{ $post->comments->count() }}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-share text-green-500 mr-2"></i>
                                                <span>{{ $post->shares->count() }}</span>
                                            </div>
                                        </div>
                                        <a href="{{ route('posts.show', $post) }}" class="text-facebook-500 hover:text-facebook-600 font-medium transition-colors">
                                            Voir les détails →
                                        </a>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Pagination -->
                            @if($posts->hasPages())
                                <div class="flex justify-center">
                                    {{ $posts->links() }}
                                </div>
                            @endif
                        @else
                            <div class="card p-12 text-center">
                                <div class="w-24 h-24 bg-gradient-to-br from-orange-100 to-red-100 dark:from-orange-900/20 dark:to-red-900/20 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-file-alt text-orange-500 text-3xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-text-primary dark:text-text-primary-dark mb-3">Aucune publication</h3>
                                <p class="text-text-secondary dark:text-text-secondary-dark mb-6 max-w-md mx-auto">
                                    {{ Auth::id() === $user->id ? 'Vous n\'avez pas encore publié de contenu.' : $user->name . ' n\'a pas encore publié de contenu.' }}
                                </p>
                                @if(Auth::id() === $user->id)
                                    <a href="{{ route('posts.create') }}" class="btn-facebook">
                                        <i class="fas fa-plus mr-2"></i>
                                        Créer ma première publication
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Shares Tab -->
                    <div id="shares-content" class="space-y-6 hidden">
                        @if($sharedPosts->count() > 0)
                            @foreach($sharedPosts as $share)
                                <div class="card p-6 hover:shadow-lg transition-all duration-300">
                                    <!-- Share Header -->
                                    <div class="flex items-center justify-between mb-6">
                                        <div class="flex items-center">
                                            @if($user->profile && $user->profile->profile_picture)
                                                <img src="{{ Storage::url($user->profile->profile_picture) }}" class="w-12 h-12 rounded-xl object-cover shadow-md" alt="Profile picture">
                                            @else
                                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-facebook-400 to-facebook-600 flex items-center justify-center shadow-md">
                                                    <span class="text-white font-bold text-lg">{{ substr($user->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <div class="ml-4">
                                                <h4 class="font-semibold text-text-primary dark:text-text-primary-dark">{{ $user->name }} a partagé</h4>
                                                <p class="text-text-muted dark:text-text-muted-dark text-sm">{{ $share->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        
                                        @if(Auth::id() === $user->id)
                                            <form action="{{ route('shares.destroy', $share) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce partage ?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all duration-300">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    <!-- Share Comment -->
                                    @if($share->comment)
                                        <div class="mb-6 p-4 bg-background-hover dark:bg-background-hover-dark rounded-2xl">
                                            <p class="text-text-primary dark:text-text-primary-dark leading-relaxed">{{ $share->comment }}</p>
                                        </div>
                                    @endif

                                    <!-- Original Post -->
                                    <div class="p-6 border-2 border-gray-100 dark:border-border-dark rounded-2xl">
                                        <div class="flex items-center mb-4">
                                            @if($share->post->user->profile && $share->post->user->profile->profile_picture)
                                                <img src="{{ Storage::url($share->post->user->profile->profile_picture) }}" class="w-10 h-10 rounded-xl object-cover" alt="Profile picture">
                                            @else
                                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center">
                                                    <span class="text-white font-bold text-sm">{{ substr($share->post->user->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <div class="ml-3">
                                                <h5 class="font-semibold text-text-primary dark:text-text-primary-dark">{{ $share->post->user->name }}</h5>
                                                <p class="text-text-muted dark:text-text-muted-dark text-sm">{{ $share->post->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        
                                        <p class="text-text-primary dark:text-text-primary-dark mb-4">{{ $share->post->content }}</p>
                                        
                                        @if($share->post->image)
                                            <img src="{{ Storage::url($share->post->image) }}" class="w-full h-auto max-h-64 object-cover rounded-xl" alt="Post image">
                                        @endif
                                    </div>

                                    <div class="flex justify-end mt-4">
                                        <a href="{{ route('posts.show', $share->post) }}" class="text-facebook-500 hover:text-facebook-600 font-medium transition-colors">
                                            Voir la publication originale →
                                        </a>
                                    </div>
                                </div>
                            @endforeach

                            @if($sharedPosts->hasPages())
                                <div class="flex justify-center">
                                    {{ $sharedPosts->links() }}
                                </div>
                            @endif
                        @else
                            <div class="card p-12 text-center">
                                <div class="w-24 h-24 bg-gradient-to-br from-green-100 to-blue-100 dark:from-green-900/20 dark:to-blue-900/20 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-share-alt text-green-500 text-3xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-text-primary dark:text-text-primary-dark mb-3">Aucun partage</h3>
                                <p class="text-text-secondary dark:text-text-secondary-dark max-w-md mx-auto">
                                    {{ Auth::id() === $user->id ? 'Vous n\'avez pas encore partagé de publications.' : $user->name . ' n\'a pas encore partagé de publications.' }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function showTab(tabName) {
            // Hide all content
            document.getElementById('posts-content').classList.add('hidden');
            document.getElementById('shares-content').classList.add('hidden');
            
            // Show selected content
            document.getElementById(tabName + '-content').classList.remove('hidden');
            
            // Update tab styles
            document.getElementById('posts-tab').className = 'pb-4 text-text-secondary dark:text-text-secondary-dark hover:text-facebook-500 transition-colors font-semibold';
            document.getElementById('shares-tab').className = 'pb-4 text-text-secondary dark:text-text-secondary-dark hover:text-facebook-500 transition-colors font-semibold';
            
            document.getElementById(tabName + '-tab').className = 'pb-4 text-facebook-500 border-b-2 border-facebook-500 font-semibold';
        }
    </script>
    @endpush
</x-app-layout> 