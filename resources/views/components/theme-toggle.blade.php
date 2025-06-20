<div x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" 
     x-init="
        $watch('darkMode', value => {
            localStorage.setItem('darkMode', value);
            if (value) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        });
        if (darkMode) {
            document.documentElement.classList.add('dark');
        }
     "
     class="relative">
    
    <button @click="darkMode = !darkMode" 
            class="p-2 bg-background-hover dark:bg-background-hover-dark hover:bg-gray-300 dark:hover:bg-gray-600 rounded-full transition-all duration-300 group">
        
        <!-- Sun Icon (Light Mode) -->
        <div x-show="!darkMode" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 rotate-90 scale-0"
             x-transition:enter-end="opacity-1 rotate-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-1 rotate-0 scale-100"
             x-transition:leave-end="opacity-0 -rotate-90 scale-0"
             class="w-5 h-5 text-yellow-500 group-hover:text-yellow-600">
            <svg fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.166a.75.75 0 00-1.06-1.06l-1.591 1.59a.75.75 0 101.06 1.061l1.591-1.59zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5H21a.75.75 0 01.75.75zM17.834 18.894a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 10-1.061 1.06l1.59 1.591zM12 18a.75.75 0 01.75.75V21a.75.75 0 01-1.5 0v-2.25A.75.75 0 0112 18zM7.758 17.303a.75.75 0 00-1.061-1.06l-1.591 1.59a.75.75 0 001.06 1.061l1.591-1.59zM6 12a.75.75 0 01-.75.75H3a.75.75 0 010-1.5h2.25A.75.75 0 016 12zM6.697 7.757a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 00-1.061 1.06l1.59 1.591z" />
            </svg>
        </div>
        
        <!-- Moon Icon (Dark Mode) -->
        <div x-show="darkMode" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 rotate-90 scale-0"
             x-transition:enter-end="opacity-1 rotate-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-1 rotate-0 scale-100"
             x-transition:leave-end="opacity-0 -rotate-90 scale-0"
             class="w-5 h-5 text-purple-400 group-hover:text-purple-300">
            <svg fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M9.528 1.718a.75.75 0 01.162.819A8.97 8.97 0 009 6a9 9 0 009 9 8.97 8.97 0 003.463-.69.75.75 0 01.981.98 10.503 10.503 0 01-9.694 6.46c-5.799 0-10.5-4.701-10.5-10.5 0-4.368 2.667-8.112 6.46-9.694a.75.75 0 01.818.162z" clip-rule="evenodd" />
            </svg>
        </div>
    </button>
    
    <!-- Simple Tooltip -->
    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 dark:bg-gray-700 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap">
        <span x-text="darkMode ? 'Mode clair' : 'Mode sombre'"></span>
        <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
    </div>
</div> 