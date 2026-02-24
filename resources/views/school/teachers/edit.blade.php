@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-6">Edit Teacher</h2>
        <form action="{{ route('school.teachers.update', $teacher->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Employee ID</label>
                    <input type="text" name="employee_id" value="{{ old('employee_id', $teacher->employee_id) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                    <input type="text" name="name" value="{{ old('name', $teacher->name) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Designation</label>
                    <select name="designation"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-white"
                        required>
                        <option value="">Select Designation</option>
                        @foreach($designations as $designation)
                            <option value="{{ $designation->name }}" {{ old('designation', $teacher->designation) == $designation->name ? 'selected' : '' }}>{{ $designation->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Joining Date</label>
                    <input type="text" name="joining_date" id="joining_date"
                        value="{{ old('joining_date', $teacher->joining_date) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                </div>
                <!-- ... -->
            </div>


            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Mobile</label>
                <input type="text" name="mobile" value="{{ old('mobile', $teacher->mobile) }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Blood Group</label>
                <input type="text" name="blood_group" value="{{ old('blood_group', $teacher->blood_group) }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Photo <span class="text-xs text-gray-500">(Max
                        150KB, 300x300px)</span></label>
                <input type="file" name="photo"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                @if($teacher->photo) <img src="{{ asset('storage/' . $teacher->photo) }}" class="h-12 mt-2"> @endif
            </div>
    </div>

    <div class="flex items-center justify-end mt-4">
        <button type="submit"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Update Teacher
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