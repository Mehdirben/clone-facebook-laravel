<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Facebook - Log In or Sign Up</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=helvetica:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-background-light via-gray-50 to-blue-50 dark:from-background-dark dark:via-gray-900 dark:to-gray-800 font-sans antialiased min-h-screen">
    <div class="min-h-screen flex items-center justify-center px-4 py-12 relative overflow-hidden">
        
        <!-- Background decorative elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-facebook-100 dark:bg-facebook-900/20 rounded-full blur-3xl opacity-30 animate-pulse-slow"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-100 dark:bg-purple-900/20 rounded-full blur-3xl opacity-30 animate-pulse-slow"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-r from-facebook-50 to-purple-50 dark:from-facebook-900/10 dark:to-purple-900/10 rounded-full blur-3xl opacity-20 animate-float"></div>
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
                            <div class="w-8 h-8 bg-facebook-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-lg">See photos and updates from friends</span>
                        </div>
                        <div class="flex items-center space-x-4 text-text-secondary dark:text-text-secondary-dark">
                            <div class="w-8 h-8 bg-facebook-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-lg">Share what's new in your life</span>
                        </div>
                        <div class="flex items-center space-x-4 text-text-secondary dark:text-text-secondary-dark">
                            <div class="w-8 h-8 bg-facebook-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-lg">Find more of what you're looking for</span>
                        </div>
                    </div>
                </div>
                
                <!-- Right Side - Login Form -->
                <div class="w-full max-w-md mx-auto lg:mx-0 animate-slide-left">
                    <div class="bg-white/90 dark:bg-background-card-dark/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 dark:border-gray-700/30 p-10 relative overflow-hidden">
                        
                        <!-- Form glow effect -->
                        <div class="absolute inset-0 bg-gradient-to-r from-facebook-500/5 to-purple-500/5 rounded-3xl"></div>
                        
                        <div class="relative z-10">
                            <!-- Session Status -->
                            @if (session('status'))
                                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-2xl animate-bounce-in">
                                    <p class="text-green-700 dark:text-green-300 text-sm">{{ session('status') }}</p>
                                </div>
                            @endif
                            
                            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                                @csrf
                                
                                <!-- Email Address -->
                                <div class="group">
                                    <input 
                                        id="email" 
                                        type="email" 
                                        name="email" 
                                        value="{{ old('email') }}" 
                                        required 
                                        autofocus 
                                        autocomplete="username"
                                        placeholder="Email or phone number"
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
                                        autocomplete="current-password"
                                        placeholder="Password"
                                        class="w-full px-5 py-5 text-lg border-2 border-gray-200 dark:border-border-dark rounded-2xl focus:ring-2 focus:ring-facebook-500 focus:border-facebook-500 transition-all duration-300 bg-white/80 dark:bg-background-card-dark/80 text-text-primary dark:text-text-primary-dark placeholder-text-muted dark:placeholder-text-muted-dark backdrop-blur-sm group-hover:border-gray-300 dark:group-hover:border-gray-600 transform focus:scale-105"
                                    />
                                    @error('password')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 animate-slide-up">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Login Button -->
                                <div>
                                    <button 
                                        type="submit" 
                                        class="w-full bg-gradient-to-r from-facebook-500 to-facebook-600 hover:from-facebook-600 hover:to-facebook-700 text-white font-bold py-5 px-6 rounded-2xl transition-all duration-300 text-lg focus:ring-4 focus:ring-facebook-300 focus:outline-none transform hover:scale-105 hover:shadow-2xl active:scale-95 relative overflow-hidden group"
                                    >
                                        <span class="relative z-10">Log In</span>
                                        <div class="absolute inset-0 bg-gradient-to-r from-facebook-600 to-facebook-700 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                                    </button>
                                </div>
                                
                                <!-- Remember Me -->
                                <div class="flex items-center justify-center">
                                    <label for="remember_me" class="flex items-center cursor-pointer group">
                                        <input 
                                            id="remember_me" 
                                            type="checkbox" 
                                            name="remember" 
                                            class="rounded-lg border-gray-300 text-facebook-600 shadow-sm focus:ring-facebook-500 mr-3 transform group-hover:scale-110 transition-transform duration-200"
                                        >
                                        <span class="text-sm text-text-secondary dark:text-text-secondary-dark group-hover:text-text-primary dark:group-hover:text-text-primary-dark transition-colors duration-200">Remember me</span>
                                    </label>
                                </div>
                                
                                <!-- Forgot Password -->
                                @if (Route::has('password.request'))
                                    <div class="text-center">
                                        <a 
                                            href="{{ route('password.request') }}" 
                                            class="text-facebook-500 hover:text-facebook-600 text-sm font-medium hover:underline transition-all duration-200 transform hover:scale-105 inline-block"
                                        >
                                            Forgotten password?
                                        </a>
                                    </div>
                                @endif
                                
                                <!-- Divider -->
                                <div class="relative my-8">
                                    <div class="absolute inset-0 flex items-center">
                                        <div class="w-full border-t border-gray-300 dark:border-border-dark"></div>
                                    </div>
                                    <div class="relative flex justify-center text-sm">
                                        <span class="px-4 bg-white/90 dark:bg-background-card-dark/90 text-text-muted dark:text-text-muted-dark">or</span>
                                    </div>
                                </div>
                                
                                <!-- Create Account Button -->
                                <div class="text-center">
                                    <a 
                                        href="{{ route('register') }}" 
                                        class="inline-block bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 focus:ring-4 focus:ring-green-300 focus:outline-none transform hover:scale-105 hover:shadow-2xl active:scale-95 relative overflow-hidden group"
                                    >
                                        <span class="relative z-10">Create New Account</span>
                                        <div class="absolute inset-0 bg-gradient-to-r from-green-600 to-green-700 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
