<div class="bg-white shadow rounded-lg mb-4 p-4" x-data="{ isLiked: {{ $share->post->isLikedBy(Auth::user()) ? 'true' : 'false' }}, likesCount: {{ $share->post->likes()->count() }} }">
    <div class="flex items-start">
        @if ($share->user->profile && $share->user->profile->profile_picture)
            <img src="{{ Storage::url($share->user->profile->profile_picture) }}" alt="{{ $share->user->name }}" class="w-10 h-10 rounded-full mr-3">
        @else
            <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                <span class="text-gray-600 text-sm font-bold">{{ substr($share->user->name, 0, 1) }}</span>
            </div>
        @endif
        
        <div class="flex-1">
            <div class="flex items-center justify-between mb-1">
                <div>
                    <a href="{{ route('profile.show', $share->user) }}" class="font-semibold text-blue-600 hover:underline">{{ $share->user->name }}</a>
                    <p class="text-gray-500 text-xs">
                        <span>{{ $share->created_at->diffForHumans() }}</span>
                        <span class="ml-2"><i class="fas fa-share-alt"></i> a partagé une publication</span>
                    </p>
                </div>
                
                @if ($share->user_id === Auth::id())
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="text-gray-500 hover:bg-gray-100 rounded p-1">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 z-10 bg-white border border-gray-200 rounded shadow-lg w-48">
                            <form action="{{ route('shares.destroy', $share) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce partage?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Supprimer</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
            
            @if ($share->comment)
                <div class="mb-3 p-3 bg-gray-100 rounded-lg">
                    <p class="text-gray-800">{{ $share->comment }}</p>
                </div>
            @endif
            
            <div class="border border-gray-200 rounded-lg p-4 mb-3">
                <div class="flex items-start">
                    @if ($share->post->user->profile && $share->post->user->profile->profile_picture)
                        <img src="{{ Storage::url($share->post->user->profile->profile_picture) }}" alt="{{ $share->post->user->name }}" class="w-8 h-8 rounded-full mr-2">
                    @else
                        <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center mr-2">
                            <span class="text-gray-600 text-xs font-bold">{{ substr($share->post->user->name, 0, 1) }}</span>
                        </div>
                    @endif
                    
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <div>
                                <a href="{{ route('profile.show', $share->post->user) }}" class="font-semibold text-blue-600 hover:underline">{{ $share->post->user->name }}</a>
                                <p class="text-gray-500 text-xs">{{ $share->post->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <p class="text-gray-800 whitespace-pre-line">{{ $share->post->content }}</p>
                            
                            @if ($share->post->image)
                                <div class="mt-3">
                                    <img src="{{ Storage::url($share->post->image) }}" alt="Image" class="rounded-lg max-h-64 w-auto">
                                </div>
                            @endif
                            
                            @if ($share->post->video)
                                <div class="mt-3">
                                    <video src="{{ Storage::url($share->post->video) }}" controls class="rounded-lg max-h-64 w-auto"></video>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-between items-center text-gray-500 border-t border-b py-2 mb-3">
                <div>
                    <span x-show="likesCount > 0"><i class="fas fa-thumbs-up text-blue-600"></i> <span x-text="likesCount"></span></span>
                </div>
                <div class="flex space-x-4">
                    @php
                        $commentsCount = $share->post->comments()->count();
                        $sharesCount = $share->post->shares()->count();
                    @endphp
                    @if ($commentsCount > 0)
                        <span>{{ $commentsCount }} commentaire(s)</span>
                    @endif
                    @if ($sharesCount > 0)
                        <span>{{ $sharesCount }} partage(s)</span>
                    @endif
                </div>
            </div>
            
            <div class="flex justify-around">
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
                    :class="isLiked ? 'text-blue-600' : 'text-gray-600'" 
                    class="flex items-center hover:bg-gray-100 px-3 py-1 rounded"
                >
                    <i :class="isLiked ? 'fas fa-thumbs-up' : 'far fa-thumbs-up'" class="mr-1"></i> J'aime
                </button>
                
                <a href="{{ route('posts.show', $share->post) }}" class="flex items-center text-blue-600 hover:bg-gray-100 px-3 py-1 rounded">
                    <i class="fas fa-external-link-alt mr-1"></i> Voir la publication originale
                </a>
            </div>
        </div>
    </div>
</div> 