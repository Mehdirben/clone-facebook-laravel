<div class="card p-6 animate-fade-in" x-data="{ 
    isLiked: {{ $share->post->isLikedBy(Auth::user()) ? 'true' : 'false' }}, 
    likesCount: {{ $share->post->likes()->count() }},
    showOptions: false 
}">
    <!-- En-tête du partage -->
    <div class="flex items-start justify-between mb-4">
        <div class="flex items-center space-x-3">
            @if ($share->user->profile && $share->user->profile->profile_picture)
                <img src="{{ Storage::url($share->user->profile->profile_picture) }}" alt="{{ $share->user->name }}" class="avatar avatar-md">
            @else
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-facebook-400 to-facebook-600 flex items-center justify-center">
                    <span class="text-white font-bold">{{ substr($share->user->name, 0, 1) }}</span>
                </div>
            @endif
            
            <div>
                <a href="{{ route('profile.show', $share->user) }}" class="font-semibold text-text-primary dark:text-text-primary-dark hover:text-facebook-500 transition-colors">
                    {{ $share->user->name }}
                </a>
                <div class="flex items-center space-x-1 text-text-secondary dark:text-text-secondary-dark text-sm">
                    <span>{{ $share->created_at->diffForHumans() }}</span>
                    <span>·</span>
                    <i class="fas fa-share-alt"></i>
                    <span>a partagé une publication</span>
                </div>
            </div>
        </div>
        
        @if ($share->user_id === Auth::id())
            <div class="relative" x-data="{ showOptions: false }">
                <button @click="showOptions = !showOptions" class="btn-icon text-text-secondary dark:text-text-secondary-dark hover:text-text-primary dark:hover:text-text-primary-dark">
                    <i class="fas fa-ellipsis-h"></i>
                </button>
                
                <div x-show="showOptions" 
                     @click.away="showOptions = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-1 transform scale-100"
                     class="absolute right-0 top-full mt-2 w-48 bg-white dark:bg-background-card-dark rounded-xl shadow-facebook-hover dark:shadow-facebook-dark border border-gray-100 dark:border-border-dark py-2 z-20">
                    
                    <form action="{{ route('shares.destroy', $share) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce partage?');" class="block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full text-left px-4 py-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors">
                            <i class="fas fa-trash-alt mr-2"></i>
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Commentaire du partage -->
    @if ($share->comment)
        <div class="mb-4 p-4 bg-gradient-to-r from-background-hover to-gray-100 dark:from-background-hover-dark dark:to-gray-700 rounded-xl">
            <p class="text-text-primary dark:text-text-primary-dark">{{ $share->comment }}</p>
        </div>
    @endif
    
    <!-- Publication originale -->
    <div class="border border-gray-200 dark:border-border-dark rounded-xl p-4 mb-4 bg-gradient-to-br from-white to-gray-50 dark:from-background-card-dark dark:to-gray-800">
        <div class="flex items-start space-x-3 mb-3">
            @if ($share->post->user->profile && $share->post->user->profile->profile_picture)
                <img src="{{ Storage::url($share->post->user->profile->profile_picture) }}" alt="{{ $share->post->user->name }}" class="w-8 h-8 rounded-xl object-cover">
            @else
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                    <span class="text-white text-xs font-bold">{{ substr($share->post->user->name, 0, 1) }}</span>
                </div>
            @endif
            
            <div class="flex-1">
                <a href="{{ route('profile.show', $share->post->user) }}" class="font-semibold text-text-primary dark:text-text-primary-dark hover:text-facebook-500 transition-colors">
                    {{ $share->post->user->name }}
                </a>
                <div class="flex items-center space-x-1 text-text-secondary dark:text-text-secondary-dark text-xs">
                    <span>{{ $share->post->created_at->diffForHumans() }}</span>
                    <span>·</span>
                    @if ($share->post->is_public)
                        <i class="fas fa-globe-americas"></i>
                    @else
                        <i class="fas fa-lock"></i>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Contenu de la publication originale -->
        <div class="mb-3">
            <p class="text-text-primary dark:text-text-primary-dark whitespace-pre-line leading-relaxed">{{ $share->post->content }}</p>
            
            @if ($share->post->image)
                <div class="mt-3 rounded-xl overflow-hidden">
                    <img src="{{ Storage::url($share->post->image) }}" alt="Image" class="w-full h-auto object-cover post-media">
                </div>
            @endif
            
            @if ($share->post->video)
                <div class="mt-3 rounded-xl overflow-hidden">
                    <video src="{{ Storage::url($share->post->video) }}" controls class="w-full h-auto post-media"></video>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Statistiques d'engagement -->
    <div class="flex justify-between items-center py-3 border-y border-gray-100 dark:border-border-dark mb-3">
        <div class="flex items-center space-x-2">
            <div x-show="likesCount > 0" class="flex items-center space-x-1">
                <div class="w-5 h-5 bg-facebook-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-thumbs-up text-white text-xs"></i>
                </div>
                <span x-text="likesCount" class="text-text-secondary dark:text-text-secondary-dark text-sm"></span>
            </div>
        </div>
        <div class="flex items-center space-x-4 text-text-secondary dark:text-text-secondary-dark text-sm">
            @php
                $commentsCount = $share->post->comments()->count();
                $sharesCount = $share->post->shares()->count();
            @endphp
            @if ($commentsCount > 0)
                <span>{{ $commentsCount }} commentaire{{ $commentsCount > 1 ? 's' : '' }}</span>
            @endif
            @if ($sharesCount > 0)
                <span>{{ $sharesCount }} partage{{ $sharesCount > 1 ? 's' : '' }}</span>
            @endif
        </div>
    </div>
    
    <!-- Actions d'interaction -->
    <div class="grid grid-cols-2 gap-2">
        <button 
            @click="
                isLiked = !isLiked;
                if (isLiked) {
                    likesCount++;
                    fetch('{{ route('posts.like', $share->post) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                } else {
                    likesCount--;
                    fetch('{{ route('posts.unlike', $share->post) }}', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                }
            " 
            :class="isLiked ? 'text-facebook-500' : 'text-text-secondary dark:text-text-secondary-dark'" 
            class="interaction-btn"
        >
            <i :class="isLiked ? 'fas fa-thumbs-up' : 'far fa-thumbs-up'" class="mr-2"></i>
            <span>J'aime</span>
        </button>
        
        <a href="{{ route('posts.show', $share->post) }}" class="interaction-btn">
            <i class="fas fa-external-link-alt mr-2"></i>
            <span>Voir l'original</span>
        </a>
    </div>
</div> 