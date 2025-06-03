<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password | Facebook</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=helvetica:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-background-light via-gray-50 to-purple-50 dark:from-background-dark dark:via-gray-900 dark:to-gray-800 font-sans antialiased min-h-screen">
    <div class="min-h-screen flex items-center justify-center px-4 py-12 relative overflow-hidden">
        
        <!-- Background decorative elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-100 dark:bg-purple-900/20 rounded-full blur-3xl opacity-30 animate-pulse-slow"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-facebook-100 dark:bg-facebook-900/20 rounded-full blur-3xl opacity-30 animate-pulse-slow"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-r from-purple-50 to-facebook-50 dark:from-purple-900/10 dark:to-facebook-900/10 rounded-full blur-3xl opacity-20 animate-float"></div>
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
                            <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-lg">Secure password recovery process</span>
                        </div>
                        <div class="flex items-center space-x-4 text-text-secondary dark:text-text-secondary-dark">
                            <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                            </div>
                            <span class="text-lg">Reset link sent to your email</span>
                        </div>
                        <div class="flex items-center space-x-4 text-text-secondary dark:text-text-secondary-dark">
                            <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-lg">Your account stays protected</span>
                        </div>
                    </div>
                </div>
                
                <!-- Right Side - Forgot Password Form -->
                <div class="w-full max-w-md mx-auto lg:mx-0 animate-slide-left">
                    <div class="bg-white/90 dark:bg-background-card-dark/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 dark:border-gray-700/30 p-10 relative overflow-hidden">
                        
                        <!-- Form glow effect -->
                        <div class="absolute inset-0 bg-gradient-to-r from-purple-500/5 to-facebook-500/5 rounded-3xl"></div>
                        
                        <div class="relative z-10">
                            <!-- Header -->
                            <div class="mb-8 text-center">
                                <h2 class="text-text-primary dark:text-text-primary-dark text-3xl font-bold mb-3">Reset your password</h2>
                                <p class="text-text-secondary dark:text-text-secondary-dark text-lg">Enter your email and we'll send you a reset link.</p>
                            </div>
                            
                            <!-- Divider -->
                            <div class="border-b border-gray-200 dark:border-border-dark mb-8"></div>
                            
                            <!-- Session Status -->
                            @if (session('status'))
                                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-2xl animate-bounce-in">
                                    <p class="text-green-700 dark:text-green-300 text-sm">{{ session('status') }}</p>
                                </div>
                            @endif
                            
                            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
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
                                        placeholder="Enter your email address"
                                        class="w-full px-5 py-5 text-lg border-2 border-gray-200 dark:border-border-dark rounded-2xl focus:ring-2 focus:ring-facebook-500 focus:border-facebook-500 transition-all duration-300 bg-white/80 dark:bg-background-card-dark/80 text-text-primary dark:text-text-primary-dark placeholder-text-muted dark:placeholder-text-muted-dark backdrop-blur-sm group-hover:border-gray-300 dark:group-hover:border-gray-600 transform focus:scale-105"
                                    />
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 animate-slide-up">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Send Reset Link Button -->
                                <div>
                                    <button 
                                        type="submit" 
                                        class="w-full bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-bold py-5 px-6 rounded-2xl transition-all duration-300 text-lg focus:ring-4 focus:ring-purple-300 focus:outline-none transform hover:scale-105 hover:shadow-2xl active:scale-95 relative overflow-hidden group"
                                    >
                                        <span class="relative z-10">Send Password Reset Email</span>
                                        <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-purple-700 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                                    </button>
                                </div>
                                
                                <!-- Divider -->
                                <div class="relative my-8">
                                    <div class="absolute inset-0 flex items-center">
                                        <div class="w-full border-t border-gray-300 dark:border-border-dark"></div>
                                    </div>
                                    <div class="relative flex justify-center text-sm">
                                        <span class="px-4 bg-white/90 dark:bg-background-card-dark/90 text-text-muted dark:text-text-muted-dark">or</span>
                                    </div>
                                </div>
                                
                                <!-- Back to Login -->
                                <div class="text-center">
                                    <a 
                                        href="{{ route('login') }}" 
                                        class="inline-block bg-gradient-to-r from-facebook-500 to-facebook-600 hover:from-facebook-600 hover:to-facebook-700 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 focus:ring-4 focus:ring-facebook-300 focus:outline-none transform hover:scale-105 hover:shadow-2xl active:scale-95 relative overflow-hidden group"
                                    >
                                        <span class="relative z-10">Back to Login</span>
                                        <div class="absolute inset-0 bg-gradient-to-r from-facebook-600 to-facebook-700 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
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
