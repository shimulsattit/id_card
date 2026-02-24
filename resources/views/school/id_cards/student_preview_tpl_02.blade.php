<!DOCTYPE html>
<html>

<head>
    <title>ID Card Preview - STD ID-2</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .cards-grid {
            display: grid;
            @if(($layout ?? '') === 'dice_90')
                grid-template-columns: repeat(10, 1fr);
            @elseif(($layout ?? '') === 'dice_36')
                grid-template-columns: repeat(6, 1fr);
            @else grid-template-columns: repeat(auto-fill, 160px);
            @endif justify-content: center;
            padding: 10px;
            gap: 10px;
            width: fit-content;
            margin: 0 auto;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                margin: 0;
                padding: 0;
            }

            @page {
                size: 376mm 561.976mm;
                margin: 5mm;
            }

            .cards-grid {
                display: block;
                padding: 0;
                gap: 0;
            }
        }
    </style>
</head>

<body class="bg-gray-100 p-4">

    {{-- Toolbar --}}
    <div class="mb-4 no-print flex flex-wrap items-center justify-center gap-4">
        <div class="flex items-center space-x-2 bg-white p-1 rounded-lg shadow-sm border border-gray-200">
            <button onclick="switchLayout('single')"
                class="px-4 py-1.5 rounded-md text-sm font-bold transition {{ ($layout ?? 'single') === 'single' ? 'bg-blue-600 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">
                Single Card
            </button>
            <button onclick="switchLayout('dice_36')"
                class="px-4 py-1.5 rounded-md text-sm font-bold transition {{ ($layout ?? '') === 'dice_36' ? 'bg-blue-600 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">
                Dice 36
            </button>
            <button onclick="switchLayout('dice_90')"
                class="px-4 py-1.5 rounded-md text-sm font-bold transition {{ ($layout ?? '') === 'dice_90' ? 'bg-blue-600 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">
                Dice 90
            </button>
        </div>

        <div class="h-6 w-px bg-gray-300"></div>

        <form action="{{ route('school.idcards.students.download') }}" method="POST" target="_blank"
            class="inline-block">
            @csrf
            <input type="hidden" name="template" value="{{ $template->id }}">
            <input type="hidden" name="layout" value="{{ $layout ?? 'single' }}">
            @foreach($students as $student)
                <input type="hidden" name="student_ids[]" value="{{ $student->id }}">
            @endforeach
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2 rounded shadow">
                🖨️ Print ID Card
            </button>
        </form>

        <button onclick="window.close()"
            class="bg-gray-500 hover:bg-gray-600 text-white font-bold px-6 py-2 rounded shadow">
            Close
        </button>
    </div>

    @php
        $bgUrl = asset('storage/' . $template->background_image);
        $defaults = [
            'photo' => ['top' => 97, 'left' => 42, 'width' => 71, 'height' => 71], // approx conversion for px preview
            'name' => ['top' => 152, 'left' => 10, 'fontSize' => 10],
            'details' => ['top' => 168, 'left' => 16, 'fontSize' => 8],
            'signature' => ['top' => 205, 'left' => 90, 'width' => 50],
        ];
        $settings = array_merge($defaults, $template->settings ?? []);

        // Function to convert mm to px for preview (approx 1mm = 3.78px for 155x244 display)
        $mmToPx = 2.68; // adjusted for 155mm card container
    @endphp

    <div class="cards-grid">
        @foreach($students as $student)
            <div class="relative shadow-xl overflow-hidden rounded-lg user-select-none print:shadow-none bg-white mb-4"
                style="width: 155px; height: 244px; background-image: url('{{ $bgUrl }}'); background-size: cover; background-position: center; border: 1px solid #ddd; page-break-inside: avoid;">

                <!-- Photo -->
                <div class="absolute" style="top: {{ $settings['photo']['top'] * $mmToPx }}px; 
                               left: {{ $settings['photo']['left'] * $mmToPx }}px; 
                               width: {{ $settings['photo']['width'] * $mmToPx }}px; 
                               height: {{ $settings['photo']['height'] * $mmToPx }}px;">
                    <div class="w-full h-full rounded-[3px] bg-gray-100 flex items-center justify-center overflow-hidden"
                        style="border: 1.5px solid {{ $template->photo_border_color ?? '#148bc9' }};">
                        @if($student->photo)
                            <img src="{{ asset('storage/' . $student->photo) }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-[8px] opacity-50">PHOTO</span>
                        @endif
                    </div>
                </div>

                <!-- Name Box -->
                <div class="absolute text-center" style="top: {{ $settings['name']['top'] * $mmToPx }}px; 
                               left: {{ $settings['name']['left'] * $mmToPx }}px; 
                               right: 4px;">
                    <h2 class="font-bold leading-tight uppercase truncate"
                        style="font-size: {{ $settings['name']['fontSize'] * 0.9 }}pt; color: {{ $template->name_color ?? '#000000' }};">
                        {{ $student->name }}
                    </h2>
                </div>

                <!-- Details -->
                <div class="absolute text-left" style="top: {{ $settings['details']['top'] * $mmToPx }}px; 
                               left: {{ $settings['details']['left'] * $mmToPx }}px; 
                               right: 4px;">
                    <table class="w-full font-bold leading-tight"
                        style="font-size: {{ $settings['details']['fontSize'] * 0.9 }}pt; color: {{ $template->data_color ?? '#ffffff' }};">
                        <tr>
                            <td class="align-top w-[38px]">F Name</td>
                            <td class="align-top truncate">: {{ $student->father_name }}</td>
                        </tr>
                        <tr>
                            <td class="align-top">M Name</td>
                            <td class="align-top truncate">: {{ $student->mother_name }}</td>
                        </tr>
                        <tr>
                            <td class="align-top">Roll No</td>
                            <td class="align-top">: {{ $student->roll }}</td>
                        </tr>
                        <tr>
                            <td class="align-top">Session</td>
                            <td class="align-top">: {{ $student->session ?? '2024-2025' }}</td>
                        </tr>
                        <tr>
                            <td class="align-top">BG</td>
                            <td class="align-top">: {{ $student->blood_group ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="align-top">Mobile</td>
                            <td class="align-top">: {{ $student->contact_no }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Signature -->
                <div class="absolute text-center" style="top: {{ $settings['signature']['top'] * $mmToPx }}px; 
                               left: {{ $settings['signature']['left'] * $mmToPx }}px; 
                               width: {{ $settings['signature']['width'] * $mmToPx }}px;">
                    @if($student->signature)
                        <img src="{{ asset('storage/' . $student->signature) }}" class="h-[16px] max-w-full mx-auto">
                    @endif
                    <div class="text-[6px] border-t mt-[1px] pt-[1px] px-1"
                        style="color: {{ $template->data_color ?? '#ffffff' }}; border-color: {{ $template->data_color ?? '#ffffff' }};">
                        Head of the Department
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        function switchLayout(layout) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = window.location.href;

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);

            const tmpl = document.createElement('input');
            tmpl.type = 'hidden';
            tmpl.name = 'template';
            tmpl.value = '{{ $template->id }}';
            form.appendChild(tmpl);

            const lay = document.createElement('input');
            lay.type = 'hidden';
            lay.name = 'layout';
            lay.value = layout;
            form.appendChild(lay);

            @foreach($students as $student)
                const s{{ $student->id }} = document.createElement('input');
                s{{ $student->id }}.type = 'hidden';
                s{{ $student->id }}.name = 'student_ids[]';
                s{{ $student->id }}.value = '{{ $student->id }}';
                form.appendChild(s{{ $student->id }});
            @endforeach

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>

</html>