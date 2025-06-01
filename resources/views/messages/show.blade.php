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
                                <p class="text-text-secondary dark:text-text-secondary-dark text-sm">Conversation</p>
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
                            <div class="flex items-center text-facebook-500 bg-facebook-50 dark:bg-facebook-900/20 p-3 rounded-xl">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-comment-alt text-white"></i>
                                </div>
                                <span class="font-semibold">Messages</span>
                            </div>
                            <a href="{{ route('notifications.index') }}" class="flex items-center text-text-secondary dark:text-text-secondary-dark hover:text-facebook-500 hover:bg-background-hover dark:hover:bg-background-hover-dark p-3 rounded-xl transition-all duration-300 group">
                                <div class="w-10 h-10 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-bell text-white"></i>
                                </div>
                                <span class="font-medium">Notifications</span>
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Main Chat Content -->
                <div class="lg:col-span-3">
                    <div class="card h-full flex flex-col" style="height: calc(100vh - 8rem);">
                        <!-- Chat Header -->
                        <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-border-dark">
                            <div class="flex items-center">
                                <a href="{{ route('messages.index') }}" class="mr-4 p-2 text-text-secondary dark:text-text-secondary-dark hover:text-facebook-500 hover:bg-background-hover dark:hover:bg-background-hover-dark rounded-xl transition-all duration-300">
                                    <i class="fas fa-arrow-left text-lg"></i>
                                </a>
                                
                                <div class="relative">
                                    @if($user->profile && $user->profile->profile_picture)
                                        <img src="{{ Storage::url($user->profile->profile_picture) }}" class="w-12 h-12 rounded-xl object-cover shadow-md" alt="Profile picture">
                                    @else
                                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center shadow-md">
                                            <span class="text-white font-bold text-lg">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <!-- Online indicator -->
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white dark:border-background-card-dark rounded-full"></div>
                                </div>
                                
                                <div class="ml-4">
                                    <h3 class="font-semibold text-text-primary dark:text-text-primary-dark text-lg">{{ $user->name }}</h3>
                                    <p class="text-text-muted dark:text-text-muted-dark text-sm">En ligne maintenant</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('profile.show', $user) }}" class="btn-facebook">
                                    <i class="fas fa-user mr-2"></i>
                                    Voir le profil
                                </a>
                            </div>
                        </div>

                        <!-- Messages Container -->
                        <div class="flex-1 overflow-y-auto p-6 space-y-4" id="chat-messages">
                            @if($messages->count() > 0)
                                @foreach($messages as $message)
                                    <div class="flex {{ $message->user_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                                        <div class="max-w-md">
                                            @if($message->user_id !== Auth::id())
                                                <div class="flex items-end space-x-3">
                                                    @if($user->profile && $user->profile->profile_picture)
                                                        <img src="{{ Storage::url($user->profile->profile_picture) }}" class="w-8 h-8 rounded-full object-cover" alt="Profile picture">
                                                    @else
                                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center">
                                                            <span class="text-white font-bold text-xs">{{ substr($user->name, 0, 1) }}</span>
                                                        </div>
                                                    @endif
                                                    <div>
                                            @endif
                                            
                                            <div class="message-bubble {{ $message->user_id === Auth::id() ? 'bg-gradient-to-r from-facebook-500 to-facebook-600 text-white' : 'bg-background-hover dark:bg-background-hover-dark text-text-primary dark:text-text-primary-dark' }} px-4 py-3 rounded-2xl {{ $message->user_id === Auth::id() ? 'rounded-br-md' : 'rounded-bl-md' }} shadow-md">
                                                @if($message->attachment)
                                                    <div class="message-attachment mb-3">
                                                        @php
                                                            $extension = pathinfo($message->attachment, PATHINFO_EXTENSION);
                                                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                                        @endphp
                                                        
                                                        @if($isImage)
                                                            <img src="{{ Storage::url($message->attachment) }}" class="w-full max-w-xs h-auto rounded-xl shadow-md cursor-pointer hover:scale-105 transition-transform" alt="Attachment">
                                                        @else
                                                            <a href="{{ Storage::url($message->attachment) }}" target="_blank" class="flex items-center p-3 bg-white/20 rounded-xl hover:bg-white/30 transition-all duration-300">
                                                                <i class="fas fa-file text-xl mr-3"></i>
                                                                <span class="font-medium">Télécharger le fichier</span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                @endif
                                                
                                                @if($message->content)
                                                    <p class="leading-relaxed">{{ $message->content }}</p>
                                                @endif
                                            </div>
                                            
                                            <div class="flex items-center mt-2 {{ $message->user_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                                                <span class="text-text-muted dark:text-text-muted-dark text-xs">
                                                    {{ $message->created_at->format('H:i') }}
                                                </span>
                                                @if($message->is_read && $message->user_id === Auth::id())
                                                    <i class="fas fa-check-double ml-2 text-facebook-500 text-xs"></i>
                                                @endif
                                            </div>
                                            
                                            @if($message->user_id !== Auth::id())
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="flex flex-col items-center justify-center h-full text-center py-16">
                                    <div class="w-24 h-24 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/20 dark:to-pink-900/20 rounded-3xl flex items-center justify-center mb-6">
                                        <i class="fas fa-comment-alt text-purple-500 text-3xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-text-primary dark:text-text-primary-dark mb-3">Commencez la conversation !</h3>
                                    <p class="text-text-secondary dark:text-text-secondary-dark max-w-md">
                                        Envoyez votre premier message à {{ $user->name }} et commencez à échanger.
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Message Input -->
                        <div class="p-6 border-t border-gray-100 dark:border-border-dark">
                            <form action="{{ route('messages.send', $user) }}" method="POST" enctype="multipart/form-data" x-data="{ hasContent: false, selectedFile: null }" class="flex items-center space-x-3">
                                @csrf
                                
                                <!-- Attachment Button -->
                                <div class="flex-shrink-0">
                                    <label for="attachment" class="cursor-pointer w-14 h-14 bg-background-hover dark:bg-background-hover-dark hover:bg-facebook-50 dark:hover:bg-facebook-900/20 rounded-2xl flex items-center justify-center text-text-secondary dark:text-text-secondary-dark hover:text-facebook-500 transition-all duration-300 group shadow-sm hover:shadow-md border border-transparent hover:border-facebook-200 dark:hover:border-facebook-700">
                                        <i class="fas fa-paperclip text-xl group-hover:scale-110 transition-transform"></i>
                                    </label>
                                    <input type="file" name="attachment" id="attachment" class="hidden" @change="selectedFile = $event.target.files[0]">
                                </div>

                                <!-- Message Input Container -->
                                <div class="flex-1 relative">
                                    <textarea name="content" rows="1" 
                                              class="w-full border-0 bg-background-hover dark:bg-background-hover-dark rounded-2xl px-5 py-3.5 focus:ring-2 focus:ring-facebook-500 resize-none text-text-primary dark:text-text-primary-dark placeholder-text-muted dark:placeholder-text-muted-dark transition-all duration-300 max-h-32 shadow-sm focus:shadow-md"
                                              placeholder="Écrivez votre message..."
                                              @input="hasContent = $event.target.value.trim().length > 0"
                                              @keydown.enter.prevent="if (!$event.shiftKey && (hasContent || selectedFile)) { $event.target.form.submit(); }"
                                              style="scrollbar-width: none; -ms-overflow-style: none; min-height: 48px; line-height: 1.4;"></textarea>
                                    
                                    <!-- File Preview -->
                                    <div x-show="selectedFile" x-cloak class="absolute bottom-full left-0 right-0 mb-3 p-4 bg-white dark:bg-background-card-dark rounded-2xl border border-gray-200 dark:border-border-dark shadow-lg">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 bg-facebook-500 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-file text-white text-sm"></i>
                                                </div>
                                                <span class="text-text-primary dark:text-text-primary-dark text-sm font-medium" x-text="selectedFile?.name"></span>
                                            </div>
                                            <button type="button" @click="selectedFile = null; document.getElementById('attachment').value = ''" class="w-8 h-8 text-red-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg flex items-center justify-center transition-all duration-300">
                                                <i class="fas fa-times text-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Send Button -->
                                <div class="flex-shrink-0">
                                    <button type="submit" 
                                            class="w-14 h-14 bg-gradient-to-r from-facebook-500 to-facebook-600 text-white rounded-2xl hover:from-facebook-600 hover:to-facebook-700 transition-all duration-300 shadow-md hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-md flex items-center justify-center group transform hover:scale-105"
                                            :disabled="!hasContent && !selectedFile"
                                            :class="{ 
                                                'opacity-50 cursor-not-allowed': !hasContent && !selectedFile,
                                                'bg-gradient-to-r from-facebook-500 to-facebook-600 hover:from-facebook-600 hover:to-facebook-700': hasContent || selectedFile,
                                                'bg-gray-300 dark:bg-gray-600': !hasContent && !selectedFile
                                            }">
                                        <i class="fas fa-paper-plane text-xl group-hover:translate-x-0.5 transition-transform" :class="{ 'animate-pulse': hasContent || selectedFile }"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Scroll to bottom of chat
            const chatMessages = document.getElementById('chat-messages');
            chatMessages.scrollTop = chatMessages.scrollHeight;
            
            // Auto-resize textarea
            const textarea = document.querySelector('textarea[name="content"]');
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 128) + 'px';
            });
            
            // Focus on message input
            textarea.focus();
        });
    </script>
    @endpush
</x-app-layout> 