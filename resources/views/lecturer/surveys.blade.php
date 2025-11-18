<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Course Surveys - Dashboard</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.6s ease-out',
                        'fade-out': 'fadeOut 0.4s ease-in forwards',
                        'slide-up': 'slideUp 0.8s ease-out forwards',
                        'slide-down': 'slideDown 0.4s ease-in forwards',
                        'bounce-in': 'bounceIn 0.6s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        fadeOut: {
                            '0%': { opacity: '1', transform: 'translateY(0)' },
                            '100%': { opacity: '0', transform: 'translateY(-20px)' }
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        slideDown: {
                            '0%': { opacity: '1', transform: 'translateY(0)' },
                            '100%': { opacity: '0', transform: 'translateY(30px)' }
                        },
                        bounceIn: {
                            '0%': { transform: 'scale(0.3)', opacity: '0' },
                            '50%': { transform: 'scale(1.05)' },
                            '70%': { transform: 'scale(0.9)' },
                            '100%': { transform: 'scale(1)', opacity: '1' }
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 text-gray-800 dark:text-gray-200 transition-all duration-300 min-h-screen">
    <div class="min-h-screen">
        <!-- Enhanced Header with Better Navigation -->
        <header class="bg-white/95 dark:bg-gray-800/95 backdrop-blur-lg border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Top Header Bar -->
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('lecturer.page') }}" class="flex items-center space-x-2 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chalkboard-teacher text-white text-sm"></i>
                            </div>
                            <span class="font-bold text-lg hidden sm:block">Lecturer Portal</span>
                        </a>
                    </div>
                    
                    <!-- Quick Actions & User Menu -->
                    <div class="flex items-center space-x-3">
                        <!-- Print Button -->
                        <button id="export-btn" class="px-3 py-2 sm:px-4 sm:py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm">
                            <i class="fas fa-print mr-1 sm:mr-2"></i>
                            <span class="hidden sm:inline">Print</span>
                            <span class="sm:hidden">Print</span>
                        </button>
                        
                        <!-- Theme Toggle -->
                        <button id="theme-toggle" class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 dark:hidden" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden dark:block" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- User Menu Dropdown -->
                        <div class="relative">
                            <button id="user-menu-button" class="group flex items-center space-x-3 p-2 rounded-xl bg-white dark:bg-gray-700 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-600 hover:scale-105">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <div class="hidden sm:block text-left">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Lecturer</p>
                                </div>
                                <i class="fas fa-chevron-down text-gray-400 text-sm transition-transform duration-200 group-hover:rotate-180"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div id="user-dropdown" class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 opacity-0 invisible transform scale-95 transition-all duration-200 z-50">
                                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                                </div>
                                <div class="py-2">
                                    <a href="/lecturer/page" class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <i class="fas fa-tachometer-alt mr-3 text-blue-500"></i>
                                        Dashboard
                                    </a>
                                    <form method="POST" action="/logout" class="block">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center px-4 py-3 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-200">
                                            <i class="fas fa-sign-out-alt mr-3"></i>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation Tabs -->
                <div class="border-t border-gray-200 dark:border-gray-700">
                    <nav class="flex space-x-8 overflow-x-auto scrollbar-hide" aria-label="Tabs">
                        <a href="{{ route('lecturer.evaluations') }}" class="group flex items-center py-4 px-1 border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 font-medium text-sm whitespace-nowrap transition-colors">
                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mr-3 group-hover:bg-gray-200 dark:group-hover:bg-gray-600 transition-colors">
                                <i class="fas fa-star-half-alt text-gray-500 dark:text-gray-400"></i>
                            </div>
                            <span>Evaluations</span>
                        </a>
                        
                        <a href="{{ route('lecturer.surveys') }}" class="group flex items-center py-4 px-1 border-b-2 border-purple-500 text-purple-600 dark:text-purple-400 font-medium text-sm whitespace-nowrap">
                            <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-purple-200 dark:group-hover:bg-purple-900/50 transition-colors">
                                <i class="fas fa-clipboard-check text-purple-600 dark:text-purple-400"></i>
                            </div>
                            <span>Course Survey</span>
                            <div class="ml-2 px-2 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-full text-xs font-medium">Active</div>
                        </a>
                        
                        <a href="{{ route('lecturer.questions') }}" class="group flex items-center py-4 px-1 border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 font-medium text-sm whitespace-nowrap transition-colors">
                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mr-3 group-hover:bg-gray-200 dark:group-hover:bg-gray-600 transition-colors">
                                <i class="fas fa-list-check text-gray-500 dark:text-gray-400"></i>
                            </div>
                            <span>Questions</span>
                        </a>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Filters Section -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 mb-8 animate-fade-in">
                <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                    <div class="flex flex-col sm:flex-row gap-4 flex-1">
                        <div class="flex-1">
                            <label for="course-select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-book mr-2 text-purple-500"></i>Select Course
                            </label>
                            <select id="course-select" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                                <option value="">All Courses</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <label for="semester-select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-calendar mr-2 text-indigo-500"></i>Semester
                            </label>
                            <select id="semester-select" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                <option value="">All Semesters</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-2 w-full sm:w-auto">
                        <button id="export-btn" class="flex-1 sm:flex-none px-3 py-2 sm:px-4 sm:py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm sm:text-base">
                            <i class="fas fa-print mr-1 sm:mr-2"></i>
                            <span class="hidden sm:inline">Print</span>
                            <span class="sm:hidden">Print</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-8">
                <div class="block bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-3 sm:p-6 animate-slide-up">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Total Students</p>
                            <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white" id="total-students">-</p>
                        </div>
                        <div class="w-8 h-8 sm:w-12 sm:h-12 bg-purple-100 dark:bg-purple-900 rounded-xl flex items-center justify-center">
                            <i class="fas fa-users text-purple-600 dark:text-purple-400 text-sm sm:text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="block bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 animate-slide-up" style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Responses</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400" id="total-responses">-</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-xl flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="block bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 animate-slide-up" style="animation-delay: 0.2s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Response Rate</p>
                            <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400" id="response-rate">-</p>
                        </div>
                        <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-xl flex items-center justify-center">
                            <i class="fas fa-percentage text-indigo-600 dark:text-indigo-400 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="block bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 animate-slide-up" style="animation-delay: 0.3s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">CLOs Rated</p>
                            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400" id="clos-rated">-</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-xl flex items-center justify-center">
                            <i class="fas fa-star text-yellow-600 dark:text-yellow-400 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Submission Timeline -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 animate-fade-in">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-chart-line mr-2 text-purple-500"></i>Submission Timeline
                    </h3>
                    <div class="h-64">
                        <canvas id="timeline-chart"></canvas>
                    </div>
                </div>

                <!-- CLO Analytics Summary for selected course -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 animate-fade-in">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-braille mr-2 text-indigo-500"></i>CLO Analytics
                    </h3>
                    <div id="clo-analytics" class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                        <p class="text-gray-500 dark:text-gray-400">Select a course to view CLO analytics.</p>
                    </div>
                </div>
            </div>

            <!-- Student Tables -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Submitted Surveys -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 animate-slide-up">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-check-circle mr-2 text-green-500"></i>Submitted Surveys
                        </h3>
                        <span class="px-3 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full text-sm font-medium" id="submitted-count">0</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <th class="text-left py-3 px-2 text-sm font-medium text-gray-600 dark:text-gray-400">Student</th>
                                    <th class="text-left py-3 px-2 text-sm font-medium text-gray-600 dark:text-gray-400">Date</th>
                                    <th class="text-left py-3 px-2 text-sm font-medium text-gray-600 dark:text-gray-400">Comment</th>
                                </tr>
                            </thead>
                            <tbody id="submitted-table">
                                <tr>
                                    <td colspan="3" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                                        <p>Loading...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pending Students -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 animate-slide-up" style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-clock mr-2 text-orange-500"></i>Pending Students
                        </h3>
                        <span class="px-3 py-1 bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200 rounded-full text-sm font-medium" id="pending-count">0</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <th class="text-left py-3 px-2 text-sm font-medium text-gray-600 dark:text-gray-400">Student</th>
                                    <th class="text-left py-3 px-2 text-sm font-medium text-gray-600 dark:text-gray-400">Days</th>
                                    <th class="text-left py-3 px-2 text-sm font-medium text-gray-600 dark:text-gray-400">Action</th>
                                </tr>
                            </thead>
                            <tbody id="pending-table">
                                <tr>
                                    <td colspan="3" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                                        <p>Loading...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>

         // Print functionality
         const printButtons = [document.getElementById('print-btn'), document.getElementById('mobile-print-btn'), document.getElementById('export-btn')];
        
        printButtons.forEach(btn => {
            if (btn) {
                btn.addEventListener('click', () => {
                    window.print();
                });
            }
        });
        
        // Theme toggle and user dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Theme toggle
            const toggle = document.getElementById('theme-toggle');
            if (toggle) {
                toggle.addEventListener('click', function() {
                    document.documentElement.classList.toggle('dark');
                    localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
                });
            }
            if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }

            // Mobile menu functionality
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isVisible = !mobileMenu.classList.contains('opacity-0');
                    
                    if (isVisible) {
                        // Hide mobile menu with animation
                        mobileMenu.classList.add('animate-slide-down');
                        setTimeout(() => {
                            mobileMenu.classList.add('opacity-0', 'invisible', '-translate-y-2');
                            mobileMenu.classList.remove('opacity-100', 'visible', 'translate-y-0', 'animate-slide-down');
                        }, 200);
                    } else {
                        // Show mobile menu with animation
                        mobileMenu.classList.remove('opacity-0', 'invisible', '-translate-y-2');
                        mobileMenu.classList.add('opacity-100', 'visible', 'translate-y-0');
                    }
                });

                // Close mobile menu when clicking outside
                document.addEventListener('click', function() {
                    if (!mobileMenu.classList.contains('opacity-0')) {
                        mobileMenu.classList.add('animate-slide-down');
                        setTimeout(() => {
                            mobileMenu.classList.add('opacity-0', 'invisible', '-translate-y-2');
                            mobileMenu.classList.remove('opacity-100', 'visible', 'translate-y-0', 'animate-slide-down');
                        }, 200);
                    }
                });
            }

            // User dropdown functionality
            const userMenuButton = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-dropdown');
            
            if (userMenuButton && userDropdown) {
                userMenuButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isVisible = !userDropdown.classList.contains('opacity-0');
                    
                    if (isVisible) {
                        // Hide dropdown with animation
                        userDropdown.classList.add('animate-fade-out');
                        setTimeout(() => {
                            userDropdown.classList.add('opacity-0', 'invisible', 'scale-95');
                            userDropdown.classList.remove('opacity-100', 'visible', 'scale-100', 'animate-fade-out');
                        }, 200);
                    } else {
                        // Show dropdown with animation
                        userDropdown.classList.remove('opacity-0', 'invisible', 'scale-95');
                        userDropdown.classList.add('opacity-100', 'visible', 'scale-100');
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function() {
                    if (!userDropdown.classList.contains('opacity-0')) {
                        userDropdown.classList.add('animate-fade-out');
                        setTimeout(() => {
                            userDropdown.classList.add('opacity-0', 'invisible', 'scale-95');
                            userDropdown.classList.remove('opacity-100', 'visible', 'scale-100', 'animate-fade-out');
                        }, 200);
                    }
                });
            }
        });

        let timelineChart;
        let surveyData = [];

        document.addEventListener('DOMContentLoaded', function() {
            loadLecturerCourses();
            loadSurveyData();
            initializeCharts();

            document.getElementById('course-select').addEventListener('change', loadSurveyData);
            document.getElementById('semester-select').addEventListener('change', loadSurveyData);
            
            document.getElementById('export-btn').addEventListener('click', exportData);
        });

        async function loadLecturerCourses() {
            try {
                const response = await fetch('/lecturer/courses');
                if (!response.ok) {
                    throw new Error(`Failed to load courses (${response.status})`);
                }
                const data = await response.json();

                const courseSelect = document.getElementById('course-select');
                const semesterSelect = document.getElementById('semester-select');

                // Populate courses
                if (data.courses && data.courses.length) {
                    data.courses.forEach(course => {
                        const option = document.createElement('option');
                        option.value = course.id;
                        option.textContent = `${course.code} - ${course.name}`;
                        courseSelect.appendChild(option);
                    });
                }

                // Populate semesters
                if (data.courses && data.courses.length) {
                    const semesters = [...new Set(data.courses.map(course => course.semester))];
                    semesters.forEach(semester => {
                        const option = document.createElement('option');
                        option.value = semester;
                        option.textContent = semester;
                        semesterSelect.appendChild(option);
                    });
                } else {
                    Swal.fire({ icon: 'info', title: 'No Courses', text: 'No courses assigned to your account yet.' });
                }
            } catch (error) {
                console.error('Error loading courses:', error);
                Swal.fire({ icon: 'error', title: 'Failed to load courses', text: error.message || 'Please try again later.' });
            }
        }

        async function loadSurveyData() {
            try {
                const courseId = document.getElementById('course-select').value;
                const semester = document.getElementById('semester-select').value;

                const params = new URLSearchParams();
                if (courseId) params.append('course_id', courseId);
                if (semester) params.append('semester', semester);

                const response = await fetch(`/lecturer/api/surveys/data?${params}`);
                let data;
                try {
                    data = await response.json();
                } catch (_) {
                    data = {};
                }
                if (!response.ok) {
                    const message = (data && (data.error || data.message)) ? (data.error || data.message) : `Failed to load survey data (${response.status})`;
                    throw new Error(message);
                }

                surveyData = data;
                updateStatistics(data);
                updateTables(data);
                updateCharts(data);
                updateCloAnalytics(data);
            } catch (error) {
                console.error('Error loading survey data:', error);
                showError(error.message || 'Failed to load survey data');
                // Gracefully reset UI sections on error
                document.getElementById('total-students').textContent = '-';
                document.getElementById('total-responses').textContent = '-';
                document.getElementById('response-rate').textContent = '-';
                document.getElementById('clos-rated').textContent = '-';
                const submittedTable = document.getElementById('submitted-table');
                const pendingTable = document.getElementById('pending-table');
                document.getElementById('submitted-count').textContent = '0';
                document.getElementById('pending-count').textContent = '0';
                submittedTable.innerHTML = `
                    <tr>
                        <td colspan="3" class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <i class="fas fa-triangle-exclamation text-2xl mb-2"></i>
                            <p>Unable to load submissions</p>
                        </td>
                    </tr>
                `;
                pendingTable.innerHTML = `
                    <tr>
                        <td colspan="3" class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <i class="fas fa-triangle-exclamation text-2xl mb-2"></i>
                            <p>Unable to load pending students</p>
                        </td>
                    </tr>
                `;
                if (timelineChart) {
                    timelineChart.data.labels = [];
                    timelineChart.data.datasets[0].data = [];
                    timelineChart.update();
                }
            }
        }

        function updateStatistics(data) {
            document.getElementById('total-students').textContent = data.total_students || 0;
            document.getElementById('total-responses').textContent = data.total_responses || 0;
            document.getElementById('response-rate').textContent = `${data.response_rate || 0}%`;
            // Estimate CLOs rated (sum of total_ratings for selected course if any)
            const courseId = document.getElementById('course-select').value;
            let closRated = 0;
            if (courseId && data.clo_analytics && data.clo_analytics[courseId]) {
                data.clo_analytics[courseId].forEach(row => closRated += (row.total_ratings || 0));
            } else if (data.clo_analytics) {
                Object.values(data.clo_analytics).forEach(arr => arr.forEach(row => closRated += (row.total_ratings || 0)));
            }
            document.getElementById('clos-rated').textContent = closRated;
        }

        function updateTables(data) {
            // Submitted table
            const submittedTable = document.getElementById('submitted-table');
            const submittedCount = document.getElementById('submitted-count');

            if (data.submitted && data.submitted.length > 0) {
                submittedTable.innerHTML = data.submitted.map(item => `
                    <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="py-3 px-2">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-green-600 dark:text-green-400 text-sm"></i>
                                </div>
                                <span class="font-medium text-gray-900 dark:text-white">${item.name}</span>
                            </div>
                        </td>
                        <td class="py-3 px-2 text-sm text-gray-600 dark:text-gray-400">
                            ${new Date(item.submitted_at).toLocaleDateString()}
                        </td>
                        <td class="py-3 px-2 text-sm text-gray-600 dark:text-gray-400 truncate max-w-[240px]" title="${item.comment || ''}">
                            ${item.comment ? item.comment : '-'}
                        </td>
                    </tr>
                `).join('');
                submittedCount.textContent = data.submitted.length;
            } else {
                submittedTable.innerHTML = `
                    <tr>
                        <td colspan="3" class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <i class="fas fa-inbox text-2xl mb-2"></i>
                            <p>No submissions yet</p>
                        </td>
                    </tr>
                `;
                submittedCount.textContent = '0';
            }

            // Pending table
            const pendingTable = document.getElementById('pending-table');
            const pendingCount = document.getElementById('pending-count');

            if (data.pending && data.pending.length > 0) {
                pendingTable.innerHTML = data.pending.map(student => `
                    <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="py-3 px-2">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-orange-600 dark:text-orange-400 text-sm"></i>
                                </div>
                                <span class="font-medium text-gray-900 dark:text-white">${student.name}</span>
                            </div>
                        </td>
                        <td class="py-3 px-2 text-sm text-gray-600 dark:text-gray-400">
                            ${student.days_since_start || 0}
                        </td>
                        <td class="py-3 px-2">
                            <button onclick="sendReminder(${student.id})" class="px-3 py-1 bg-purple-500 hover:bg-purple-600 text-white rounded-lg text-xs font-medium transition-colors">
                                <i class="fas fa-bell mr-1"></i>Remind
                            </button>
                        </td>
                    </tr>
                `).join('');
                pendingCount.textContent = data.pending.length;
            } else {
                pendingTable.innerHTML = `
                    <tr>
                        <td colspan="3" class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <i class="fas fa-check-circle text-2xl mb-2"></i>
                            <p>All students have submitted</p>
                        </td>
                    </tr>
                `;
                pendingCount.textContent = '0';
            }
        }

        function initializeCharts() {
            // Timeline chart
            const timelineCtx = document.getElementById('timeline-chart').getContext('2d');
            timelineChart = new Chart(timelineCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Submissions',
                        data: [],
                        borderColor: '#8B5CF6',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                }
            });
        }

        function updateCharts(data) {
            if (data.timeline) {
                timelineChart.data.labels = data.timeline.map(item => item.date);
                timelineChart.data.datasets[0].data = data.timeline.map(item => item.count);
                timelineChart.update();
            }
        }

        function updateCloAnalytics(data) {
            const container = document.getElementById('clo-analytics');
            const courseId = document.getElementById('course-select').value;

            if (!data.clo_analytics || (courseId && !data.clo_analytics[courseId])) {
                container.innerHTML = '<p class="text-gray-500 dark:text-gray-400">No CLO analytics available for the selected criteria.</p>';
                return;
            }

            const analytics = courseId ? data.clo_analytics[courseId] : null;
            if (!analytics) {
                container.innerHTML = '<p class="text-gray-500 dark:text-gray-400">Select a course to view CLO analytics.</p>';
                return;
            }

            if (analytics.length === 0) {
                container.innerHTML = '<p class="text-gray-500 dark:text-gray-400">No CLO analytics available.</p>';
                return;
            }

            container.innerHTML = analytics.map(row => `
                <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 px-2 py-1 rounded-lg text-xs font-semibold">CLO ${row.clo_number}</span>
                            <span>${row.description}</span>
                        </div>
                        <div class="text-sm font-semibold ${row.average_rating ? 'text-purple-600 dark:text-purple-300' : 'text-gray-400'}">
                            ${row.average_rating ? row.average_rating + '/5' : '-'}
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2 overflow-hidden">
                            <div class="h-2 bg-purple-500" style="width: ${Math.min(100, (row.average_rating || 0) * 20)}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
                            <span>1</span><span>2</span><span>3</span><span>4</span><span>5</span>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        async function sendReminder(studentId) {
            try {
                const response = await fetch('/lecturer/evaluations/remind', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ student_id: studentId })
                });

                const data = await response.json();
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'Reminder Sent', text: 'Student has been notified.', timer: 2000, showConfirmButton: false });
                } else {
                    throw new Error(data.message || 'Failed');
                }
            } catch (error) {
                Swal.fire({ icon: 'error', title: 'Failed to Send Reminder', text: error.message });
            }
        }

        function exportData() {
            const courseId = document.getElementById('course-select').value;
            const semester = document.getElementById('semester-select').value;

            const params = new URLSearchParams();
            if (courseId) params.append('course_id', courseId);
            if (semester) params.append('semester', semester);

            window.open(`/lecturer/api/surveys/export?${params}`, '_blank');
        }

        function showError(message) {
            Swal.fire({ icon: 'error', title: 'Error', text: message });
        }
    </script>
</body>
</html>
