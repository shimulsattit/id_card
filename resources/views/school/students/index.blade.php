@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h1 class="text-3xl font-bold text-gray-800">Manage Students</h1>
            <div class="flex gap-2">
                 <a href="{{ route('school.students.export-report') }}" class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white font-bold py-2 px-6 rounded-lg shadow-md transform transition hover:-translate-y-0.5">
                    <i class="fas fa-file-alt mr-2"></i> Student Report
                </a>
                <a href="{{ route('school.students.create') }}"
                    class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-2 px-6 rounded-lg shadow-md transform transition hover:-translate-y-0.5">
                    + Add New Student
                </a>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 mb-8">
            <form action="{{ route('school.students.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <select name="class" onchange="this.form.submit()" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition duration-200 bg-white">
                    <option value="">Filter by Class</option>
                    @foreach($classes as $class)
                        <option value="{{ $class }}" {{ request('class') == $class ? 'selected' : '' }}>{{ $class }}</option>
                    @endforeach
                </select>
                
                <select name="section" onchange="this.form.submit()" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition duration-200 bg-white">
                    <option value="">Filter by Section</option>
                    @foreach($sections as $section)
                        <option value="{{ $section }}" {{ request('section') == $section ? 'selected' : '' }}>{{ $section }}</option>
                    @endforeach
                </select>

                <select name="session" onchange="this.form.submit()" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition duration-200 bg-white">
                    <option value="">Filter by Session</option>
                    @foreach($sessions as $sess)
                        <option value="{{ $sess }}" {{ request('session') == $sess ? 'selected' : '' }}>{{ $sess }}</option>
                    @endforeach
                </select>
                
                <input type="text" name="search" placeholder="Search Name/Roll" value="{{ request('search') }}"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition duration-200">
                
                <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 shadow-md">
                    Filter Results
                </button>
            </form>
        </div>

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">SN</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ID No</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Photo</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Student Details</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Session</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Parents</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Blood Group</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($students as $key => $student)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-600">
                                    {{ $students->firstItem() + $key }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700">
                                    {{ $student->student_id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($student->photo)
                                        <img src="{{ asset('storage/' . $student->photo) }}" class="h-12 w-12 rounded-full object-cover shadow-sm bg-gray-200">
                                    @else
                                        <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-400 font-bold text-xs">N/A</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-900 text-base">{{ $student->name }}</span>
                                        <span class="text-sm text-gray-500 mt-1">
                                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded-full font-medium">Class: {{ $student->class }}</span>
                                            <span class="ml-1 bg-purple-100 text-purple-800 text-xs px-2 py-0.5 rounded-full font-medium">Sec: {{ $student->section }}</span>
                                            <span class="ml-1 bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full font-medium">Roll: {{ $student->roll }}</span>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $student->session ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex flex-col">
                                        <span class="text-gray-900 font-medium">F: {{ $student->father_name }}</span>
                                        <span class="text-gray-500 text-xs">M: {{ $student->mother_name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($student->blood_group)
                                        <span class="bg-red-50 text-red-700 px-2 py-0.5 rounded-md text-xs font-bold border border-red-100">{{ $student->blood_group }}</span>
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 font-medium">
                                    {{ $student->contact_no }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('school.students.edit', $student->id) }}"
                                           class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-md transition duration-200">Edit</a>
                                        
                                        <form action="{{ route('school.students.destroy', $student->id) }}" method="POST"
                                              class="inline-block" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-md transition duration-200">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $students->links() }}
            </div>
        </div>
    </div>
@endsection