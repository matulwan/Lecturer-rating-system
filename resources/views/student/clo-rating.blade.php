<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rate Course Learning Outcomes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    transitionProperty: {
                        'width': 'width',
                        'height': 'height'
                    },
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 transition-colors duration-300">
    <div class="min-h-screen p-4 md:p-6">
        <!-- Header -->
        <header class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">Rate Course Learning Outcomes</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400" id="course-info">Loading course information...</p>
            </div>
            <div class="flex items-center space-x-4">
                <button onclick="history.back()" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                    Back
                </button>
                <button id="theme-toggle" class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 dark:hidden" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden dark:block" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </header>

        <!-- Instructions -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
            <h2 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-2">Instructions</h2>
            <p class="text-blue-700 dark:text-blue-300">Please rate your performance at work by ticking the following scale for each Course Learning Outcome (CLO). Rate from 1 (Poor) to 5 (Excellent).</p>
        </div>

        <!-- CLO Rating Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <form id="clo-rating-form">
                <div id="clo-ratings-container" class="space-y-6">
                    <!-- CLO ratings will be loaded here -->
                </div>
                
                <div class="mt-8 flex justify-between items-center">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <span id="completed-count">0</span> of <span id="total-count">0</span> CLOs rated
                    </div>
                    <button type="submit" id="submit-ratings" class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>
                        Submit CLO Ratings
                    </button>
                </div>
            </form>
        </div>

        <!-- Success Message -->
        <div id="success-message" class="hidden mt-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-green-800 dark:text-green-200 font-medium">CLO ratings submitted successfully!</span>
            </div>
        </div>
    </div>

    <script>
        let courseId = null;
        let courseData = null;
        let existingRatings = {};

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

        // Get course ID from URL
        function getCourseIdFromUrl() {
            const pathParts = window.location.pathname.split('/');
            return pathParts[pathParts.indexOf('course') + 1];
        }

        // Load course data and existing ratings
        async function loadCourseData() {
            try {
                courseId = getCourseIdFromUrl();
                
                // Load course data
                const courseResponse = await fetch(`/admin/courses/${courseId}`);
                courseData = await courseResponse.json();
                
                document.getElementById('course-info').textContent = `${courseData.code} - ${courseData.name}`;
                
                // Load existing ratings
                try {
                    const ratingsResponse = await fetch(`/course/${courseId}/my-clo-ratings`);
                    if (ratingsResponse.ok) {
                        existingRatings = await ratingsResponse.json();
                    }
                } catch (error) {
                    console.log('No existing ratings found');
                }
                
                // Load CLO rating form
                loadCLORatingForm();
                
            } catch (error) {
                console.error('Error loading course data:', error);
                document.getElementById('course-info').textContent = 'Error loading course';
            }
        }

        // Load CLO rating form
        function loadCLORatingForm() {
            const container = document.getElementById('clo-ratings-container');
            const outcomes = courseData.course_outcomes || [];
            
            container.innerHTML = '';
            
            if (outcomes.length === 0) {
                container.innerHTML = '<p class="text-center text-gray-500">No course learning outcomes available for rating</p>';
                return;
            }
            
            document.getElementById('total-count').textContent = outcomes.length;
            
            outcomes.forEach((outcome, index) => {
                const cloNumber = index + 1;
                const existingRating = existingRatings[cloNumber]?.rating || null;
                
                const ratingDiv = document.createElement('div');
                ratingDiv.className = 'border border-gray-200 dark:border-gray-600 rounded-lg p-4';
                ratingDiv.innerHTML = `
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">CLO ${cloNumber}</h3>
                        <p class="text-gray-700 dark:text-gray-300 mt-1">${outcome}</p>
                    </div>
                    
                    <div class="mb-3">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rate your performance:</p>
                        <div class="flex space-x-6">
                            ${[1, 2, 3, 4, 5].map(rating => `
                                <label class="flex flex-col items-center cursor-pointer group">
                                    <input type="radio" name="clo_${cloNumber}" value="${rating}" class="sr-only clo-rating" data-clo="${cloNumber}" ${existingRating === rating ? 'checked' : ''}>
                                    <div class="w-12 h-12 rounded-full border-2 border-gray-300 dark:border-gray-600 flex items-center justify-center text-lg font-semibold transition-all group-hover:border-blue-400 ${existingRating === rating ? 'bg-blue-600 text-white border-blue-600' : 'text-gray-600 dark:text-gray-400'}">
                                        ${rating}
                                    </div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">${getRatingLabel(rating)}</span>
                                </label>
                            `).join('')}
                        </div>
                    </div>
                `;
                container.appendChild(ratingDiv);
            });
            
            // Add event listeners to radio buttons
            document.querySelectorAll('.clo-rating').forEach(radio => {
                radio.addEventListener('change', updateRatingDisplay);
            });
            
            // Update initial counts
            updateProgress();
        }

        // Get rating label
        function getRatingLabel(rating) {
            const labels = {
                1: 'Poor',
                2: 'Fair',
                3: 'Good',
                4: 'Very Good',
                5: 'Excellent'
            };
            return labels[rating];
        }

        // Update rating display
        function updateRatingDisplay(event) {
            const radio = event.target;
            const cloNumber = radio.dataset.clo;
            const rating = parseInt(radio.value);
            
            // Update visual appearance
            const container = radio.closest('.border');
            const allRadios = container.querySelectorAll(`input[name="clo_${cloNumber}"]`);
            const allCircles = container.querySelectorAll('.w-12.h-12');
            
            allCircles.forEach((circle, index) => {
                if (index + 1 === rating) {
                    circle.className = 'w-12 h-12 rounded-full border-2 border-blue-600 bg-blue-600 text-white flex items-center justify-center text-lg font-semibold transition-all';
                } else {
                    circle.className = 'w-12 h-12 rounded-full border-2 border-gray-300 dark:border-gray-600 flex items-center justify-center text-lg font-semibold transition-all group-hover:border-blue-400 text-gray-600 dark:text-gray-400';
                }
            });
            
            updateProgress();
        }

        // Update progress
        function updateProgress() {
            const totalCLOs = (courseData.course_outcomes || []).length;
            const ratedCLOs = document.querySelectorAll('.clo-rating:checked').length;
            
            document.getElementById('completed-count').textContent = ratedCLOs;
            document.getElementById('submit-ratings').disabled = ratedCLOs < totalCLOs;
        }

        // Submit ratings
        async function submitRatings(event) {
            event.preventDefault();
            
            const ratings = [];
            const checkedRadios = document.querySelectorAll('.clo-rating:checked');
            
            checkedRadios.forEach(radio => {
                ratings.push({
                    clo_number: parseInt(radio.dataset.clo),
                    rating: parseInt(radio.value)
                });
            });
            
            try {
                const response = await fetch('/clo-ratings', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        course_id: courseId,
                        ratings: ratings
                    })
                });
                
                if (response.ok) {
                    document.getElementById('success-message').classList.remove('hidden');
                    document.getElementById('submit-ratings').disabled = true;
                    document.getElementById('submit-ratings').textContent = 'Ratings Submitted';
                    
                    // Scroll to success message
                    document.getElementById('success-message').scrollIntoView({ behavior: 'smooth' });
                } else {
                    const errorData = await response.json();
                    alert('Failed to submit ratings: ' + (errorData.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error submitting ratings:', error);
                alert('Error submitting ratings. Please try again.');
            }
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', () => {
            loadCourseData();
            document.getElementById('clo-rating-form').addEventListener('submit', submitRatings);
        });
    </script>
</body>
</html>
