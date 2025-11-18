<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student Dashboard</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.6s ease-out',
                        'slide-up': 'slideUp 0.8s ease-out forwards',
                        'bounce-in': 'bounceIn 0.6s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
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

        async function fetchCompletionStatus() {
            try {
                const res = await fetch('/student/status');
                if (!res.ok) throw new Error('Failed to fetch status');
                const status = await res.json();
                if (!status.success) throw new Error(status.message || 'Status error');
                studentStatus = status;
            } catch (e) {
                console.warn('Status fetch failed:', e);
            }
        }

        function updateCardIconsAllComplete() {
            const evalIcon = document.getElementById('evaluation-status');
            const surveyIcon = document.getElementById('survey-status');

            const assignedLecturers = (studentAssignedData?.[0]?.lecturers || []).length;
            const assignedCourses = (studentAssignedData?.[0]?.courses || []).length;
            const completedLecturers = new Set(studentStatus?.evaluated_lecturer_ids || []);
            const completedCourses = new Set(studentStatus?.surveyed_course_ids || []);

            const evalAll = assignedLecturers > 0 && completedLecturers.size === assignedLecturers;
            const surveyAll = assignedCourses > 0 && completedCourses.size === assignedCourses;

            evalIcon.innerHTML = evalAll
                ? '<i class="fas fa-check-circle text-green-500"></i>'
                : '<i class="fas fa-clock text-orange-500"></i>';
            surveyIcon.innerHTML = surveyAll
                ? '<i class="fas fa-check-circle text-green-500"></i>'
                : '<i class="fas fa-clock text-orange-500"></i>';
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 text-gray-800 dark:text-gray-200 transition-all duration-300 min-h-screen">
    <!-- Mobile-first responsive container -->
    <div class="min-h-screen">
        <!-- Header with improved mobile design -->
        <header class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16 sm:h-20">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-graduation-cap text-white text-lg sm:text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                Student Portal
                            </h1>
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400" id="student-name">{{ auth()->user()->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2 sm:space-x-4">
                        <button id="theme-toggle" class="p-2 sm:p-3 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 dark:hidden" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 hidden dark:block" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <form method="POST" action="/logout" class="inline">
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
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            <!-- Welcome Section -->
            <div class="mb-8 sm:mb-12 text-center animate-fade-in">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-800 dark:text-gray-100 mb-2">
                    Welcome Back! 👋
                </h2>
                <p class="text-gray-600 dark:text-gray-400 text-sm sm:text-base">
                    Complete your evaluations and surveys to help improve your learning experience
                </p>
            </div>

            <!-- Quick Actions Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-8 sm:mb-12">
                <!-- Lecturer Evaluation Card -->
                <div class="group animate-slide-up" style="animation-delay: 0.1s">
                    <button id="evaluation-btn" class="w-full p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-600 group-hover:scale-105">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-md">
                                <i class="fas fa-user-tie text-white text-lg"></i>
                            </div>
                            <span class="text-2xl" id="evaluation-status">
                                <i class="fas fa-clock text-orange-500"></i>
                            </span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Lecturer Evaluation</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 text-left">Rate your lecturers' teaching performance</p>
                    </button>
                </div>

                <!-- End of Course Survey Card -->
                <div class="group animate-slide-up" style="animation-delay: 0.2s">
                    <button id="survey-btn" class="w-full p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 hover:border-purple-300 dark:hover:border-purple-600 group-hover:scale-105">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-md">
                                <i class="fas fa-poll text-white text-lg"></i>
                            </div>
                            <span class="text-2xl" id="survey-status">
                                <i class="fas fa-clock text-orange-500"></i>
                            </span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Course Survey</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 text-left">Evaluate course learning outcomes</p>
                    </button>
                </div>
            </div>

            <!-- Your Courses Section -->
            <div class="animate-fade-in" style="animation-delay: 0.4s">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-gray-100">Your Courses</h2>
                    <div class="flex items-center space-x-2">
                        <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm font-medium">
                            <i class="fas fa-book mr-1"></i>
                            <span id="course-count">Loading...</span>
                        </span>
                    </div>
                </div>
                
                <div id="courses-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    <!-- Courses will be loaded here dynamically -->
                </div>
            </div>
        </main>
    </div>

    <!-- Evaluation Modal -->
    <div id="evaluationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Lecturer Evaluation</h3>
                    <button onclick="closeModal('evaluationModal')" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="evaluationForm" class="space-y-4">
                    @csrf
                    <div>
                        <label for="evaluation_lecturer" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lecturer</label>
                        <select id="evaluation_lecturer" required class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Lecturer</option>
                        </select>
                    </div>
                    <div>
                        <label for="evaluation_course" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Course</label>
                        <select id="evaluation_course" required class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Course</option>
                        </select>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">Evaluation Questions</h4>
                        <div id="evaluation-questions-container" class="space-y-4">
                            <div class="text-center py-4">
                                <div class="animate-spin w-6 h-6 border-4 border-blue-500 border-t-transparent rounded-full mx-auto mb-2"></div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Loading questions...</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="evaluation_suggestion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Suggestion (Optional)</label>
                        <textarea id="evaluation_suggestion" rows="3" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500" placeholder="Share any suggestions for improvement"></textarea>
                    </div>
                    <div class="flex justify-end space-x-2 mt-6">
                        <button type="button" onclick="closeModal('evaluationModal')" class="px-4 py-2 border rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 transition-colors">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">Submit Evaluation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Survey Modal -->
    <div id="surveyModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-poll text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white">Course Survey</h3>
                            <p id="survey-subtitle" class="text-sm text-gray-600 dark:text-gray-400 hidden">Rate Course Learning Outcomes</p>
                        </div>
                    </div>
                    <button onclick="closeModal('surveyModal')" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-140px)]">
                <form id="surveyForm" class="space-y-6">
                    @csrf
                    <div>
                        <label for="survey_course" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-book mr-2 text-purple-500"></i>Select Course
                        </label>
                        <select id="survey_course" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                            <option value="">Choose a course...</option>
                        </select>
                    </div>
                    
                    <div id="clo-questions-container" class="space-y-4">
                        <!-- Dynamic CLO questions will be loaded here -->
                        <div class="text-center py-8">
                            <div class="animate-spin w-8 h-8 border-4 border-purple-500 border-t-transparent rounded-full mx-auto mb-4"></div>
                            <p class="text-gray-500 dark:text-gray-400">Select a course to load survey questions</p>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" onclick="closeModal('surveyModal')" class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 transition-colors font-medium">
                            Cancel
                        </button>
                        <button type="submit" id="submit-survey-btn" disabled class="px-6 py-3 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl hover:from-purple-600 hover:to-purple-700 transition-all duration-200 font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-paper-plane mr-2"></i>Submit Survey
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Theme toggle
        document.getElementById('theme-toggle').addEventListener('click', function() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        });

        // Check for saved theme preference
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }

        // Button click handlers
        document.getElementById('evaluation-btn').addEventListener('click', function() {
            // Option A: Always allow navigating to evaluation page.
            // Server will enforce per-lecturer duplicate prevention.
            window.location.href = '/student/evaluation';
        });

        document.getElementById('evaluationForm').addEventListener('submit', submitEvaluation);

        document.getElementById('survey-btn').addEventListener('click', function() {
            // Option A: Always allow opening survey modal to submit per course.
            openModal('surveyModal');
            populateSurveyForm();
        });

        document.getElementById('surveyForm').addEventListener('submit', submitSurvey);


        // Function to open/close modals
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            if (modalId === 'evaluationModal') {
                document.getElementById('evaluationForm').reset();
            } else if (modalId === 'surveyModal') {
                document.getElementById('surveyForm').reset();
                // Clear CLO questions container when closing survey modal
                const container = document.getElementById('clo-questions-container');
                const submitBtn = document.getElementById('submit-survey-btn');
                if (container) {
                    container.innerHTML = `
                        <div class="text-center py-8">
                            <div class="animate-spin w-8 h-8 border-4 border-purple-500 border-t-transparent rounded-full mx-auto mb-4"></div>
                            <p class="text-gray-500 dark:text-gray-400">Select a course to load survey questions</p>
                        </div>
                    `;
                }
                if (submitBtn) {
                    submitBtn.disabled = true;
                }
            }
        }

        let studentAssignedData = []; // Store fetched assigned data
        let studentStatus = null; // Store fetched completion status

        async function fetchStudentAssignedData() {
            try {
                const response = await fetch('/student/assigned-data');
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                if (data.success === false) {
                    throw new Error(data.message || 'Failed to fetch assigned data');
                }
                studentAssignedData = data;
                console.log('Fetched student assigned data:', studentAssignedData);
                
                // Update courses display
                displayCourses();
            } catch (error) {
                console.error('Error fetching student assigned data:', error);
                displayCoursesError();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load your assigned lecturers and courses.',
                    background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                    color: document.documentElement.classList.contains('dark') ? '#FFFFFF' : '#000000',
                });
            }
        }

        function displayCourses() {
            const container = document.getElementById('courses-container');
            const countElement = document.getElementById('course-count');
            
            if (studentAssignedData.length > 0 && studentAssignedData[0].courses && studentAssignedData[0].courses.length > 0) {
                const courses = studentAssignedData[0].courses;
                countElement.textContent = `${courses.length} courses`;
                
                let coursesHTML = '';
                courses.forEach((course, index) => {
                    coursesHTML += `
                        <div class="group animate-slide-up" style="animation-delay: ${0.1 * (index + 1)}s">
                            <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-600 hover:-translate-y-2 group-hover:scale-105">
                                <div class="flex items-start justify-between mb-6">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-4">
                                            <div class="w-14 h-14 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                                <i class="fas fa-book text-white text-xl"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-xl font-bold text-gray-800 dark:text-white">${course.code}</h3>
                                                <div class="flex items-center mt-1">
                                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">Active</span>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-gray-700 dark:text-gray-300 font-medium mb-4">${course.name}</p>
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <i class="fas fa-chalkboard-teacher mr-2 text-indigo-500"></i>
                                            <span>Lecturer assigned</span>
                                        </div>
                                    </div>
                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <i class="fas fa-arrow-right text-indigo-500 text-xl"></i>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-star text-blue-600 dark:text-blue-400 text-sm"></i>
                                            </div>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">ID: ${course.id}</span>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <div class="w-3 h-3 bg-gradient-to-r from-green-400 to-blue-500 rounded-full animate-pulse"></div>
                                        <div class="w-3 h-3 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full animate-pulse" style="animation-delay: 0.2s"></div>
                                        <div class="w-3 h-3 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full animate-pulse" style="animation-delay: 0.4s"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                container.innerHTML = coursesHTML;
            } else {
                countElement.textContent = '0 courses';
                container.innerHTML = `
                    <div class="col-span-full text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-book text-gray-400 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-600 dark:text-gray-400 mb-2">No Courses Assigned</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-500">You don't have any courses assigned yet. Please contact your administrator.</p>
                    </div>
                `;
            }
        }

        function displayCoursesError() {
            const container = document.getElementById('courses-container');
            const countElement = document.getElementById('course-count');
            
            countElement.textContent = 'Error';
            container.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <div class="w-16 h-16 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-red-600 dark:text-red-400 mb-2">Failed to Load Courses</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-500 mb-4">Unable to fetch your course data. Please try refreshing the page.</p>
                    <button onclick="fetchStudentAssignedData()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-refresh mr-2"></i>Try Again
                    </button>
                </div>
            `;
        }

        function populateEvaluationForm() {
            const lecturerSelect = document.getElementById('evaluation_lecturer');
            const courseSelect = document.getElementById('evaluation_course');

            lecturerSelect.innerHTML = '<option value="">Select Lecturer</option>';
            courseSelect.innerHTML = '<option value="">Select Course</option>';

            if (studentAssignedData.length > 0 && studentAssignedData[0].lecturers) {
                studentAssignedData[0].lecturers.forEach(lecturer => {
                    const option = document.createElement('option');
                    option.value = lecturer.id;
                    const isDone = Array.isArray(studentStatus?.evaluated_lecturer_ids) && studentStatus.evaluated_lecturer_ids.includes(lecturer.id);
                    option.textContent = isDone ? `${lecturer.name} (Completed)` : lecturer.name;
                    if (isDone) option.disabled = true;
                    lecturerSelect.appendChild(option);
                });
            }

            if (studentAssignedData.length > 0 && studentAssignedData[0].courses) {
                studentAssignedData[0].courses.forEach(course => {
                    const option = document.createElement('option');
                    option.value = course.id;
                    const isDone = Array.isArray(studentStatus?.surveyed_course_ids) && studentStatus.surveyed_course_ids.includes(course.id);
                    option.textContent = isDone ? `${course.code} - ${course.name} (Completed)` : `${course.code} - ${course.name}`;
                    if (isDone) option.disabled = true;
                    courseSelect.appendChild(option);
                });
            }
            // Load dynamic evaluation questions
            loadEvaluationQuestions();
        }

        async function loadEvaluationQuestions() {
            const container = document.getElementById('evaluation-questions-container');
            if (!container) return;

            container.innerHTML = `
                <div class="text-center py-4">
                    <div class="animate-spin w-6 h-6 border-4 border-blue-500 border-t-transparent rounded-full mx-auto mb-2"></div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Loading questions...</p>
                </div>
            `;

            try {
                const response = await fetch('/evaluation/questions');
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                const data = await response.json();
                const sections = data.sections || {};

                let html = '';
                Object.entries(sections).forEach(([sectionName, questions]) => {
                    html += `
                        <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl p-4">
                            <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">${sectionName}</h5>
                            <div class="space-y-3">
                    `;
                    questions.forEach(q => {
                        if (q.type === 'text') {
                            // Skip text question here; we already render Suggestion textarea separately
                            return;
                        }
                        html += `
                            <div>
                                <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">
                                    <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-0.5 rounded mr-2 text-xs font-semibold">Q${q.id}</span>
                                    ${q.text}
                                </label>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Poor</span>
                                    <div class="flex space-x-2">
                                        ${[1,2,3,4,5].map(r => `
                                            <label class="flex items-center">
                                                <input type="radio" name="q_${q.id}" value="${r}" required class="sr-only peer">
                                                <div class="w-8 h-8 rounded-full border-2 border-gray-300 dark:border-gray-600 flex items-center justify-center cursor-pointer hover:border-blue-400 peer-checked:bg-blue-500 peer-checked:border-blue-500 peer-checked:text-white transition">${r}</div>
                                            </label>
                                        `).join('')}
                                    </div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Excellent</span>
                                </div>
                            </div>
                        `;
                    });
                    html += `
                            </div>
                        </div>
                    `;
                });

                container.innerHTML = html || '<p class="text-sm text-gray-500 dark:text-gray-400">No questions available.</p>';
            } catch (err) {
                console.error('Failed to load evaluation questions', err);
                container.innerHTML = '<p class="text-sm text-red-600 dark:text-red-400">Failed to load questions.</p>';
            }
        }

        function populateSurveyForm() {
            const courseSelect = document.getElementById('survey_course');
            const subtitle = document.getElementById('survey-subtitle');
            courseSelect.innerHTML = '<option value="">Choose a course...</option>';
            if (subtitle) subtitle.classList.add('hidden');

            if (studentAssignedData.length > 0 && studentAssignedData[0].courses) {
                studentAssignedData[0].courses.forEach(course => {
                    const option = document.createElement('option');
                    option.value = course.id;
                    const isDone = Array.isArray(studentStatus?.surveyed_course_ids) && studentStatus.surveyed_course_ids.includes(course.id);
                    option.textContent = isDone ? `${course.code} - ${course.name} (Completed)` : `${course.code} - ${course.name}`;
                    if (isDone) option.disabled = true;
                    courseSelect.appendChild(option);
                });
            }

            // Attach single change handler to avoid duplicates
            courseSelect.onchange = loadCLOQuestions;
        }

        async function loadCLOQuestions() {
            const courseId = document.getElementById('survey_course').value;
            const container = document.getElementById('clo-questions-container');
            const submitBtn = document.getElementById('submit-survey-btn');
            const subtitle = document.getElementById('survey-subtitle');

            if (!courseId) {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <div class="animate-spin w-8 h-8 border-4 border-purple-500 border-t-transparent rounded-full mx-auto mb-4"></div>
                        <p class="text-gray-500 dark:text-gray-400">Select a course to load survey questions</p>
                    </div>
                `;
                submitBtn.disabled = true;
                if (subtitle) subtitle.classList.add('hidden');
                return;
            }

            // Show loading state
            container.innerHTML = `
                <div class="text-center py-8">
                    <div class="animate-spin w-8 h-8 border-4 border-purple-500 border-t-transparent rounded-full mx-auto mb-4"></div>
                    <p class="text-gray-500 dark:text-gray-400">Loading course learning outcomes...</p>
                </div>
            `;

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                const response = await fetch(`/courses/${courseId}/clos`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        ...(csrfToken && { 'X-CSRF-TOKEN': csrfToken.getAttribute('content') })
                    }
                });
                
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                
                if (!response.ok) {
                    // If CLOs not found or lecturer hasn't filled them, show a friendly message
                    if (response.status === 404 || response.status === 204) {
                        container.innerHTML = `
                            <div class="text-center py-8">
                                <i class="fas fa-exclamation-triangle text-yellow-500 text-3xl mb-4"></i>
                                <p class="text-gray-600 dark:text-gray-400">The lecturer has not submitted Course Learning Outcomes for this course.</p>
                                <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Rate Course Learning Outcomes will appear here once the lecturer provides them.</p>
                            </div>
                        `;
                        submitBtn.disabled = true;
                        if (subtitle) subtitle.classList.add('hidden');
                        return;
                    }
                    const errorText = await response.text();
                    console.error('Response error:', errorText);
                    throw new Error(`HTTP ${response.status}: ${errorText}`);
                }
                
                const data = await response.json();
                console.log('CLO data received:', data);
                const clos = Array.isArray(data?.clos) ? data.clos : [];

                if (clos.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-triangle text-yellow-500 text-3xl mb-4"></i>
                            <p class="text-gray-600 dark:text-gray-400">The lecturer has not submitted Course Learning Outcomes for this course.</p>
                            <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Please contact your lecturer to add CLOs. The rating section will appear once available.</p>
                        </div>
                    `;
                    submitBtn.disabled = true;
                    if (subtitle) subtitle.classList.add('hidden');
                    return;
                }

                // Generate CLO questions
                let questionsHTML = `
                    <div class="mb-4">
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">
                            <i class="fas fa-star text-purple-500 mr-2"></i>Rate Course Learning Outcomes
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Please rate how well you achieved each learning outcome (1 = Poor, 5 = Excellent)</p>
                    </div>
                `;

                clos.forEach((clo, index) => {
                    questionsHTML += `
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4 border border-gray-200 dark:border-gray-600">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                <span class="flex items-start">
                                    <span class="bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 px-2 py-1 rounded-lg text-xs font-semibold mr-3 mt-0.5">CLO ${index + 1}</span>
                                    <span class="flex-1">${clo.description}</span>
                                </span>
                            </label>
                            <div class="flex items-center justify-between mt-3">
                                <span class="text-xs text-gray-500 dark:text-gray-400">Poor</span>
                                <div class="flex space-x-2">
                                    ${[1, 2, 3, 4, 5].map(rating => `
                                        <label class="flex items-center">
                                            <input type="radio" name="clo_${clo.id}" value="${rating}" required 
                                                   class="sr-only peer" onchange="checkFormCompletion()">
                                            <div class="w-10 h-10 rounded-full border-2 border-gray-300 dark:border-gray-600 flex items-center justify-center cursor-pointer hover:border-purple-400 peer-checked:bg-purple-500 peer-checked:border-purple-500 peer-checked:text-white transition-all duration-200">
                                                ${rating}
                                            </div>
                                        </label>
                                    `).join('')}
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Excellent</span>
                            </div>
                        </div>
                    `;
                });

                container.innerHTML = questionsHTML;
                submitBtn.disabled = false;
                if (subtitle) {
                    subtitle.textContent = 'Rate Course Learning Outcomes';
                    subtitle.classList.remove('hidden');
                }

            } catch (error) {
                console.error('Error loading CLOs:', error);
                container.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-circle text-red-500 text-3xl mb-4"></i>
                        <p class="text-red-600 dark:text-red-400">Failed to load course learning outcomes.</p>
                        <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Please try again or contact support.</p>
                    </div>
                `;
                submitBtn.disabled = true;
            }
        }

        function checkFormCompletion() {
            const courseSelect = document.getElementById('survey_course');
            const submitBtn = document.getElementById('submit-survey-btn');
            const radioGroups = document.querySelectorAll('input[type="radio"][name^="clo_"]');
            
            // Get unique CLO groups
            const cloGroups = new Set();
            radioGroups.forEach(radio => {
                cloGroups.add(radio.name);
            });
            
            // Check if all CLO groups have a selection
            let allCompleted = courseSelect.value !== '';
            for (const groupName of cloGroups) {
                const groupRadios = document.querySelectorAll(`input[name="${groupName}"]`);
                const hasSelection = Array.from(groupRadios).some(radio => radio.checked);
                if (!hasSelection) {
                    allCompleted = false;
                    break;
                }
            }
            
            submitBtn.disabled = !allCompleted;
        }

        async function submitEvaluation(event) {
            event.preventDefault();
            
            const submitBtn = event.target.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';

            const lecturerId = document.getElementById('evaluation_lecturer').value;
            const courseId = document.getElementById('evaluation_course').value;
            // Collect answers from Q1–Q23 radios
            const answers = {};
            const radios = document.querySelectorAll('#evaluation-questions-container input[type="radio"]:checked');
            radios.forEach(r => {
                const qId = r.name.replace('q_', '');
                answers[qId] = parseInt(r.value);
            });
            const suggestion = document.getElementById('evaluation_suggestion').value;
            const semester = studentAssignedData.length > 0 ? studentAssignedData[0].semester : ''; // Get semester from fetched data

            try {
                const response = await fetch('/evaluations', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        lecturer_id: lecturerId,
                        course_id: courseId,
                        answers: answers,
                        suggestion: suggestion,
                        semester: semester,
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    let errorMessage = data.message || 'Failed to submit evaluation.';
                    if (data.errors) {
                        errorMessage += '\n' + Object.values(data.errors).map(e => e.join(', ')).join('\n');
                    }
                    throw new Error(errorMessage);
                }

                Swal.fire({
                    title: 'Evaluation Submitted!',
                    text: data.message || 'Thank you for your feedback.',
                    icon: 'success',
                    confirmButtonColor: '#3B82F6',
                    background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                    color: document.documentElement.classList.contains('dark') ? '#FFFFFF' : '#000000',
                });
                closeModal('evaluationModal');
                // Optionally update UI based on successful submission
                document.getElementById('evaluation-status').innerHTML = '<i class="fas fa-check-circle"></i>';
                document.getElementById('evaluation-status').classList.remove('text-red-500');
                document.getElementById('evaluation-status').classList.add('text-green-500');
            } catch (error) {
                console.error('Error submitting evaluation:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Submission Failed',
                    text: error.message,
                    background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                    color: document.documentElement.classList.contains('dark') ? '#FFFFFF' : '#000000',
                });
            }
            finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit Evaluation';
            }
        }

        async function submitSurvey(event) {
            event.preventDefault();

            const submitBtn = event.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Submitting...';

            const courseId = document.getElementById('survey_course').value;
            const semester = studentAssignedData.length > 0 ? `Semester ${studentAssignedData[0].semester}` : '';

            // Collect CLO ratings
            const cloRatings = {};
            const radioGroups = document.querySelectorAll('input[type="radio"][name^="clo_"]:checked');
            
            radioGroups.forEach(radio => {
                const cloId = radio.name.replace('clo_', '');
                cloRatings[cloId] = parseInt(radio.value);
            });

            try {
                const response = await fetch('/surveys', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        course_id: courseId,
                        clo_ratings: cloRatings,
                        comment: document.getElementById('survey_comment')?.value || '',
                        semester: semester,
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    let errorMessage = `${data.message || 'Failed to submit survey.'}`;
                    if (data.error) {
                        errorMessage += `\nDetails: ${data.error}`;
                    }
                    if (data.errors) {
                        errorMessage += '\n' + Object.values(data.errors).map(e => e.join(', ')).join('\n');
                    }
                    errorMessage += `\nStatus: ${response.status}`;
                    throw new Error(errorMessage);
                }

                Swal.fire({
                    title: 'Survey Submitted!',
                    text: data.message || 'Thank you for rating the course learning outcomes.',
                    icon: 'success',
                    confirmButtonColor: '#8B5CF6',
                    background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                    color: document.documentElement.classList.contains('dark') ? '#FFFFFF' : '#000000',
                });
                closeModal('surveyModal');
                // Update local status arrays for accurate icon computation
                if (studentStatus) {
                    studentStatus.surveyed_course_ids = Array.isArray(studentStatus.surveyed_course_ids) ? studentStatus.surveyed_course_ids : [];
                    const cid = parseInt(document.getElementById('survey_course').value, 10);
                    if (!studentStatus.surveyed_course_ids.includes(cid)) studentStatus.surveyed_course_ids.push(cid);
                }
                updateCardIconsAllComplete();
            } catch (error) {
                console.error('Error submitting survey:', error);
                if (String(error.message || '').includes('409')) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Already Submitted',
                        text: 'You have already submitted this survey for the selected course and semester.',
                    });
                    // Reflect state locally and recompute icons
                    if (studentStatus) {
                        studentStatus.surveyed_course_ids = Array.isArray(studentStatus.surveyed_course_ids) ? studentStatus.surveyed_course_ids : [];
                        const cid = parseInt(document.getElementById('survey_course').value, 10);
                        if (!studentStatus.surveyed_course_ids.includes(cid)) studentStatus.surveyed_course_ids.push(cid);
                    }
                    updateCardIconsAllComplete();
                    return;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Submission Failed',
                    text: error.message,
                    background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                    color: document.documentElement.classList.contains('dark') ? '#FFFFFF' : '#000000',
                });
            }
            finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }

        // Show CLO Rating Modal with course selection
        async function showCLORatingModal() {
            if (studentAssignedData.length === 0 || !studentAssignedData[0].courses) {
                Swal.fire({
                    icon: 'info',
                    title: 'No Courses Available',
                    text: 'You have no courses assigned for CLO rating.',
                    background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                    color: document.documentElement.classList.contains('dark') ? '#FFFFFF' : '#000000',
                });
                return;
            }

            const courses = studentAssignedData[0].courses;
            const courseOptions = courses.map(course => 
                `<option value="${course.id}">${course.code} - ${course.name}</option>`
            ).join('');

            const { value: courseId } = await Swal.fire({
                title: 'Select Course to Rate CLOs',
                html: `
                    <select id="course-select" class="swal2-input">
                        <option value="">Select a course</option>
                        ${courseOptions}
                    </select>
                `,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Continue',
                background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                color: document.documentElement.classList.contains('dark') ? '#FFFFFF' : '#000000',
                preConfirm: () => {
                    const courseId = document.getElementById('course-select').value;
                    if (!courseId) {
                        Swal.showValidationMessage('Please select a course');
                    }
                    return courseId;
                }
            });

            if (courseId) {
                window.location.href = `/course/${courseId}/rate-clos`;
            }
        }

        // Initial data fetch on page load
        document.addEventListener('DOMContentLoaded', async () => {
            await fetchStudentAssignedData();
            await fetchCompletionStatus();
            updateCardIconsAllComplete();
            // Option A: Keep both entry buttons enabled; rely on per-lecturer/per-course validation server-side
        });
    </script>
</body>
</html>