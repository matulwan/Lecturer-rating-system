@extends('admin.page')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-2xl font-semibold mb-4">{{ $lecturer->user->name }}'s Details</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-gray-600 dark:text-gray-400"><strong>IC Number:</strong> {{ $lecturer->user->user_code }}</p>
            </div>
            <div>
                <p class="text-gray-600 dark:text-gray-400"><strong>Salary Number:</strong> {{ $lecturer->salary_number }}</p>
            </div>
        </div>

        <h3 class="text-xl font-semibold mt-6 mb-3">Assigned Courses</h3>
        @if($lecturer->courses->isEmpty())
            <p class="text-gray-500 dark:text-gray-400">No courses assigned to this lecturer.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Course Code</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Course Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Semester</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($lecturer->courses as $course)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $course->code }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $course->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $course->semester }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
