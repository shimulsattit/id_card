@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-6">Create New School</h2>
        <form action="{{ route('admin.schools.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">School Name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Address</label>
                <textarea name="address"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('address') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">School Logo</label>
                <input type="file" name="logo"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Available Classes</label>
                @php
                    $classes = ['Play', 'Nursery', 'KG', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Honours'];
                @endphp
                <div id="classes-container" class="grid grid-cols-3 gap-2">
                    @foreach($classes as $class)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="classes[]" value="{{ $class }}"
                                class="form-checkbox h-5 w-5 text-blue-600">
                            <span class="ml-2 text-gray-700">{{ $class }}</span>
                        </label>
                    @endforeach
                </div>

                <div class="mt-4 flex gap-2">
                    <input type="text" id="new-class-input" placeholder="Enter new class name"
                        class="shadow border rounded py-1 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-sm flex-1">
                    <button type="button" onclick="addNewClass()"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-1 px-4 rounded text-sm transition shadow-sm">
                        + Add Class
                    </button>
                </div>
            </div>

            <script>
                function addNewClass() {
                    const input = document.getElementById('new-class-input');
                    const rawValue = input.value.trim();
                    if (!rawValue) return;

                    // Split by comma to support multiple classes at once
                    const classNames = rawValue.split(',').map(name => name.trim()).filter(name => name !== '');

                    const container = document.getElementById('classes-container');
                    const existing = Array.from(document.querySelectorAll('input[name="classes[]"]'))
                        .map(i => i.value.toLowerCase());

                    classNames.forEach(className => {
                        if (existing.includes(className.toLowerCase())) {
                            console.log(`Class ${className} already exists`);
                            return;
                        }

                        const label = document.createElement('label');
                        label.className = 'inline-flex items-center mt-2';
                        label.innerHTML = `
                                <input type="checkbox" name="classes[]" value="${className}" class="form-checkbox h-5 w-5 text-blue-600" checked>
                                <span class="ml-2 text-gray-700">${className}</span>
                            `;
                        container.appendChild(label);
                        existing.push(className.toLowerCase());
                    });

                    input.value = '';
                }
            </script>

            <hr class="my-6">
            <h3 class="text-lg font-bold mb-4">Initial School Admin</h3>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Admin Name</label>
                <input type="text" name="admin_name" value="{{ old('admin_name') }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Admin Email</label>
                <input type="email" name="admin_email" value="{{ old('admin_email') }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Admin Password</label>
                <input type="password" name="admin_password"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Create School & Admin
                </button>
            </div>
        </form>
    </div>
@endsection