<div class="bg-white shadow rounded-lg mb-4 p-4">
    <div class="flex items-start">
        @if ($post->user->profile && $post->user->profile->profile_picture)
            <img src="{{ Storage::url($post->user->profile->profile_picture) }}" alt="{{ $post->user->name }}" class="w-10 h-10 rounded-full mr-3">
        @else
            <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                <span class="text-gray-600 text-sm font-bold">{{ substr($post->user->name, 0, 1) }}</span>
            </div>
        @endif
        
        <div class="flex-1">
            <div class="flex items-center justify-between mb-1">
                <div>
                    <a href="{{ route('profile.show', $post->user) }}" class="font-semibold text-blue-600 hover:underline">{{ $post->user->name }}</a>
                    <p class="text-gray-500 text-xs">{{ $post->created_at->diffForHumans() }} · 
                        @if ($post->is_public)
                            <i class="fas fa-globe-americas"></i> Public
                        @else
                            <i class="fas fa-lock"></i> Privé
                        @endif
                    </p>
                </div>
                
                @if ($post->user_id === Auth::id())
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="text-gray-500 hover:bg-gray-100 rounded p-1">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 z-10 bg-white border border-gray-200 rounded shadow-lg w-48">
                            <a href="{{ route('posts.edit', $post) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Modifier</a>
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette publication?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Supprimer</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
            
            <div class="mb-3">
                <p class="text-gray-800 whitespace-pre-line">{{ $post->content }}</p>
                
                @if ($post->image)
                    <div class="mt-3">
                        <img src="{{ Storage::url($post->image) }}" alt="Image" class="rounded-lg max-h-96 w-auto">
                    </div>
                @endif
                
                @if ($post->video)
                    <div class="mt-3">
                        <video src="{{ Storage::url($post->video) }}" controls class="rounded-lg max-h-96 w-auto"></video>
                    </div>
                @endif
            </div>
            
            <div class="flex justify-between items-center text-gray-500 border-t border-b py-2 mb-3">
                <div>
                    @php
                        $likesCount = $post->likes()->count();
                    @endphp
                    @if ($likesCount > 0)
                        <span><i class="fas fa-thumbs-up text-blue-600"></i> {{ $likesCount }}</span>
                    @endif
                </div>
                <div>
                    @php
                        $commentsCount = $post->comments()->count();
                    @endphp
                    @if ($commentsCount > 0)
                        <span>{{ $commentsCount }} commentaire(s)</span>
                    @endif
                </div>
            </div>
            
            <div class="flex justify-around mb-3">
                @php
                    $liked = $post->likes()->where('user_id', Auth::id())->first();
                @endphp
                
                @if ($liked)
                    <form action="{{ route('posts.unlike', $post) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex items-center text-blue-600 hover:bg-gray-100 px-3 py-1 rounded">
                            <i class="fas fa-thumbs-up mr-1"></i> J'aime
                        </button>
                    </form>
                @else
                    <form action="{{ route('posts.like', $post) }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="type" value="like">
                        <button type="submit" class="flex items-center text-gray-600 hover:bg-gray-100 px-3 py-1 rounded">
                            <i class="far fa-thumbs-up mr-1"></i> J'aime
                        </button>
                    </form>
                @endif
                
                <button @click="$refs.commentForm.classList.toggle('hidden')" class="flex items-center text-gray-600 hover:bg-gray-100 px-3 py-1 rounded">
                    <i class="far fa-comment-alt mr-1"></i> Commenter
                </button>
                
                <button class="flex items-center text-gray-600 hover:bg-gray-100 px-3 py-1 rounded">
                    <i class="far fa-share-square mr-1"></i> Partager
                </button>
            </div>
            
            <div x-ref="commentForm" class="hidden">
                <form action="{{ route('posts.comments.store', $post) }}" method="POST" enctype="multipart/form-data" class="mb-4">
                    @csrf
                    <div class="flex">
                        @if (Auth::user()->profile && Auth::user()->profile->profile_picture)
                            <img src="{{ Storage::url(Auth::user()->profile->profile_picture) }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full mr-2">
                        @else
                            <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center mr-2">
                                <span class="text-gray-600 text-xs font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <textarea name="content" rows="2" placeholder="Écrire un commentaire..." class="w-full rounded-lg border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"></textarea>
                            
                            <div class="flex justify-between items-center mt-2">
                                <div>
                                    <label for="comment-image-{{ $post->id }}" class="cursor-pointer text-gray-500 hover:text-gray-700">
                                        <i class="far fa-image"></i>
                                    </label>
                                    <input id="comment-image-{{ $post->id }}" type="file" name="image" class="hidden" accept="image/*">
                                </div>
                                
                                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                    Commenter
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="mt-4 space-y-4">
                @foreach ($post->comments()->with('user', 'likes')->latest()->limit(3)->get() as $comment)
                    <div class="flex items-start">
                        @if ($comment->user->profile && $comment->user->profile->profile_picture)
                            <img src="{{ Storage::url($comment->user->profile->profile_picture) }}" alt="{{ $comment->user->name }}" class="w-8 h-8 rounded-full mr-2">
                        @else
                            <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center mr-2">
                                <span class="text-gray-600 text-xs font-bold">{{ substr($comment->user->name, 0, 1) }}</span>
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <div class="bg-gray-100 rounded-lg p-3">
                                <div class="flex justify-between items-start">
                                    <a href="{{ route('profile.show', $comment->user) }}" class="font-semibold text-blue-600 hover:underline">{{ $comment->user->name }}</a>
                                    
                                    @if ($comment->user_id === Auth::id())
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open" class="text-gray-500 hover:bg-gray-200 rounded p-1">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </button>
                                            <div x-show="open" @click.away="open = false" class="absolute right-0 z-10 bg-white border border-gray-200 rounded shadow-lg w-48">
                                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Modifier</a>
                                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Supprimer</button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                <p class="text-gray-800 text-sm mt-1">{{ $comment->content }}</p>
                                
                                @if ($comment->image)
                                    <div class="mt-2">
                                        <img src="{{ Storage::url($comment->image) }}" alt="Image" class="rounded-lg max-h-40 w-auto">
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex space-x-3 text-xs text-gray-500 mt-1 ml-1">
                                <span class="text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                
                                @php
                                    $liked = $comment->likes()->where('user_id', Auth::id())->first();
                                @endphp
                                
                                @if ($liked)
                                    <form action="{{ route('comments.unlike', $comment) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-blue-600 hover:underline">
                                            J'aime
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('comments.like', $comment) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="type" value="like">
                                        <button type="submit" class="hover:underline">
                                            J'aime
                                        </button>
                                    </form>
                                @endif
                                
                                <button @click="$refs.replyForm{{ $comment->id }}.classList.toggle('hidden')" class="hover:underline">
                                    Répondre
                                </button>
                            </div>
                            
                            <div x-ref="replyForm{{ $comment->id }}" class="hidden mt-2">
                                <form action="{{ route('comments.reply', $comment) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="flex">
                                        <div class="flex-1">
                                            <textarea name="content" rows="1" placeholder="Écrire une réponse..." class="w-full rounded-lg border border-gray-300 p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"></textarea>
                                            
                                            <div class="flex justify-between items-center mt-1">
                                                <div>
                                                    <label for="reply-image-{{ $comment->id }}" class="cursor-pointer text-gray-500 hover:text-gray-700">
                                                        <i class="far fa-image"></i>
                                                    </label>
                                                    <input id="reply-image-{{ $comment->id }}" type="file" name="image" class="hidden" accept="image/*">
                                                </div>
                                                
                                                <button type="submit" class="bg-blue-600 text-white px-2 py-1 rounded text-xs hover:bg-blue-700">
                                                    Répondre
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                            @if ($comment->replies->count() > 0)
                                <div class="mt-2 ml-6 space-y-2">
                                    @foreach ($comment->replies as $reply)
                                        <div class="flex items-start">
                                            @if ($reply->user->profile && $reply->user->profile->profile_picture)
                                                <img src="{{ Storage::url($reply->user->profile->profile_picture) }}" alt="{{ $reply->user->name }}" class="w-6 h-6 rounded-full mr-2">
                                            @else
                                                <div class="w-6 h-6 rounded-full bg-gray-300 flex items-center justify-center mr-2">
                                                    <span class="text-gray-600 text-xs font-bold">{{ substr($reply->user->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            
                                            <div class="flex-1">
                                                <div class="bg-gray-100 rounded-lg p-2">
                                                    <a href="{{ route('profile.show', $reply->user) }}" class="font-semibold text-blue-600 hover:underline text-sm">{{ $reply->user->name }}</a>
                                                    <p class="text-gray-800 text-sm">{{ $reply->content }}</p>
                                                    
                                                    @if ($reply->image)
                                                        <div class="mt-1">
                                                            <img src="{{ Storage::url($reply->image) }}" alt="Image" class="rounded-lg max-h-32 w-auto">
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <div class="flex space-x-3 text-xs text-gray-500 mt-1 ml-1">
                                                    <span class="text-gray-400">{{ $reply->created_at->diffForHumans() }}</span>
                                                    
                                                    @php
                                                        $liked = $reply->likes()->where('user_id', Auth::id())->first();
                                                    @endphp
                                                    
                                                    @if ($liked)
                                                        <form action="{{ route('comments.unlike', $reply) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-blue-600 hover:underline">
                                                                J'aime
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('comments.like', $reply) }}" method="POST" class="inline">
                                                            @csrf
                                                            <input type="hidden" name="type" value="like">
                                                            <button type="submit" class="hover:underline">
                                                                J'aime
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
                
                @if ($post->comments()->count() > 3)
                    <div class="text-center">
                        <a href="{{ route('posts.show', $post) }}" class="text-blue-600 hover:underline text-sm">Voir tous les commentaires</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div> 