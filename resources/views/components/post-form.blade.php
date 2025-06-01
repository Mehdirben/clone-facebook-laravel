<div class="card p-6 hover:shadow-lg transition-all duration-300">
    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" x-data="{ 
        showOptions: false, 
        hasContent: false, 
        showLocationPicker: false,
        selectedLocation: null,
        visibilityOpen: false,
        selectedVisibility: { 
            value: 'public', 
            label: 'Public', 
            description: 'Tout le monde', 
            icon: 'fas fa-globe-americas',
            gradient: 'from-green-400 to-green-600'
        }
    }">
        @csrf
        
        <!-- Header avec avatar et input -->
        <div class="flex space-x-4 mb-6">
            @if (Auth::user()->profile && Auth::user()->profile->profile_picture)
                <img src="{{ Storage::url(Auth::user()->profile->profile_picture) }}" alt="{{ Auth::user()->name }}" class="w-12 h-12 rounded-xl object-cover shadow-md ring-2 ring-transparent hover:ring-facebook-300 dark:hover:ring-facebook-600 transition-all duration-300">
            @else
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-facebook-400 to-facebook-600 flex items-center justify-center shadow-md">
                    <span class="text-white font-bold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
            @endif
            
            <div class="flex-1">
                <textarea name="content" rows="3" 
                          placeholder="Que voulez-vous partager, {{ Auth::user()->name }} ?" 
                          class="w-full border-0 bg-background-hover dark:bg-background-hover-dark rounded-2xl px-4 py-3 focus:ring-2 focus:ring-facebook-500 resize-none text-text-primary dark:text-text-primary-dark placeholder-text-muted dark:placeholder-text-muted-dark transition-all duration-300"
                          @focus="showOptions = true"
                          @input="hasContent = $event.target.value.length > 0"></textarea>
            </div>
        </div>

        <!-- Selected Location Display -->
        <div x-show="selectedLocation" class="mb-4 animate-fade-in">
            <div class="flex items-center space-x-3 p-3 bg-pink-50 dark:bg-pink-900/20 rounded-xl border border-pink-200 dark:border-pink-800">
                <div class="w-10 h-10 bg-gradient-to-br from-pink-400 to-pink-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-map-marker-alt text-white"></i>
                </div>
                <div class="flex-1">
                    <div class="font-medium text-text-primary dark:text-text-primary-dark">Se trouve à <span x-text="selectedLocation"></span></div>
                </div>
                <button type="button" @click="selectedLocation = null" class="p-1 hover:bg-pink-200 dark:hover:bg-pink-800 rounded-full transition-colors">
                    <i class="fas fa-times text-pink-600 dark:text-pink-400 text-sm"></i>
                </button>
            </div>
        </div>
        
        <!-- Prévisualisation des médias -->
        <div id="preview" class="hidden mb-6 animate-fade-in">
            <div class="relative bg-background-hover dark:bg-background-hover-dark rounded-2xl p-4 border-2 border-dashed border-gray-300 dark:border-gray-600">
                <img id="image-preview" class="hidden w-full h-auto max-h-96 object-cover rounded-xl" alt="Aperçu de l'image">
                <video id="video-preview" class="hidden w-full h-auto max-h-96 rounded-xl" controls></video>
                <button type="button" id="remove-media" 
                        class="absolute top-2 right-2 p-2 bg-red-500 text-white rounded-full hover:bg-red-600 transition-all duration-300 shadow-lg hover:scale-110">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>
        
        <!-- Options et actions -->
        <div x-show="showOptions" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-1 transform scale-100"
             class="border-t border-gray-100 dark:border-border-dark pt-6">
            
            <!-- Actions rapides -->
            <div class="mb-6">
                <div class="text-text-primary dark:text-text-primary-dark font-semibold mb-4 flex items-center">
                    <i class="fas fa-plus text-facebook-500 mr-2"></i>
                    Ajouter à votre publication
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    <label for="image" class="flex flex-col items-center p-4 rounded-xl hover:bg-green-50 dark:hover:bg-green-900/10 cursor-pointer transition-all duration-300 group border-2 border-transparent hover:border-green-200 dark:hover:border-green-800">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center mb-2 group-hover:scale-110 transition-transform shadow-md">
                            <i class="far fa-image text-white text-lg"></i>
                        </div>
                        <span class="text-sm font-medium text-text-primary dark:text-text-primary-dark group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">Photo</span>
                        <input id="image" type="file" name="image" class="hidden" accept="image/*" onchange="previewImage(this)">
                    </label>
                    
                    <label for="video" class="flex flex-col items-center p-4 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/10 cursor-pointer transition-all duration-300 group border-2 border-transparent hover:border-red-200 dark:hover:border-red-800">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center mb-2 group-hover:scale-110 transition-transform shadow-md">
                            <i class="fas fa-video text-white text-lg"></i>
                        </div>
                        <span class="text-sm font-medium text-text-primary dark:text-text-primary-dark group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">Vidéo</span>
                        <input id="video" type="file" name="video" class="hidden" accept="video/*" onchange="previewVideo(this)">
                    </label>
                    
                    <div class="relative">
                        <button type="button" 
                                @click.prevent.stop="showLocationPicker = !showLocationPicker; visibilityOpen = false" 
                                class="w-full flex flex-col items-center p-4 rounded-xl hover:bg-pink-50 dark:hover:bg-pink-900/10 transition-all duration-300 group border-2 border-transparent hover:border-pink-200 dark:hover:border-pink-800">
                            <div class="w-12 h-12 bg-gradient-to-br from-pink-400 to-pink-600 rounded-xl flex items-center justify-center mb-2 group-hover:scale-110 transition-transform shadow-md">
                                <i class="fas fa-map-marker-alt text-white text-lg"></i>
                            </div>
                            <span class="text-sm font-medium text-text-primary dark:text-text-primary-dark group-hover:text-pink-600 dark:group-hover:text-pink-400 transition-colors">Lieu</span>
                        </button>
                        
                        <!-- Location Picker -->
                        <div x-show="showLocationPicker" 
                             @click.away="showLocationPicker = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-1 transform scale-100"
                             class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 w-72 bg-white dark:bg-background-card-dark rounded-2xl shadow-2xl border border-gray-100 dark:border-border-dark p-4 z-50"
                             style="position: fixed; z-index: 9999;">
                            <h3 class="font-semibold text-text-primary dark:text-text-primary-dark mb-3 text-center">Où êtes-vous ?</h3>
                            <div class="space-y-2">
                                <button type="button" 
                                        @click.prevent.stop="selectedLocation = 'Paris, France'; showLocationPicker = false" 
                                        class="w-full text-left p-3 rounded-xl hover:bg-pink-50 dark:hover:bg-pink-900/20 transition-colors">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-map-marker-alt text-pink-500"></i>
                                        <div>
                                            <div class="font-medium text-text-primary dark:text-text-primary-dark">Paris, France</div>
                                            <div class="text-sm text-text-secondary dark:text-text-secondary-dark">Ville</div>
                                        </div>
                                    </div>
                                </button>
                                <button type="button" 
                                        @click.prevent.stop="selectedLocation = 'Chez moi'; showLocationPicker = false" 
                                        class="w-full text-left p-3 rounded-xl hover:bg-pink-50 dark:hover:bg-pink-900/20 transition-colors">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-home text-pink-500"></i>
                                        <div>
                                            <div class="font-medium text-text-primary dark:text-text-primary-dark">Chez moi</div>
                                            <div class="text-sm text-text-secondary dark:text-text-secondary-dark">Domicile</div>
                                        </div>
                                    </div>
                                </button>
                                <button type="button" 
                                        @click.prevent.stop="selectedLocation = 'Au travail'; showLocationPicker = false" 
                                        class="w-full text-left p-3 rounded-xl hover:bg-pink-50 dark:hover:bg-pink-900/20 transition-colors">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-building text-pink-500"></i>
                                        <div>
                                            <div class="font-medium text-text-primary dark:text-text-primary-dark">Au travail</div>
                                            <div class="text-sm text-text-secondary dark:text-text-secondary-dark">Bureau</div>
                                        </div>
                                    </div>
                                </button>
                                <div class="border-t border-gray-200 dark:border-gray-600 pt-2 mt-2">
                                    <input type="text" 
                                           placeholder="Rechercher un lieu..." 
                                           class="w-full p-2 bg-background-hover dark:bg-background-hover-dark rounded-lg border-0 text-sm focus:ring-2 focus:ring-pink-500" 
                                           @keyup.enter.prevent.stop="selectedLocation = $event.target.value; showLocationPicker = false; $event.target.value = ''">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Visibilité et publication -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <!-- Sélecteur de visibilité -->
                    <div class="relative">
                        <button type="button" @click.stop="visibilityOpen = !visibilityOpen; showLocationPicker = false" 
                                class="flex items-center space-x-3 px-4 py-3 bg-background-hover dark:bg-background-hover-dark rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-300 border-2 border-transparent hover:border-facebook-200 dark:hover:border-facebook-700">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-gradient-to-br" :class="selectedVisibility.gradient">
                                <i :class="selectedVisibility.icon" class="text-white text-sm"></i>
                            </div>
                            <div class="text-left">
                                <div class="text-text-primary dark:text-text-primary-dark text-sm font-semibold" x-text="selectedVisibility.label"></div>
                                <div class="text-text-muted dark:text-text-muted-dark text-xs" x-text="selectedVisibility.description"></div>
                            </div>
                            <i class="fas fa-chevron-down text-text-muted dark:text-text-muted-dark text-xs transition-transform duration-300" :class="{ 'rotate-180': visibilityOpen }"></i>
                        </button>
                        
                        <div x-show="visibilityOpen" @click.away="visibilityOpen = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-1 transform scale-100"
                             class="absolute top-full left-0 mt-2 w-80 bg-white dark:bg-background-card-dark rounded-2xl shadow-2xl dark:shadow-facebook-dark border border-gray-100 dark:border-border-dark py-3 z-50">
                            <div class="px-4 py-2 border-b border-gray-100 dark:border-border-dark">
                                <h3 class="font-semibold text-text-primary dark:text-text-primary-dark">Choisir l'audience</h3>
                            </div>
                            
                            <button type="button" @click="selectedVisibility = { value: 'public', label: 'Public', description: 'Tout le monde', icon: 'fas fa-globe-americas', gradient: 'from-green-400 to-green-600' }; visibilityOpen = false" 
                                    class="w-full flex items-center space-x-4 px-4 py-4 hover:bg-background-hover dark:hover:bg-background-hover-dark transition-colors text-left">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center shadow-md">
                                    <i class="fas fa-globe-americas text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="font-semibold text-text-primary dark:text-text-primary-dark">Public</div>
                                    <div class="text-sm text-text-secondary dark:text-text-secondary-dark">Tout le monde peut voir cette publication</div>
                                </div>
                            </button>
                            
                            <button type="button" @click="selectedVisibility = { value: 'friends', label: 'Amis', description: 'Vos amis', icon: 'fas fa-user-friends', gradient: 'from-blue-400 to-blue-600' }; visibilityOpen = false" 
                                    class="w-full flex items-center space-x-4 px-4 py-4 hover:bg-background-hover dark:hover:bg-background-hover-dark transition-colors text-left">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center shadow-md">
                                    <i class="fas fa-user-friends text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="font-semibold text-text-primary dark:text-text-primary-dark">Amis</div>
                                    <div class="text-sm text-text-secondary dark:text-text-secondary-dark">Seuls vos amis peuvent voir</div>
                                </div>
                            </button>
                            
                            <button type="button" @click="selectedVisibility = { value: 'private', label: 'Privé', description: 'Seulement vous', icon: 'fas fa-lock', gradient: 'from-gray-400 to-gray-600' }; visibilityOpen = false" 
                                    class="w-full flex items-center space-x-4 px-4 py-4 hover:bg-background-hover dark:hover:bg-background-hover-dark transition-colors text-left">
                                <div class="w-12 h-12 bg-gradient-to-br from-gray-400 to-gray-600 rounded-xl flex items-center justify-center shadow-md">
                                    <i class="fas fa-lock text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="font-semibold text-text-primary dark:text-text-primary-dark">Privé</div>
                                    <div class="text-sm text-text-secondary dark:text-text-secondary-dark">Seulement vous</div>
                                </div>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Input caché pour compatibilité -->
                    <input type="hidden" name="visibility" x-bind:value="selectedVisibility.value">
                    <!-- Hidden inputs for location -->
                    <input type="hidden" name="location" x-bind:value="selectedLocation">
                </div>
                
                <!-- Bouton publier -->
                <button type="submit" 
                        class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-facebook-500 to-facebook-600 text-white font-semibold rounded-xl hover:from-facebook-600 hover:to-facebook-700 focus:ring-4 focus:ring-facebook-300 dark:focus:ring-facebook-800 transition-all duration-300 shadow-lg hover:shadow-facebook-glow disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                        :disabled="!hasContent"
                        :class="{ 'opacity-50 cursor-not-allowed': !hasContent }">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Publier
                </button>
            </div>
        </div>
        
        <!-- Actions minimales (quand pas focus) -->
        <div x-show="!showOptions" class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-border-dark">
            <div class="flex items-center space-x-6">
                <label for="image-quick" class="flex items-center space-x-3 text-text-secondary dark:text-text-secondary-dark hover:text-facebook-500 transition-colors group cursor-pointer">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="far fa-image text-white"></i>
                    </div>
                    <span class="font-medium">Photo</span>
                    <input id="image-quick" type="file" name="image" class="hidden" accept="image/*" onchange="previewImage(this); document.querySelector('[x-data]').__x.$data.showOptions = true;">
                </label>
                <label for="video-quick" class="flex items-center space-x-3 text-text-secondary dark:text-text-secondary-dark hover:text-facebook-500 transition-colors group cursor-pointer">
                    <div class="w-10 h-10 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-video text-white"></i>
                    </div>
                    <span class="font-medium">Vidéo</span>
                    <input id="video-quick" type="file" name="video" class="hidden" accept="video/*" onchange="previewVideo(this); document.querySelector('[x-data]').__x.$data.showOptions = true;">
                </label>
            </div>
            
            <button type="submit" 
                    class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-text-secondary dark:text-text-secondary-dark font-medium rounded-xl hover:bg-facebook-500 hover:text-white transition-all duration-300"
                    :disabled="!hasContent"
                    :class="{ 'opacity-50 cursor-not-allowed': !hasContent }">
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
            
            // Clear video inputs
            document.getElementById('video').value = '';
            const videoQuick = document.getElementById('video-quick');
            if (videoQuick) videoQuick.value = '';
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
            
            // Clear image inputs
            document.getElementById('image').value = '';
            const imageQuick = document.getElementById('image-quick');
            if (imageQuick) imageQuick.value = '';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

document.getElementById('remove-media').addEventListener('click', function() {
    document.getElementById('image-preview').classList.add('hidden');
    document.getElementById('video-preview').classList.add('hidden');
    document.getElementById('preview').classList.add('hidden');
    
    // Clear all inputs
    document.getElementById('image').value = '';
    document.getElementById('video').value = '';
    const imageQuick = document.getElementById('image-quick');
    const videoQuick = document.getElementById('video-quick');
    if (imageQuick) imageQuick.value = '';
    if (videoQuick) videoQuick.value = '';
});
</script> 