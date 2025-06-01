<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Retour au fil d'actualité -->
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-facebook-500 hover:text-facebook-600 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                <span>Retour au fil d'actualité</span>
            </a>
        </div>

        <!-- Publication principale -->
        <div class="mb-6">
            <x-post-card :post="$post" />
        </div>

        <!-- Section des commentaires -->
        <div class="card p-6">
            <h3 class="text-xl font-bold text-text-primary dark:text-text-primary-dark mb-6 flex items-center">
                <div class="w-8 h-8 bg-gradient-to-br from-facebook-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-comments text-white text-sm"></i>
                </div>
                Commentaires ({{ $post->comments()->count() }})
            </h3>

            <!-- Formulaire d'ajout de commentaire -->
            @auth
                <div class="mb-6 pb-6 border-b border-gray-100 dark:border-border-dark">
                    <form action="{{ route('posts.comments.store', $post) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex space-x-3">
                            @if (Auth::user()->profile && Auth::user()->profile->profile_picture)
                                <img src="{{ Storage::url(Auth::user()->profile->profile_picture) }}" alt="{{ Auth::user()->name }}" class="avatar avatar-sm">
                            @else
                                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-facebook-400 to-facebook-600 flex items-center justify-center">
                                    <span class="text-white text-xs font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                            @endif
                            
                            <div class="flex-1">
                                <div class="bg-background-hover dark:bg-background-hover-dark rounded-2xl p-4">
                                    <textarea name="content" rows="3" 
                                              placeholder="Écrivez un commentaire..." 
                                              class="w-full border-0 bg-transparent focus:ring-0 resize-none text-text-primary dark:text-text-primary-dark placeholder-text-muted dark:placeholder-text-muted-dark"></textarea>
                                    
                                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                                        <div class="flex items-center space-x-3">
                                            <label class="cursor-pointer text-text-secondary dark:text-text-secondary-dark hover:text-facebook-500 transition-colors">
                                                <i class="fas fa-image mr-1"></i>
                                                <span class="text-sm">Photo</span>
                                                <input type="file" name="image" accept="image/*" class="hidden">
                                            </label>
                                        </div>
                                        
                                        <button type="submit" class="btn-facebook px-4 py-2 text-sm">
                                            <i class="fas fa-paper-plane mr-2"></i>
                                            Commenter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @endauth

            <!-- Liste des commentaires -->
            <div class="space-y-4">
                @forelse ($post->comments()->with(['user.profile', 'replies.user.profile'])->latest()->get() as $comment)
                    <div class="flex space-x-3 animate-fade-in">
                        @if ($comment->user->profile && $comment->user->profile->profile_picture)
                            <img src="{{ Storage::url($comment->user->profile->profile_picture) }}" alt="{{ $comment->user->name }}" class="avatar avatar-sm">
                        @else
                            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                <span class="text-white text-xs font-bold">{{ substr($comment->user->name, 0, 1) }}</span>
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <div class="comment-bubble">
                                <div class="mb-1">
                                    <a href="{{ route('profile.show', $comment->user) }}" class="font-semibold text-text-primary dark:text-text-primary-dark hover:text-facebook-500 transition-colors">
                                        {{ $comment->user->name }}
                                    </a>
                                </div>
                                <p class="text-text-primary dark:text-text-primary-dark leading-relaxed">{{ $comment->content }}</p>
                                
                                @if ($comment->image)
                                    <div class="mt-2">
                                        <img src="{{ Storage::url($comment->image) }}" alt="Image du commentaire" class="rounded-xl max-h-48 w-auto">
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex items-center space-x-4 mt-2 text-text-secondary dark:text-text-secondary-dark text-sm">
                                <span>{{ $comment->created_at->diffForHumans() }}</span>
                                
                                @auth
                                    <button class="hover:text-facebook-500 transition-colors">
                                        <i class="far fa-thumbs-up mr-1"></i>
                                        J'aime
                                    </button>
                                    
                                    <button class="hover:text-facebook-500 transition-colors">
                                        <i class="far fa-comment mr-1"></i>
                                        Répondre
                                    </button>
                                @endauth
                                
                                @if ($comment->user_id === Auth::id())
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" class="hover:text-red-500 transition-colors">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        
                                        <div x-show="open" @click.away="open = false"
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 transform scale-95"
                                             x-transition:enter-end="opacity-1 transform scale-100"
                                             class="absolute right-0 top-full mt-1 w-32 bg-white dark:bg-background-card-dark rounded-xl shadow-facebook-hover dark:shadow-facebook-dark border border-gray-100 dark:border-border-dark py-2 z-10">
                                            
                                            <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full text-left px-3 py-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors text-sm">
                                                    <i class="fas fa-trash mr-2"></i>
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Réponses au commentaire -->
                            @if ($comment->replies && $comment->replies->count() > 0)
                                <div class="mt-4 ml-4 space-y-3 border-l-2 border-gray-200 dark:border-gray-600 pl-4">
                                    @foreach ($comment->replies as $reply)
                                        <div class="flex space-x-3">
                                            @if ($reply->user->profile && $reply->user->profile->profile_picture)
                                                <img src="{{ Storage::url($reply->user->profile->profile_picture) }}" alt="{{ $reply->user->name }}" class="w-6 h-6 rounded-lg object-cover">
                                            @else
                                                <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                                                    <span class="text-white text-xs font-bold">{{ substr($reply->user->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            
                                            <div class="flex-1">
                                                <div class="bg-gray-100 dark:bg-gray-700 rounded-xl px-3 py-2">
                                                    <div class="mb-1">
                                                        <a href="{{ route('profile.show', $reply->user) }}" class="font-semibold text-text-primary dark:text-text-primary-dark hover:text-facebook-500 transition-colors text-sm">
                                                            {{ $reply->user->name }}
                                                        </a>
                                                    </div>
                                                    <p class="text-text-primary dark:text-text-primary-dark text-sm">{{ $reply->content }}</p>
                                                </div>
                                                
                                                <div class="flex items-center space-x-3 mt-1 text-text-secondary dark:text-text-secondary-dark text-xs">
                                                    <span>{{ $reply->created_at->diffForHumans() }}</span>
                                                    
                                                    @auth
                                                        <button class="hover:text-facebook-500 transition-colors">J'aime</button>
                                                    @endauth
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-comment-slash text-gray-400 dark:text-gray-500 text-xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-text-primary dark:text-text-primary-dark mb-2">Aucun commentaire</h4>
                        <p class="text-text-secondary dark:text-text-secondary-dark">Soyez le premier à commenter cette publication !</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout> 