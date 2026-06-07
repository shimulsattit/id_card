<!DOCTYPE html>
<html>

<head>
    <title>ID Card Preview</title>
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

        .id-card-container {
            width: 155px;
            height: 244px;
            position: relative;
            background-size: cover;
            background-position: center;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
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

            /* Custom paper: 376mm × 561.976mm */
            @page {
                size: 376mm 561.976mm;
                margin: 5mm;
            }

            .cards-grid {
                display: block;
                padding: 0;
                gap: 0;
            }

            .id-card-container {
                width: 58mm;
                height: 91mm;
                margin: 1mm;
                float: left;
                box-shadow: none;
                border: 0.5px solid #ccc;
                page-break-inside: avoid;
            }
        }

        .dynamic-text {
            color:
                {{ $template->text_color ?? '#000000' }}
            ;
        }
    </style>
</head>

<body class="bg-gray-100 p-4">

    {{-- Toolbar --}}
    <div class="mb-4 no-print flex flex-wrap items-center justify-center gap-4">
        {{-- Layout Switcher --}}
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

        {{-- Print ID Card → opens PDF in browser (new tab) --}}
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

        <form action="{{ route('school.idcards.students.download_word') }}" method="POST" class="inline-block">
            @csrf
            <input type="hidden" name="template" value="{{ $template->id }}">
            <input type="hidden" name="layout" value="{{ $layout ?? 'single' }}">
            @foreach($students as $student)
                <input type="hidden" name="student_ids[]" value="{{ $student->id }}">
            @endforeach
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold px-6 py-2 rounded shadow">
                Download as Docx
            </button>
        </form>

        <button onclick="window.close()"
            class="bg-gray-500 hover:bg-gray-600 text-white font-bold px-6 py-2 rounded shadow">
            Close
        </button>
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

    @php
        $settings = $template->settings ?? [];
        $hasSettings = !empty($settings);
        $isID2 = ($template->id == 2 || $template->id == 3 || stripos($template->name, 'ID-2') !== false);
        $isID5 = ($template->id == 5);
        $isID6 = ($template->id == 6);
        $isID7 = ($template->id == 7);
        $isID8 = ($template->id == 8);
        $isID9 = ($template->id == 9);
        $isID10 = ($template->id == 10);

        $getStyle = function ($key) use ($settings, $hasSettings, $isID2) {
            if ($hasSettings && isset($settings[$key])) {
                $s = $settings[$key];
                $style = "top: {$s['top']}mm; left: {$s['left']}mm;";
                if (isset($s['width']))
                    $style .= " width: {$s['width']}mm;";
                if (isset($s['fontSize']))
                    $style .= " font-size: " . ($s['fontSize'] * 1.33) . "px;";
                if (($s['left'] ?? 0) == 0)
                    $style .= " width: 100%; text-align: center;";
                return $style;
            }
            return ""; // Fallback to classes
        };
    @endphp

    {{-- All cards in one grid --}}
    <div class="cards-grid">
        @foreach($students as $student)

            <div class="id-card-container"
                style="background-image: url('{{ asset('storage/' . $template->background_image) }}');">

                {{-- Photo --}}
                <div class="absolute {{ $hasSettings ? '' : ($isID10 ? 'top-[60px] left-0 right-0' : ($isID9 ? 'top-[105px] left-0 right-0' : ($isID8 ? 'top-[84px] left-0 right-0' : ($isID7 ? 'top-[50px] left-[70px]' : ($isID6 ? 'top-[68px]' : ($isID5 ? 'top-[57px]' : ($isID2 ? 'top-[64px]' : ($template->id == 4 ? 'top-[70px]' : 'top-[42px]')))))))) }} text-center"
                    style="{{ $getStyle('photo') }}">
                    @if($student->photo)
                        <img src="{{ asset('storage/' . $student->photo) }}" class="mx-auto object-cover"
                            style="width: {{ $hasSettings ? ($settings['photo']['width'] ?? 20) : ($isID10 ? '41' : ($isID9 ? '75' : ($isID8 ? '74' : ($isID7 ? '75' : ($isID6 ? '85' : ($template->id == 4 ? '67' : ($isID5 ? '86.9' : 56))))))) }}px; height: {{ $hasSettings ? ($settings['photo']['width'] ?? 20) : ($isID10 ? '41' : ($isID9 ? '90' : ($isID8 ? '85' : ($isID7 ? '75' : ($isID6 ? '93' : ($template->id == 4 ? '65' : ($isID5 ? '94.5' : 56))))))) }}px; border: {{ ($isID8 || $isID9) ? '2px' : '2.5px' }} solid {{ $isID7 ? '#f15b2a' : ($template->photo_border_color ?? '#000000') }}; border-radius: {{ $isID7 ? '50%' : '4px' }};">
                    @else
                        <div class="mx-auto bg-gray-200"
                            style="width: {{ $hasSettings ? ($settings['photo']['width'] ?? 20) : ($isID10 ? '41' : ($isID9 ? '75' : ($isID8 ? '74' : ($isID7 ? '75' : ($isID6 ? '85' : ($template->id == 4 ? '67' : ($isID5 ? '86.9' : 56))))))) }}px; height: {{ $hasSettings ? ($settings['photo']['width'] ?? 20) : ($isID10 ? '41' : ($isID9 ? '90' : ($isID8 ? '85' : ($isID7 ? '75' : ($isID6 ? '93' : ($template->id == 4 ? '65' : ($isID5 ? '94.5' : 56))))))) }}px; border: {{ ($isID8 || $isID9) ? '2px' : '2.5px' }} solid {{ $isID7 ? '#f15b2a' : ($template->photo_border_color ?? '#000000') }}; border-radius: {{ $isID7 ? '50%' : '4px' }};">
                        </div>
                    @endif
                </div>

                {{-- Vertical ID for 9 --}}
                @if($isID9)
                    <div class="absolute top-[148px] px-[10px] w-full flex justify-end pr-[5px]">
                        <div class="font-bold text-[#f16522] whitespace-nowrap" style="transform: rotate(-90deg); font-size: 13px; height: 110px; display: flex; align-items: center;">
                            {{ $student->student_id }}
                        </div>
                    </div>
                @endif

                {{-- Name (Visible for all now) --}}
                <div class="absolute {{ $hasSettings ? '' : ($isID9 ? 'top-[152px] left-0 right-0' : ($isID8 ? 'top-[139px] left-0 right-0' : ($isID6 ? 'top-[137px] left-0 right-0' : ($isID5 ? 'top-[133px] left-0 right-0' : ($isID2 ? ($template->id == 4 ? 'top-[134px] left-0 right-0 py-[2px]' : ($template->id == 3 ? 'top-[148px]' : 'top-[148px]') . ' left-[28px] right-[28px]') : 'top-[122px] left-0 right-0'))))) }} text-center leading-tight"
                    style="{{ $getStyle('name') }} @if($isID7) display: none; @endif">
                    @php
                        $previewNameSize = $isID8 ? '9px' : '12px';
                        if (!$hasSettings) {
                            if ($isID5) {
                                $len = strlen($student->name);
                                if ($len > 25)
                                    $previewNameSize = '8px';
                                elseif ($len > 20)
                                    $previewNameSize = '9px';
                                elseif ($len > 15)
                                    $previewNameSize = '10px';
                                else
                                    $previewNameSize = '12px';
                            } elseif ($isID8) {
                                $len = mb_strlen($student->name);
                                if ($len > 22)
                                    $previewNameSize = '7px';
                                elseif ($len > 18)
                                    $previewNameSize = '8px';
                                elseif ($len > 14)
                                    $previewNameSize = '9px';
                                else
                                    $previewNameSize = '11px';
                            } elseif ($isID6) {
                                $len = mb_strlen($student->name);
                                if ($len > 25)
                                    $previewNameSize = '9px';
                                elseif ($len > 20)
                                    $previewNameSize = '10.5px';
                                elseif ($len > 15)
                                    $previewNameSize = '12px';
                                else
                                    $previewNameSize = '13.5px';
                            } elseif ($template->id == 4) {
                                $previewNameSize = '11px';
                            } elseif ($template->id == 3) {
                                $previewNameSize = '16px';
                            } elseif ($isID8) {
                                $previewNameSize = '18px';
                            } elseif ($isID2) {
                                $previewNameSize = '10px';
                            } elseif ($isID9) {
                                $len = mb_strlen($student->name);
                                if ($len > 24) $previewNameSize = '11px';
                                elseif ($len > 18) $previewNameSize = '12px';
                                else $previewNameSize = '13px';
                            } elseif ($isID10) {
                                $len = mb_strlen($student->name);
                                if ($len > 24) $previewNameSize = '10px';
                                elseif ($len > 18) $previewNameSize = '11px';
                                else $previewNameSize = '12px';
                            }
                        }
                    @endphp
                    <h2 class="font-bold uppercase m-0"
                        style="color: {{ ($isID9 || $isID10) ? ($isID9 ? '#a70063' : ($template->name_color ?? '#e62128')) : (($isID2 && !$hasSettings && $template->id != 3) ? '#000000' : ($template->name_color ?? '#148bc9')) }}; font-size: {{ $hasSettings ? '' : $previewNameSize }}; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; padding: 0 4px; text-transform: uppercase; font-weight: bold; @if($isID6) font-family: 'Franklin Gothic Demi', 'Franklin Gothic', sans-serif; @endif">
                        {{ ($isID10 || $isID6) ? strtoupper($student->name) : $student->name }}
                    </h2>
                </div>

                {{-- Details --}}
                <div class="absolute {{ $hasSettings ? '' : ($isID10 ? 'top-[123px] left-[8px] right-0' : ($isID9 ? 'top-[223px] left-[13px] right-0' : ($isID8 ? 'top-[158px] left-[15px] right-1' : ($isID7 ? 'top-[125px] left-[20px] right-1' : ($isID6 ? 'top-[157px] left-[10px] right-1' : ($isID5 ? 'top-[146px] left-[4px] right-1' : ($isID2 ? ($template->id == 4 ? 'top-[135px] left-[8.5px] right-1' : 'top-[168px] left-4 right-1') : 'top-[142px] left-4 right-1'))))))) }} text-left"
                    style="{{ $getStyle('details') }}">
                    <table
                        class="w-full font-bold {{ ($template->id == 4 || $template->id == 5) ? 'leading-tight' : 'leading-tight' }}"
                        style="color: {{ $template->data_color ?? '#000000' }}; font-size: {{ $hasSettings ? '' : ($isID10 ? '9px' : ($isID9 ? '7px' : ($isID8 ? '11px' : ($isID5 ? '8.5px' : ($template->id == 4 ? '9px' : ($template->id == 3 ? '10px' : ($isID2 ? '11px' : '12px'))))))) }};">

                        @if($template->id == 4)
                            <tr>
                                <td class="align-top w-[48px] whitespace-nowrap">F.Name</td>
                                <td class="align-top">: {{ $student->father_name ?? 'Rahim Uddin' }}</td>
                            </tr>
                            <tr>
                                <td class="align-top w-[48px] whitespace-nowrap">M. Name</td>
                                <td class="align-top">: {{ $student->mother_name ?? 'Sultana Begum' }}</td>
                            </tr>
                            <tr>
                                <td class="align-top w-[48px] whitespace-nowrap">Class</td>
                                <td class="align-top">: {{ $student->class ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="align-top w-[48px] whitespace-nowrap">Roll</td>
                                <td class="align-top">: {{ $student->roll ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="align-top w-[48px] whitespace-nowrap">DOB</td>
                                <td class="align-top">: {{ $student->dob ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="align-top w-[48px] whitespace-nowrap">ID No</td>
                                <td class="align-top">: {{ $student->student_id ?? 'N/A' }}</td>
                            </tr>
                        @elseif($template->id == 5)
                            @php
                                $fNameLen = strlen($student->father_name ?? '');
                                $mNameLen = strlen($student->mother_name ?? '');
                                $fFontSize = $fNameLen > 25 ? '6px' : ($fNameLen > 20 ? '6.8px' : '7.5px');
                                $mFontSize = $mNameLen > 25 ? '6px' : ($mNameLen > 20 ? '6.8px' : '7.5px');
                            @endphp
                            <tr>
                                <td class="align-top w-[47px] whitespace-nowrap">F Name</td>
                                <td class="align-top" style="font-size: {{ $fFontSize }};">:
                                    {{ $student->father_name ?? 'N/A' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="align-top w-[47px] whitespace-nowrap">M Name</td>
                                <td class="align-top" style="font-size: {{ $mFontSize }};">:
                                    {{ $student->mother_name ?? 'N/A' }}
                                </td>
                            </tr>
                            <tr style="font-size: 7.5px;">
                                <td class="align-top w-[47px] whitespace-nowrap">Reg.No</td>
                                <td class="align-top">: {{ $student->registration_no ?? 'N/A' }}</td>
                            </tr>
                            <tr style="font-size: 7.5px;">
                                <td class="align-top w-[47px] whitespace-nowrap">Roll</td>
                                <td class="align-top">: {{ $student->roll ?? 'N/A' }}</td>
                            </tr>
                            @if($student->blood_group)
                                <tr style="font-size: 7.5px;">
                                    <td class="align-top w-[47px] whitespace-nowrap">BG</td>
                                    <td class="align-top">: {{ $student->blood_group }}</td>
                                </tr>
                            @endif
                            <tr style="font-size: 7.5px;">
                            <tr style="font-size: 7.5px;">
                                <td class="align-top w-[47px] whitespace-nowrap">Valid up to</td>
                                <td class="align-top">: <span style="color: red;">2030-2031 (Session)</span></td>
                            </tr>
                        @elseif($template->id == 3)

                            <tr>
                                <td class="align-top w-[48px] whitespace-nowrap">ID</td>
                                <td class="align-top whitespace-nowrap">: {{ $student->student_id ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="align-top w-[48px] whitespace-nowrap">Blood Group</td>
                                <td class="align-top whitespace-nowrap">: {{ $student->blood_group ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="align-top w-[48px] whitespace-nowrap">Contact No</td>
                                <td class="align-top whitespace-nowrap">: {{ $student->contact_no }}</td>
                            </tr>
                        @elseif($isID6)
                            <tr>
                                <td class="align-top w-[55px] whitespace-nowrap">Father</td>
                                <td class="align-top">: {{ $student->father_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="align-top">ID No</td>
                                <td class="align-top">: {{ $student->student_id ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="align-top">Class</td>
                                <td class="align-top">: {{ $student->class ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="align-top">Session</td>
                                <td class="align-top">: 2026</td>
                            </tr>
                            <tr style="font-size: 11.5px;">
                                <td class="align-top">Emg. Con</td>
                                <td class="align-top">: 01622661353</td>
                            </tr>
                        @elseif($template->id == 1)
                            <tr>
                                <td class="align-top w-[60px]">ID NO</td>
                                <td class="align-top whitespace-nowrap">: {{ $student->student_id ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="align-top">Class</td>
                                <td class="align-top whitespace-nowrap">: {{ $student->class ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="align-top">Roll</td>
                                <td class="align-top whitespace-nowrap">: {{ $student->roll ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="align-top">Mobile</td>
                                <td class="align-top whitespace-nowrap">: {{ $student->contact_no }}</td>
                            </tr>
                        @elseif($isID7)
                            <tr style="line-height: .6;">
                                <td class="align-top whitespace-nowrap" style="font-size: 5.7pt; font-weight: normal;">Name:</td>
                            </tr>
                            <tr style="line-height: .8;">
                                <td class="align-top whitespace-nowrap" style="font-size: 10pt; color: #27265f; font-weight: bold;">
                                    {{ $student->name }}</td>
                            </tr>
                            <tr style="line-height: .6;">
                                <td class="align-top whitespace-nowrap" style="font-size: 5.7pt; font-weight: normal;">ID No:</td>
                            </tr>
                            <tr style="line-height: .8;">
                                <td class="align-top whitespace-nowrap" style="font-size: 10pt; color: #27265f; font-weight: bold;">
                                    {{ $student->student_id }}</td>
                            </tr>
                            <tr style="line-height: .6;">
                                <td class="align-top whitespace-nowrap" style="font-size: 5.7pt; font-weight: normal;">CLASS:</td>
                            </tr>
                            <tr style="line-height: .8;">
                                <td class="align-top whitespace-nowrap" style="font-size: 10pt; color: #27265f; font-weight: bold;">
                                    {{ $student->class }}</td>
                            </tr>
                            <tr style="line-height: .6;">
                                <td class="align-top whitespace-nowrap" style="font-size: 5.7pt; font-weight: normal;">BLOOD GROUP:</td>
                            </tr>
                            <tr style="line-height: .8;">
                                <td class="align-top whitespace-nowrap" style="font-size: 10pt; color: #27265f; font-weight: bold;">
                                    @if($student->blood_group)
                                        {{ $student->getBloodGroup($student->blood_group) }} ({{ $student->blood_group }})
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr style="line-height: .6;">
                                <td class="align-top whitespace-nowrap" style="font-size: 5.7pt; font-weight: normal;">MOBILE NO:</td>
                            </tr>
                            <tr style="line-height: .8;">
                                <td class="align-top whitespace-nowrap" style="font-size: 10pt; color: #27265f; font-weight: bold;">
                                    {{ $student->contact_no }}</td>
                            </tr>
                        @elseif($isID8)
                            <tr style="line-height: 1.2;">
                                <td class="align-top w-[70px] whitespace-nowrap">Student ID</td>
                                <td class="align-top">: {{ $student->student_id ?? 'N/A' }}</td>
                            </tr>
                            <tr style="line-height: 1.2; color: red;">
                                <td class="align-top w-[70px] whitespace-nowrap">Blood Group</td>
                                <td class="align-top">: {{ $student->blood_group ?? 'N/A' }}</td>
                            </tr>
                            <tr style="line-height: 1.2;">
                                <td class="align-top w-[70px] whitespace-nowrap">Guardian Mobile</td>
                                <td class="align-top">: {{ $student->contact_no }}</td>
                            </tr>
                        @elseif($isID9)
                            <tr style="color: #213d78;">
                                <td class="align-top w-[50px] whitespace-nowrap text-[11px] text-left">FATHER</td>
                                <td class="align-top text-[11px] text-left">: {{ $student->father_name }}</td>
                            </tr>
                            <tr style="color: #213d78;">
                                <td class="align-top w-[50px] whitespace-nowrap text-[11px] text-left">MOTHER</td>
                                <td class="align-top text-[11px] text-left">: {{ $student->mother_name }}</td>
                            </tr>
                            <tr style="color: #213d78;">
                                <td class="align-top w-[50px] whitespace-nowrap text-[11px] text-left">MOBILE</td>
                                <td class="align-top text-[11px] text-left">: {{ $student->contact_no }}</td>
                            </tr>
                        @elseif($isID10)
                            <tr>
                                <td class="align-top w-[48px] whitespace-nowrap">F. Name</td>
                                <td class="align-top">: {{ $student->father_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="align-top w-[48px] whitespace-nowrap">M. Name</td>
                                <td class="align-top">: {{ $student->mother_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="align-top w-[48px] whitespace-nowrap">Class</td>
                                <td class="align-top">: {{ $student->class ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="align-top w-[48px] whitespace-nowrap">Roll</td>
                                <td class="align-top">: {{ $student->roll ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="align-top w-[48px] whitespace-nowrap">DOB</td>
                                <td class="align-top">: {{ $student->dob ? \Carbon\Carbon::parse($student->dob)->format('d/m/Y') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="align-top w-[48px] whitespace-nowrap">ID No</td>
                                <td class="align-top">: {{ $student->student_id ?? 'N/A' }}</td>
                            </tr>
                        @elseif($isID2 && !$hasSettings)
                            <tr>
                                <td class="align-top w-[38px]">F Name</td>
                                <td class="align-top whitespace-nowrap">: father name</td>
                            </tr>
                            <tr>
                                <td class="align-top">M Name</td>
                                <td class="align-top whitespace-nowrap">: mother name</td>
                            </tr>
                            <tr>
                                <td class="align-top">Roll No</td>
                                <td class="align-top">: 101</td>
                            </tr>
                            <tr>
                                <td class="align-top">Session</td>
                                <td class="align-top">: 2024-25</td>
                            </tr>
                        @else
                            <tr>
                                <td class="align-top w-[40px]">ID NO</td>
                                <td class="align-top whitespace-nowrap">: {{ $student->student_id ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="align-top">Class</td>
                                <td class="align-top whitespace-nowrap">: {{ $student->class }}</td>
                            </tr>
                            <tr>
                                <td class="align-top">Roll</td>
                                <td class="align-top whitespace-nowrap">: {{ $student->roll }}</td>
                            </tr>
                            <tr>
                                <td class="align-top">Mobile</td>
                                <td class="align-top whitespace-nowrap">: {{ $student->contact_no }}</td>
                            </tr>
                        @endif
                    </table>
                </div>

                {{-- Signature --}}
                @if($template->school && $template->school->signature)
                    <div class="absolute" style="{{ $getStyle('signature') }}">
                        <img src="{{ asset('storage/' . $template->school->signature) }}" style="width: 100%;">
                    </div>
                @endif
            </div>

        @endforeach
    </div>

</body>

</html>