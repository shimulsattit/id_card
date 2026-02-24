@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-6">Generate Teacher ID Cards</h2>

        @if($teachers->count() > 0)
            <form id="idCardForm" method="POST" target="_blank">
                @csrf

                <div class="mb-4 space-x-4">
                    <button type="submit" formaction="{{ route('school.idcards.teachers.preview') }}"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Preview Selected
                    </button>
                    <button type="submit" formaction="{{ route('school.idcards.teachers.download') }}"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Download PDF
                    </button>
                </div>

                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left"><input type="checkbox"
                                    id="selectAll"></th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left">Name</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left">Designation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teachers as $teacher)
                            <tr>
                                <td class="px-5 py-2 border-b"><input type="checkbox" name="teacher_ids[]"
                                        value="{{ $teacher->id }}" class="teacher-checkbox"></td>
                                <td class="px-5 py-2 border-b">{{ $teacher->name }}</td>
                                <td class="px-5 py-2 border-b">{{ $teacher->designation }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        @else
            <p class="text-gray-500">No teachers found.</p>
        @endif
    </div>

    <script>
        document.getElementById('selectAll').addEventListener('change', function (e) {
            const checkboxes = document.querySelectorAll('.teacher-checkbox');
            checkboxes.forEach(cb => cb.checked = e.target.checked);
        });
    </script>
@endsection