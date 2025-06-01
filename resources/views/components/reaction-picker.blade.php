<div x-data="{ showReactions: false }" class="relative inline-block">
    <!-- Main Like Button -->
    <button 
        @mouseenter="showReactions = true"
        @mouseleave="setTimeout(() => showReactions = false, 300)"
        @click="
            isLiked = !isLiked;
            if (isLiked) {
                likesCount++;
                fetch('{{ $likeRoute }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
            } else {
                likesCount--;
                fetch('{{ $unlikeRoute }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
            }
        " 
        :class="isLiked ? 'text-facebook-500' : 'text-text-secondary'" 
        class="interaction-btn group"
    >
        <i :class="isLiked ? 'fas fa-thumbs-up' : 'far fa-thumbs-up'" class="mr-2 group-hover:scale-110 transition-transform"></i>
        <span>J'aime</span>
    </button>

    <!-- Reactions Picker -->
    <div 
        x-show="showReactions"
        @mouseenter="showReactions = true"
        @mouseleave="showReactions = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform scale-90 translate-y-2"
        x-transition:enter-end="opacity-1 transform scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-1 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90"
        class="absolute bottom-full left-0 mb-2 bg-white rounded-full shadow-facebook-hover border border-gray-100 p-2 flex space-x-1 z-20"
    >
        <!-- Like -->
        <button @click="react('like')" class="reaction-btn group" title="J'aime">
            <div class="w-10 h-10 rounded-full bg-facebook-500 flex items-center justify-center group-hover:scale-125 transition-transform">
                <i class="fas fa-thumbs-up text-white text-sm"></i>
            </div>
        </button>
        
        <!-- Love -->
        <button @click="react('love')" class="reaction-btn group" title="J'adore">
            <div class="w-10 h-10 rounded-full bg-red-500 flex items-center justify-center group-hover:scale-125 transition-transform">
                <i class="fas fa-heart text-white text-sm"></i>
            </div>
        </button>
        
        <!-- Laugh -->
        <button @click="react('laugh')" class="reaction-btn group" title="Haha">
            <div class="w-10 h-10 rounded-full bg-yellow-500 flex items-center justify-center group-hover:scale-125 transition-transform">
                <span class="text-white text-lg">😂</span>
            </div>
        </button>
        
        <!-- Wow -->
        <button @click="react('wow')" class="reaction-btn group" title="Wow">
            <div class="w-10 h-10 rounded-full bg-orange-500 flex items-center justify-center group-hover:scale-125 transition-transform">
                <span class="text-white text-lg">😮</span>
            </div>
        </button>
        
        <!-- Sad -->
        <button @click="react('sad')" class="reaction-btn group" title="Triste">
            <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center group-hover:scale-125 transition-transform">
                <span class="text-white text-lg">😢</span>
            </div>
        </button>
        
        <!-- Angry -->
        <button @click="react('angry')" class="reaction-btn group" title="En colère">
            <div class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center group-hover:scale-125 transition-transform">
                <span class="text-white text-lg">😡</span>
            </div>
        </button>
    </div>
</div>

<script>
function react(type) {
    // Send reaction to server
    fetch('{{ $reactRoute }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ reaction: type })
    }).then(response => response.json())
      .then(data => {
          // Update UI based on response
          console.log('Reaction added:', type);
      });
}
</script>

<style>
.reaction-btn {
    position: relative;
    overflow: hidden;
}

.reaction-btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.3s, height 0.3s;
}

.reaction-btn:hover::before {
    width: 100%;
    height: 100%;
}
</style> 