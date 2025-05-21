<div class="bg-white shadow rounded-lg mb-6 p-4">
    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="flex">
            @if (Auth::user()->profile && Auth::user()->profile->profile_picture)
                <img src="{{ Storage::url(Auth::user()->profile->profile_picture) }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full mr-3">
            @else
                <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                    <span class="text-gray-600 text-sm font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
            @endif
            
            <div class="flex-1">
                <textarea name="content" rows="3" placeholder="Que voulez-vous partager, {{ Auth::user()->name }}?" class="w-full rounded-lg border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"></textarea>
                
                <div id="preview" class="hidden my-3">
                    <img id="image-preview" class="rounded-lg max-h-80 w-auto hidden" alt="Aperçu de l'image">
                    <video id="video-preview" class="rounded-lg max-h-80 w-auto hidden" controls></video>
                    <button type="button" id="remove-media" class="mt-2 text-red-600 hover:text-red-800">
                        <i class="fas fa-times mr-1"></i> Supprimer
                    </button>
                </div>
                
                <div class="border-t pt-3 mt-3">
                    <div class="flex justify-between items-center">
                        <div class="flex space-x-4">
                            <label for="image" class="flex items-center cursor-pointer text-gray-600 hover:text-blue-600">
                                <i class="far fa-image mr-1"></i> Photo
                                <input id="image" type="file" name="image" class="hidden" accept="image/*" onchange="previewImage(this)">
                            </label>
                            
                            <label for="video" class="flex items-center cursor-pointer text-gray-600 hover:text-blue-600">
                                <i class="fas fa-video mr-1"></i> Vidéo
                                <input id="video" type="file" name="video" class="hidden" accept="video/*" onchange="previewVideo(this)">
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="mr-3">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_public" value="1" checked class="mr-1 focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                    <span class="text-sm text-gray-600"><i class="fas fa-globe-americas mr-1"></i> Public</span>
                                </label>
                            </div>
                            
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                Publier
                            </button>
                        </div>
                    </div>
                </div>
            </div>
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