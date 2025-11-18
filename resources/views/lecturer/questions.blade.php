<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manage Evaluation Questions</title>
    <!-- Apply saved theme before paint to avoid flash/fade -->
    <script>
        (function() {
            try {
                var stored = localStorage.getItem('theme');
                var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (stored === 'dark' || (!stored && prefersDark)) {
                    document.documentElement.classList.add('dark');
                }
            } catch (e) {}
        })();
    </script>
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
                        'fade-out': 'fadeOut 0.3s ease-out',
                        'slide-down': 'slideDown 0.3s ease-out',
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
                        },
                        fadeOut: {
                            '0%': { opacity: '1', transform: 'scale(1)' },
                            '100%': { opacity: '0', transform: 'scale(0.95)' }
                        },
                        slideDown: {
                            '0%': { opacity: '1', transform: 'translateY(0)' },
                            '100%': { opacity: '0', transform: 'translateY(-10px)' }
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="/css/app.css" />
    <style>
      .no-transitions * { transition: none !important; }
    </style>
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
                        <!-- Add Question Button -->
                        <button id="btnAdd" class="px-3 py-2 sm:px-4 sm:py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm">
                            <i class="fa-solid fa-plus mr-1 sm:mr-2"></i>
                            <span class="hidden sm:inline">Add Question</span>
                            <span class="sm:hidden">Add</span>
                        </button>
                        
                        <!-- Theme Toggle -->
                        <button id="theme-toggle" class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 dark:hidden" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                            </svg>
                            <svg xmlns="http://www.w3.2000/svg" class="h-5 w-5 hidden dark:block" viewBox="0 0 20 20" fill="currentColor">
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
                        
                        <a href="{{ route('lecturer.surveys') }}" class="group flex items-center py-4 px-1 border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 font-medium text-sm whitespace-nowrap transition-colors">
                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mr-3 group-hover:bg-gray-200 dark:group-hover:bg-gray-600 transition-colors">
                                <i class="fas fa-clipboard-check text-gray-500 dark:text-gray-400"></i>
                            </div>
                            <span>Course Survey</span>
                        </a>
                        
                        <a href="{{ route('lecturer.questions') }}" class="group flex items-center py-4 px-1 border-b-2 border-blue-500 text-blue-600 dark:text-blue-400 font-medium text-sm whitespace-nowrap">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-200 dark:group-hover:bg-blue-900/50 transition-colors">
                                <i class="fas fa-list-check text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <span>Questions</span>
                            <div class="ml-2 px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full text-xs font-medium">Active</div>
                        </a>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Controls -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 mb-8 animate-fade-in">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Search</label>
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500"></i>
                        <input id="search" type="text" placeholder="Search by text..." class="w-full pl-9 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-300" />
                    </div>
                </div>
                <div>
                    <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Section</label>
                    <select id="filter-section" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                        <option value="">All</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Type</label>
                    <select id="filter-type" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                        <option value="">All</option>
                        <option value="scale">Scale</option>
                        <option value="text">Text</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden animate-fade-in">
            <table class="min-w-full">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">Section</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">#</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">Text</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">Type</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody id="rows"></tbody>
            </table>
        </div>
        </main>
    </div>

    <!-- Modal -->
    <div id="modal" class="fixed inset-0 hidden items-center justify-center bg-black/50 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-xl">
            <div class="flex items-center justify-between px-5 py-4 border-b">
                <h2 id="modalTitle" class="font-semibold text-lg">Add Question</h2>
                <button id="btnClose" class="text-gray-500 hover:text-gray-300"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="form" class="p-5 space-y-4">
                <input type="hidden" id="question_id" />
                <input type="hidden" id="original_section" />
                <div>
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">Section</label>
                    <select id="section" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100"></select>
                </div>
                <!-- Number removed: numbering is assigned automatically per section -->
                <div>
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">Text</label>
                    <textarea id="text" rows="3" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-300" placeholder="Enter the question text" required></textarea>
                </div>
                <div>
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">Type</label>
                    <select id="type" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                        <option value="scale">Scale (1-5)</option>
                        <option value="text">Text</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" id="btnCancel" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-100">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save</button>
                </div>
            </form>
        </div>
    </div>

<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;
const rowsEl = document.getElementById('rows');
const modal = document.getElementById('modal');
const form = document.getElementById('form');
const btnAdd = document.getElementById('btnAdd');
const btnClose = document.getElementById('btnClose');
const btnCancel = document.getElementById('btnCancel');
const modalTitle = document.getElementById('modalTitle');
const searchEl = document.getElementById('search');
const filterSectionEl = document.getElementById('filter-section');
const filterTypeEl = document.getElementById('filter-type');
const themeToggle = document.getElementById('theme-toggle');
const sectionSelect = document.getElementById('section');

let allQuestions = [];

function openModal(edit = false, q = null) {
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    modalTitle.textContent = edit ? 'Edit Question' : 'Add Question';
    document.getElementById('question_id').value = q?.id || '';
    document.getElementById('section').value = q?.section || 'Section A';
    document.getElementById('original_section').value = q?.section || '';
    // numbering is automatic; when editing we keep existing number internally
    document.getElementById('text').value = q?.text || '';
    document.getElementById('type').value = q?.type || 'scale';
}

function closeModal() {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

btnAdd.onclick = () => openModal(false);
btnClose.onclick = closeModal;
btnCancel.onclick = closeModal;

async function loadRows() {
    rowsEl.innerHTML = '<tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">Loading...</td></tr>';
    const res = await fetch('/lecturer/api/questions');
    if (!res.ok) {
        rowsEl.innerHTML = '<tr><td colspan="6" class="px-4 py-6 text-center text-red-600">Failed to load</td></tr>';
        return;
    }
    const data = await res.json();
    allQuestions = data.questions || [];
    populateSections();
    renderRows();
}

function renderRows() {
    const term = (searchEl.value || '').toLowerCase();
    const sec = filterSectionEl.value || '';
    const typ = filterTypeEl.value || '';

    const list = allQuestions.filter(q => {
        const matchesSearch = !term || (q.text || '').toLowerCase().includes(term);
        const matchesSec = !sec || q.section === sec;
        const matchesType = !typ || q.type === typ;
        return matchesSearch && matchesSec && matchesType;
    });

    if (list.length === 0) {
        rowsEl.innerHTML = '<tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">No questions found.</td></tr>';
        return;
    }

    rowsEl.innerHTML = list.map(q => `
        <tr class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
            <td class="px-4 py-3 text-gray-900 dark:text-gray-100">${q.section}</td>
            <td class="px-4 py-3 text-gray-900 dark:text-gray-100">${q.number}</td>
            <td class="px-4 py-3 max-w-2xl">
                <div class="text-gray-900 dark:text-gray-100">${q.text}</div>
            </td>
            <td class="px-4 py-3">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${q.type === 'scale' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300'}">${q.type}</span>
            </td>
            <td class="px-4 py-3 space-x-2 whitespace-nowrap">
                <button class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-100" onclick='editQ(${JSON.stringify(q)})'><i class="fa-regular fa-pen-to-square mr-1"></i>Edit</button>
                <button class="px-3 py-1 border border-red-300 dark:border-red-600 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/30 text-red-600 dark:text-red-400" onclick='deleteQ(${q.id})'><i class="fa-regular fa-trash-can mr-1"></i>Delete</button>
            </td>
        </tr>
    `).join('');
}

function populateSections() {
    const uniqueSections = Array.from(new Set(allQuestions.map(q => q.section).filter(Boolean)));
    // Populate filter dropdown
    const currentFilter = filterSectionEl.value;
    filterSectionEl.innerHTML = '<option value="">All</option>' + uniqueSections.map(s => `<option value="${s}">${s}</option>`).join('');
    filterSectionEl.value = currentFilter || '';
    // Populate modal section select
    const currentModal = sectionSelect.value;
    sectionSelect.innerHTML = uniqueSections.map(s => `<option value="${s}">${s}</option>`).join('');
    if (!sectionSelect.innerHTML) {
        sectionSelect.innerHTML = '<option value="Section A">Section A</option><option value="Section B">Section B</option>';
    }
    if (currentModal) sectionSelect.value = currentModal;
}

window.editQ = function(q) { openModal(true, q); }

window.deleteQ = async function(id) {
    const result = await Swal.fire({
        icon: 'warning',
        title: 'Delete this question?',
        text: 'This action cannot be undone.',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel'
    });
    
    if (!result.isConfirmed) return;
    
    try {
        const res = await fetch(`/lecturer/api/questions/${id}`, { 
            method: 'DELETE', 
            headers: { 'X-CSRF-TOKEN': csrf } 
        });
        
        if (res.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: 'Question deleted successfully',
                showConfirmButton: false,
                timer: 2000,
                toast: true,
                position: 'top-end'
            });
        } else {
            throw new Error('Failed to delete question');
        }
        
        await loadRows();
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to delete question',
            confirmButtonColor: '#ef4444'
        });
    }
}

form.onsubmit = async (e) => {
    e.preventDefault();
    const id = document.getElementById('question_id').value;
    const sectionVal = document.getElementById('section').value;
    const originalSection = document.getElementById('original_section').value;
    // Determine number: keep existing on edit, assign next within section on create
    const existing = allQuestions.find(q => String(q.id) === String(id));
    const nextNumber = (() => {
        const inSection = allQuestions.filter(q => q.section === sectionVal);
        if (inSection.length === 0) return 1;
        const maxNum = Math.max(...inSection.map(q => parseInt(q.number || 0) || 0));
        return (isFinite(maxNum) ? maxNum : 0) + 1;
    })();
    const assignedNumber = existing ? (existing.section !== sectionVal ? nextNumber : existing.number) : nextNumber;
    const payload = {
        section: sectionVal,
        number: assignedNumber,
        text: document.getElementById('text').value,
        type: document.getElementById('type').value,
    };
    const method = id ? 'PUT' : 'POST';
    const url = id ? `/lecturer/api/questions/${id}` : '/lecturer/api/questions';
    const res = await fetch(url, {
        method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf
        },
        body: JSON.stringify(payload)
    });
    if (!res.ok) {
        const data = await res.json().catch(() => ({}));
        console.error('Question save failed:', data);
        
        let errorMessage = 'Unknown error';
        if (data?.message) {
            errorMessage = data.message;
        } else if (data?.errors) {
            errorMessage = Object.values(data.errors).flat().join(', ');
        }
        
        Swal.fire({ 
            icon: 'error', 
            title: 'Failed to save question', 
            text: errorMessage,
            confirmButtonText: 'OK',
            confirmButtonColor: '#ef4444'
        });
        return;
    }
    
    const responseData = await res.json();
    closeModal();
    
    Swal.fire({ 
        icon: 'success', 
        title: 'Success!', 
        text: responseData?.message || 'Question saved successfully',
        showConfirmButton: false, 
        timer: 2000,
        timerProgressBar: true,
        toast: true,
        position: 'top-end'
    });
    loadRows();
}

// Event listeners for controls
;['input','change'].forEach(evt => {
    searchEl.addEventListener(evt, renderRows);
    filterSectionEl.addEventListener(evt, renderRows);
    filterTypeEl.addEventListener(evt, renderRows);
});

// Theme toggle and user dropdown functionality
document.addEventListener('DOMContentLoaded', function() {
    // Theme toggle
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
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

loadRows();
</script>
</body>
</html>
