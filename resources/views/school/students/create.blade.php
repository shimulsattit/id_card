@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Add New Student</h2>
            <a href="{{ route('school.students.index') }}"
                class="text-blue-600 hover:text-blue-800 font-semibold flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Back to List
            </a>
        </div>

        <!-- Bulk Import Section -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 mb-8">
            <div class="bg-gradient-to-r from-green-600 to-green-800 px-8 py-4 flex justify-between items-center">
                <p class="text-green-100 text-sm font-semibold">Bulk Import Students</p>
                <a href="{{ route('school.students.download-sample') }}"
                    class="bg-white text-green-700 hover:bg-green-50 px-4 py-2 rounded-lg text-xs font-bold shadow-md transition duration-200">
                    <i class="fas fa-download mr-1"></i> Download Sample
                </a>
            </div>
            <div class="p-8">
                <form action="{{ route('school.students.import') }}" method="POST" enctype="multipart/form-data"
                    class="flex gap-4 items-end">
                    @csrf
                    <div class="flex-grow">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Upload CSV File (.csv)</label>
                        <input type="file" name="file" accept=".csv" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition duration-200 outline-none">
                    </div>
                    <button type="submit"
                        class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold py-3 px-8 rounded-lg shadow-lg transform transition hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Import
                    </button>
                </form>
            </div>
        </div>

        <!-- Bulk Photo Import Section -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 mb-8">
            <div class="bg-gradient-to-r from-purple-600 to-purple-800 px-8 py-4">
                <p class="text-purple-100 text-sm font-semibold">Bulk Photo Import (Zip)</p>
            </div>
            <div class="p-8">
                <div class="mb-4 text-sm text-gray-600 bg-purple-50 p-4 rounded-lg border border-purple-100">
                    <p class="font-bold mb-1"><i class="fas fa-info-circle mr-1"></i> Instructions:</p>
                    <ul class="list-disc list-inside space-y-1 ml-1">
                        <li>Rename all student photos with their <strong>Student ID</strong> (e.g.,
                            <code>2023001.jpg</code>, <code>2023002.png</code>).
                        </li>
                        <li>Compress all photos into a single <strong>.zip</strong> file.</li>
                        <li>Upload the zip file below. The system will automatically match and assign photos.</li>
                    </ul>
                </div>

                <form action="{{ route('school.students.import-photos') }}" method="POST" enctype="multipart/form-data"
                    class="flex gap-4 items-end">
                    @csrf
                    <div class="flex-grow">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Upload Zip File (.zip)</label>
                        <input type="file" name="zip_file" accept=".zip" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition duration-200 outline-none">
                    </div>
                    <button type="submit"
                        class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white font-bold py-3 px-8 rounded-lg shadow-lg transform transition hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Upload Photos
                    </button>
                </form>
            </div>
        </div>



        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-8 py-4">
                <p class="text-blue-100 text-sm">Enter student details for registration.</p>
            </div>

            <form action="{{ route('school.students.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Student Name -->
                    <div class="mb-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Student Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 outline-none"
                            placeholder="e.g. Rahim Uddin" required>
                    </div>

                    <!-- STD ID -->
                    <div class="mb-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">STD ID (Registration No) <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="student_id" value="{{ old('student_id') }}"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 outline-none"
                            placeholder="e.g. 2023001" required>
                    </div>

                    <!-- Board Registration -->
                    <div class="mb-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Board Registration</label>
                        <input type="text" name="registration_no" value="{{ old('registration_no') }}"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 outline-none"
                            placeholder="e.g. 1234567890">
                    </div>

                    <!-- Class -->
                    <div class="mb-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Class <span
                                class="text-red-500">*</span></label>
                        <select name="class"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 outline-none bg-white"
                            required>
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class }}" {{ old('class') == $class ? 'selected' : '' }}>{{ $class }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Section -->
                    <div class="mb-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Section <span
                                class="text-red-500">*</span></label>
                        <select name="section"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 outline-none bg-white"
                            required>
                            <option value="">Select Section</option>
                            @foreach($sections as $section)
                                <option value="{{ $section }}" {{ old('section') == $section ? 'selected' : '' }}>{{ $section }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Roll -->
                    <div class="mb-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Roll Number <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="roll" value="{{ old('roll') }}"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 outline-none"
                            placeholder="e.g. 05" required>
                    </div>

                    <!-- Session -->
                    <div class="mb-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Session</label>
                        <select name="session"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 outline-none bg-white">
                            <option value="">Select Session</option>
                            @foreach($sessions as $sess)
                                <option value="{{ $sess }}" {{ old('session', '2024-2025') == $sess ? 'selected' : '' }}>
                                    {{ $sess }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Father's Name -->
                    <div class="mb-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Father's Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="father_name" value="{{ old('father_name') }}"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 outline-none"
                            required>
                    </div>

                    <!-- Mother's Name -->
                    <div class="mb-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Mother's Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="mother_name" value="{{ old('mother_name') }}"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 outline-none"
                            required>
                    </div>

                    <!-- Date of Birth -->
                    <div class="mb-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Date of Birth</label>
                        <input type="date" name="dob" value="{{ old('dob') }}"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 outline-none">
                    </div>

                    <!-- Blood Group -->
                    <div class="mb-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Blood Group</label>
                        <select name="blood_group"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 outline-none bg-white">
                            <option value="">Select Blood Group</option>
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Contact -->
                    <div class="mb-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Contact Number <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="contact_no" value="{{ old('contact_no') }}"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 outline-none"
                            placeholder="e.g. 01700000000" required>
                    </div>

                    <!-- Photo -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Student Photo <span
                                class="text-red-500">*</span> <span class="text-xs text-gray-500 font-normal ml-1">(Max
                                150KB, 300x300px)</span></label>
                        <div
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 transition duration-200">
                            <div class="space-y-1 text-center">
                                <!-- Preview Image -->
                                <img id="photo_preview" src="#" alt="Preview"
                                    class="mx-auto h-24 w-24 rounded-full object-cover mb-3 shadow-md hidden">

                                <!-- Placeholder Icon -->
                                <svg id="photo_placeholder" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                    fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>

                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="photo"
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload a file</span>
                                        <input id="photo" name="photo" type="file" class="sr-only" required
                                            onchange="previewPhoto(this)">
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
                        Save Student
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewPhoto(input) {
            const preview = document.getElementById('photo_preview');
            const placeholder = document.getElementById('photo_placeholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '#';
                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
        }
    </script>
@endsection