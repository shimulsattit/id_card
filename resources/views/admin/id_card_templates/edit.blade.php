@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Edit Template</h2>
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
            <div class="bg-gradient-to-r from-teal-600 to-teal-800 px-8 py-4">
                <p class="text-teal-100 text-sm">Update ID card design template.</p>
            </div>

            <form action="{{ route('admin.id-card-templates.update', $idCardTemplate->id) }}" method="POST"
                enctype="multipart/form-data" class="p-8">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Template Name -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Template Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $idCardTemplate->name) }}"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition duration-200 outline-none"
                            required>
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Template Type <span
                                class="text-red-500">*</span></label>
                        <select name="type"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition duration-200 outline-none bg-white">
                            <option value="student" {{ $idCardTemplate->type == 'student' ? 'selected' : '' }}>Student ID Card
                            </option>
                            <option value="teacher" {{ $idCardTemplate->type == 'teacher' ? 'selected' : '' }}>Teacher ID Card
                            </option>
                        </select>
                    </div>

                    <!-- Text Color -->
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Default Text Color</label>
                        <div class="flex items-center h-full">
                            <input type="color" name="text_color"
                                value="{{ old('text_color', $idCardTemplate->text_color) }}"
                                class="h-10 w-20 rounded cursor-pointer border border-gray-300 p-1">
                            <span class="ml-3 text-gray-500 text-sm">Photo border color</span>
                        </div>
                    </div>

                    <!-- Name Color -->
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Name Title Color</label>
                        <div class="flex items-center h-full">
                            <input type="color" name="name_color"
                                value="{{ old('name_color', $idCardTemplate->name_color ?? '#148bc9') }}"
                                class="h-10 w-20 rounded cursor-pointer border border-gray-300 p-1">
                            <span class="ml-3 text-gray-500 text-sm">Student/Teacher name color</span>
                        </div>
                    </div>

                    <!-- Data Color -->
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Data Text Color</label>
                        <div class="flex items-center h-full">
                            <input type="color" name="data_color"
                                value="{{ old('data_color', $idCardTemplate->data_color ?? '#000000') }}"
                                class="h-10 w-20 rounded cursor-pointer border border-gray-300 p-1">
                            <span class="ml-3 text-gray-500 text-sm">ID, Class, Roll, Mobile color</span>
                        </div>
                    </div>

                    <!-- Photo Border Color -->
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Photo Border Color</label>
                        <div class="flex items-center h-full">
                            <input type="color" name="photo_border_color"
                                value="{{ old('photo_border_color', $idCardTemplate->photo_border_color ?? '#000000') }}"
                                class="h-10 w-20 rounded cursor-pointer border border-gray-300 p-1">
                            <span class="ml-3 text-gray-500 text-sm">Image border/frame color</span>
                        </div>
                    </div>

                    <!-- Front Image -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Front Background Image</label>
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $idCardTemplate->background_image) }}" alt="Current Front"
                                class="h-32 w-auto rounded border border-gray-200 shadow-sm">
                            <p class="text-xs text-gray-500 mt-1">Current Image</p>
                        </div>
                        <input type="file" name="background_image" accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 transition duration-200">
                        <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image.</p>
                    </div>

                    <!-- Back Image -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Back Background Image</label>
                        @if($idCardTemplate->background_image_back)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $idCardTemplate->background_image_back) }}" alt="Current Back"
                                    class="h-32 w-auto rounded border border-gray-200 shadow-sm">
                                <p class="text-xs text-gray-500 mt-1">Current Image</p>
                            </div>
                        @else
                            <p class="text-xs text-gray-400 mb-2">No back image uploaded.</p>
                        @endif
                        <input type="file" name="background_image_back" accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 transition duration-200">
                        <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image.</p>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-12 border-t border-gray-100 pt-6">
                    <button type="submit"
                        class="bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white font-bold py-3 px-8 rounded-lg shadow-lg transform transition hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                        Update Template
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection