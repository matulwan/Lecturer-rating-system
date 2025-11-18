<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.8s ease-out forwards',
                        'slide-up': 'slideUp 0.8s ease-out forwards',
                        'float': 'float 3s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            'from': { opacity: '0' },
                            'to': { opacity: '1' }
                        },
                        slideUp: {
                            'from': { 
                                opacity: '0',
                                transform: 'translateY(20px)'
                            },
                            'to': { 
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-8px)' }
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-gray-900 dark:via-slate-900 dark:to-gray-800 text-gray-800 dark:text-gray-100 transition-all duration-300 min-h-screen">    
    <!-- Animated background elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-r from-blue-400/20 to-purple-600/20 rounded-full blur-3xl animate-pulse-slow"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-r from-indigo-400/20 to-pink-600/20 rounded-full blur-3xl animate-pulse-slow" style="animation-delay: 2s"></div>
    </div>
    <!-- Enhanced Dark Mode Toggle -->
    <div class="absolute top-6 right-6">
        <button id="theme-toggle" class="p-3 rounded-xl bg-white/80 dark:bg-gray-700/80 shadow-md hover:shadow-lg transition-all duration-300 hover:scale-110">
            <svg id="theme-icon-light" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 dark:hidden" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
            </svg>
            <svg id="theme-icon-dark" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400 hidden dark:block" viewBox="0 0 20 20" fill="currentColor">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
            </svg>
        </button>
    </div>

    <div class="relative min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Enhanced Animated Logo -->
            <div class="text-center animate-fade-in">
                <div class="flex justify-center mb-6 animate-float">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-600 rounded-3xl blur opacity-75 animate-pulse"></div>
                        <div class="relative bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6 rounded-3xl shadow-2xl transform transition-all duration-500 hover:rotate-3 hover:scale-105">
                            <i class="fas fa-graduation-cap text-4xl"></i>
                        </div>
                    </div>
                </div>
                <div class="animate-slide-up" style="animation-delay: 0.2s">
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-2">Welcome Back</h1>
                    <p class="text-gray-600 dark:text-gray-400">Sign in to your Lecturer Rating System account</p>
                </div>
            </div>

            <!-- Enhanced Animated Login Form -->
            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-lg rounded-3xl shadow-2xl border border-gray-200/50 dark:border-gray-700/50 p-8 animate-slide-up" style="animation-delay: 0.4s;">
                <form class="space-y-6" action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div class="opacity-0 animate-[fadeIn_0.8s_ease-out_0.5s_forwards]">
                            <label for="user_code" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-user mr-2 text-blue-500"></i>User ID
                            </label>
                            <div class="relative">
                                <input id="user_code" name="user_code" type="text" required
                                    value="{{ old('user_code') }}"
                                    class="w-full px-4 py-4 pl-12 border-2 border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white dark:bg-slate-700 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover:border-blue-400" 
                                    placeholder="Enter your ID">
                                <i class="fas fa-id-card absolute left-4 top-5 text-gray-400"></i>
                            </div>
                            @error('user_code')
                                <span class="text-red-500 text-sm mt-2 block animate-fade-in flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </span>
                            @enderror
                        </div>
                        
                        <div class="opacity-0 animate-[fadeIn_0.8s_ease-out_0.6s_forwards]">
                            <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-lock mr-2 text-purple-500"></i>Password
                            </label>
                            <div class="relative">
                                <input id="password" name="password" type="password" required 
                                    class="w-full px-4 py-4 pl-12 border-2 border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white dark:bg-slate-700 rounded-2xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 hover:border-purple-400" 
                                    placeholder="Enter your password">
                                <i class="fas fa-key absolute left-4 top-5 text-gray-400"></i>
                            </div>
                            @error('password')
                                <span class="text-red-500 text-sm mt-2 block animate-fade-in flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="opacity-0 animate-[fadeIn_0.8s_ease-out_0.7s_forwards]">
                            <button type="submit" 
                                class="group relative w-full flex justify-center py-4 px-6 border border-transparent text-lg font-semibold rounded-2xl text-white bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 hover:from-blue-500 hover:via-purple-500 hover:to-indigo-500 focus:outline-none focus:ring-4 focus:ring-blue-500/50 shadow-2xl hover:shadow-3xl transition-all duration-300 hover:scale-105 transform">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                                    <i class="fas fa-sign-in-alt text-white/80 group-hover:text-white transition duration-300"></i>
                                </span>
                                <span class="ml-3">Sign In to Dashboard</span>
                               </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Enhanced Back Link -->
            <div class="text-center opacity-0 animate-[fadeIn_0.8s_ease-out_0.8s_forwards]">
                <a href="/" class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 rounded-xl transition-all duration-200 hover:scale-105">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Home
                </a>
            </div>
        </div>
    </div>

    <script>
        // Dark mode toggle functionality
        const themeToggle = document.getElementById('theme-toggle');
        const themeIconLight = document.getElementById('theme-icon-light');
        const themeIconDark = document.getElementById('theme-icon-dark');
        const html = document.documentElement;

        // Check for saved theme preference or use system preference
        if (localStorage.getItem('theme') === 'dark' || 
            (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
            themeIconLight.classList.add('hidden');
            themeIconDark.classList.remove('hidden');
        } else {
            html.classList.remove('dark');
            themeIconLight.classList.remove('hidden');
            themeIconDark.classList.add('hidden');
        }

        // Toggle theme on button click
        themeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            
            if (html.classList.contains('dark')) {
                localStorage.setItem('theme', 'dark');
                themeIconLight.classList.add('hidden');
                themeIconDark.classList.remove('hidden');
            } else {
                localStorage.setItem('theme', 'light');
                themeIconLight.classList.remove('hidden');
                themeIconDark.classList.add('hidden');
            }
        });
    </script>
</body>
</html>