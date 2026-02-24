@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Add New Teacher</h2>
            <a href="{{ route('school.teachers.index') }}"
                class="text-blue-600 hover:text-blue-800 font-semibold flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Back to List
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-8 py-4">
                <p class="text-blue-100 text-sm">Fill in the details below to register a new faculty member.</p>
            </div>

            <form action="{{ route('school.teachers.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Employee ID -->
                    <div class="mb-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Employee ID <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="employee_id" value="{{ old('employee_id') }}"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 outline-none"
                            placeholder="e.g. EMP-001" required>
                    </div>

                    <!-- Name -->
                    <div class="mb-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Full Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 outline-none"
                            placeholder="e.g. John Doe" required>
                    </div>

                    <!-- Designation -->
                    <div class="mb-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Designation <span
                                class="text-red-500">*</span></label>
                        <select name="designation"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 outline-none bg-white"
                            required>
                            <option value="">Select Designation</option>
                            @foreach($designations as $designation)
                                <option value="{{ $designation->name }}" {{ old('designation') == $designation->name ? 'selected' : '' }}>{{ $designation->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Joining Date -->
                    <div class="mb-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Joining Date <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="joining_date" id="joining_date" value="{{ old('joining_date') }}"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 outline-none bg-white"
                            placeholder="Select Date" required>
                    </div>

                    <!-- Mobile -->
                    <div class="mb-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Mobile Number <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="mobile" value="{{ old('mobile') }}"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 outline-none"
                            placeholder="e.g. 01700000000" required>
                    </div>

                    <!-- Blood Group -->
                    <div class="mb-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Blood Group</label>
                        <select name="blood_group"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 outline-none bg-white">
                            <option value="">Select Blood Group</option>
                            <option value="A+" {{ old('blood_group') == 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A-" {{ old('blood_group') == 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="B+" {{ old('blood_group') == 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B-" {{ old('blood_group') == 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="AB+" {{ old('blood_group') == 'AB+' ? 'selected' : '' }}>AB+</option>
                            <option value="AB-" {{ old('blood_group') == 'AB-' ? 'selected' : '' }}>AB-</option>
                            <option value="O+" {{ old('blood_group') == 'O+' ? 'selected' : '' }}>O+</option>
                            <option value="O-" {{ old('blood_group') == 'O-' ? 'selected' : '' }}>O-</option>
                        </select>
                    </div>

                    <!-- Photo -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Profile Photo <span
                                class="text-red-500">*</span> <span class="text-xs text-gray-500 font-normal ml-1">(Max
                                150KB, 300x300px)</span></label>
                        <div
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 transition duration-200">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48" aria-hidden="true">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="photo"
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload a file</span>
                                        <input id="photo" name="photo" type="file" class="sr-only" required>
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 150KB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-8">
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3 px-8 rounded-lg shadow-lg transform transition hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Save Teacher
                    </button>
                </div>
            </form>
        </div>

        <script>
            flatpickr("#joining_date", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d-m-Y",
                allowInput: true
            });
        </script>
@endsection