@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Manage Designations</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Add New Designation Form -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 h-fit">
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                    <h3 class="text-white font-bold text-lg">Add New Designation</h3>
                </div>
                <form action="{{ route('school.designations.store') }}" method="POST" class="p-6">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Designation Name</label>
                        <input type="text" name="name"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 outline-none"
                            placeholder="e.g. Senior Teacher" required>
                    </div>
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3 px-8 rounded-lg shadow-lg transform transition hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Add Designation
                    </button>
                </form>
            </div>

            <!-- List Designations -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                    <h3 class="text-gray-800 font-bold text-lg">Available Designations</h3>
                </div>
                <div class="p-0">
                    <ul class="divide-y divide-gray-100">
                        @foreach($designations as $designation)
                            <li class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition duration-150">
                                <span class="text-gray-700 font-medium">{{ $designation->name }}</span>
                                @if($designation->school_id == Auth::user()->school_id)
                                    <form action="{{ route('school.designations.destroy', $designation->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-500 hover:text-red-700 text-sm font-semibold">Delete</button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded">Default</span>
                                @endif
                            </li>
                        @endforeach
                        @if($designations->isEmpty())
                            <li class="px-6 py-4 text-center text-gray-500">No designations found.</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection