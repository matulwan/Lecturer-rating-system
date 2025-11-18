<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    transitionProperty: {
                        'width': 'width',
                        'height': 'height'
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.6s ease-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'bounce-in': 'bounceIn 0.8s ease-out',
                        'float': 'float 3s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s infinite'
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(-10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        bounceIn: {
                            '0%': { opacity: '0', transform: 'scale(0.3)' },
                            '50%': { opacity: '1', transform: 'scale(1.05)' },
                            '70%': { transform: 'scale(0.9)' },
                            '100%': { opacity: '1', transform: 'scale(1)' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' }
                        }
                    }
                }
            }
        }
    </script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 text-gray-800 dark:text-gray-200 transition-all duration-300">
    <div class="flex min-h-screen animate-fade-in">
        <!-- Sidebar removed -->
        
        <!-- Main content -->
        <div class="flex-1 overflow-auto w-full">
            <div class="p-3 sm:p-4 md:p-6 max-w-full">
                <!-- Enhanced Header -->
                <header class="mb-8 sm:mb-12 sticky top-0 z-40 bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg rounded-2xl shadow-lg border border-gray-200/50 dark:border-gray-700/50 p-6 animate-slide-up">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
                        <div class="flex items-center space-x-4">
                            <div>
                                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold leading-tight">
                                    Welcome, <span class="bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent" id="admin-name">{{ auth()->user()->name }}</span> !
                                </h1>
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
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="group relative px-2 py-2 sm:px-6 sm:py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg sm:rounded-xl hover:from-red-600 hover:to-red-700 active:from-red-700 active:to-red-800 transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 text-xs sm:text-base font-medium min-w-[44px] min-h-[44px] sm:min-w-[48px] sm:min-h-[48px] flex items-center justify-center" title="Logout" aria-label="Logout">
                                    <i class="fas fa-sign-out-alt text-white text-sm sm:text-lg mr-1 sm:mr-2 group-hover:scale-110 transition-transform duration-200"></i>
                                    <span class="hidden sm:inline text-white font-medium">Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

                <!-- Dashboard Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12 animate-slide-up" style="animation-delay: 0.2s">
                    <div class="group bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl border border-gray-100 dark:border-gray-700 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                        <div class="p-8">
                            <div class="flex items-center justify-between">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Students</dt>
                                    <dd class="text-3xl font-bold text-gray-900 dark:text-white" id="total-students">-</dd>
                                </div>
                                <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <i class="fas fa-arrow-right text-blue-500 text-xl"></i>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Active Users</span>
                            </div>
                        </div>
                    </div>

                    <div class="group bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl border border-gray-100 dark:border-gray-700 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2" style="animation-delay: 0.3s">
                        <div class="p-8">
                            <div class="flex items-center justify-between">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Lecturers</dt>
                                    <dd class="text-3xl font-bold text-gray-900 dark:text-white" id="total-lecturers">-</dd>
                                </div>
                                <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <i class="fas fa-arrow-right text-emerald-500 text-xl"></i>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Faculty Members</span>
                            </div>
                        </div>
                    </div>

                    <div class="group bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl border border-gray-100 dark:border-gray-700 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2" style="animation-delay: 0.4s">
                        <div class="p-8">
                            <div class="flex items-center justify-between">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Courses</dt>
                                    <dd class="text-3xl font-bold text-gray-900 dark:text-white" id="total-courses">-</dd>
                                </div>
                                <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <i class="fas fa-arrow-right text-purple-500 text-xl"></i>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Available Courses</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Student Management Section -->
                <section id="student-management" class="mb-12 sm:mb-16 animate-slide-up" style="animation-delay: 0.5s">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8 space-y-4 sm:space-y-0">
                        <div class="flex items-center">
                            
                            <div>
                                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Student Management</h2>
                                <p class="text-gray-600 dark:text-gray-400 mt-2">Manage student accounts and registrations</p>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button onclick="openModal('addStudentModal')" class="group w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 text-sm font-semibold shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                                <span class="flex items-center justify-center">
                                    <i class="fas fa-user-plus mr-2 group-hover:scale-110 transition-transform duration-200"></i>
                                    Add Student
                                </span>
                            </button>
                            <label class="group w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-xl hover:from-emerald-700 hover:to-teal-700 cursor-pointer transition-all duration-200 text-sm font-semibold shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                                <span class="flex items-center justify-center">
                                    <i class="fas fa-file-import mr-2 group-hover:scale-110 transition-transform duration-200"></i>
                                    Import Excel
                                </span>
                                <input type="file" id="studentsImportInput" accept=".xlsx,.xls,.csv" class="hidden" onchange="importStudents(this)">
                            </label>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden mb-6">
                        <div class="p-3 sm:p-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between">
                                <div class="relative w-full sm:w-80">
                                    <input id="studentsSearchInput" type="text" placeholder="Search students by name or IC..." class="w-full pl-12 pr-4 py-3 text-sm border-2 border-gray-200 dark:border-gray-600 rounded-xl dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                    <i class="fas fa-search absolute left-4 top-4 text-gray-400"></i>
                                </div>
                                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                                    <select id="studentsSemesterFilter" class="w-full sm:w-auto px-4 py-3 text-sm border-2 border-gray-200 dark:border-gray-600 rounded-xl dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                        <option value="all">All Semesters</option>
                                        <option value="1">Semester 1</option>
                                        <option value="2">Semester 2</option>
                                        <option value="3">Semester 3</option>
                                        <option value="4">Semester 4</option>
                                        <option value="5">Semester 5</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Mobile Card View -->
                        <div class="block sm:hidden" id="studentsCardView">
                            <!-- Mobile cards will be populated here -->
                        </div>
                        
                        <!-- Desktop Table View -->
                        <div class="hidden sm:block overflow-x-auto">
                            <table id="studentsTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/3">Name</th>
                                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/4">IC Number</th>
                                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/4">Semester</th>
                                        <th class="px-4 lg:px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/6">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                    <!-- JavaScript will populate this -->
                                </tbody>
                            </table>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-600">
                            <div class="flex-1 flex justify-between sm:hidden">
                                <button onclick="studentsGoPrev()" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" id="studentsPrevBtn"> Previous </button>
                                <button onclick="studentsGoNext()" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" id="studentsNextBtn"> Next </button>
                            </div>
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p id="studentsCountText" class="text-sm text-gray-700 dark:text-gray-300"></p>
                                </div>
                                <div>
                                    <nav id="studentsPagination" class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination"></nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Enhanced Lecturer Management Section -->
                <section id="lecturer-management" class="mb-12 sm:mb-16 animate-slide-up" style="animation-delay: 0.8s">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8 space-y-4 sm:space-y-0">
                        <div class="flex items-center">
                            
                            <div>
                                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Lecturer Management</h2>
                                <p class="text-gray-600 dark:text-gray-400 mt-2">Manage lecturer accounts and assignments</p>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                        <button onclick="openModal('addLecturerModal')" class="group w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 text-sm font-semibold shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                                <span class="flex items-center justify-center">
                                    <i class="fas fa-user-plus mr-2 group-hover:scale-110 transition-transform duration-200"></i>
                                    Add Lecturer
                                </span>
                            </button>
                            <label class="group w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-xl hover:from-emerald-700 hover:to-teal-700 cursor-pointer transition-all duration-200 text-sm font-semibold shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                                <span class="flex items-center justify-center">
                                    <i class="fas fa-file-import mr-2 group-hover:scale-110 transition-transform duration-200"></i>
                                    Import Excel
                                </span>
                                <input type="file" id="lecturersImportInput" accept=".xlsx,.xls,.csv" class="hidden" onchange="importLecturers(this)">
                            </label>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden mb-6">
                        <div class="p-3 sm:p-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between">
                                <div class="relative w-full sm:w-80">
                                    <input id="lecturersSearchInput" type="text" placeholder="Search lecturers by name or IC..." class="w-full pl-12 pr-4 py-3 text-sm border-2 border-gray-200 dark:border-gray-600 rounded-xl dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200">
                                    <i class="fas fa-search absolute left-4 top-4 text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Mobile Card View -->
                        <div class="block sm:hidden" id="lecturersCardView">
                            <!-- Mobile cards will be populated here -->
                        </div>
                        
                        <!-- Desktop Table View -->
                        <div class="hidden sm:block overflow-x-auto">
                            <table id="lecturersTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/3">Name</th>
                                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/4">IC Number</th>
                                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/4">Salary Number</th>
                                        <th class="px-4 lg:px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/6">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                    <!-- Rows will be injected via JavaScript -->
                                </tbody>
                            </table>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-600">
                            <div class="flex-1 flex justify-between sm:hidden">
                                <button onclick="lecturersGoPrev()" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" id="lecturersPrevBtn"> Previous </button>
                                <button onclick="lecturersGoNext()" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" id="lecturersNextBtn"> Next </button>
                            </div>
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p id="lecturersCountText" class="text-sm text-gray-700 dark:text-gray-300"></p>
                                </div>
                                <div>
                                    <nav id="lecturersPagination" class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination"></nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Enhanced Assign Courses Section -->
                <section id="assign-courses" class="mb-12 sm:mb-16 animate-slide-up" style="animation-delay: 0.9s">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8 space-y-4 sm:space-y-0">
                        <div>
                            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Assign Courses to Lecturers</h2>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">Select a lecturer and assign multiple courses to them</p>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <!-- Enhanced Header -->
                        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 px-6 py-6 border-b border-gray-200 dark:border-gray-600">
                            <div class="flex items-center">
                                
                                <div class="ml-4">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Course Assignment</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Manage lecturer-course relationships</p>
                                </div>
                            </div>
                        </div>

                        <form id="assignCourseForm" class="p-6">
                            @csrf
                            <!-- Step 1: Select Lecturer -->
                            <div class="mb-8">
                                <div class="flex items-center mb-4">
                                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-semibold text-blue-600 dark:text-blue-300">1</span>
                                    </div>
                                    <h4 class="ml-3 text-lg font-medium text-gray-900 dark:text-white">Select Lecturer</h4>
                                </div>
                                <div class="ml-11">
                                    <label for="assign_lecturer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Choose the lecturer to assign courses to</label>
                                    <div class="relative">
                                        <select id="assign_lecturer_id" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors appearance-none">
                                            <option value="">Choose a lecturer</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Select Courses -->
                            <div class="mb-8">
                                <div class="flex items-center mb-4">
                                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-semibold text-blue-600 dark:text-blue-300">2</span>
                                    </div>
                                    <h4 class="ml-3 text-lg font-medium text-gray-900 dark:text-white">Select Courses</h4>
                                </div>
                                <div class="ml-11">
                                    <label for="assign_course_ids" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Choose courses to assign</label>
                                    
                                    <!-- Mobile-friendly course selection -->
                                    <div class="block sm:hidden">
                                        <div id="mobile_course_selection" class="space-y-2">
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Tap courses to select/deselect them:</p>
                                            <div id="mobile_course_list" class="space-y-2">
                                                <!-- Mobile course checkboxes will be populated here -->
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Desktop course selection -->
                                    <div class="hidden sm:block">
                                        <select id="assign_course_ids" multiple required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors min-h-[160px]">
                                            <!-- Courses will be loaded here -->
                                        </select>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Hold Ctrl (Windows) or Cmd (Mac) to select multiple courses
                                        </p>
                                    </div>

                                    <!-- Selected courses preview -->
                                    <div id="selected_courses_preview" class="mt-4 hidden">
                                        <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Selected Courses:</h5>
                                        <div id="selected_courses_list" class="flex flex-wrap gap-2">
                                            <!-- Selected course tags will appear here -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action buttons -->
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center pt-6 border-t border-gray-200 dark:border-gray-600 space-y-3 sm:space-y-0">
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    <span id="assignment_status">Select a lecturer and courses to proceed</span>
                                </div>
                                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                                    <button type="button" onclick="clearAssignmentForm()" class="w-full sm:w-auto px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500">
                                        Clear Selection
                                    </button>
                                    <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span class="flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Assign Courses
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>

                <!-- Enhanced Course Management Section -->
                <section id="course-management" class="mb-12 sm:mb-16 animate-slide-up" style="animation-delay: 1.0s">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8 space-y-4 sm:space-y-0">
                        <div class="flex items-center">
                            
                            <div>
                                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Course Management</h2>
                                <p class="text-gray-600 dark:text-gray-400 mt-2">Manage course catalog and curriculum</p>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button onclick="openModal('addCourseModal')" class="group w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 text-sm font-semibold shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                                <span class="flex items-center justify-center">
                                    <i class="fas fa-plus mr-2 group-hover:scale-110 transition-transform duration-200"></i>
                                    Add Course
                                </span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden mb-6">
                        <div class="p-3 sm:p-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between">
                                <div class="relative w-full sm:w-80">
                                    <input id="coursesSearchInput" type="text" placeholder="Search courses by code or name..." class="w-full pl-12 pr-4 py-3 text-sm border-2 border-gray-200 dark:border-gray-600 rounded-xl dark:bg-gray-800 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                                    <i class="fas fa-search absolute left-4 top-4 text-gray-400"></i>
                                </div>
                                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                                    <select id="coursesSemesterFilter" class="w-full sm:w-auto px-4 py-3 text-sm border-2 border-gray-200 dark:border-gray-600 rounded-xl dark:bg-gray-800 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                                        <option value="all">All Semesters</option>
                                        <option value="1">Semester 1</option>
                                        <option value="2">Semester 2</option>
                                        <option value="3">Semester 3</option>
                                        <option value="4">Semester 4</option>
                                        <option value="5">Semester 5</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Mobile Card View -->
                        <div class="block sm:hidden" id="coursesCardView">
                            <!-- Mobile cards will be populated here -->
                        </div>
                        
                        <!-- Desktop Table View -->
                        <div class="hidden sm:block overflow-x-auto">
                            <table id="coursesTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Course Code</th>
                                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Course Name</th>
                                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Semester</th>
                                        <th class="px-4 lg:px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                    <!-- Course data will be populated here -->
                                </tbody>
                            </table>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-600">
                            <div class="flex-1 flex justify-between sm:hidden">
                                <button onclick="coursesGoPrev()" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" id="coursesPrevBtn"> Previous </button>
                                <button onclick="coursesGoNext()" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" id="coursesNextBtn"> Next </button>
                            </div>
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p id="coursesCountText" class="text-sm text-gray-700 dark:text-gray-300"></p>
                                </div>
                                <div>
                                    <nav id="coursesPagination" class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination"></nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                

    <!-- Add Student Modal -->
    <div id="addStudentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Add New Student</h3>
                    <button onclick="closeModal('addStudentModal')" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form onsubmit="addStudent(event)" id="addStudentForm" class="space-y-4">
                    @csrf
                    <div>
                        <label for="student_name" class="block text-sm font-medium mb-1">Full Name</label>
                        <input type="text" id="student_name" required class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div>
                        <label for="ic_number" class="block text-sm font-medium mb-1">IC Number</label>
                        <input type="text" id="ic_number" required placeholder="e.g. 050221030621" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div>
                        <label for="semester" class="block text-sm font-medium mb-1">Semester</label>
                        <select id="semester" required class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600">
                            <option value="1">Semester 1</option>
                            <option value="2">Semester 2</option>
                            <option value="3">Semester 3</option>
                            <option value="4">Semester 4</option>
                            <option value="5">Semester 5</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-2 mt-6">
                        <button type="button" onclick="closeModal('addStudentModal')" class="px-4 py-2 border rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Add Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div> 

    <!-- Add Lecturer Modal -->
    <div id="addLecturerModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Add New Lecturer</h3>
                    <button onclick="closeModal('addLecturerModal')" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form id="addLecturerForm" onsubmit="addLecturer(event)" class="space-y-4">
                    @csrf
                    <div>
                        <label for="lecturer_name" class="block text-sm font-medium mb-1">Full Name</label>
                        <input type="text" id="lecturer_name" required class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div>
                        <label for="lecturer_ic" class="block text-sm font-medium mb-1">IC Number</label>
                        <input type="text" id="lecturer_ic" required class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div>
                        <label for="lecturer_salary" class="block text-sm font-medium mb-1">Salary Number</label>
                        <input type="text" id="lecturer_salary" required class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div class="flex justify-end space-x-2 mt-6">
                        <button type="button" onclick="closeModal('addLecturerModal')" class="px-4 py-2 border rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Add Lecturer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Student Modal -->
    <div id="editStudentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Edit Student</h3>
                    <button onclick="closeModal('editStudentModal')" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form onsubmit="updateStudent(event)" id="editStudentForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="edit_student_id">
                    <div>
                        <label for="edit_student_name" class="block text-sm font-medium mb-1">Full Name</label>
                        <input type="text" id="edit_student_name" required class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div>
                        <label for="edit_student_ic_number" class="block text-sm font-medium mb-1">IC Number</label>
                        <input type="text" id="edit_student_ic_number" required class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Will be used as password</p>
                    </div>
                    <div>
                        <label for="edit_student_semester" class="block text-sm font-medium mb-1">Semester</label>
                        <select id="edit_student_semester" required class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600">
                            <option value="1">Semester 1</option>
                            <option value="2">Semester 2</option>
                            <option value="3">Semester 3</option>
                            <option value="4">Semester 4</option>
                            <option value="5">Semester 5</option>
                        </select>
                    </div>
                    <div class="mt-6 flex justify-end space-x-2">
                        <button type="button" onclick="closeModal('editStudentModal')" class="px-4 py-2 border rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Lecturer Modal -->
    <div id="editLecturerModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Edit Lecturer</h3>
                    <button onclick="closeModal('editLecturerModal')" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Full Name</label>
                        <input type="text" id="edit_lecturer_name" value="${lecturer.name}" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">IC Number</label>
                        <input type="text" id="edit_lecturer_ic_number" value="${lecturer.ic_number}" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Will be used as password</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Salary Number</label>
                        <input type="text" id="edit_lecturer_salary_number" value="${lecturer.salary_number}" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div class="mt-6 flex justify-end space-x-2">
                        <button onclick="closeModal('editLecturerModal')" type="button" class="px-4 py-2 border rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            Cancel
                        </button>
                        <button onclick="updateLecturer(${id})" type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Course Modal -->
    <div id="addCourseModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Add New Course</h3>
                    <button onclick="closeModal('addCourseModal')" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form onsubmit="addCourse(event)" id="addCourseForm" class="space-y-4">
                    @csrf
                    <div>
                        <label for="course_code" class="block text-sm font-medium mb-1">Course Code</label>
                        <input type="text" id="course_code" required class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div>
                        <label for="course_name" class="block text-sm font-medium mb-1">Course Name</label>
                        <input type="text" id="course_name" required class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div>
                        <label for="course_semester" class="block text-sm font-medium mb-1">Semester</label>
                        <select id="course_semester" required class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600">
                            <option value="">Select Semester</option>
                            <option value="Semester 1">Semester 1</option>
                            <option value="Semester 2">Semester 2</option>
                            <option value="Semester 3">Semester 3</option>
                            <option value="Semester 4">Semester 4</option>
                            <option value="Semester 5">Semester 5</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-2 mt-6">
                        <button type="button" onclick="closeModal('addCourseModal')" class="px-4 py-2 border rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Add Course
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Course Modal -->
    <div id="editCourseModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Edit Course</h3>
                    <button onclick="closeModal('editCourseModal')" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form onsubmit="updateCourse(event)" id="editCourseForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="edit_course_id">
                    <div>
                        <label for="edit_course_code" class="block text-sm font-medium mb-1">Course Code</label>
                        <input type="text" id="edit_course_code" required class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div>
                        <label for="edit_course_name" class="block text-sm font-medium mb-1">Course Name</label>
                        <input type="text" id="edit_course_name" required class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div>
                        <label for="edit_course_semester" class="block text-sm font-medium mb-1">Semester</label>
                        <select id="edit_course_semester" required class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600">
                            <option value="">Select Semester</option>
                            <option value="Semester 1">Semester 1</option>
                            <option value="Semester 2">Semester 2</option>
                            <option value="Semester 3">Semester 3</option>
                            <option value="Semester 4">Semester 4</option>
                            <option value="Semester 5">Semester 5</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-2 mt-6">
                        <button type="button" onclick="closeModal('editCourseModal')" class="px-4 py-2 border rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
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

        // Modal functions
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('fixed')) {
                document.querySelectorAll('.fixed').forEach(modal => {
                    modal.classList.add('hidden');
                });
            }
        }

        // Pagination settings
        const PAGE_SIZE = 5; // Changed from 10 to 5
        let studentsData = [];
        let studentsCurrentPage = 1;
        let lecturersData = [];
        let lecturersCurrentPage = 1;
        let coursesData = [];
    
        function studentsGoPrev() {
            if (studentsCurrentPage > 1) {
                studentsCurrentPage--;
                renderStudents();
            }
        }
    
        function studentsGoNext() {
            const totalPages = Math.max(1, Math.ceil(studentsData.length / PAGE_SIZE));
            if (studentsCurrentPage < totalPages) {
                studentsCurrentPage++;
                renderStudents();
            }
        }
    
        function renderStudents() {
            console.log('renderStudents called with data:', studentsData);
            const tbody = document.querySelector('#studentsTable tbody');
            const countText = document.getElementById('studentsCountText');
            const pagination = document.getElementById('studentsPagination');
            
            if (!tbody) {
                console.error('Students table tbody not found');
                return;
            }
            
            tbody.innerHTML = '';
            if (pagination) pagination.innerHTML = '';
    
            // Apply client-side filters
            const search = (document.getElementById('studentsSearchInput')?.value || '').toLowerCase();
            const semVal = document.getElementById('studentsSemesterFilter')?.value || 'all';
            const filtered = studentsData.filter(s => {
                const matchSearch = !search || (s.name?.toLowerCase().includes(search) || s.ic_number?.toLowerCase().includes(search));
                const matchSem = semVal === 'all' || String(s.semester) === semVal;
                return matchSearch && matchSem;
            });

            const total = filtered.length;
            if (total === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No students found</td>
                    </tr>
                `;
                if (countText) countText.textContent = 'Showing 0 results';
                return;
            }
    
            const totalPages = Math.max(1, Math.ceil(total / PAGE_SIZE));
            if (studentsCurrentPage > totalPages) studentsCurrentPage = totalPages;
    
            const startIndex = (studentsCurrentPage - 1) * PAGE_SIZE;
            const endIndex = Math.min(total, studentsCurrentPage * PAGE_SIZE);
            const pageItems = filtered.slice(startIndex, endIndex);
    
            // Render desktop table rows
            pageItems.forEach(student => {
                tbody.innerHTML += `
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 lg:px-6 py-4">${student.name}</td>
                        <td class="px-4 lg:px-6 py-4">${student.ic_number}</td>
                        <td class="px-4 lg:px-6 py-4">Semester ${student.semester}</td>
                        <td class="px-4 lg:px-6 py-4 text-right">
                            <button onclick="editStudent(${student.id})" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 mr-3">Edit</button>
                            <button onclick="deleteStudent(${student.id})" class="text-red-600 dark:text-red-400 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                `;
            });

            // Render mobile card view
            const cardView = document.getElementById('studentsCardView');
            if (cardView) {
                cardView.innerHTML = '';
                pageItems.forEach(student => {
                    const card = document.createElement('div');
                    card.className = 'p-4 border-b border-gray-200 dark:border-gray-700 last:border-b-0';
                    card.innerHTML = `
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white">${student.name}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">IC: ${student.ic_number}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Semester: ${student.semester}</p>
                            </div>
                            <div class="flex space-x-2 ml-4">
                                <button onclick="editStudent(${student.id})" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 text-xs px-2 py-1 border border-blue-300 rounded">Edit</button>
                                <button onclick="deleteStudent(${student.id})" class="text-red-600 dark:text-red-400 hover:text-red-900 text-xs px-2 py-1 border border-red-300 rounded">Delete</button>
                            </div>
                        </div>
                    `;
                    cardView.appendChild(card);
                });
            }
    
            if (countText) {
                countText.innerHTML = `Showing <span class="font-medium">${startIndex + 1}</span> to <span class="font-medium">${endIndex}</span> of <span class="font-medium">${total}</span> results`;
            }
    
            if (pagination) {
                const prevDisabled = studentsCurrentPage === 1 ? 'opacity-50 cursor-not-allowed' : '';
                const nextDisabled = studentsCurrentPage === totalPages ? 'opacity-50 cursor-not-allowed' : '';
                pagination.innerHTML = `
                    <a class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 ${prevDisabled}" onclick="studentsGoPrev()">
                        <span class="sr-only">Previous</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                    </a>
                    <span class="z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">${studentsCurrentPage} / ${totalPages}</span>
                    <a class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 ${nextDisabled}" onclick="studentsGoNext()">
                        <span class="sr-only">Next</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                    </a>
                `;
            }
            
            // Update mobile pagination buttons
            const prevBtn = document.getElementById('studentsPrevBtn');
            const nextBtn = document.getElementById('studentsNextBtn');
            if (prevBtn) {
                prevBtn.disabled = studentsCurrentPage === 1;
            }
            if (nextBtn) {
                nextBtn.disabled = studentsCurrentPage === totalPages;
            }
        }
    
        function lecturersGoPrev() {
            if (lecturersCurrentPage > 1) {
                lecturersCurrentPage--;
                renderLecturers();
            }
        }
    
        function lecturersGoNext() {
            const totalPages = Math.max(1, Math.ceil(lecturersData.length / PAGE_SIZE));
            if (lecturersCurrentPage < totalPages) {
                lecturersCurrentPage++;
                renderLecturers();
            }
        }
    
        function renderLecturers() {
            console.log('renderLecturers called with data:', lecturersData);
            const tbody = document.querySelector('#lecturersTable tbody');
            const countText = document.getElementById('lecturersCountText');
            const pagination = document.getElementById('lecturersPagination');
            
            if (!tbody) {
                console.error('Lecturers table tbody not found');
                return;
            }
            tbody.innerHTML = '';
            if (pagination) pagination.innerHTML = '';
    
            const search = (document.getElementById('lecturersSearchInput')?.value || '').toLowerCase();
            const filtered = lecturersData.filter(l => {
                return !search || (l.name?.toLowerCase().includes(search) || l.ic_number?.toLowerCase().includes(search) || l.salary_number?.toLowerCase().includes(search));
            });

            const total = filtered.length;
            if (total === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No lecturers found</td>
                    </tr>
                `;
                if (countText) countText.textContent = 'Showing 0 results';
                return;
            }
    
            const totalPages = Math.max(1, Math.ceil(total / PAGE_SIZE));
            if (lecturersCurrentPage > totalPages) lecturersCurrentPage = totalPages;
    
            const startIndex = (lecturersCurrentPage - 1) * PAGE_SIZE;
            const endIndex = Math.min(total, lecturersCurrentPage * PAGE_SIZE);
            const pageItems = filtered.slice(startIndex, endIndex);
    
            // Render desktop table rows
            pageItems.forEach(lecturer => {
                tbody.innerHTML += `
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 lg:px-6 py-4"><a href="#" onclick="loginAsLecturer(${lecturer.id})" class="text-blue-600 dark:text-blue-400 hover:underline">${lecturer.name}</a></td>
                        <td class="px-4 lg:px-6 py-4">${lecturer.ic_number}</td>
                        <td class="px-4 lg:px-6 py-4">${lecturer.salary_number}</td>
                        <td class="px-4 lg:px-6 py-4 text-right">
                            <button onclick="editLecturer(${lecturer.id})" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 mr-3">Edit</button>
                            <button onclick="deleteLecturer(${lecturer.id})" class="text-red-600 dark:text-red-400 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                `;
            });

            // Render mobile card view
            const cardView = document.getElementById('lecturersCardView');
            if (cardView) {
                cardView.innerHTML = '';
                pageItems.forEach(lecturer => {
                    const card = document.createElement('div');
                    card.className = 'p-4 border-b border-gray-200 dark:border-gray-700 last:border-b-0';
                    card.innerHTML = `
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                                    <a href="#" onclick="loginAsLecturer(${lecturer.id})" class="text-blue-600 dark:text-blue-400 hover:underline">${lecturer.name}</a>
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">IC: ${lecturer.ic_number}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Salary: ${lecturer.salary_number}</p>
                            </div>
                            <div class="flex space-x-2 ml-4">
                                <button onclick="editLecturer(${lecturer.id})" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 text-xs px-2 py-1 border border-blue-300 rounded">Edit</button>
                                <button onclick="deleteLecturer(${lecturer.id})" class="text-red-600 dark:text-red-400 hover:text-red-900 text-xs px-2 py-1 border border-red-300 rounded">Delete</button>
                            </div>
                        </div>
                    `;
                    cardView.appendChild(card);
                });
            }
    
            if (countText) {
                countText.innerHTML = `Showing <span class="font-medium">${startIndex + 1}</span> to <span class="font-medium">${endIndex}</span> of <span class="font-medium">${total}</span> results`;
            }
    
            if (pagination) {
                const prevDisabled = lecturersCurrentPage === 1 ? 'opacity-50 cursor-not-allowed' : '';
                const nextDisabled = lecturersCurrentPage === totalPages ? 'opacity-50 cursor-not-allowed' : '';
                pagination.innerHTML = `
                    <a class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 ${prevDisabled}" onclick="lecturersGoPrev()">
                        <span class="sr-only">Previous</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                    </a>
                    <span class="z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">${lecturersCurrentPage} / ${totalPages}</span>
                    <a class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 ${nextDisabled}" onclick="lecturersGoNext()">
                        <span class="sr-only">Next</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                    </a>
                `;
            }
            
            // Update mobile pagination buttons
            const prevBtn = document.getElementById('lecturersPrevBtn');
            const nextBtn = document.getElementById('lecturersNextBtn');
            if (prevBtn) {
                prevBtn.disabled = lecturersCurrentPage === 1;
            }
            if (nextBtn) {
                nextBtn.disabled = lecturersCurrentPage === totalPages;
            }
        }

        let coursesCurrentPage = 1;
        
        function coursesGoPrev() {
            if (coursesCurrentPage > 1) {
                coursesCurrentPage--;
                renderCourses();
            }
        }
    
        function coursesGoNext() {
            const totalPages = Math.max(1, Math.ceil(coursesData.length / PAGE_SIZE));
            if (coursesCurrentPage < totalPages) {
                coursesCurrentPage++;
                renderCourses();
            }
        }
    
        function renderCourses() {
            console.log('renderCourses called with data:', coursesData);
            console.log('coursesData type:', typeof coursesData);
            console.log('coursesData is array:', Array.isArray(coursesData));
            console.log('coursesData length:', coursesData ? coursesData.length : 'undefined');
            
            const tbody = document.querySelector('#coursesTable tbody');
            const countText = document.getElementById('coursesCountText');
            const pagination = document.getElementById('coursesPagination');
            
            if (!tbody) {
                console.error('Courses table tbody not found');
                return;
            }
            tbody.innerHTML = '';
            if (pagination) pagination.innerHTML = '';
    
            // Ensure coursesData is an array
            if (!Array.isArray(coursesData)) {
                console.error('coursesData is not an array:', coursesData);
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-red-500">Invalid data format received</td>
                    </tr>
                `;
                if (countText) countText.textContent = 'Data format error';
                return;
            }
    
            const search = (document.getElementById('coursesSearchInput')?.value || '').toLowerCase();
            const semesterFilter = document.getElementById('coursesSemesterFilter')?.value || 'all';
            
            console.log('Applying filters - Search:', search, 'Semester:', semesterFilter);
            
            const filtered = coursesData.filter(c => {
                const matchesSearch = !search || (c.code?.toLowerCase().includes(search) || c.name?.toLowerCase().includes(search));
                
                // Handle both "Semester X" and "X" formats
                let matchesSemester = semesterFilter === 'all';
                if (!matchesSemester && c.semester) {
                    const courseSemester = c.semester.toString();
                    // Check if semester matches exactly or if it contains the number
                    matchesSemester = courseSemester == semesterFilter || 
                                    courseSemester.toLowerCase().includes(`semester ${semesterFilter}`) ||
                                    courseSemester.endsWith(semesterFilter);
                }
                
                console.log(`Course ${c.code}: semester="${c.semester}", filter="${semesterFilter}", matchesSearch=${matchesSearch}, matchesSemester=${matchesSemester}`);
                return matchesSearch && matchesSemester;
            });
            
            console.log('Filtered courses:', filtered);
    
            const total = filtered.length;
            if (total === 0) {
                const message = coursesData.length === 0 
                    ? 'No courses available. Try adding some courses first.' 
                    : 'No courses match your current filters.';
                    
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            ${message}
                            ${coursesData.length > 0 ? '<br><small>Try clearing your search or changing the semester filter.</small>' : ''}
                        </td>
                    </tr>
                `;
                if (countText) countText.textContent = `Showing 0 of ${coursesData.length} courses`;
                return;
            }
    
            console.log('Filtered courses for rendering:', filtered); // Add this line
            const totalPages = Math.max(1, Math.ceil(total / PAGE_SIZE));
            if (coursesCurrentPage > totalPages) coursesCurrentPage = totalPages;
    
            const startIndex = (coursesCurrentPage - 1) * PAGE_SIZE;
            const endIndex = Math.min(total, coursesCurrentPage * PAGE_SIZE);
            const pageItems = filtered.slice(startIndex, endIndex);
    
            // Render desktop table rows
            pageItems.forEach(course => {
                tbody.innerHTML += `
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 lg:px-6 py-4">${course.code}</td>
                        <td class="px-4 lg:px-6 py-4">${course.name}</td>
                        <td class="px-4 lg:px-6 py-4 whitespace-nowrap">${course.semester}</td>
                        <td class="px-4 lg:px-6 py-4 text-right">
                            <button onclick="editCourse(${course.id})" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 mr-3">Edit</button>
                            <button onclick="deleteCourse(${course.id})" class="text-red-600 dark:text-red-400 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                `;
            });

            // Render mobile card view
            const cardView = document.getElementById('coursesCardView');
            if (cardView) {
                cardView.innerHTML = '';
                pageItems.forEach(course => {
                    const card = document.createElement('div');
                    card.className = 'p-4 border-b border-gray-200 dark:border-gray-700 last:border-b-0';
                    card.innerHTML = `
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white">${course.code}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">${course.name}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Semester: ${course.semester}</p>
                            </div>
                            <div class="flex space-x-2 ml-4">
                                <button onclick="editCourse(${course.id})" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 text-xs px-2 py-1 border border-blue-300 rounded">Edit</button>
                                <button onclick="deleteCourse(${course.id})" class="text-red-600 dark:text-red-400 hover:text-red-900 text-xs px-2 py-1 border border-red-300 rounded">Delete</button>
                            </div>
                        </div>
                    `;
                    cardView.appendChild(card);
                });
            }
    
            if (countText) {
                countText.innerHTML = `Showing <span class="font-medium">${startIndex + 1}</span> to <span class="font-medium">${endIndex}</span> of <span class="font-medium">${total}</span> results`;
            }
    
            if (pagination) {
                const prevDisabled = coursesCurrentPage === 1 ? 'opacity-50 cursor-not-allowed' : '';
                const nextDisabled = coursesCurrentPage === totalPages ? 'opacity-50 cursor-not-allowed' : '';
                pagination.innerHTML = `
                    <a class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 ${prevDisabled}" onclick="coursesGoPrev()">
                        <span class="sr-only">Previous</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                    </a>
                    <span class="z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">${coursesCurrentPage} / ${totalPages}</span>
                    <a class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 ${nextDisabled}" onclick="coursesGoNext()">
                        <span class="sr-only">Next</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                    </a>
                `;
            }
            
            // Update mobile pagination buttons
            const prevBtn = document.getElementById('coursesPrevBtn');
            const nextBtn = document.getElementById('coursesNextBtn');
            if (prevBtn) {
                prevBtn.disabled = coursesCurrentPage === 1;
            }
            if (nextBtn) {
                nextBtn.disabled = coursesCurrentPage === totalPages;
            }
        }

        function importLecturers(input) {
            const file = input.files && input.files[0];
            if (!file) return;
            const formData = new FormData();
            formData.append('file', file);

            fetch('/admin/lecturers/import', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'same-origin',
                body: formData
            })
            .then(r => r.json())
            .then(res => {
                if (!res.success) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Import Failed',
                        text: res.message || 'Import failed',
                        confirmButtonColor: '#dc2626'
                    });
                    return;
                }
                const s = res.summary || {};
                Swal.fire({
                    icon: 'success',
                    title: 'Import Completed!',
                    html: `<div class="text-left"><strong>Import Summary:</strong><br>Total: ${s.total_rows || 0}<br>Created: ${s.created || 0}<br>Skipped: ${s.skipped || 0}<br>Errors: ${(s.errors || []).length}</div>`,
                    confirmButtonColor: '#059669'
                });
                loadLecturers();
                input.value = '';
            })
            .catch(err => {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Import Failed',
                    text: 'An error occurred during import',
                    confirmButtonColor: '#dc2626'
                });
                input.value = '';
            });
        }

        // Student edit and delete functions
        function editStudent(id) {
            fetch(`/admin/students/${id}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    const student = data.data;
                    
                    document.getElementById('edit_student_id').value = student.id;
                    document.getElementById('edit_student_name').value = student.name;
                    document.getElementById('edit_student_ic_number').value = student.ic_number;
                    document.getElementById('edit_student_semester').value = student.semester;
                    
                    openModal('editStudentModal');
                }
            })
            .catch(error => {
                console.error('Error fetching student data:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error loading student data',
                    confirmButtonColor: '#dc2626'
                });
            });
        }

        function updateStudent(event) {
            event.preventDefault();
            
            const submitBtn = event.target.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Saving...';

            const studentId = document.getElementById('edit_student_id').value;
            const formData = {
                name: document.getElementById('edit_student_name').value,
                ic_number: document.getElementById('edit_student_ic_number').value,
                semester: document.getElementById('edit_student_semester').value
            };

            fetch(`/admin/students/${studentId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'same-origin',
                body: JSON.stringify(formData)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Student updated successfully!',
                    confirmButtonColor: '#059669',
                    timer: 2000,
                    showConfirmButton: false
                });
                loadStudents();
                closeModal('editStudentModal');
            })
            .catch(error => {
                console.error('Error:', error);
                if (error.errors) {
                    let errorList = Object.keys(error.errors).map(key => 
                        `<strong>${key}:</strong> ${error.errors[key].join(', ')}`
                    ).join('<br>');
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Errors',
                        html: errorList,
                        confirmButtonColor: '#dc2626'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: error.message || 'Unknown error occurred',
                        confirmButtonColor: '#dc2626'
                    });
                }
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Save Changes';
            });
        }

        function deleteStudent(id) {
            Swal.fire({
                title: 'Delete Student?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/students/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Student deleted successfully!',
                            confirmButtonColor: '#059669',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        loadStudents();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error deleting student',
                            confirmButtonColor: '#dc2626'
                        });
                    });
                }
            });
        }

function addStudent(event) {
    event.preventDefault();
    
    const submitBtn = event.target.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Adding...';
    
    const formData = {
        name: document.getElementById('student_name').value,
        ic_number: document.getElementById('ic_number').value,
        semester: document.getElementById('semester').value,
    };

    fetch('/admin/students', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
                credentials: 'same-origin',
        body: JSON.stringify(formData)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        alert('Student added successfully!');
        loadStudents();
        document.getElementById('addStudentForm').reset();
        closeModal('addStudentModal');
    })
    .catch(error => {
        console.error('Error:', error);
        if (error.errors) {
            let errorMessage = 'Validation errors:\n';
            Object.keys(error.errors).forEach(key => {
                errorMessage += `${key}: ${error.errors[key].join(', ')}\n`;
            });
            alert(errorMessage);
        } else {
            alert('Error adding student: ' + (error.message || 'Unknown error'));
        }
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Add Student';
    });
}

function addLecturer(event) {
    event.preventDefault();
    const formData = {
        name: document.getElementById('lecturer_name').value,
        ic_number: document.getElementById('lecturer_ic').value,
        salary_number: document.getElementById('lecturer_salary').value,
        role: 'lecturer',
        password: document.getElementById('lecturer_salary').value // Password is salary number
    };

    fetch('/admin/lecturers', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(formData)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: data.message || 'Lecturer added successfully!',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end'
        });
        document.getElementById('addLecturerForm').reset();
        closeModal('addLecturerModal');
        loadLecturers(); // Call this to refresh the list
        loadLecturersAndCoursesForAssignment(); // Refresh assignment dropdowns
    })
    .catch(error => {
        console.error('Error:', error);
        if (error.errors) {
            let errorMessages = [];
            Object.keys(error.errors).forEach(key => {
                errorMessages.push(`${key}: ${error.errors[key].join(', ')}`);
            });
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: errorMessages.join('<br>'),
                confirmButtonText: 'OK',
                confirmButtonColor: '#ef4444'
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Error adding lecturer',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ef4444'
            });
        }
    });
}

function editLecturer(id) {
            fetch(`/admin/lecturers/${id}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                const lecturer = data.data;
                
                const modal = document.getElementById('editLecturerModal');
                const form = modal.querySelector('.space-y-4');
                
                form.innerHTML = `
                    <div>
                        <label class="block text-sm font-medium mb-1">Full Name</label>
                        <input type="text" id="edit_lecturer_name" value="${lecturer.name}" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">IC Number</label>
                        <input type="text" id="edit_lecturer_ic_number" value="${lecturer.ic_number}" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Will be used as password</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Salary Number</label>
                        <input type="text" id="edit_lecturer_salary_number" value="${lecturer.salary_number}" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div class="mt-6 flex justify-end space-x-2">
                        <button onclick="closeModal('editLecturerModal')" type="button" class="px-4 py-2 border rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            Cancel
                        </button>
                        <button onclick="updateLecturer(${id})" type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            Save Changes
                        </button>
                    </div>
                `;
                
                openModal('editLecturerModal');
            }
        })
        .catch(error => {
            console.error('Error fetching lecturer data:', error);
            alert('Error loading lecturer data');
        });
}

function updateLecturer(id) {
    const formData = {
        name: document.getElementById('edit_lecturer_name').value,
        ic_number: document.getElementById('edit_lecturer_ic_number').value,
        salary_number: document.getElementById('edit_lecturer_salary_number').value
    };

    fetch(`/admin/lecturers/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
                credentials: 'same-origin',
        body: JSON.stringify(formData)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        Swal.fire({
            icon: 'success',
            title: 'Updated!',
            text: 'Lecturer updated successfully!',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end'
        });
        loadLecturers();
        loadLecturersAndCoursesForAssignment(); // Refresh assignment dropdowns
        closeModal('editLecturerModal');
    })
    .catch(error => {
        console.error('Error:', error);
        if (error.errors) {
            let errorMessages = [];
            Object.keys(error.errors).forEach(key => {
                errorMessages.push(`${key}: ${error.errors[key].join(', ')}`);
            });
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: errorMessages.join('<br>'),
                confirmButtonText: 'OK',
                confirmButtonColor: '#ef4444'
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Error updating lecturer',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ef4444'
            });
        }
    });
}

function deleteLecturer(id) {
    Swal.fire({
        title: 'Delete Lecturer?',
        text: 'This action cannot be undone!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/lecturers/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: 'Lecturer deleted successfully!',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end'
                });
                loadLecturers();
                loadLecturersAndCoursesForAssignment(); // Refresh assignment dropdowns
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error deleting lecturer',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#ef4444'
                });
            });
        }
    });
}

function loadLecturers() {
            fetch('/admin/lecturers', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'same-origin'
            })
        .then(response => {
            console.log('Lecturers response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(lecturers => {
                    lecturersData = lecturers; // Store the data for pagination
                    console.log('Fetched lecturers:', lecturersData); // Add this line
                    renderLecturers();
        })
        .catch(error => {
            console.error('Error loading lecturers:', error);
            const tbody = document.querySelector('#lecturersTable tbody');
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-red-500">Error loading lecturers</td>
                </tr>
            `;
                    const countText = document.getElementById('lecturersCountText');
                    if (countText) {
                        countText.textContent = 'Error loading results';
                    }
        });
}

function loadStudents() { // Add this function
    fetch('/admin/students', {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        credentials: 'same-origin'
    })
    .then(response => {
        console.log('Students response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(students => {
        studentsData = students; // Store the data for pagination
        console.log('Fetched students:', studentsData); // Add this line
        renderStudents();
    })
    .catch(error => {
        console.error('Error loading students:', error);
        const tbody = document.querySelector('#studentsTable tbody');
        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="px-6 py-4 text-center text-red-500">Error loading students</td>
            </tr>
        `;
        const countText = document.getElementById('studentsCountText');
        if (countText) {
            countText.textContent = 'Error loading results';
        }
    });
}

        // Load data when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardCounts();
            loadStudents();
            console.log('Fetching students...'); // Add this line
            loadLecturers();
            console.log('Fetching lecturers...'); // Add this line
            loadLecturersAndCoursesForAssignment();
            loadCourses(); // Add this line
            loadAnalytics(); // Initial load of analytics data

            // Wire up search and filter events (with simple debounce)
            let t1, t2, t3;
            const sSearch = document.getElementById('studentsSearchInput');
            const sSem = document.getElementById('studentsSemesterFilter');
            if (sSearch) sSearch.addEventListener('input', () => { clearTimeout(t1); t1 = setTimeout(() => { studentsCurrentPage = 1; renderStudents(); }, 200); });
            if (sSem) sSem.addEventListener('change', () => { studentsCurrentPage = 1; renderStudents(); });

            const lSearch = document.getElementById('lecturersSearchInput');
            if (lSearch) lSearch.addEventListener('input', () => { clearTimeout(t2); t2 = setTimeout(() => { lecturersCurrentPage = 1; renderLecturers(); }, 200); });

            // Handle course assignment form submission
            document.getElementById('assignCourseForm').addEventListener('submit', assignCoursesToLecturer);

            // Wire up course search (with simple debounce)
            const cSearch = document.getElementById('coursesSearchInput');
            if (cSearch) cSearch.addEventListener('input', () => { clearTimeout(t3); t3 = setTimeout(() => { coursesCurrentPage = 1; renderCourses(); }, 200); });
            
            // Wire up course semester filter
            const cSemesterFilter = document.getElementById('coursesSemesterFilter');
            if (cSemesterFilter) cSemesterFilter.addEventListener('change', () => { coursesCurrentPage = 1; renderCourses(); });
            
            // Wire up course assignment functionality
            const assignLecturerSelect = document.getElementById('assign_lecturer_id');
            if (assignLecturerSelect) {
                assignLecturerSelect.addEventListener('change', updateAssignmentStatus);
            }
            
            const assignCourseSelect = document.getElementById('assign_course_ids');
            if (assignCourseSelect) {
                assignCourseSelect.addEventListener('change', function() {
                    updateSelectedCoursesPreview();
                    updateAssignmentStatus();
                });
            }
        });

        let availableCourses = [];
        let selectedCourseIds = [];

        async function loadLecturersAndCoursesForAssignment() {
            try {
                // Fetch lecturers
                const lecturersResponse = await fetch('/admin/lecturers', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'same-origin'
                });
                const lecturers = await lecturersResponse.json();
                const lecturerSelect = document.getElementById('assign_lecturer_id');
                lecturerSelect.innerHTML = '<option value="">Choose a lecturer</option>';
                lecturers.forEach(lecturer => {
                    const option = document.createElement('option');
                    option.value = lecturer.id;
                    option.textContent = lecturer.name;
                    lecturerSelect.appendChild(option);
                });

                // Fetch courses
                const coursesResponse = await fetch('/admin/courses', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'same-origin'
                });
                const courses = await coursesResponse.json();
                availableCourses = courses;
                
                // Populate desktop select
                const courseSelect = document.getElementById('assign_course_ids');
                courseSelect.innerHTML = '';
                courses.forEach(course => {
                    const option = document.createElement('option');
                    option.value = course.id;
                    option.textContent = `${course.code} - ${course.name}`;
                    courseSelect.appendChild(option);
                });

                // Populate mobile checkboxes
                populateMobileCourseList(courses);

                // Add event listeners
                lecturerSelect.addEventListener('change', updateAssignmentStatus);
                courseSelect.addEventListener('change', updateSelectedCoursesPreview);

            } catch (error) {
                console.error('Error loading lecturers or courses for assignment:', error);
                alert('Failed to load lecturers or courses for assignment.');
            }
        }

        function populateMobileCourseList(courses) {
            const mobileList = document.getElementById('mobile_course_list');
            mobileList.innerHTML = '';
            
            courses.forEach(course => {
                const checkboxContainer = document.createElement('div');
                checkboxContainer.className = 'flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors';
                
                checkboxContainer.innerHTML = `
                    <input type="checkbox" id="mobile_course_${course.id}" value="${course.id}" 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                           onchange="handleMobileCourseSelection(${course.id})">
                    <label for="mobile_course_${course.id}" class="ml-3 flex-1 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                        <div class="font-semibold">${course.code}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">${course.name}</div>
                    </label>
                `;
                
                mobileList.appendChild(checkboxContainer);
            });
        }

        function handleMobileCourseSelection(courseId) {
            const checkbox = document.getElementById(`mobile_course_${courseId}`);
            if (checkbox.checked) {
                if (!selectedCourseIds.includes(courseId)) {
                    selectedCourseIds.push(courseId);
                }
            } else {
                selectedCourseIds = selectedCourseIds.filter(id => id !== courseId);
            }
            updateSelectedCoursesPreview();
            updateAssignmentStatus();
        }

        function updateSelectedCoursesPreview() {
            const preview = document.getElementById('selected_courses_preview');
            const list = document.getElementById('selected_courses_list');
            
            // Get selected courses from desktop select if on desktop
            if (window.innerWidth >= 640) {
                const courseSelect = document.getElementById('assign_course_ids');
                selectedCourseIds = Array.from(courseSelect.options)
                    .filter(option => option.selected)
                    .map(option => parseInt(option.value));
            }

            if (selectedCourseIds.length > 0) {
                preview.classList.remove('hidden');
                list.innerHTML = '';
                
                selectedCourseIds.forEach(courseId => {
                    const course = availableCourses.find(c => c.id === courseId);
                    if (course) {
                        const tag = document.createElement('span');
                        tag.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
                        tag.innerHTML = `
                            ${course.code}
                            <button type="button" onclick="removeCourseSelection(${courseId})" class="ml-2 inline-flex items-center justify-center w-4 h-4 text-blue-400 hover:text-blue-600">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        `;
                        list.appendChild(tag);
                    }
                });
            } else {
                preview.classList.add('hidden');
            }
        }

        function removeCourseSelection(courseId) {
            selectedCourseIds = selectedCourseIds.filter(id => id !== courseId);
            
            // Update mobile checkbox
            const mobileCheckbox = document.getElementById(`mobile_course_${courseId}`);
            if (mobileCheckbox) mobileCheckbox.checked = false;
            
            // Update desktop select
            const courseSelect = document.getElementById('assign_course_ids');
            Array.from(courseSelect.options).forEach(option => {
                if (parseInt(option.value) === courseId) {
                    option.selected = false;
                }
            });
            
            updateSelectedCoursesPreview();
            updateAssignmentStatus();
        }

        function updateAssignmentStatus() {
            const lecturerId = document.getElementById('assign_lecturer_id').value;
            const statusElement = document.getElementById('assignment_status');
            const submitButton = document.querySelector('#assignCourseForm button[type="submit"]');
            
            if (!lecturerId) {
                statusElement.textContent = 'Select a lecturer to continue';
                submitButton.disabled = true;
            } else if (selectedCourseIds.length === 0) {
                statusElement.textContent = 'Select courses to assign';
                submitButton.disabled = true;
            } else {
                const lecturerName = document.getElementById('assign_lecturer_id').selectedOptions[0]?.textContent;
                statusElement.textContent = `Ready to assign ${selectedCourseIds.length} course(s) to ${lecturerName}`;
                submitButton.disabled = false;
            }
        }

        function clearAssignmentForm() {
            document.getElementById('assign_lecturer_id').value = '';
            selectedCourseIds = [];
            
            // Clear desktop select
            const courseSelect = document.getElementById('assign_course_ids');
            Array.from(courseSelect.options).forEach(option => option.selected = false);
            
            // Clear mobile checkboxes
            document.querySelectorAll('#mobile_course_list input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = false;
            });
            
            updateSelectedCoursesPreview();
            updateAssignmentStatus();
        }

        async function assignCoursesToLecturer(event) {
            event.preventDefault();
            const lecturerId = document.getElementById('assign_lecturer_id').value;
            
            // Use the global selectedCourseIds array for both mobile and desktop
            if (!lecturerId || selectedCourseIds.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Selection',
                    text: 'Please select a lecturer and at least one course.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#f59e0b'
                });
                return;
            }

            const submitButton = event.target.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Assigning...
            `;

            try {
                const response = await fetch(`/admin/lecturers/${lecturerId}/assign-courses`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ course_ids: selectedCourseIds })
                });

                const data = await response.json();
                if (response.ok) {
                    // Show success message with SweetAlert
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message || 'Courses assigned successfully!',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        toast: true,
                        position: 'top-end'
                    });
                    
                    // Update status element
                    const statusElement = document.getElementById('assignment_status');
                    statusElement.innerHTML = `<span class="text-green-600 dark:text-green-400">✓ ${data.message}</span>`;
                    
                    // Clear form after successful assignment
                    setTimeout(() => {
                        clearAssignmentForm();
                    }, 2000);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Assignment Failed',
                        text: data.message || 'Failed to assign courses.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                console.error('Error assigning courses:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Network Error',
                    text: 'An error occurred while assigning courses.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#ef4444'
                });
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        }

        function addCourse(event) {
            event.preventDefault();
            
            const submitBtn = event.target.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Adding...';

            const formData = {
                code: document.getElementById('course_code').value,
                name: document.getElementById('course_name').value,
                semester: document.getElementById('course_semester').value,
            };

            fetch('/admin/courses', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'same-origin',
                body: JSON.stringify(formData)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Course added successfully!',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end'
                });
                loadCourses();
                document.getElementById('addCourseForm').reset();
                closeModal('addCourseModal');
                loadLecturersAndCoursesForAssignment(); // Refresh assignment dropdowns
            })
            .catch(error => {
                console.error('Error:', error);
                if (error.errors) {
                    let errorMessages = [];
                    Object.keys(error.errors).forEach(key => {
                        errorMessages.push(`${key}: ${error.errors[key].join(', ')}`);
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        html: errorMessages.join('<br>'),
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#ef4444'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error adding course: ' + (error.message || 'Unknown error'),
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#ef4444'
                    });
                }
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Add Course';
            });
        }

        function editCourse(id) {
            fetch(`/admin/courses/${id}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data) {
                    document.getElementById('edit_course_id').value = data.id;
                    document.getElementById('edit_course_code').value = data.code;
                    document.getElementById('edit_course_name').value = data.name;
                    document.getElementById('edit_course_semester').value = data.semester;
                    openModal('editCourseModal');
                }
            })
            .catch(error => {
                console.error('Error fetching course data:', error);
                alert('Error loading course data');
            });
        }

        function updateCourse(event) {
            event.preventDefault();
            
            const submitBtn = event.target.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Saving...';

            const courseId = document.getElementById('edit_course_id').value;
            const formData = {
                code: document.getElementById('edit_course_code').value,
                name: document.getElementById('edit_course_name').value,
                semester: document.getElementById('edit_course_semester').value,
            };

            fetch(`/admin/courses/${courseId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'same-origin',
                body: JSON.stringify(formData)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: 'Course updated successfully!',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end'
                });
                loadCourses();
                closeModal('editCourseModal');
            })
            .catch(error => {
                console.error('Error:', error);
                if (error.errors) {
                    let errorMessages = [];
                    Object.keys(error.errors).forEach(key => {
                        errorMessages.push(`${key}: ${error.errors[key].join(', ')}`);
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        html: errorMessages.join('<br>'),
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#ef4444'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error updating course: ' + (error.message || 'Unknown error'),
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#ef4444'
                    });
                }
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Save Changes';
            });
        }

        function deleteCourse(id) {
            Swal.fire({
                title: 'Delete Course?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/courses/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Course deleted successfully!',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            toast: true,
                            position: 'top-end'
                        });
                        loadCourses();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error deleting course',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#ef4444'
                        });
                    });
                }
            });
        }

        function loadCourses() {
            console.log('Loading courses...');
            fetch('/admin/courses', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'same-origin'
            })
            .then(response => {
                console.log('Courses response status:', response.status);
                console.log('Courses response headers:', response.headers);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(courses => {
                console.log('Raw courses response:', courses);
                console.log('Courses data type:', typeof courses);
                console.log('Is courses an array?', Array.isArray(courses));
                console.log('Courses length:', courses ? courses.length : 'undefined');
                
                coursesData = courses || []; // Store the data for pagination, ensure it's an array
                console.log('Stored coursesData:', coursesData);
                renderCourses();
            })
            .catch(error => {
                console.error('Error loading courses:', error);
                console.error('Error details:', error.message);
                
                // Show user-friendly error message
                Swal.fire({
                    icon: 'error',
                    title: 'Loading Error',
                    text: 'Failed to load courses. Please check the console for details.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#ef4444'
                });
                
                const tbody = document.querySelector('#coursesTable tbody');
                if (tbody) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-red-500">
                                Error loading courses: ${error.message}
                                <br><small>Check browser console for details</small>
                            </td>
                        </tr>
                    `;
                }
                const countText = document.getElementById('coursesCountText');
                if (countText) {
                    countText.textContent = 'Error loading results';
                }
            });
        }

        let analyticsData = [];
        // Chart instances to allow updates without re-creating canvases
        let chartEvalByCourse = null;
        let chartResponsesByCourse = null;

        function loadAnalytics(applyFilters = false) {
            const lectEl = document.getElementById('analyticsLecturerFilter');
            const courseEl = document.getElementById('analyticsCourseFilter');
            const semEl = document.getElementById('analyticsSemesterFilter');
            if (!lectEl) {
                console.warn('Analytics section not present; skipping loadAnalytics');
                return;
            }
            const lecturerFilter = lectEl.value;
            const courseFilter = courseEl ? courseEl.value : ''; // Keep for reference, but won't be sent to backend
            const semesterFilter = semEl ? semEl.value : ''; // Keep for reference, but won't be sent to backend

            let queryParams = '';
            const params = [];

            if (lecturerFilter !== 'all') {
                params.push(`lecturer_name=${lecturerFilter}`);
            }
            // Course and Semester filters are explicitly NOT sent to the backend

            if (params.length > 0) {
                queryParams = `?${params.join('&')}`;
            }

            // Always fetch from backend as the filters won't be applied by default except for lecturer
            fetch(`/admin/analytics${queryParams}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                analyticsData = data;
                console.log('Fetched analytics data:', analyticsData);
                renderAnalytics();
                populateAnalyticsFilters(); // Populate filters after data is loaded (only lecturer will be dynamic)
            })
            .catch(error => {
                console.error('Error loading analytics data:', error);
                // Reset KPIs
                ['kpi_eval_avg','kpi_survey_avg','kpi_total_responses','kpi_active_courses'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.textContent = '-';
                });
                // Clear tables
                const topT = document.getElementById('table_top_courses');
                const botT = document.getElementById('table_bottom_courses');
                if (topT) topT.innerHTML = '<tr><td colspan="4" class="px-4 py-3 text-center text-red-500">Failed to load</td></tr>';
                if (botT) botT.innerHTML = '<tr><td colspan="4" class="px-4 py-3 text-center text-red-500">Failed to load</td></tr>';
                // Clear charts
                if (chartEvalByCourse) { chartEvalByCourse.destroy(); chartEvalByCourse = null; }
                if (chartResponsesByCourse) { chartResponsesByCourse.destroy(); chartResponsesByCourse = null; }
            });
        }

        function renderAnalytics() {
            const lectEl = document.getElementById('analyticsLecturerFilter');
            const courseEl = document.getElementById('analyticsCourseFilter');
            const semEl = document.getElementById('analyticsSemesterFilter');
            if (!lectEl) return;
            const lecturerFilter = lectEl.value;
            const courseFilter = courseEl ? courseEl.value : '';
            const semesterFilter = semEl ? semEl.value : '';

            const filtered = analyticsData.filter(item => {
                const lecturerOk = lecturerFilter === 'all' || (item.lecturer_name && item.lecturer_name === lecturerFilter);
                const courseOk = !courseFilter || `${item.course_code} - ${item.course_name}` === courseFilter || item.course_code === courseFilter;
                const semOk = !semesterFilter || String(item.semester) === String(semesterFilter);
                return lecturerOk && courseOk && semOk;
            });

            // KPIs
            const totalResponses = filtered.reduce((s, x) => s + (Number(x.responses) || 0), 0);
            const weightedEval = filtered.reduce((s, x) => s + (Number(x.evaluation_avg) || 0) * (Number(x.responses) || 0), 0);
            const weightedSurvey = filtered.reduce((s, x) => s + (Number(x.survey_avg) || 0) * (Number(x.responses) || 0), 0);
            const evalAvg = totalResponses > 0 ? (weightedEval / totalResponses) : (filtered.length ? filtered.reduce((s,x)=>s+(Number(x.evaluation_avg)||0),0)/filtered.length : 0);
            const surveyAvg = totalResponses > 0 ? (weightedSurvey / totalResponses) : (filtered.length ? filtered.reduce((s,x)=>s+(Number(x.survey_avg)||0),0)/filtered.length : 0);
            const activeCourses = new Set(filtered.map(x => `${x.course_code}|${x.course_name}`)).size;

            const setText = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
            setText('kpi_eval_avg', evalAvg ? evalAvg.toFixed(2) : '-');
            setText('kpi_survey_avg', surveyAvg ? surveyAvg.toFixed(2) : '-');
            setText('kpi_total_responses', String(totalResponses));
            setText('kpi_active_courses', String(activeCourses));

            // Prepare per-course aggregates (use last record per course or average across duplicates)
            const perCourseMap = new Map();
            filtered.forEach(it => {
                const key = `${it.course_code}|${it.course_name}|${it.semester}|${it.lecturer_name}`;
                perCourseMap.set(key, {
                    course: `${it.course_code} - ${it.course_name}`,
                    lecturer: it.lecturer_name || '-',
                    semester: it.semester,
                    evaluation_avg: Number(it.evaluation_avg) || 0,
                    responses: Number(it.responses) || 0,
                });
            });
            const perCourse = Array.from(perCourseMap.values());

            // Charts: render only if Charts tab is visible
            const chartsSection = document.getElementById('analyticsChartsSection');
            const chartsVisible = chartsSection && !chartsSection.classList.contains('hidden');
            const labels = perCourse.map(x => x.course);
            const evalData = perCourse.map(x => x.evaluation_avg);
            const respData = perCourse.map(x => x.responses);

            if (chartsVisible) {
                // Eval chart
                const evalCtx = document.getElementById('chart_eval_by_course');
                if (evalCtx) {
                    if (chartEvalByCourse) chartEvalByCourse.destroy();
                    chartEvalByCourse = new Chart(evalCtx, {
                        type: 'bar',
                        data: {
                            labels,
                            datasets: [{
                                label: 'Evaluation Avg',
                                data: evalData,
                                backgroundColor: 'rgba(59, 130, 246, 0.6)'
                            }]
                        },
                        options: {
                            animation: false,
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: { y: { beginAtZero: true, max: 5 } },
                            plugins: { legend: { display: false } }
                        }
                    });
                }

                // Responses chart
                const respCtx = document.getElementById('chart_responses_by_course');
                if (respCtx) {
                    if (chartResponsesByCourse) chartResponsesByCourse.destroy();
                    chartResponsesByCourse = new Chart(respCtx, {
                        type: 'doughnut',
                        data: {
                            labels,
                            datasets: [{
                                label: 'Responses',
                                data: respData,
                                backgroundColor: labels.map((_, i) => `hsl(${(i*47)%360} 90% 60%)`)
                            }]
                        },
                        options: {
                            animation: false,
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { position: 'bottom' } }
                        }
                    });
                }
            }

            // Tables: top/bottom 5 by evaluation_avg (require at least 1 response)
            const ranked = perCourse
                .filter(x => x.responses >= 1)
                .sort((a, b) => b.evaluation_avg - a.evaluation_avg);
            const top5 = ranked.slice(0, 5);
            const bottom5 = ranked.slice(-5).reverse();

            const renderRows = (rows) => rows.map(r => `
                <tr>
                    <td class="px-4 py-2 whitespace-nowrap">${r.course}</td>
                    <td class="px-4 py-2 whitespace-nowrap">${r.lecturer}</td>
                    <td class="px-4 py-2 whitespace-nowrap">${r.semester}</td>
                    <td class="px-4 py-2 whitespace-nowrap">${r.evaluation_avg.toFixed(2)}</td>
                </tr>
            `).join('');

            const topT = document.getElementById('table_top_courses');
            const botT = document.getElementById('table_bottom_courses');
            if (topT) topT.innerHTML = top5.length ? renderRows(top5) : '<tr><td colspan="4" class="px-4 py-3 text-center text-gray-500">No data</td></tr>';
            if (botT) botT.innerHTML = bottom5.length ? renderRows(bottom5) : '<tr><td colspan="4" class="px-4 py-3 text-center text-gray-500">No data</td></tr>';
        }

        function populateAnalyticsFilters() {
            const lecturerFilter = document.getElementById('analyticsLecturerFilter');
            const courseFilter = document.getElementById('analyticsCourseFilter'); // Keep for UI, but no dynamic population
            const semesterFilter = document.getElementById('analyticsSemesterFilter'); // Keep for UI, but no dynamic population

            // Store current lecturer selection
            const currentLecturerSelection = lecturerFilter.value;

            // Clear previous options for lecturer, keep "All"
            lecturerFilter.innerHTML = '<option value="all">All Lecturers</option>';
           

            const uniqueLecturers = [...new Set(analyticsData.map(item => item.lecturer_name))];
            uniqueLecturers.forEach(name => {
                if (name) { // Only add if name is not null or undefined
                    const option = document.createElement('option');
                    option.value = name;
                    option.textContent = name;
                    lecturerFilter.appendChild(option);
                }
            });

            // Course and Semester dropdowns are no longer dynamically populated.
            // They will only have their default "All Courses" and "All Semesters" options.

            // Restore lecturer selection
            lecturerFilter.value = currentLecturerSelection;
        }

        // Tabs for Analytics section to reduce page length
        function setAnalyticsTab(tab) {
            const sections = {
                overview: 'analyticsOverviewSection',
                charts: 'analyticsChartsSection',
                details: 'analyticsDetailsSection'
            };
            Object.entries(sections).forEach(([key, id]) => {
                const el = document.getElementById(id);
                if (el) el.classList.toggle('hidden', key !== tab);
            });

            // Toggle active button styles
            const btnIds = ['tab_overview', 'tab_charts', 'tab_details'];
            btnIds.forEach(id => {
                const btn = document.getElementById(id);
                if (!btn) return;
                const isActive = id === `tab_${tab}`;
                btn.classList.toggle('bg-gray-100', isActive);
                btn.classList.toggle('dark:bg-gray-700', isActive);
                btn.classList.toggle('bg-white', !isActive);
                btn.classList.toggle('dark:bg-gray-800', !isActive);
            });

            // Ensure charts are sized/rendered when shown
            if (tab === 'charts') {
                renderAnalytics();
            } else {
                // Destroy charts when hiding charts tab to avoid hidden-canvas resize loops
                if (chartEvalByCourse) { chartEvalByCourse.destroy(); chartEvalByCourse = null; }
                if (chartResponsesByCourse) { chartResponsesByCourse.destroy(); chartResponsesByCourse = null; }
            }
        }

        // Function to handle admin login as lecturer with SweetAlert2 UX
        function loginAsLecturer(lecturerId) {
            Swal.fire({
                title: 'Login as this lecturer?',
                text: 'You will be redirected to the lecturer dashboard.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, continue',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#6b7280',
                background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#111827'
            }).then((result) => {
                if (!result.isConfirmed) return;

                Swal.fire({
                    title: 'Signing you in...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                fetch(`/admin/lecturers/${lecturerId}/login-as`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                })
                .then(async response => {
                    const data = await response.json().catch(() => ({ success: false, message: 'Unexpected server response' }));
                    if (response.ok && data.success) {
                        Swal.fire({
                            title: 'Success',
                            text: data.message || 'Logged in successfully.',
                            icon: 'success',
                            timer: 1200,
                            showConfirmButton: false
                        });
                        setTimeout(() => {
                            window.location.href = data.redirect_url || '/lecturer/dashboard';
                        }, 1000);
                    } else {
                        throw new Error(data.message || `Request failed (${response.status})`);
                    }
                })
                .catch(error => {
                    console.error('Login-as error:', error);
                    Swal.fire({
                        title: 'Action failed',
                        text: error.message || 'An error occurred while trying to login as lecturer.',
                        icon: 'error'
                    });
                });
            });
        }

        // Dashboard counts functionality
        async function loadDashboardCounts() {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                const headers = {
                    'Content-Type': 'application/json'
                };
                
                if (csrfToken) {
                    headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
                }
                
                const response = await fetch('/admin/dashboard/counts', {
                    method: 'GET',
                    headers: headers
                });
                
                console.log('Dashboard counts response status:', response.status);
                if (response.ok) {
                    const data = await response.json();
                    
                    // Add loading animation effect (this will set the final values)
                    animateCountUp('total-students', data.students);
                    animateCountUp('total-lecturers', data.lecturers);
                    animateCountUp('total-courses', data.courses);
                } else {
                    console.error('Failed to load dashboard counts. Status:', response.status);
                    const errorText = await response.text();
                    console.error('Error response:', errorText);
                    // Set fallback values
                    document.getElementById('total-students').textContent = '0';
                    document.getElementById('total-lecturers').textContent = '0';
                    document.getElementById('total-courses').textContent = '0';
                }
            } catch (error) {
                console.error('Error loading dashboard counts:', error);
                // Set fallback values
                document.getElementById('total-students').textContent = '0';
                document.getElementById('total-lecturers').textContent = '0';
                document.getElementById('total-courses').textContent = '0';
            }
        }

        // Animate count up effect
        function animateCountUp(elementId, targetValue) {
            const element = document.getElementById(elementId);
            const startValue = 0;
            const duration = 1500; // 1.5 seconds
            const startTime = performance.now();

            function updateCount(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                // Easing function for smooth animation
                const easeOutQuart = 1 - Math.pow(1 - progress, 4);
                const currentValue = Math.floor(startValue + (targetValue - startValue) * easeOutQuart);
                
                element.textContent = currentValue;
                
                if (progress < 1) {
                    requestAnimationFrame(updateCount);
                } else {
                    element.textContent = targetValue; // Ensure final value is exact
                }
            }
            
            requestAnimationFrame(updateCount);
        }

        // Refresh counts every 30 seconds (initial load is handled in main DOMContentLoaded)
        setInterval(loadDashboardCounts, 30000);

    </script>
</body>
</html>