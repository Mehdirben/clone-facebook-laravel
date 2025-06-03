<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign up for Facebook | Facebook</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=helvetica:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-background-light via-gray-50 to-green-50 dark:from-background-dark dark:via-gray-900 dark:to-gray-800 font-sans antialiased min-h-screen">
    <div class="min-h-screen flex items-center justify-center px-4 py-12 relative overflow-hidden">
        
        <!-- Background decorative elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-green-100 dark:bg-green-900/20 rounded-full blur-3xl opacity-30 animate-pulse-slow"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-facebook-100 dark:bg-facebook-900/20 rounded-full blur-3xl opacity-30 animate-pulse-slow"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-r from-green-50 to-facebook-50 dark:from-green-900/10 dark:to-facebook-900/10 rounded-full blur-3xl opacity-20 animate-float"></div>
        </div>
        
        <div class="w-full max-w-7xl mx-auto relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 items-center">
                
                <!-- Left Side - Branding -->
                <div class="text-center lg:text-left space-y-8 animate-slide-right">
                    <div>
                        <h1 class="text-facebook-500 text-7xl lg:text-8xl font-bold mb-6 tracking-tight leading-none">
                            facebook
                        </h1>
                        <p class="text-text-primary dark:text-text-primary-dark text-2xl lg:text-4xl font-light leading-tight max-w-lg">
                            Connect with friends and the world around you on Facebook.
                        </p>
                    </div>
                    
                    <!-- Additional branding elements -->
                    <div class="hidden lg:block space-y-6 mt-12">
                        <div class="flex items-center space-x-4 text-text-secondary dark:text-text-secondary-dark">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-lg">Create your profile in seconds</span>
                        </div>
                        <div class="flex items-center space-x-4 text-text-secondary dark:text-text-secondary-dark">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                </svg>
                            </div>
                            <span class="text-lg">Connect with friends and family</span>
                        </div>
                        <div class="flex items-center space-x-4 text-text-secondary dark:text-text-secondary-dark">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-lg">Share moments that matter</span>
                        </div>
                    </div>
                </div>
                
                <!-- Right Side - Registration Form -->
                <div class="w-full max-w-md mx-auto lg:mx-0 animate-slide-left">
                    <div class="bg-white/90 dark:bg-background-card-dark/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 dark:border-gray-700/30 p-10 relative overflow-hidden">
                        
                        <!-- Form glow effect -->
                        <div class="absolute inset-0 bg-gradient-to-r from-green-500/5 to-facebook-500/5 rounded-3xl"></div>
                        
                        <div class="relative z-10">
                            <!-- Header -->
                            <div class="mb-8 text-center">
                                <h2 class="text-text-primary dark:text-text-primary-dark text-3xl font-bold mb-3">Create a new account</h2>
                                <p class="text-text-secondary dark:text-text-secondary-dark text-lg">It's quick and easy.</p>
                            </div>
                            
                            <!-- Divider -->
                            <div class="border-b border-gray-200 dark:border-border-dark mb-8"></div>
                            
                            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                                @csrf
                                
                                <!-- Name Fields -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="group">
                                        <input 
                                            id="first_name" 
                                            type="text" 
                                            name="first_name" 
                                            value="{{ old('first_name') }}" 
                                            required 
                                            autofocus 
                                            placeholder="First name"
                                            class="w-full px-4 py-4 text-lg border-2 border-gray-200 dark:border-border-dark rounded-2xl focus:ring-2 focus:ring-facebook-500 focus:border-facebook-500 transition-all duration-300 bg-white/80 dark:bg-background-card-dark/80 text-text-primary dark:text-text-primary-dark placeholder-text-muted dark:placeholder-text-muted-dark backdrop-blur-sm group-hover:border-gray-300 dark:group-hover:border-gray-600 transform focus:scale-105"
                                        />
                                        @error('first_name')
                                            <p class="mt-1 text-xs text-red-600 dark:text-red-400 animate-slide-up">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="group">
                                        <input 
                                            id="last_name" 
                                            type="text" 
                                            name="last_name" 
                                            value="{{ old('last_name') }}" 
                                            required 
                                            placeholder="Last name"
                                            class="w-full px-4 py-4 text-lg border-2 border-gray-200 dark:border-border-dark rounded-2xl focus:ring-2 focus:ring-facebook-500 focus:border-facebook-500 transition-all duration-300 bg-white/80 dark:bg-background-card-dark/80 text-text-primary dark:text-text-primary-dark placeholder-text-muted dark:placeholder-text-muted-dark backdrop-blur-sm group-hover:border-gray-300 dark:group-hover:border-gray-600 transform focus:scale-105"
                                        />
                                        @error('last_name')
                                            <p class="mt-1 text-xs text-red-600 dark:text-red-400 animate-slide-up">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- Hidden full name field for backend compatibility -->
                                <input type="hidden" id="name" name="name" value="">
                                
                                <!-- Email Address -->
                                <div class="group">
                                    <input 
                                        id="email" 
                                        type="email" 
                                        name="email" 
                                        value="{{ old('email') }}" 
                                        required 
                                        autocomplete="username"
                                        placeholder="Mobile number or email"
                                        class="w-full px-5 py-5 text-lg border-2 border-gray-200 dark:border-border-dark rounded-2xl focus:ring-2 focus:ring-facebook-500 focus:border-facebook-500 transition-all duration-300 bg-white/80 dark:bg-background-card-dark/80 text-text-primary dark:text-text-primary-dark placeholder-text-muted dark:placeholder-text-muted-dark backdrop-blur-sm group-hover:border-gray-300 dark:group-hover:border-gray-600 transform focus:scale-105"
                                    />
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 animate-slide-up">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Password -->
                                <div class="group">
                                    <input 
                                        id="password" 
                                        type="password" 
                                        name="password" 
                                        required 
                                        autocomplete="new-password"
                                        placeholder="New password"
                                        class="w-full px-5 py-5 text-lg border-2 border-gray-200 dark:border-border-dark rounded-2xl focus:ring-2 focus:ring-facebook-500 focus:border-facebook-500 transition-all duration-300 bg-white/80 dark:bg-background-card-dark/80 text-text-primary dark:text-text-primary-dark placeholder-text-muted dark:placeholder-text-muted-dark backdrop-blur-sm group-hover:border-gray-300 dark:group-hover:border-gray-600 transform focus:scale-105"
                                    />
                                    @error('password')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 animate-slide-up">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Confirm Password -->
                                <div class="group">
                                    <input 
                                        id="password_confirmation" 
                                        type="password" 
                                        name="password_confirmation" 
                                        required 
                                        autocomplete="new-password"
                                        placeholder="Confirm password"
                                        class="w-full px-5 py-5 text-lg border-2 border-gray-200 dark:border-border-dark rounded-2xl focus:ring-2 focus:ring-facebook-500 focus:border-facebook-500 transition-all duration-300 bg-white/80 dark:bg-background-card-dark/80 text-text-primary dark:text-text-primary-dark placeholder-text-muted dark:placeholder-text-muted-dark backdrop-blur-sm group-hover:border-gray-300 dark:group-hover:border-gray-600 transform focus:scale-105"
                                    />
                                    @error('password_confirmation')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 animate-slide-up">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Sign Up Button -->
                                <div>
                                    <button 
                                        type="submit" 
                                        class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-5 px-6 rounded-2xl transition-all duration-300 text-lg focus:ring-4 focus:ring-green-300 focus:outline-none transform hover:scale-105 hover:shadow-2xl active:scale-95 relative overflow-hidden group"
                                    >
                                        <span class="relative z-10">Sign Up</span>
                                        <div class="absolute inset-0 bg-gradient-to-r from-green-600 to-green-700 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                                    </button>
                                </div>
                                
                                <!-- Already have account -->
                                <div class="text-center pt-4">
                                    <a 
                                        href="{{ route('login') }}" 
                                        class="inline-block text-facebook-500 hover:text-facebook-600 font-medium hover:underline transition-all duration-200 transform hover:scale-105"
                                    >
                                        Already have an account?
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Combine first and last name into name field for backend compatibility
        document.addEventListener('DOMContentLoaded', function() {
            const firstNameInput = document.getElementById('first_name');
            const lastNameInput = document.getElementById('last_name');
            const nameInput = document.getElementById('name');
            
            function updateFullName() {
                nameInput.value = firstNameInput.value + ' ' + lastNameInput.value;
            }
            
            firstNameInput.addEventListener('input', updateFullName);
            lastNameInput.addEventListener('input', updateFullName);
        });
    </script>
</body>
</html>
