@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Manage ID Card Templates</h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Upload Form -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Upload New Template</h2>
                    <form action="{{ route('school.id-card-templates.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Template Name</label>
                            <input type="text" name="name"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none"
                                placeholder="e.g. Annual Sports" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Type</label>
                            <select name="type"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="student">Student ID Card</option>
                                <option value="teacher">Teacher ID Card</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Front Part Image</label>
                            <div
                                class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:bg-gray-50 transition">
                                <input type="file" name="background_image"
                                    class="w-full text-sm text-gray-500 file:block file:mx-auto file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 mb-2"
                                    required>
                                <span class="text-xs text-gray-400 block">Recommended: 155px x 244px</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Back Part Image (Optional)</label>
                            <div
                                class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:bg-gray-50 transition">
                                <input type="file" name="background_image_back"
                                    class="w-full text-sm text-gray-500 file:block file:mx-auto file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 mb-2">
                                <span class="text-xs text-gray-400 block">Recommended: 155px x 244px</span>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">Text Color</label>
                            <input type="color" name="text_color" value="#000000"
                                class="h-10 w-full rounded cursor-pointer">
                        </div>

                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition">Upload
                            Template</button>
                    </form>
                </div>
            </div>

            <!-- Template List -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Your Templates</h2>

                    @if($templates->isEmpty())
                        <p class="text-gray-500 text-center py-8">No templates uploaded yet.</p>
                    @else
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                            @foreach($templates as $template)
                                <div class="border rounded-lg p-2 relative group hover:shadow-lg transition">
                                    <img src="{{ asset('storage/' . $template->background_image) }}"
                                        class="w-full h-48 object-cover rounded border bg-gray-50">
                                    <div class="mt-2 text-center">
                                        <h3 class="font-bold text-sm text-gray-800">{{ $template->name }}</h3>
                                        <span class="text-xs text-gray-500 uppercase">{{ $template->type }}</span>
                                    </div>

                                    @if($template->school_id === Auth::user()->school_id)
                                        <form action="{{ route('school.id-card-templates.destroy', $template->id) }}" method="POST"
                                            class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition"
                                            onsubmit="return confirm('Delete this template?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-500 text-white p-1 rounded-full shadow hover:bg-red-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection