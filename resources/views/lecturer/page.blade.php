<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturer Dashboard</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.8s ease-out forwards',
                        'slide-up': 'slideUp 0.6s ease-out forwards',
                        'bounce-in': 'bounceIn 0.8s ease-out forwards',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(40px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        bounceIn: {
                            '0%': { transform: 'scale(0.3)', opacity: '0' },
                            '50%': { transform: 'scale(1.05)' },
                            '70%': { transform: 'scale(0.9)' },
                            '100%': { transform: 'scale(1)', opacity: '1' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' }
                        }
                    },
                    transitionProperty: {
                        'width': 'width',
                        'height': 'height'
                    },
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 text-gray-800 dark:text-gray-200 transition-all duration-300 min-h-screen">
    <div class="min-h-screen">
        <!-- Enhanced Header with backdrop blur -->
        <header class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40 animate-fade-in">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg animate-float">
                            <i class="fas fa-chalkboard-teacher text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                                Welcome, <span id="lecturer-name">Lecturer</span> !
                            </h1>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Professional Dashboard</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        
                        <button id="theme-toggle" class="p-3 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 dark:hidden text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden dark:block text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-2 sm:px-4 sm:py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg text-sm sm:text-base font-medium">
                                    <i class="fas fa-sign-out-alt mr-1 sm:mr-2"></i>
                                    <span class="hidden sm:inline">Logout</span>
                                </button>
                            </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Analytics Cards Section -->
            <section class="mb-12 animate-slide-up" style="animation-delay: 0.2s">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Analytics Overview</h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Track your teaching performance and student feedback</p>
                    </div>
                    <div class="hidden md:flex items-center space-x-2">
                        <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Live Data</span>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Lecturer Evaluation Card -->
                    <a href="/lecturer-evaluation" class="group block animate-slide-up" style="animation-delay: 0.3s">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 border border-gray-100 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-600 h-full">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center space-x-4">
                                    <div class="p-4 rounded-2xl bg-gradient-to-r from-blue-500 to-blue-600 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas fa-star-half-alt text-white text-2xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Lecturer Evaluation</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Student feedback on teaching</p>
                                    </div>
                                </div>
                                <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <i class="fas fa-arrow-right text-blue-500 text-xl"></i>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-3 gap-6">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400" id="evaluation-rating">-</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Average Rating</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-green-600 dark:text-green-400" id="evaluation-responses">-</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Responses</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-purple-600 dark:text-purple-400" id="evaluation-updated">-</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Last Updated</div>
                                </div>
                            </div>
                        </div>
                    </a>
                    
                    <!-- End of Course Survey Card -->
                    <a href="/course-survey" class="group block animate-slide-up" style="animation-delay: 0.4s">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 border border-gray-100 dark:border-gray-700 hover:border-green-300 dark:hover:border-green-600 h-full">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center space-x-4">
                                    <div class="p-4 rounded-2xl bg-gradient-to-r from-green-500 to-emerald-600 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas fa-clipboard-check text-white text-2xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Course Survey</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Course completion feedback</p>
                                    </div>
                                </div>
                                <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <i class="fas fa-arrow-right text-green-500 text-xl"></i>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-3 gap-6">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-green-600 dark:text-green-400" id="survey-average-rating">-</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Average Rating</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400" id="survey-responses">-</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Responses</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-purple-600 dark:text-purple-400" id="survey-updated">-</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Last Updated</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </section>

            <!-- Course Management Section -->
            <section class="animate-slide-up" style="animation-delay: 0.5s">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Course Management</h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Manage your assigned courses and students</p>
                    </div>
                    
                </div>
                
                <div id="courses-container" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                    <!--     Course cards will be loaded here by JavaScript -->
                </div>
            </section>
        </main>
    </div>

    <script>
        // Theme toggle
        const themeToggle = document.getElementById('theme-toggle');
        const html = document.documentElement;

        themeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        });

        // Check for saved theme preference
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }

        // Print functionality
        const printButtons = [document.getElementById('print-btn'), document.getElementById('mobile-print-btn')];
        
        printButtons.forEach(btn => {
            if (btn) {
                btn.addEventListener('click', () => {
                    window.print();
                });
            }
        });
        
        // Fetch and display lecturer's courses
        async function fetchLecturerCourses() {
            try {
                const response = await fetch('/lecturer/courses');
                let data;
                try {
                    data = await response.json();
                } catch (e) {
                    data = { error: 'Invalid JSON from /lecturer/courses' };
                }

                const coursesContainer = document.getElementById('courses-container');
                coursesContainer.innerHTML = ''; // Clear existing static content

                if (!response.ok) {
                    const message = (data && (data.error || data.message)) || `HTTP ${response.status}`;
                    coursesContainer.innerHTML = `<p class="text-center text-red-500 col-span-full">Error loading courses: ${message}</p>`;
                    return;
                }

                const courses = Array.isArray(data.courses) ? data.courses : [];
                if (courses.length === 0) {
                    coursesContainer.innerHTML = '<p class="text-center text-gray-500 col-span-full">No courses assigned to you.</p>';
                    return;
                }

                courses.forEach((course, index) => {
                    const studentsCount = (typeof course.students_count === 'number' ? course.students_count :
                                            typeof course.student_count === 'number' ? course.student_count :
                                            typeof course.total_students === 'number' ? course.total_students :
                                            Array.isArray(course.students) ? course.students.length :
                                            null);
                    const studentsText = (studentsCount !== null && !Number.isNaN(studentsCount)) ? studentsCount : '-';
                    // Derive a clean semester number if possible
                    let semesterNumber = null;
                    if (typeof course.semester === 'number') {
                        semesterNumber = course.semester;
                    } else if (typeof course.semester === 'string') {
                        const m = course.semester.match(/\d+/);
                        semesterNumber = m ? parseInt(m[0], 10) : null;
                    }
                    const semesterText = (semesterNumber !== null ? `Semester ${semesterNumber}` : '-');
                    const courseCard = `
                        <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 border border-gray-100 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-600 animate-slide-up" style="animation-delay: ${0.1 * (index + 1)}s">
                            <div class="p-8">
                                <div class="flex items-start justify-between mb-6">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-3">
                                            <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                                <i class="fas fa-book text-white text-lg"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">${course.code}</h3>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">${semesterText}</p>
                                            </div>
                                        </div>
                                        <p class="text-gray-700 dark:text-gray-300 font-medium mb-4">${course.name}</p>
                                    </div>
                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <i class="fas fa-arrow-right text-indigo-500 text-xl"></i>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-users text-blue-600 dark:text-blue-400"></i>
                                        </div>
                                        <div>
                                            <div class="text-2xl font-bold text-gray-900 dark:text-white">${studentsText}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Students</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="w-3 h-3 bg-green-500 rounded-full mb-1"></div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Active</div>
                                    </div>
                                </div>
                                
                                <a href="/course/${course.id}" class="block w-full py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-center rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl font-semibold group-hover:scale-105 transform">
                                    <i class="fas fa-cog mr-2"></i>Manage Course
                                </a>
                            </div>
                        </div>
                    `;
                    coursesContainer.innerHTML += courseCard;
                });
            } catch (error) {
                console.error('Error fetching lecturer courses:', error);
                document.getElementById('courses-container').innerHTML = '<p class="text-center text-red-500 col-span-full">Error loading courses.</p>';
            }
        }

        // Fetch and display lecturer's name
        async function fetchLecturerName() {
            try {
                const response = await fetch('/api/user'); // Assuming an API endpoint to get the authenticated user
                const userData = await response.json();
                if (userData && userData.name) {
                    document.getElementById('lecturer-name').textContent = userData.name;
                }
            } catch (error) {
                console.error('Error fetching lecturer name:', error);
                document.getElementById('lecturer-name').textContent = 'Lecturer';
            }
        }

        // Fetch and display lecturer's evaluation and survey statistics
        async function fetchLecturerStatistics() {
            try {
                const response = await fetch('/lecturer/statistics');
                const data = await response.json();

                // Update evaluation statistics
                const evaluationRating = document.getElementById('evaluation-rating');
                const evaluationResponses = document.getElementById('evaluation-responses');
                const evaluationUpdated = document.getElementById('evaluation-updated');

                if (data.evaluation.average_rating !== null) {
                    evaluationRating.innerHTML = `${data.evaluation.average_rating}<span class="text-sm text-gray-500">/5.0</span>`;
                } else {
                    evaluationRating.innerHTML = `-<span class="text-sm text-gray-500">/5.0</span>`;
                }

                evaluationResponses.textContent = data.evaluation.responses || '-';

                if (data.evaluation.last_updated) {
                    const lastUpdated = new Date(data.evaluation.last_updated);
                    const now = new Date();
                    const diffTime = Math.abs(now - lastUpdated);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    
                    if (diffDays === 1) {
                        evaluationUpdated.textContent = '1d';
                    } else if (diffDays < 7) {
                        evaluationUpdated.textContent = `${diffDays}d`;
                    } else if (diffDays < 30) {
                        const weeks = Math.floor(diffDays / 7);
                        evaluationUpdated.textContent = `${weeks}w`;
                    } else {
                        const months = Math.floor(diffDays / 30);
                        evaluationUpdated.textContent = `${months}m`;
                    }
                } else {
                    evaluationUpdated.textContent = '-';
                }

                // Update survey statistics
                const surveyAverageRating = document.getElementById('survey-average-rating');
                const surveyResponses = document.getElementById('survey-responses');
                const surveyUpdated = document.getElementById('survey-updated');

                if (data.survey.average_rating > 0) {
                    surveyAverageRating.innerHTML = `${data.survey.average_rating}<span class="text-sm text-gray-500">/5.0</span>`;
                } else {
                    surveyAverageRating.innerHTML = `-<span class="text-sm text-gray-500">/5.0</span>`;
                }

                surveyResponses.textContent = data.survey.responses || '-';

                if (data.survey.last_updated) {
                    const lastUpdated = new Date(data.survey.last_updated);
                    const now = new Date();
                    const diffTime = Math.abs(now - lastUpdated);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    
                    if (diffDays === 1) {
                        surveyUpdated.textContent = '1d';
                    } else if (diffDays < 7) {
                        surveyUpdated.textContent = `${diffDays}d`;
                    } else if (diffDays < 30) {
                        const weeks = Math.floor(diffDays / 7);
                        surveyUpdated.textContent = `${weeks}w`;
                    } else {
                        const months = Math.floor(diffDays / 30);
                        surveyUpdated.textContent = `${months}m`;
                    }
                } else {
                    surveyUpdated.textContent = '-';
                }

            } catch (error) {
                console.error('Error fetching lecturer statistics:', error);
                // Keep default values (-/5.0, -, etc.) on error
            }
        }

        // Load lecturer statistics on page load and when page becomes visible
        document.addEventListener('DOMContentLoaded', function() {
            fetchLecturerStatistics();
        });

        // Refresh data when page becomes visible (e.g., when navigating back)
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                fetchLecturerStatistics();
            }
        });

        // Also refresh when window gains focus
        window.addEventListener('focus', function() {
            fetchLecturerStatistics();
        });

        // Call functions on page load
        document.addEventListener('DOMContentLoaded', () => {
            fetchLecturerCourses();
            fetchLecturerName();
            fetchLecturerStatistics();
        });

        // Refresh courses when page becomes visible (e.g., when navigating back)
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                fetchLecturerCourses();
                fetchLecturerStatistics();
            }
        });

        // Also refresh when window gains focus
        window.addEventListener('focus', function() {
            fetchLecturerCourses();
            fetchLecturerStatistics();
        });
    </script>
</body>
</html>