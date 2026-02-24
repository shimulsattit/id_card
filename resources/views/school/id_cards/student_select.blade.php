@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-2xl font-bold text-gray-800">Generate Student ID Cards</h2>
                <p class="text-gray-500 text-sm mt-1">Select a design template and choose students to generate ID cards.</p>
            </div>

            <div class="p-6">
                <form action="{{ route('school.idcards.students.preview') }}" method="POST" id="generateForm">
                    @csrf

                    <!-- 1. Template Selection -->
                    <div class="mb-10">
                        <h3 class="text-lg font-bold text-gray-700 mb-4 border-l-4 border-blue-500 pl-3">1. Select Design
                            Template</h3>

                        <input type="hidden" name="template" id="selectedTemplateId"
                            value="{{ request('template', $templates->first()->id ?? '') }}">

                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                            @forelse($templates as $template)
                                <div class="relative group cursor-pointer" onclick="selectTemplate('{{ $template->id }}')">
                                    <div id="card_{{ $template->id }}"
                                        class="border-4 rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1
                                                                                                                             {{ (request('template') == $template->id || $loop->first && !request('template')) ? 'border-blue-500 ring-2 ring-blue-200' : 'border-transparent' }}">
                                        <img src="{{ asset('storage/' . $template->background_image) }}"
                                            alt="{{ $template->name }}" class="w-full h-auto object-cover aspect-[2.125/3.375]">

                                        <!-- Overlay for selected state -->
                                        <div id="overlay_{{ $template->id }}"
                                            class="absolute inset-0 bg-blue-600 bg-opacity-20 hidden"></div>
                                    </div>

                                    <div class="mt-3 text-center">
                                        <p class="text-sm font-bold text-gray-700">{{ $template->name }}</p>
                                        <p class="text-xs text-gray-500">{{ ucfirst($template->type) }}</p>
                                    </div>

                                    <!-- Checkmark -->
                                    <div id="check_{{ $template->id }}"
                                        class="absolute top-3 right-3 bg-blue-500 text-white rounded-full p-1 shadow-md transform scale-0 transition-transform duration-200
                                                                                                                             {{ (request('template') == $template->id || $loop->first && !request('template')) ? 'scale-100' : '' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            @empty
                                <div
                                    class="col-span-full text-center py-8 text-gray-500 border-2 border-dashed border-gray-300 rounded-xl">
                                    <p>No templates found. Please contact administrator.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- layout Selection -->
                    <div class="mb-10">
                        <h3 class="text-lg font-bold text-gray-700 mb-4 border-l-4 border-orange-500 pl-3">2. Select Layout
                        </h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            <label
                                class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none border-blue-500 ring-2 ring-blue-500"
                                id="layout_single_container">
                                <input type="radio" name="layout" value="single" class="sr-only" checked
                                    onclick="selectLayout('single')">
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="block text-sm font-medium text-gray-900">Single Card</span>
                                        <span class="mt-1 flex items-center text-sm text-gray-500">One card per page (High
                                            Quality)</span>
                                    </span>
                                </span>
                                <svg class="h-5 w-5 text-blue-600" id="layout_single_check" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </label>

                            <label
                                class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none border-gray-300"
                                id="layout_dice_36_container">
                                <input type="radio" name="layout" value="dice_36" class="sr-only"
                                    onclick="selectLayout('dice_36')">
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="block text-sm font-medium text-gray-900">Dice 36 (6x6)</span>
                                        <span class="mt-1 flex items-center text-sm text-gray-500">36 cards per (376x562mm)
                                            page</span>
                                    </span>
                                </span>
                                <svg class="h-5 w-5 text-blue-600 hidden" id="layout_dice_36_check" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </label>

                            <label
                                class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none border-gray-300"
                                id="layout_dice_90_container">
                                <input type="radio" name="layout" value="dice_90" class="sr-only"
                                    onclick="selectLayout('dice_90')">
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="block text-sm font-medium text-gray-900">Dice 90 (10x9)</span>
                                        <span class="mt-1 flex items-center text-sm text-gray-500">90 cards per
                                            (1647x2298px) page</span>
                                    </span>
                                </span>
                                <svg class="h-5 w-5 text-blue-600 hidden" id="layout_dice_90_check" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </label>
                        </div>
                    </div>

                    <!-- 3. Quick Generate Section -->
                    <div class="mb-10">
                        <h3 class="text-lg font-bold text-gray-700 mb-4 border-l-4 border-red-500 pl-3">3. Quick Generate by
                            Student IDs (Optional)</h3>
                        <div class="bg-red-50 p-6 rounded-xl border border-red-100">
                            <label class="block text-sm font-medium text-red-700 mb-2">Paste Student IDs (Comma, Space or
                                Newline separated)</label>
                            <textarea name="student_id_list" rows="3"
                                class="w-full rounded-lg border-red-200 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 transition placeholder-red-300"
                                placeholder="Example: 250243, 250244, 250245"></textarea>
                            <p class="text-xs text-red-600 mt-2 italic">* This will prioritize these IDs over the selection
                                list below.</p>
                        </div>
                    </div>

                    <!-- 4. Filter Students -->
                    <div class="mb-10">
                        <h3 class="text-lg font-bold text-gray-700 mb-4 border-l-4 border-purple-500 pl-3">4. Filter
                            Students</h3>

                        <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Class</label>
                                    <select name="class" id="classFilter"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                                        <option value="">All Classes</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class }}" {{ request('class') == $class ? 'selected' : '' }}>
                                                {{ $class }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Section</label>
                                    <select name="section" id="sectionFilter"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                                        <option value="">All Sections</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section }}" {{ request('section') == $section ? 'selected' : '' }}>
                                                {{ $section }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Session</label>
                                    <select name="session" id="sessionFilter"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                                        <option value="">All Sessions</option>
                                        @foreach($sessions as $sess)
                                            <option value="{{ $sess }}" {{ request('session') == $sess ? 'selected' : '' }}>
                                                {{ $sess }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex items-end md:col-span-1">
                                    <button type="button" onclick="applyFilter()"
                                        class="w-full bg-gray-800 hover:bg-gray-900 text-white font-bold py-2.5 px-4 rounded-lg shadow transition duration-200">
                                        Apply Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Select Students -->
                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-4 border-l-4 border-green-500 pl-3">
                            <h3 class="text-lg font-bold text-gray-700">5. Select Students</h3>
                            <div class="space-x-2">
                                <button type="button" onclick="selectAll()"
                                    class="text-sm text-blue-600 hover:text-blue-800 font-medium bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded transition">Select
                                    All</button>
                                <button type="button" onclick="deselectAll()"
                                    class="text-sm text-gray-600 hover:text-gray-800 font-medium bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded transition">Deselect
                                    All</button>
                            </div>
                        </div>

                        <div
                            class="overflow-x-auto bg-white border border-gray-200 rounded-xl shadow-sm max-h-[500px] overflow-y-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 sticky top-0 z-10">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <input type="checkbox" id="masterCheckbox" onclick="toggleMasterCheckbox()"
                                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            ID</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Photo</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Name</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Class/Roll/Session</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Contact</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($students as $student)
                                        <tr class="hover:bg-blue-50 transition duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                                    class="student-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700">
                                                {{ $student->student_id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($student->photo)
                                                    <img src="{{ asset('storage/' . $student->photo) }}"
                                                        class="h-10 w-10 rounded-full object-cover border border-gray-200">
                                                @else
                                                    <div
                                                        class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-400 font-bold text-xs">
                                                        N/A</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $student->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <span
                                                    class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded-full">{{ $student->class }}</span>
                                                <span
                                                    class="ml-1 bg-gray-100 text-gray-800 text-xs px-2 py-0.5 rounded-full">Roll:
                                                    {{ $student->roll }}</span>
                                                <span
                                                    class="ml-1 bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full">{{ $student->session ?? '2024-2025' }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $student->contact_no }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                                No students found. Please try different filters.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit"
                            class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3 px-8 rounded-lg shadow-lg transform transition hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Generate ID Cards <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function selectTemplate(id) {
            // Reset all
            document.querySelectorAll('[id^="card_"]').forEach(el => {
                el.classList.remove('border-blue-500', 'ring-2', 'ring-blue-200');
                el.classList.add('border-transparent');
            });
            document.querySelectorAll('[id^="check_"]').forEach(el => {
                el.classList.remove('scale-100');
                el.classList.add('scale-0');
            });
            document.querySelectorAll('[id^="overlay_"]').forEach(el => {
                el.classList.add('hidden');
            });

            // Select clicked
            const card = document.getElementById('card_' + id);
            const check = document.getElementById('check_' + id);
            const overlay = document.getElementById('overlay_' + id);

            if (card) {
                card.classList.remove('border-transparent');
                card.classList.add('border-blue-500', 'ring-2', 'ring-blue-200');
            }
            if (check) {
                check.classList.remove('scale-0');
                check.classList.add('scale-100');
            }
            // overlay optional, keeping hidden for cleaner look or enable if desired

            document.getElementById('selectedTemplateId').value = id;
        }

        function applyFilter() {
            const classVal = document.getElementById('classFilter').value;
            const sectionVal = document.getElementById('sectionFilter').value;
            const sessionVal = document.getElementById('sessionFilter').value;
            const templateId = document.getElementById('selectedTemplateId').value;

            let url = new URL(window.location.href);
            if (classVal) url.searchParams.set('class', classVal);
            else url.searchParams.delete('class');

            if (sectionVal) url.searchParams.set('section', sectionVal);
            else url.searchParams.delete('section');

            if (sessionVal) url.searchParams.set('session', sessionVal);
            else url.searchParams.delete('session');

            if (templateId) url.searchParams.set('template', templateId); // Keep template selected

            const layoutVal = document.querySelector('input[name="layout"]:checked')?.value;
            if (layoutVal) url.searchParams.set('layout', layoutVal);

            window.location.href = url.toString();
        }

        function toggleMasterCheckbox() {
            const isChecked = document.getElementById('masterCheckbox').checked;
            const checkboxes = document.querySelectorAll('.student-checkbox');
            checkboxes.forEach(cb => cb.checked = isChecked);
        }

        function selectAll() {
            document.getElementById('masterCheckbox').checked = true;
            toggleMasterCheckbox();
        }

        function deselectAll() {
            document.getElementById('masterCheckbox').checked = false;
            toggleMasterCheckbox();
        }

        function selectLayout(layout) {
            // Reset all containers
            const layouts = ['single', 'dice_36', 'dice_90'];

            layouts.forEach(l => {
                const container = document.getElementById('layout_' + l + '_container');
                const check = document.getElementById('layout_' + l + '_check');

                if (container) {
                    container.classList.remove('border-blue-500', 'ring-2', 'ring-blue-500');
                    container.classList.add('border-gray-300');
                }
                if (check) {
                    check.classList.add('hidden');
                }
            });

            // Select active
            const activeContainer = document.getElementById('layout_' + layout + '_container');
            const activeCheck = document.getElementById('layout_' + layout + '_check');

            if (activeContainer) {
                activeContainer.classList.remove('border-gray-300');
                activeContainer.classList.add('border-blue-500', 'ring-2', 'ring-blue-500');
            }
            if (activeCheck) {
                activeCheck.classList.remove('hidden');
            }
        }

        // Initialize Selection on Load if preset
        document.addEventListener('DOMContentLoaded', () => {
            const currentTemplate = "{{ request('template', $templates->first()->id ?? '') }}";
            if (currentTemplate) {
                selectTemplate(currentTemplate);
            }

            const currentLayout = "{{ request('layout', 'single') }}";
            if (currentLayout) {
                selectLayout(currentLayout);
                const radio = document.querySelector(`input[name="layout"][value="${currentLayout}"]`);
                if (radio) radio.checked = true;
            }
        });
    </script>
@endsection