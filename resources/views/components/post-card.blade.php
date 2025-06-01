<div class="card p-6 animate-fade-in" x-data="{ 
    showCommentForm: false, 
    showShareForm: false, 
    isLiked: {{ $post->isLikedBy(Auth::user()) ? 'true' : 'false' }}, 
    likesCount: {{ $post->likes()->count() }},
    showOptions: false 
}">
    <!-- En-tête du post -->
    <div class="flex items-start justify-between mb-4">
        <div class="flex items-center space-x-3">
            @if ($post->user->profile && $post->user->profile->profile_picture)
                <img src="{{ Storage::url($post->user->profile->profile_picture) }}" alt="{{ $post->user->name }}" class="w-12 h-12 rounded-xl object-cover ring-2 ring-transparent hover:ring-facebook-300 dark:hover:ring-facebook-600 transition-all duration-300 shadow-md">
            @else
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-facebook-400 to-facebook-600 flex items-center justify-center shadow-md">
                    <span class="text-white font-bold text-lg">{{ substr($post->user->name, 0, 1) }}</span>
                </div>
            @endif
            
            <div>
                <a href="{{ route('profile.show', $post->user) }}" class="font-semibold text-text-primary dark:text-text-primary-dark hover:text-facebook-500 transition-colors">
                    {{ $post->user->name }}
                </a>
                <div class="flex items-center space-x-2 text-text-secondary dark:text-text-secondary-dark text-sm">
                    <span>{{ $post->created_at->diffForHumans() }}</span>
                    <span>·</span>
                    @if ($post->is_public)
                        <i class="fas fa-globe-americas text-xs" title="Public"></i>
                    @else
                        <i class="fas fa-lock text-xs" title="Privé"></i>
                    @endif
                </div>
            </div>
        </div>
        
        @if ($post->user_id === Auth::id())
            <div class="relative">
                <button @click="showOptions = !showOptions" class="p-2 text-text-secondary dark:text-text-secondary-dark hover:text-text-primary dark:hover:text-text-primary-dark hover:bg-background-hover dark:hover:bg-background-hover-dark rounded-full transition-all duration-200">
                    <i class="fas fa-ellipsis-h"></i>
                </button>
                <div x-show="showOptions" @click.away="showOptions = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-1 transform scale-100"
                     class="absolute right-0 top-full mt-1 w-48 bg-white dark:bg-background-card-dark rounded-xl shadow-facebook dark:shadow-facebook-dark border border-gray-100 dark:border-border-dark py-2 z-10">
                    <a href="{{ route('posts.edit', $post) }}" class="flex items-center px-4 py-3 text-text-primary dark:text-text-primary-dark hover:bg-background-hover dark:hover:bg-background-hover-dark transition-colors">
                        <i class="fas fa-edit w-5 mr-3 text-text-secondary dark:text-text-secondary-dark"></i>
                        <span>Modifier</span>
                    </a>
                    <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette publication?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center px-4 py-3 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                            <i class="fas fa-trash w-5 mr-3"></i>
                            <span>Supprimer</span>
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Contenu du post -->
    <div class="mb-4">
        <p class="text-text-primary dark:text-text-primary-dark whitespace-pre-line leading-relaxed text-base">{{ $post->content }}</p>
        
        @if ($post->image)
            <div class="mt-4 rounded-2xl overflow-hidden">
                <img src="{{ Storage::url($post->image) }}" alt="Image" class="w-full h-auto object-cover hover:scale-[1.02] transition-transform duration-300">
            </div>
        @endif
        
        @if ($post->video)
            <div class="mt-4 rounded-2xl overflow-hidden">
                <video src="{{ Storage::url($post->video) }}" controls class="w-full h-auto"></video>
            </div>
        @endif
    </div>
    
    <!-- Statistiques d'engagement -->
    @php
        $commentsCount = $post->comments()->count();
        $sharesCount = $post->shares()->count();
        $hasEngagement = $post->likes()->count() > 0 || $commentsCount > 0 || $sharesCount > 0;
    @endphp
    
    @if($hasEngagement)
        <div class="flex justify-between items-center py-3 border-y border-gray-100 dark:border-border-dark mb-3">
            <div class="flex items-center space-x-2">
                <div x-show="likesCount > 0" class="flex items-center space-x-2">
                    <div class="w-6 h-6 bg-facebook-500 rounded-full flex items-center justify-center shadow-sm">
                        <i class="fas fa-thumbs-up text-white text-xs"></i>
                    </div>
                    <span x-text="likesCount" class="text-text-secondary dark:text-text-secondary-dark text-sm font-medium"></span>
                </div>
            </div>
            <div class="flex items-center space-x-4 text-text-secondary dark:text-text-secondary-dark text-sm">
                @if ($commentsCount > 0)
                    <button @click="showCommentForm = !showCommentForm" class="hover:text-text-primary dark:hover:text-text-primary-dark transition-colors hover:underline">
                        {{ $commentsCount }} commentaire{{ $commentsCount > 1 ? 's' : '' }}
                    </button>
                @endif
                @if ($sharesCount > 0)
                    <span>{{ $sharesCount }} partage{{ $sharesCount > 1 ? 's' : '' }}</span>
                @endif
            </div>
        </div>
    @endif
    
    <!-- Actions d'interaction -->
    <div class="grid grid-cols-3 gap-2 {{ $hasEngagement ? '' : 'pt-3 border-t border-gray-100 dark:border-border-dark' }}">
        <button 
            @click="
                isLiked = !isLiked;
                if (isLiked) {
                    likesCount++;
                    fetch('{{ route('posts.like', $post) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                } else {
                    likesCount--;
                    fetch('{{ route('posts.unlike', $post) }}', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                }
            " 
            :class="isLiked ? 'text-facebook-500' : 'text-text-secondary'" 
            class="interaction-btn"
        >
            <i :class="isLiked ? 'fas fa-thumbs-up' : 'far fa-thumbs-up'" class="mr-2"></i>
            <span>J'aime</span>
        </button>
        
        <button @click="showCommentForm = !showCommentForm" class="interaction-btn">
            <i class="far fa-comment mr-2"></i>
            <span>Commenter</span>
        </button>
        
        <button @click="showShareForm = !showShareForm" class="interaction-btn">
            <i class="far fa-share-square mr-2"></i>
            <span>Partager</span>
        </button>
    </div>
    
    <!-- Formulaire de partage -->
    <div x-show="showShareForm" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-1 transform scale-100"
         class="mt-4 p-4 bg-background-hover dark:bg-background-hover-dark rounded-xl">
        
        <form action="{{ route('posts.share', $post) }}" method="POST">
            @csrf
            <div class="flex space-x-3">
                @if (Auth::user()->profile && Auth::user()->profile->profile_picture)
                    <img src="{{ Storage::url(Auth::user()->profile->profile_picture) }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-xl object-cover shadow-md">
                @else
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-facebook-400 to-facebook-600 flex items-center justify-center shadow-md">
                        <span class="text-white text-sm font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                @endif
                
                <div class="flex-1">
                    <textarea name="comment" rows="2" placeholder="Ajouter un commentaire à votre partage (facultatif)..." 
                              class="w-full border-0 bg-white dark:bg-background-card-dark rounded-xl px-4 py-3 focus:ring-2 focus:ring-facebook-500 resize-none text-text-primary dark:text-text-primary-dark placeholder-text-muted dark:placeholder-text-muted-dark"></textarea>
                    
                    <div class="flex justify-end mt-3">
                        <button type="submit" class="btn-facebook">
                            <i class="fas fa-share mr-2"></i>
                            Partager
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Formulaire de commentaire -->
    <div x-show="showCommentForm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-1 transform scale-100"
         class="mt-4">
        
        <form action="{{ route('posts.comments.store', $post) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex space-x-3">
                @if (Auth::user()->profile && Auth::user()->profile->profile_picture)
                    <img src="{{ Storage::url(Auth::user()->profile->profile_picture) }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-xl object-cover shadow-md">
                @else
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-facebook-400 to-facebook-600 flex items-center justify-center shadow-md">
                        <span class="text-white text-sm font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                @endif
                
                <div class="flex-1">
                    <div class="bg-background-hover dark:bg-background-hover-dark rounded-2xl p-4">
                        <textarea name="content" rows="2" 
                                  placeholder="Écrivez un commentaire..." 
                                  class="w-full border-0 bg-transparent focus:ring-0 resize-none text-text-primary dark:text-text-primary-dark placeholder-text-muted dark:placeholder-text-muted-dark"></textarea>
                        
                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                            <div class="flex items-center space-x-3">
                                <label class="cursor-pointer text-text-secondary dark:text-text-secondary-dark hover:text-facebook-500 transition-colors">
                                    <i class="fas fa-image mr-1"></i>
                                    <span class="text-sm">Photo</span>
                                    <input type="file" name="image" accept="image/*" class="hidden">
                                </label>
                                <label class="cursor-pointer text-text-secondary dark:text-text-secondary-dark hover:text-facebook-500 transition-colors">
                                    <i class="fas fa-smile mr-1"></i>
                                    <span class="text-sm">Emoji</span>
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
    
    <!-- Liste des commentaires -->
    @if($post->comments()->count() > 0)
        <div class="mt-4 space-y-4">
            @foreach ($post->comments()->with('user', 'likes')->latest()->limit(3)->get() as $comment)
                <div class="flex items-start space-x-3" x-data="{ showCommentOptions: false }">
                    @if ($comment->user->profile && $comment->user->profile->profile_picture)
                        <img src="{{ Storage::url($comment->user->profile->profile_picture) }}" alt="{{ $comment->user->name }}" class="w-10 h-10 rounded-xl object-cover shadow-md">
                    @else
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-facebook-400 to-facebook-600 flex items-center justify-center shadow-md">
                            <span class="text-white text-sm font-bold">{{ substr($comment->user->name, 0, 1) }}</span>
                        </div>
                    @endif
                    
                    <div class="flex-1">
                        <div class="comment-bubble relative">
                            <div class="flex justify-between items-start">
                                <a href="{{ route('profile.show', $comment->user) }}" class="font-semibold text-text-primary dark:text-text-primary-dark hover:text-facebook-500 text-sm">
                                    {{ $comment->user->name }}
                                </a>
                                
                                @if ($comment->user_id === Auth::id())
                                    <button @click="showCommentOptions = !showCommentOptions" class="text-text-muted dark:text-text-muted-dark hover:text-text-primary dark:hover:text-text-primary-dark p-1 rounded transition-colors">
                                        <i class="fas fa-ellipsis-h text-xs"></i>
                                    </button>
                                    
                                    <div x-show="showCommentOptions" @click.away="showCommentOptions = false"
                                         class="absolute right-0 top-8 w-40 bg-white dark:bg-background-card-dark rounded-lg shadow-facebook dark:shadow-facebook-dark border border-gray-100 dark:border-border-dark py-1 z-10">
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Supprimer ce commentaire?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors">
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                            
                            <p class="text-text-primary dark:text-text-primary-dark text-sm mt-1">{{ $comment->content }}</p>
                            
                            @if ($comment->image)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($comment->image) }}" alt="Image" class="rounded-lg max-h-40 w-auto">
                                </div>
                            @endif
                        </div>
                        
                        <!-- Actions du commentaire -->
                        <div class="flex items-center space-x-4 mt-2 text-xs text-text-secondary dark:text-text-secondary-dark">
                            <button class="hover:text-facebook-500 font-medium transition-colors">J'aime</button>
                            <button class="hover:text-facebook-500 font-medium transition-colors">Répondre</button>
                            <span>{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
            
            @if ($post->comments()->count() > 3)
                <button class="text-text-secondary dark:text-text-secondary-dark hover:text-text-primary dark:hover:text-text-primary-dark text-sm font-medium transition-colors hover:underline">
                    Voir les {{ $post->comments()->count() - 3 }} autres commentaires
                </button>
            @endif
        </div>
    @endif
</div> 