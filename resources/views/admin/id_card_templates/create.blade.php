@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Add New Template</h2>
            <a href="{{ route('admin.id-card-templates.index') }}"
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
                <p class="text-blue-100 text-sm">Upload a new ID card design template.</p>
            </div>

            <form action="{{ route('admin.id-card-templates.store') }}" method="POST" enctype="multipart/form-data"
                class="p-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Template Name -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Template Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 outline-none"
                            placeholder="e.g. Standard Blue Theme" required>
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Template Type <span
                                class="text-red-500">*</span></label>
                        <select name="type"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 outline-none bg-white">
                            <option value="student">Student ID Card</option>
                            <option value="teacher">Teacher ID Card</option>
                        </select>
                    </div>

                    <!-- Text Color -->
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Default Text Color</label>
                        <div class="flex items-center h-full">
                            <input type="color" name="text_color" value="{{ old('text_color', '#000000') }}"
                                class="h-10 w-20 rounded cursor-pointer border border-gray-300 p-1">
                            <span class="ml-3 text-gray-500 text-sm">Photo border color</span>
                        </div>
                    </div>

                    <!-- Name Color -->
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Name Title Color</label>
                        <div class="flex items-center h-full">
                            <input type="color" name="name_color" value="{{ old('name_color', '#148bc9') }}"
                                class="h-10 w-20 rounded cursor-pointer border border-gray-300 p-1">
                            <span class="ml-3 text-gray-500 text-sm">Student/Teacher name color</span>
                        </div>
                    </div>

                    <!-- Data Color -->
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Data Text Color</label>
                        <div class="flex items-center h-full">
                            <input type="color" name="data_color" value="{{ old('data_color', '#000000') }}"
                                class="h-10 w-20 rounded cursor-pointer border border-gray-300 p-1">
                            <span class="ml-3 text-gray-500 text-sm">ID, Class, Roll, Mobile color</span>
                        </div>
                    </div>

                    <!-- Photo Border Color -->
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Photo Border Color</label>
                        <div class="flex items-center h-full">
                            <input type="color" name="photo_border_color" value="{{ old('photo_border_color', '#000000') }}"
                                class="h-10 w-20 rounded cursor-pointer border border-gray-300 p-1">
                            <span class="ml-3 text-gray-500 text-sm">Image border/frame color</span>
                        </div>
                    </div>

                    <!-- Front Image -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Front Background Image <span
                                class="text-red-500">*</span> <span class="font-normal text-gray-500 text-xs">(Recommended:
                                638x1013 px for Portrait)</span></label>
                        <input type="file" name="background_image" accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition duration-200"
                            required>
                    </div>

                    <!-- Back Image -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Back Background Image <span
                                class="font-normal text-gray-500 text-xs">(Optional)</span></label>
                        <input type="file" name="background_image_back" accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition duration-200">
                    </div>
                </div>

                <div class="flex items-center justify-end mt-8 border-t border-gray-100 pt-6">
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3 px-8 rounded-lg shadow-lg transform transition hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Create Template
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection