<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manage Course</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
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
                <h1 class="text-2xl md:text-3xl font-bold">Manage Course</h1>
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

        <!-- Course Management Sections -->
        <div class="space-y-8">

            <!-- Course Outcomes Section -->
            <section class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Course Learning Outcomes (CLOs)</h2>
                    <button id="add-outcome-btn" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Add CLO
                    </button>
                </div>
                <div id="outcomes-list" class="space-y-3">
                    <!-- Course outcomes will be loaded here -->
                </div>
            </section>

            <!-- CO-PLO/PEO Mapping Section -->
            <section class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Mapping of COs to PLOs & PEOs</h2>
                    <button id="edit-mapping-btn" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Edit Mapping
                    </button>
                </div>
                <div id="mapping-display">
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto border border-gray-200 dark:border-gray-600">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700">
                                    <th class="px-4 py-3 text-left font-semibold text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600">CLO</th>
                                    <th class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600">PLO</th>
                                    <th class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600">PEO</th>
                                </tr>
                            </thead>
                            <tbody id="mapping-table-body">
                                <!-- Mapping data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="mapping-edit" class="hidden">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Select the appropriate PLO (Program Learning Outcome) and PEO (Program Educational Objective) for each CLO</p>
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto border border-gray-200 dark:border-gray-600">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700">
                                    <th class="px-4 py-3 text-left font-semibold text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600">CLO</th>
                                    <th class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600">PLO</th>
                                    <th class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600">PEO</th>
                                </tr>
                            </thead>
                            <tbody id="mapping-edit-table-body">
                                <!-- Editable mapping data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                    <div class="flex space-x-2 mt-4">
                        <button id="save-mapping-btn" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                            Save Mapping
                        </button>
                        <button id="cancel-mapping-btn" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>
            </section>

        </div>
    </div>

    <script>
        let courseId = null;
        let courseData = null;

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
            return pathParts[pathParts.length - 1];
        }

        // Load course data
        async function loadCourseData() {
            try {
                courseId = getCourseIdFromUrl();
                const response = await fetch(`/lecturer/courses/${courseId}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                courseData = await response.json();
                
                document.getElementById('course-info').textContent = `${courseData.code} - ${courseData.name}`;
                
                
                // Load course outcomes
                loadCourseOutcomes();
                
                // Load CO-PO mapping
                loadCOPOMapping();
                
                // Remove CLO statistics loading since section is removed
                
            } catch (error) {
                console.error('Error loading course data:', error);
                document.getElementById('course-info').textContent = 'Error loading course';
                alert('Error loading course data: ' + error.message);
            }
        }

        // Load course outcomes
        function loadCourseOutcomes() {
            const outcomesList = document.getElementById('outcomes-list');
            const outcomes = courseData.course_outcomes || [];
            
            outcomesList.innerHTML = '';
            
            if (outcomes.length === 0) {
                outcomesList.innerHTML = '<p class="text-gray-500">No course outcomes defined</p>';
                return;
            }
            
            outcomes.forEach((outcome, index) => {
                const outcomeDiv = document.createElement('div');
                outcomeDiv.className = 'flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-md';
                outcomeDiv.innerHTML = `
                    <div class="flex-1">
                        <span class="font-medium">CLO ${index + 1}:</span>
                        <span class="ml-2">${outcome}</span>
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="editOutcome(${index})" class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">Edit</button>
                        <button onclick="deleteOutcome(${index})" class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">Delete</button>
                    </div>
                `;
                outcomesList.appendChild(outcomeDiv);
            });
        }

        // Load CO-PLO/PEO mapping
        function loadCOPOMapping() {
            const tableBody = document.getElementById('mapping-table-body');
            const editTableBody = document.getElementById('mapping-edit-table-body');
            const outcomes = courseData.course_outcomes || [];
            const mapping = courseData.co_po_mapping || {};
            
            tableBody.innerHTML = '';
            editTableBody.innerHTML = '';
            
            outcomes.forEach((outcome, index) => {
                const cloKey = `CLO${index + 1}`;
                const mappingData = mapping[cloKey] || { PLO: '', PEO: '' };
                
                // Display row
                const displayRow = document.createElement('tr');
                displayRow.className = 'border-b border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700';
                displayRow.innerHTML = `
                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100 border-r border-gray-200 dark:border-gray-600">CLO ${index + 1}</td>
                    <td class="px-4 py-3 text-center font-semibold text-blue-600 dark:text-blue-400 border-r border-gray-200 dark:border-gray-600">${mappingData.PLO || '<span class="text-gray-400 dark:text-gray-500">-</span>'}</td>
                    <td class="px-4 py-3 text-center font-semibold text-green-600 dark:text-green-400">${mappingData.PEO || '<span class="text-gray-400 dark:text-gray-500">-</span>'}</td>
                `;
                tableBody.appendChild(displayRow);
                
                // Edit row
                const editRow = document.createElement('tr');
                editRow.className = 'border-b border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700';
                editRow.innerHTML = `
                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100 border-r border-gray-200 dark:border-gray-600">CLO ${index + 1}</td>
                    <td class="px-4 py-3 text-center border-r border-gray-200 dark:border-gray-600">
                        <select class="mapping-select w-24 p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" data-clo="${cloKey}" data-type="PLO">
                            <option value="" ${!mappingData.PLO ? 'selected' : ''}>-</option>
                            <option value="PLO1" ${mappingData.PLO == 'PLO1' ? 'selected' : ''}>PLO1</option>
                            <option value="PLO2" ${mappingData.PLO == 'PLO2' ? 'selected' : ''}>PLO2</option>
                            <option value="PLO3" ${mappingData.PLO == 'PLO3' ? 'selected' : ''}>PLO3</option>
                            <option value="PLO4" ${mappingData.PLO == 'PLO4' ? 'selected' : ''}>PLO4</option>
                            <option value="PLO5" ${mappingData.PLO == 'PLO5' ? 'selected' : ''}>PLO5</option>
                            <option value="PLO6" ${mappingData.PLO == 'PLO6' ? 'selected' : ''}>PLO6</option>
                            <option value="PLO7" ${mappingData.PLO == 'PLO7' ? 'selected' : ''}>PLO7</option>
                            <option value="PLO8" ${mappingData.PLO == 'PLO8' ? 'selected' : ''}>PLO8</option>
                            <option value="PLO9" ${mappingData.PLO == 'PLO9' ? 'selected' : ''}>PLO9</option>
                            <option value="PLO10" ${mappingData.PLO == 'PLO10' ? 'selected' : ''}>PLO10</option>
                            <option value="PLO11" ${mappingData.PLO == 'PLO11' ? 'selected' : ''}>PLO11</option>
                            <option value="PLO12" ${mappingData.PLO == 'PLO12' ? 'selected' : ''}>PLO12</option>
                        </select>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <select class="mapping-select w-24 p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-green-500" data-clo="${cloKey}" data-type="PEO">
                            <option value="" ${!mappingData.PEO ? 'selected' : ''}>-</option>
                            <option value="PEO1" ${mappingData.PEO == 'PEO1' ? 'selected' : ''}>PEO1</option>
                            <option value="PEO2" ${mappingData.PEO == 'PEO2' ? 'selected' : ''}>PEO2</option>
                            <option value="PEO3" ${mappingData.PEO == 'PEO3' ? 'selected' : ''}>PEO3</option>
                        </select>
                    </td>
                `;
                editTableBody.appendChild(editRow);
            });
        }

        // Load CLO statistics for lecturers
        async function loadCLOStatistics() {
            const statisticsSection = document.getElementById('clo-statistics-section');
            const outcomes = courseData.course_outcomes || [];
            
            statisticsSection.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> Loading statistics...</div>';
            
            if (outcomes.length === 0) {
                statisticsSection.innerHTML = '<p class="text-gray-500">No course outcomes available</p>';
                return;
            }
            
            try {
                const response = await fetch(`/course/${courseId}/clo-ratings`);
                const data = await response.json();
                
                statisticsSection.innerHTML = '';
                
                if (data.total_students_rated === 0) {
                    statisticsSection.innerHTML = '<p class="text-gray-500">No student ratings submitted yet</p>';
                    return;
                }
                
                // Create statistics display
                const statsDiv = document.createElement('div');
                statsDiv.className = 'space-y-4';
                
                // Overall summary
                const summaryDiv = document.createElement('div');
                summaryDiv.className = 'bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg';
                summaryDiv.innerHTML = `
                    <h3 class="font-semibold text-blue-800 dark:text-blue-200 mb-2">Overall Summary</h3>
                    <p class="text-blue-700 dark:text-blue-300">Total students who rated: <span class="font-bold">${data.total_students_rated}</span></p>
                `;
                statsDiv.appendChild(summaryDiv);
                
                // Individual CLO statistics
                outcomes.forEach((outcome, index) => {
                    const cloNumber = index + 1;
                    const cloStats = data.statistics[cloNumber];
                    
                    const cloDiv = document.createElement('div');
                    cloDiv.className = 'border border-gray-200 dark:border-gray-600 rounded-lg p-4';
                    
                    if (cloStats) {
                        const distribution = cloStats.ratings_distribution || {};
                        const distributionBars = [1, 2, 3, 4, 5].map(rating => {
                            const count = distribution[rating] || 0;
                            const percentage = cloStats.total_responses > 0 ? (count / cloStats.total_responses * 100).toFixed(1) : 0;
                            return `
                                <div class="flex items-center mb-1">
                                    <span class="w-8 text-sm">${rating}★</span>
                                    <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2 mx-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: ${percentage}%"></div>
                                    </div>
                                    <span class="w-12 text-sm text-right">${count}</span>
                                </div>
                            `;
                        }).join('');
                        
                        cloDiv.innerHTML = `
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100">CLO ${cloNumber}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">${outcome}</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">${cloStats.average_rating}</div>
                                    <div class="text-xs text-gray-500">avg rating</div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h5 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Rating Distribution</h5>
                                    ${distributionBars}
                                </div>
                                <div class="flex flex-col justify-center">
                                    <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                        <div class="text-lg font-semibold">${cloStats.total_responses}</div>
                                        <div class="text-xs text-gray-500">total responses</div>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        cloDiv.innerHTML = `
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100">CLO ${cloNumber}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">${outcome}</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-gray-400">-</div>
                                    <div class="text-xs text-gray-500">no ratings</div>
                                </div>
                            </div>
                            <p class="text-gray-500 text-center py-4">No student ratings for this CLO yet</p>
                        `;
                    }
                    
                    statsDiv.appendChild(cloDiv);
                });
                
                statisticsSection.appendChild(statsDiv);
                
            } catch (error) {
                console.error('Error loading CLO statistics:', error);
                statisticsSection.innerHTML = '<p class="text-red-500">Error loading CLO statistics</p>';
            }
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', () => {
            loadCourseData();
            
            
            // Add outcome
            document.getElementById('add-outcome-btn').addEventListener('click', addOutcome);
            
            // Mapping editing
            document.getElementById('edit-mapping-btn').addEventListener('click', () => {
                document.getElementById('mapping-display').classList.add('hidden');
                document.getElementById('mapping-edit').classList.remove('hidden');
            });
            
            document.getElementById('cancel-mapping-btn').addEventListener('click', () => {
                document.getElementById('mapping-display').classList.remove('hidden');
                document.getElementById('mapping-edit').classList.add('hidden');
            });
            
            document.getElementById('save-mapping-btn').addEventListener('click', saveMapping);
        });


        // Add outcome
        function addOutcome() {
            const outcome = prompt('Enter new Course Learning Outcome:');
            if (outcome && outcome.trim()) {
                const outcomes = courseData.course_outcomes || [];
                outcomes.push(outcome.trim());
                saveCourseOutcomes(outcomes);
            }
        }

        // Edit outcome
        function editOutcome(index) {
            const outcomes = courseData.course_outcomes || [];
            const newOutcome = prompt('Edit Course Learning Outcome:', outcomes[index]);
            if (newOutcome !== null && newOutcome.trim()) {
                outcomes[index] = newOutcome.trim();
                saveCourseOutcomes(outcomes);
            }
        }

        // Delete outcome
        function deleteOutcome(index) {
            if (confirm('Are you sure you want to delete this outcome?')) {
                const outcomes = courseData.course_outcomes || [];
                outcomes.splice(index, 1);
                saveCourseOutcomes(outcomes);
            }
        }

        // Save course outcomes
        async function saveCourseOutcomes(outcomes) {
            try {
                const response = await fetch(`/lecturer/courses/${courseId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        name: courseData.name,
                        code: courseData.code,
                        semester: courseData.semester,
                        course_outcomes: outcomes,
                        co_po_mapping: courseData.co_po_mapping || {}
                    })
                });
                
                if (response.ok) {
                    courseData.course_outcomes = outcomes;
                    loadCourseOutcomes();
                    loadCOPOMapping();
                    alert('Course outcomes saved successfully!');
                } else {
                    const errorData = await response.json();
                    alert('Failed to save course outcomes: ' + (errorData.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error saving course outcomes:', error);
                alert('Error saving course outcomes: ' + error.message);
            }
        }

        // Save mapping
        async function saveMapping() {
            const mapping = {};
            const selects = document.querySelectorAll('.mapping-select');
            
            selects.forEach(select => {
                const clo = select.dataset.clo;
                const type = select.dataset.type; // PLO or PEO
                const value = select.value;
                
                if (!mapping[clo]) {
                    mapping[clo] = {};
                }
                mapping[clo][type] = value;
            });
            
            try {
                const response = await fetch(`/lecturer/courses/${courseId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        name: courseData.name,
                        code: courseData.code,
                        semester: courseData.semester,
                        course_outcomes: courseData.course_outcomes || [],
                        co_po_mapping: mapping
                    })
                });
                
                if (response.ok) {
                    const updatedData = await response.json();
                    courseData.co_po_mapping = mapping;
                    loadCOPOMapping();
                    document.getElementById('mapping-display').classList.remove('hidden');
                    document.getElementById('mapping-edit').classList.add('hidden');
                    alert('PLO/PEO mapping saved successfully!');
                } else {
                    const errorData = await response.json();
                    alert('Failed to save mapping: ' + (errorData.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error saving mapping:', error);
                alert('Error saving mapping: ' + error.message);
            }
        }
    </script>
</body>
</html>
