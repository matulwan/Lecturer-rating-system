<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lecturer Evaluations - Dashboard</title>
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
                        <!-- Theme Toggle -->
                        <button id="theme-toggle" class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 dark:hidden" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden dark:block" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        
                        <!-- User Menu -->
                        <div class="relative">
                            <button id="user-menu-button" class="flex items-center space-x-2 p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200">
                                <div class="w-6 h-6 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-white text-xs"></i>
                                </div>
                                <span class="hidden sm:block text-sm font-medium text-gray-700 dark:text-gray-300">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                            </button>
                            
                            <!-- User Dropdown -->
                            <div id="user-dropdown" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 opacity-0 invisible transform scale-95 transition-all duration-200 z-50">
                                <div class="p-3 border-b border-gray-200 dark:border-gray-700">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Lecturer</p>
                                </div>
                                <div class="py-2">
                                    <a href="{{ route('lecturer.page') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <i class="fas fa-tachometer-alt mr-2 text-blue-500"></i>Dashboard
                                    </a>
                                    <form method="POST" action="/logout" class="block">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Enhanced Navigation Tabs -->
                <div class="border-t border-gray-200 dark:border-gray-700">
                    <nav class="flex space-x-8 overflow-x-auto scrollbar-hide" aria-label="Tabs">
                        <a href="{{ route('lecturer.evaluations') }}" class="group flex items-center py-4 px-1 border-b-2 border-blue-500 text-blue-600 dark:text-blue-400 font-medium text-sm whitespace-nowrap">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-200 dark:group-hover:bg-blue-900/50 transition-colors">
                                <i class="fas fa-star-half-alt text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <span>Evaluations</span>
                            <div class="ml-2 px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full text-xs font-medium">Active</div>
                        </a>
                        
                        <a href="{{ route('lecturer.surveys') }}" class="group flex items-center py-4 px-1 border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 font-medium text-sm whitespace-nowrap transition-colors">
                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mr-3 group-hover:bg-gray-200 dark:group-hover:bg-gray-600 transition-colors">
                                <i class="fas fa-clipboard-check text-gray-500 dark:text-gray-400"></i>
                            </div>
                            <span>Course Surveys</span>
                        </a>
                        
                        <a href="/lecturer/questions" class="group flex items-center py-4 px-1 border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 font-medium text-sm whitespace-nowrap transition-colors">
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
                            <label for="semester-select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-calendar mr-2 text-purple-500"></i>Semester
                            </label>
                            <select id="semester-select" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
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
                <a href="/lecturer-evaluation" class="block bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-3 sm:p-6 animate-slide-up">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Total Students</p>
                            <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white" id="total-students">-</p>
                        </div>
                        <div class="w-8 h-8 sm:w-12 sm:h-12 bg-blue-100 dark:bg-blue-900 rounded-xl flex items-center justify-center">
                            <i class="fas fa-users text-blue-600 dark:text-blue-400 text-sm sm:text-xl"></i>
                        </div>
                    </div>
                </a>

                <a href="/lecturer-evaluation" class="block bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-3 sm:p-6 animate-slide-up" style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Responses</p>
                            <p class="text-lg sm:text-2xl font-bold text-green-600 dark:text-green-400" id="total-responses">-</p>
                        </div>
                        <div class="w-8 h-8 sm:w-12 sm:h-12 bg-green-100 dark:bg-green-900 rounded-xl flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-sm sm:text-xl"></i>
                        </div>
                    </div>
                </a>

                <a href="/lecturer-evaluation" class="block bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-3 sm:p-6 animate-slide-up" style="animation-delay: 0.2s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Response Rate</p>
                            <p class="text-lg sm:text-2xl font-bold text-purple-600 dark:text-purple-400" id="response-rate">-</p>
                        </div>
                        <div class="w-8 h-8 sm:w-12 sm:h-12 bg-purple-100 dark:bg-purple-900 rounded-xl flex items-center justify-center">
                            <i class="fas fa-percentage text-purple-600 dark:text-purple-400 text-sm sm:text-xl"></i>
                        </div>
                    </div>
                </a>

                <a href="/lecturer-evaluation" class="block bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-3 sm:p-6 animate-slide-up" style="animation-delay: 0.3s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Average Rating</p>
                            <p class="text-lg sm:text-2xl font-bold text-yellow-600 dark:text-yellow-400" id="average-rating">-</p>
                        </div>
                        <div class="w-8 h-8 sm:w-12 sm:h-12 bg-yellow-100 dark:bg-yellow-900 rounded-xl flex items-center justify-center">
                            <i class="fas fa-star text-yellow-600 dark:text-yellow-400 text-sm sm:text-xl"></i>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Rating Distribution Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 animate-fade-in">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-chart-bar mr-2 text-blue-500"></i>Rating Distribution
                    </h3>
                    <div class="h-64">
                        <canvas id="rating-chart"></canvas>
                    </div>
                </div>

                <!-- Response Timeline -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 animate-fade-in">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-chart-line mr-2 text-purple-500"></i>Response Timeline
                    </h3>
                    <div class="h-64">
                        <canvas id="timeline-chart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Student Tables -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Submitted Evaluations -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 animate-slide-up">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-check-circle mr-2 text-green-500"></i>Submitted Evaluations
                        </h3>
                        <span class="px-3 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full text-sm font-medium" id="submitted-count">0</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <th class="text-left py-3 px-2 text-sm font-medium text-gray-600 dark:text-gray-400">Student</th>
                                    <th class="text-left py-3 px-2 text-sm font-medium text-gray-600 dark:text-gray-400">Date</th>
                                    <th class="text-left py-3 px-2 text-sm font-medium text-gray-600 dark:text-gray-400">Status</th>
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

                <!-- Pending Evaluations -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 animate-slide-up" style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-clock mr-2 text-orange-500"></i>Pending Evaluations
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

        let ratingChart, timelineChart;
        let evaluationData = [];

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            loadSemesters();
            loadEvaluationData();
            initializeCharts();
            
            // Event listeners
            document.getElementById('semester-select').addEventListener('change', loadEvaluationData);
            
        });

        async function loadSemesters() {
            try {
                const response = await fetch('/lecturer/courses');
                let data;
                try {
                    data = await response.json();
                } catch (e) {
                    data = { error: 'Invalid JSON from /lecturer/courses' };
                }

                if (!response.ok) {
                    const message = (data && (data.error || data.message)) || `HTTP ${response.status}`;
                    Swal.fire({ icon: 'error', title: 'Failed to load courses', text: message });
                    return;
                }

                const semesterSelect = document.getElementById('semester-select');

                // Reset selects (keep the first default option)
                semesterSelect.innerHTML = '<option value="">All Semesters</option>';

                const courses = Array.isArray(data.courses) ? data.courses : [];
                // It's okay if there are no courses (lecturer-only evaluations). We'll still allow All Semesters.

                // Populate semesters
                const semesters = [...new Set(courses.map(course => course.semester).filter(Boolean))];
                semesters.forEach(semester => {
                    const option = document.createElement('option');
                    option.value = semester;
                    option.textContent = semester;
                    semesterSelect.appendChild(option);
                });
            } catch (error) {
                console.error('Error loading courses:', error);
                Swal.fire({ icon: 'error', title: 'Failed to load semesters', text: error.message || 'Unknown error' });
            }
        }

        async function loadEvaluationData() {
            try {
                const semester = document.getElementById('semester-select').value;
                
                const params = new URLSearchParams();
                if (semester) params.append('semester', semester);
                
                const response = await fetch(`/lecturer/evaluations/data?${params}`);
                let data;
                try {
                    data = await response.json();
                } catch (e) {
                    data = { error: 'Invalid JSON from /lecturer/evaluations/data' };
                }

                if (!response.ok || data.error) {
                    const message = (data && (data.error || data.message)) || `HTTP ${response.status}`;
                    // Reset UI to empty/default state to avoid runtime errors
                    evaluationData = {};
                    updateStatistics({ total_students: 0, total_responses: 0, response_rate: 0, average_rating: null });
                    updateTables({ submitted: [], pending: [] });
                    updateCharts({ rating_distribution: { '1': 0, '2': 0, '3': 0, '4': 0, '5': 0 }, timeline: [] });
                    Swal.fire({ icon: 'info', title: 'No Data', text: message });
                    return;
                }
                
                evaluationData = data;
                updateStatistics(data);
                updateTables(data);
                updateCharts(data);
            } catch (error) {
                console.error('Error loading evaluation data:', error);
                showError('Failed to load evaluation data');
            }
        }

        function updateStatistics(data) {
            // Calculate actual totals from the data arrays
            const submittedCount = (data.submitted && data.submitted.length) || 0;
            const pendingCount = (data.pending && data.pending.length) || 0;
            const totalStudents = submittedCount + pendingCount;
            const totalResponses = submittedCount;
            
            // Calculate response rate
            let responseRate = 0;
            if (totalStudents > 0) {
                responseRate = Math.round((totalResponses / totalStudents) * 100);
            }
            
            document.getElementById('total-students').textContent = totalStudents;
            document.getElementById('total-responses').textContent = totalResponses;
            document.getElementById('response-rate').textContent = `${responseRate}%`;
            document.getElementById('average-rating').textContent = data.average_rating ? `${data.average_rating}/5` : '-';
        }

        function updateTables(data) {
            // Update submitted table
            const submittedTable = document.getElementById('submitted-table');
            const submittedCount = document.getElementById('submitted-count');
            
            if (data.submitted && data.submitted.length > 0) {
                submittedTable.innerHTML = data.submitted.map(student => `
                    <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="py-3 px-2">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-green-600 dark:text-green-400 text-sm"></i>
                                </div>
                                <span class="font-medium text-gray-900 dark:text-white">${student.name}</span>
                            </div>
                        </td>
                        <td class="py-3 px-2 text-sm text-gray-600 dark:text-gray-400">
                            ${new Date(student.submitted_at).toLocaleDateString()}
                        </td>
                        <td class="py-3 px-2">
                            <span class="px-2 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full text-xs font-medium">
                                Submitted
                            </span>
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

            // Update pending table
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
                            <button onclick="sendReminder(${student.id})" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-xs font-medium transition-colors">
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
            // Rating Distribution Chart
            const ratingCtx = document.getElementById('rating-chart').getContext('2d');
            ratingChart = new Chart(ratingCtx, {
                type: 'bar',
                data: {
                    labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
                    datasets: [{
                        label: 'Number of Ratings',
                        data: [0, 0, 0, 0, 0],
                        backgroundColor: [
                            '#EF4444', '#F97316', '#EAB308', '#22C55E', '#10B981'
                        ],
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Timeline Chart
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
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        function updateCharts(data) {
            // Update rating chart
            if (data.rating_distribution) {
                ratingChart.data.datasets[0].data = [
                    data.rating_distribution['1'] || 0,
                    data.rating_distribution['2'] || 0,
                    data.rating_distribution['3'] || 0,
                    data.rating_distribution['4'] || 0,
                    data.rating_distribution['5'] || 0
                ];
                ratingChart.update();
            }

            // Update timeline chart
            if (data.timeline) {
                timelineChart.data.labels = data.timeline.map(item => item.date);
                timelineChart.data.datasets[0].data = data.timeline.map(item => item.count);
                timelineChart.update();
            }
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Reminder Sent',
                        text: 'Student has been notified to complete the evaluation.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to Send Reminder',
                    text: error.message
                });
            }
        }

        function exportData() {
            const semester = document.getElementById('semester-select').value;
            
            const params = new URLSearchParams();
            if (semester) params.append('semester', semester);
            
            window.open(`/lecturer/evaluations/export?${params}`, '_blank');
        }

        function showError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message
            });
        }

        // Mobile menu and user dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    const isOpen = mobileMenu.classList.contains('opacity-100');
                    
                    if (isOpen) {
                        mobileMenu.classList.remove('opacity-100', 'visible');
                        mobileMenu.classList.add('opacity-0', 'invisible');
                    } else {
                        mobileMenu.classList.remove('opacity-0', 'invisible');
                        mobileMenu.classList.add('opacity-100', 'visible');
                    }
                });
            }

            // User dropdown toggle
            const userMenuButton = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-dropdown');
            
            if (userMenuButton && userDropdown) {
                userMenuButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isOpen = userDropdown.classList.contains('opacity-100');
                    
                    if (isOpen) {
                        userDropdown.classList.remove('opacity-100', 'visible', 'scale-100');
                        userDropdown.classList.add('opacity-0', 'invisible', 'scale-95');
                    } else {
                        userDropdown.classList.remove('opacity-0', 'invisible', 'scale-95');
                        userDropdown.classList.add('opacity-100', 'visible', 'scale-100');
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
                        userDropdown.classList.remove('opacity-100', 'visible', 'scale-100');
                        userDropdown.classList.add('opacity-0', 'invisible', 'scale-95');
                    }
                });
            }

            // Theme toggle functionality
            const themeToggle = document.getElementById('theme-toggle');
            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    document.documentElement.classList.toggle('dark');
                    
                    // Save theme preference
                    const isDark = document.documentElement.classList.contains('dark');
                    localStorage.setItem('theme', isDark ? 'dark' : 'light');
                });

                // Load saved theme
                const savedTheme = localStorage.getItem('theme');
                if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                }
            }
        });
    </script>
</body>
</html>
