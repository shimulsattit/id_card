@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Class Management -->
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-2xl font-bold mb-4">Manage Classes</h2>
            <p class="mb-4 text-gray-600">Select the classes available in your school.</p>

            <form action="{{ route('school.academic.classes.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-3 md:grid-cols-5 gap-3 mb-6">
                    @foreach($allClasses as $class)
                        <label class="inline-flex items-center p-2 border rounded hover:bg-gray-50">
                            <input type="checkbox" name="classes[]" value="{{ $class }}"
                                class="form-checkbox h-5 w-5 text-blue-600" {{ in_array($class, $currentClasses) ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700">{{ $class }}</span>
                        </label>
                    @endforeach
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Update Classes
                </button>
            </form>
        </div>

        <!-- Section Management -->
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-2xl font-bold mb-4">Manage Sections</h2>
            <p class="mb-4 text-gray-600">Enter sections separated by commas (e.g., A, B, Morning, Day).</p>

            <form action="{{ route('school.academic.sections.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <textarea name="sections_input" rows="3"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"
                        placeholder="A, B, C...">{{ implode(', ', $currentSections) }}</textarea>
                </div>
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Update Sections
                </button>
            </form>

            @if(count($currentSections) > 0)
                <div class="mt-6">
                    <h3 class="font-bold mb-2">Current Sections:</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($currentSections as $sec)
                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $sec }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Session Management -->
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-2xl font-bold mb-4">Manage Sessions</h2>
            <p class="mb-4 text-gray-600">Enter sessions separated by commas (e.g., 2023-2024, 2024-2025, 2025-2026).</p>

            <form action="{{ route('school.academic.sessions.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <textarea name="sessions_input" rows="3"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"
                        placeholder="2024-2025, 2025-2026...">{{ implode(', ', $currentSessions) }}</textarea>
                </div>
                <button type="submit" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                    Update Sessions
                </button>
            </form>

            @if(count($currentSessions) > 0)
                <div class="mt-6">
                    <h3 class="font-bold mb-2">Current Sessions:</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($currentSessions as $sess)
                            <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $sess }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection