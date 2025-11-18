<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lecturer Evaluation</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 min-h-screen">
    <div class="max-w-5xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-tie text-white"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">Lecturer Evaluation</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Please answer all questions (1 = Poor, 5 = Excellent)</p>
                </div>
            </div>
            <a href="{{ route('student.page') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 shadow-sm hover:shadow-md">
                <i class="fas fa-arrow-left mr-2 text-blue-500"></i>
                <span class="font-medium">Back</span>
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
            <form id="evaluationForm" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="evaluation_lecturer" class="block text-sm font-medium mb-1">Lecturer</label>
                        <select id="evaluation_lecturer" required class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600">
                            <option value="">Select Lecturer</option>
                        </select>
                    </div>
                </div>

                <div>
                    <h2 class="text-lg font-semibold mb-3">Evaluation Questions</h2>
                    <div id="evaluation-questions-container" class="space-y-4">
                        <div class="text-center py-6">
                            <div class="animate-spin w-6 h-6 border-4 border-blue-500 border-t-transparent rounded-full mx-auto mb-2"></div>
                            <p class="text-gray-500">Loading questions...</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="evaluation_suggestion" class="block text-sm font-medium mb-1">Suggestion (Optional)</label>
                    <textarea id="evaluation_suggestion" rows="3" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600" placeholder="Share any suggestions for improvement"></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Submit Evaluation
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let studentAssignedData = [];
        let studentStatus = null;

        document.addEventListener('DOMContentLoaded', async () => {
            await fetchAssigned();
            await fetchStatus();
            populateSelects();
            loadQuestions();
            document.getElementById('evaluationForm').addEventListener('submit', submitEvaluation);
        });

        async function fetchAssigned() {
            try {
                const resp = await fetch('/student/assigned-data');
                if (!resp.ok) throw new Error('Failed to load assigned');
                const data = await resp.json();
                if (data.success === false) throw new Error(data.message || 'Error');
                studentAssignedData = data;
            } catch (e) {
                Swal.fire({icon:'error', title:'Error', text:e.message});
            }
        }

        async function fetchStatus() {
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

        function populateSelects() {
            const lecturerSelect = document.getElementById('evaluation_lecturer');
            lecturerSelect.innerHTML = '<option value="">Select Lecturer</option>';

            if (studentAssignedData.length > 0) {
                (studentAssignedData[0].lecturers || []).forEach(l => {
                    const opt = document.createElement('option');
                    const isDone = Array.isArray(studentStatus?.evaluated_lecturer_ids) && studentStatus.evaluated_lecturer_ids.includes(l.id);
                    opt.value = l.id;
                    opt.textContent = isDone ? `${l.name} (Completed)` : l.name;
                    if (isDone) opt.disabled = true;
                    lecturerSelect.appendChild(opt);
                });
            }
        }

        async function loadQuestions() {
            const container = document.getElementById('evaluation-questions-container');
            container.innerHTML = `
                <div class="text-center py-6">
                    <div class="animate-spin w-6 h-6 border-4 border-blue-500 border-t-transparent rounded-full mx-auto mb-2"></div>
                    <p class="text-gray-500">Loading questions...</p>
                </div>
            `;
            try {
                const response = await fetch('/evaluation/questions');
                if (!response.ok) throw new Error(`HTTP ${response.status}`);
                const data = await response.json();
                const sections = data.sections || {};
                let html = '';
                let globalQuestionNumber = 1; // Global counter for all questions
                
                Object.entries(sections).forEach(([sectionName, questions]) => {
                    html += `
                        <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl p-4">
                            <h5 class="text-sm font-semibold mb-3">${sectionName}</h5>
                            <div class="space-y-3">
                    `;
                    questions.forEach(q => {
                        if (q.type === 'text') return; // suggestion handled separately
                        html += `
                            <div>
                                <label class="block text-sm mb-2">
                                    <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-0.5 rounded mr-2 text-xs font-semibold">Q${globalQuestionNumber}</span>
                                    ${q.text}
                                </label>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500">Poor</span>
                                    <div class="flex space-x-2">
                                        ${[1,2,3,4,5].map(r => `
                                            <label class="flex items-center">
                                                <input type="radio" name="q_${q.id}" value="${r}" required class="sr-only peer">
                                                <div class="w-8 h-8 rounded-full border-2 border-gray-300 dark:border-gray-600 flex items-center justify-center cursor-pointer hover:border-blue-400 peer-checked:bg-blue-500 peer-checked:border-blue-500 peer-checked:text-white transition">${r}</div>
                                            </label>
                                        `).join('')}
                                    </div>
                                    <span class="text-xs text-gray-500">Excellent</span>
                                </div>
                            </div>
                        `;
                        globalQuestionNumber++; // Increment counter for next question
                    });
                    html += `
                            </div>
                        </div>
                    `;
                });
                container.innerHTML = html || '<p class="text-sm text-gray-500">No questions available.</p>';
            } catch (err) {
                container.innerHTML = '<p class="text-sm text-red-600">Failed to load questions.</p>';
            }
        }

        async function submitEvaluation(e) {
            e.preventDefault();
            const btn = e.target.querySelector('button[type="submit"]');
            btn.disabled = true; const old = btn.textContent; btn.textContent = 'Submitting...';
            try {
                const lecturerId = document.getElementById('evaluation_lecturer').value;
                const answers = {};
                const radios = document.querySelectorAll('#evaluation-questions-container input[type="radio"]:checked');
                radios.forEach(r => { const qId = r.name.replace('q_',''); answers[qId] = parseInt(r.value); });
                const suggestion = document.getElementById('evaluation_suggestion').value;
                const semester = studentAssignedData.length > 0 ? 'Semester ' + studentAssignedData[0].semester : '';

                const payload = { lecturer_id: lecturerId, answers, suggestion, semester };
                console.log('Submitting evaluation with payload:', payload);
                console.log('Number of answers:', Object.keys(answers).length);
                console.log('Student assigned data:', studentAssignedData);

                const resp = await fetch('/evaluations', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify(payload)
                });
                const data = await resp.json();
                if (!resp.ok) {
                    console.error('Evaluation submission failed:', {
                        status: resp.status,
                        statusText: resp.statusText,
                        data: data
                    });
                    
                    // Handle duplicate submission
                    if (resp.status === 409) {
                        return Swal.fire({ 
                            icon:'info', 
                            title:'Already Submitted', 
                            text: data.message || 'You have already submitted this evaluation.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#3b82f6'
                        });
                    }
                    
                    let msg = data.message || 'Failed to submit evaluation';
                    if (data.details) {
                        msg += '\nDetails: ' + data.details;
                    }
                    if (data.errors) {
                        msg += '\nValidation errors: ' + Object.values(data.errors).map(e => Array.isArray(e) ? e.join(', ') : e).join('\n');
                    }
                    throw new Error(msg);
                }
                await Swal.fire({ icon:'success', title:'Submitted', text: data.message || 'Thank you for your feedback.' });
                window.location.href = '{{ route('student.page') }}';
            } catch (err) {
                Swal.fire({ icon:'error', title:'Submission Failed', text: err.message });
            } finally {
                btn.disabled = false; btn.textContent = old;
            }
        }

        // Note: No global lock. Server prevents duplicate per lecturer; students may evaluate different lecturers.
    </script>
</body>
</html>
