<div class="card p-6">
    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" x-data="{ showOptions: false }">
        @csrf
        
        <!-- Header avec avatar et input -->
        <div class="flex space-x-4 mb-4">
            @if (Auth::user()->profile && Auth::user()->profile->profile_picture)
                <img src="{{ Storage::url(Auth::user()->profile->profile_picture) }}" alt="{{ Auth::user()->name }}" class="w-12 h-12 rounded-xl object-cover shadow-md">
            @else
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-facebook-400 to-facebook-600 flex items-center justify-center shadow-md">
                    <span class="text-white font-bold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
            @endif
            
            <div class="flex-1">
                <textarea name="content" rows="3" 
                          placeholder="Que voulez-vous partager, {{ Auth::user()->name }} ?" 
                          class="input-facebook resize-none"
                          @focus="showOptions = true"></textarea>
            </div>
        </div>
        
        <!-- Prévisualisation des médias -->
        <div id="preview" class="hidden mb-4 animate-fade-in">
            <div class="relative bg-background-hover dark:bg-background-hover-dark rounded-2xl p-4">
                <img id="image-preview" class="hidden post-media" alt="Aperçu de l'image">
                <video id="video-preview" class="hidden post-media" controls></video>
                <button type="button" id="remove-media" 
                        class="absolute top-2 right-2 p-2 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors shadow-md">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>
        
        <!-- Options et actions -->
        <div x-show="showOptions" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-1 transform scale-100"
             class="border-t border-gray-100 dark:border-border-dark pt-4">
            
            <!-- Actions rapides -->
            <div class="flex items-center justify-between mb-4">
                <div class="text-text-primary dark:text-text-primary-dark font-medium">Ajouter à votre publication</div>
                <div class="flex items-center space-x-2">
                    <label for="image" class="p-3 rounded-full hover:bg-background-hover dark:hover:bg-background-hover-dark cursor-pointer transition-colors group">
                        <i class="far fa-image text-xl text-green-500 group-hover:text-green-600 group-hover:scale-110 transition-all duration-200"></i>
                        <input id="image" type="file" name="image" class="hidden" accept="image/*" onchange="previewImage(this)">
                    </label>
                    
                    <label for="video" class="p-3 rounded-full hover:bg-background-hover dark:hover:bg-background-hover-dark cursor-pointer transition-colors group">
                        <i class="fas fa-video text-xl text-red-500 group-hover:text-red-600 group-hover:scale-110 transition-all duration-200"></i>
                        <input id="video" type="file" name="video" class="hidden" accept="video/*" onchange="previewVideo(this)">
                    </label>
                    
                    <button type="button" class="p-3 rounded-full hover:bg-background-hover dark:hover:bg-background-hover-dark transition-colors group">
                        <i class="far fa-smile text-xl text-yellow-500 group-hover:text-yellow-600 group-hover:scale-110 transition-all duration-200"></i>
                    </button>
                    
                    <button type="button" class="p-3 rounded-full hover:bg-background-hover dark:hover:bg-background-hover-dark transition-colors group">
                        <i class="fas fa-map-marker-alt text-xl text-pink-500 group-hover:text-pink-600 group-hover:scale-110 transition-all duration-200"></i>
                    </button>
                </div>
            </div>
            
            <!-- Visibilité et publication -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <!-- Sélecteur de visibilité -->
                    <div class="relative" x-data="{ open: false }">
                        <button type="button" @click="open = !open" 
                                class="flex items-center space-x-2 px-4 py-2 bg-background-hover dark:bg-background-hover-dark rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                            <i class="fas fa-globe-americas text-text-secondary dark:text-text-secondary-dark"></i>
                            <span class="text-text-primary dark:text-text-primary-dark text-sm font-medium">Public</span>
                            <i class="fas fa-chevron-down text-text-muted dark:text-text-muted-dark text-xs"></i>
                        </button>
                        
                        <div x-show="open" @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-1 transform scale-100"
                             class="absolute top-full left-0 mt-1 w-56 bg-white dark:bg-background-card-dark rounded-xl shadow-facebook dark:shadow-facebook-dark border border-gray-100 dark:border-border-dark py-2 z-10">
                            <label class="flex items-center space-x-3 px-4 py-3 hover:bg-background-hover dark:hover:bg-background-hover-dark cursor-pointer">
                                <input type="radio" name="visibility" value="public" checked class="sr-only">
                                <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-full">
                                    <i class="fas fa-globe-americas text-green-500"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-text-primary dark:text-text-primary-dark">Public</div>
                                    <div class="text-xs text-text-secondary dark:text-text-secondary-dark">Tout le monde peut voir cette publication</div>
                                </div>
                            </label>
                            <label class="flex items-center space-x-3 px-4 py-3 hover:bg-background-hover dark:hover:bg-background-hover-dark cursor-pointer">
                                <input type="radio" name="visibility" value="friends" class="sr-only">
                                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                                    <i class="fas fa-user-friends text-blue-500"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-text-primary dark:text-text-primary-dark">Amis</div>
                                    <div class="text-xs text-text-secondary dark:text-text-secondary-dark">Seuls vos amis peuvent voir</div>
                                </div>
                            </label>
                            <label class="flex items-center space-x-3 px-4 py-3 hover:bg-background-hover dark:hover:bg-background-hover-dark cursor-pointer">
                                <input type="radio" name="visibility" value="private" class="sr-only">
                                <div class="p-2 bg-gray-100 dark:bg-gray-900/30 rounded-full">
                                    <i class="fas fa-lock text-gray-500"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-text-primary dark:text-text-primary-dark">Privé</div>
                                    <div class="text-xs text-text-secondary dark:text-text-secondary-dark">Seulement vous</div>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Input caché pour compatibilité -->
                    <input type="hidden" name="is_public" value="1">
                </div>
                
                <!-- Bouton publier -->
                <button type="submit" class="btn-facebook">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Publier
                </button>
            </div>
        </div>
        
        <!-- Actions minimales (quand pas focus) -->
        <div x-show="!showOptions" class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <button type="button" @click="showOptions = true" class="flex items-center space-x-2 text-text-secondary dark:text-text-secondary-dark hover:text-text-primary dark:hover:text-text-primary-dark transition-colors">
                    <i class="far fa-image"></i>
                    <span class="text-sm">Photo/Vidéo</span>
                </button>
                <button type="button" @click="showOptions = true" class="flex items-center space-x-2 text-text-secondary dark:text-text-secondary-dark hover:text-text-primary dark:hover:text-text-primary-dark transition-colors">
                    <i class="far fa-smile"></i>
                    <span class="text-sm">Humeur</span>
                </button>
            </div>
            
            <button type="submit" class="btn-secondary">
                Publier
            </button>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('image-preview').src = e.target.result;
            document.getElementById('image-preview').classList.remove('hidden');
            document.getElementById('video-preview').classList.add('hidden');
            document.getElementById('preview').classList.remove('hidden');
            document.getElementById('video').value = '';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function previewVideo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('video-preview').src = e.target.result;
            document.getElementById('video-preview').classList.remove('hidden');
            document.getElementById('image-preview').classList.add('hidden');
            document.getElementById('preview').classList.remove('hidden');
            document.getElementById('image').value = '';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

document.getElementById('remove-media').addEventListener('click', function() {
    document.getElementById('image-preview').classList.add('hidden');
    document.getElementById('video-preview').classList.add('hidden');
    document.getElementById('preview').classList.add('hidden');
    document.getElementById('image').value = '';
    document.getElementById('video').value = '';
});
</script> 