@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manage Teachers</h1>
        <a href="{{ route('school.teachers.create') }}"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Add New Teacher
        </a>
    </div>

    <!-- Filter -->
    <div class="bg-white p-4 rounded shadow mb-6">
        <form action="{{ route('school.teachers.index') }}" method="GET" class="flex gap-4">
            <input type="text" name="search" placeholder="Search Name/Designation" value="{{ request('search') }}"
                class="border rounded px-3 py-2 w-full">
            <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded">Search</button>
        </form>
    </div>

    <div class="bg-white shadow-md rounded my-6 overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th
                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Photo</th>
                    <th
                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Name & Designation</th>
                    <th
                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Mobile</th>
                    <th
                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teachers as $teacher)
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            @if($teacher->photo)
                                <img src="{{ asset('storage/' . $teacher->photo) }}" class="h-12 w-12 rounded bg-cover">
                            @else
                                <span class="text-gray-400">N/A</span>
                            @endif
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="font-bold">{{ $teacher->name }}</p>
                            <p class="text-xs text-gray-600">{{ $teacher->designation }}</p>
                            <p class="text-xs text-gray-500">Joined:
                                {{ \Carbon\Carbon::parse($teacher->joining_date)->format('d-m-Y') }}</p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p>{{ $teacher->mobile }}</p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <a href="{{ route('school.teachers.edit', $teacher->id) }}"
                                class="text-blue-600 hover:text-blue-900 mr-2">Edit</a>
                            <form action="{{ route('school.teachers.destroy', $teacher->id) }}" method="POST"
                                class="inline-block" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-5 py-5 bg-white border-t">
            {{ $teachers->links() }}
        </div>
    </div>
@endsection