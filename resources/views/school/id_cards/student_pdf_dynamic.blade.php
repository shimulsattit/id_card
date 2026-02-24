<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        @php
            $settings = $template->settings ?? [];
            $hasSettings = !empty($settings);
            $isID2 = ($template->id == 2 || $template->id == 3 || stripos($template->name, 'ID-2') !== false);
            $isID5 = ($template->id == 5);
        @endphp

        @page {
            @if(isset($layout) && $layout === 'dice_90')
                size: 560mm 2298pt;
                margin: 23px 0 1mm 0;
            @elseif(isset($layout) && $layout === 'dice_36')
                size: 342mm 561.976mm;
                margin: 5mm 0;
            @else size: 55mm 86.753mm;
                margin: 0;
            @endif
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'SolaimanLipi', sans-serif;
            margin: 0;
            padding: 0;
        }

        .id-card {
            width: 55mm;
            height: 86.753mm;
            @if(isset($layout) && ($layout === 'dice_36' || $layout === 'dice_90'))
                float: left;
                @if($layout === 'dice_90')
                    margin: 0.75mm 0.5mm;
                @else margin: 1.5mm 1mm;
                @endif border: 0.1mm solid #eee;
            @endif position: relative;
            overflow: hidden;
        }

        .bg-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 55mm;
            height: 86.753mm;
            z-index: 0;
        }

        /* Photo */
        .photo-box {
            position: absolute;
            @if($hasSettings)
                top: {{ $settings['photo']['top'] ?? 15.2 }}mm;
                left: {{ $settings['photo']['left'] ?? 0 }}mm;
                width: 100%;
                @if(($settings['photo']['left'] ?? 0) == 0) text-align: center; @endif
            @else
                top: {{ $isID5 ? '20mm' : ($template->id == 4 ? '24.9mm' : ($isID2 ? '23mm' : '15.2mm')) }};
                left: 0;
                right: 0;
                text-align: center;
            @endif
            z-index: 1;
        }

        .photo {
            @if($hasSettings)
                width: {{ $settings['photo']['width'] ?? 27 }}mm;
                height: {{ $settings['photo']['width'] ?? 27 }}mm;
            @else
                width: {{ $template->id == 4 ? '23.7mm' : '27mm' }};
                height: {{ $template->id == 4 ? '22.9mm' : '27mm' }};
            @endif
            border: 0.85mm solid {{ $template->photo_border_color ?? '#000000' }};
            border-radius: 1mm;
            object-fit: cover;
            background: #eee;
            display: inline-block;
        }

        /* Name (Visible for all) */
        .name-box {
            display: block;
            position: absolute;
            @if($hasSettings)
                top: {{ $settings['name']['top'] ?? 45.3 }}mm;
                left: {{ $settings['name']['left'] ?? 0 }}mm;
                width: 100%;
                @if(($settings['name']['left'] ?? 0) == 0) text-align: center; @endif
            @else
                top: {{ $isID5 ? '50.5mm' : ($template->id == 4 ? '50.85mm' : ($template->id == 3 ? '52.5mm' : ($isID2 ? '54.5mm' : '45.3mm'))) }};
                left: 0;
                right: 0;
                text-align: center;
            @endif
            color: {{ $template->name_color ?? '#148bc9' }};
            z-index: 2;
        }

        .name {
            @if($hasSettings)
                font-size: {{ $settings['name']['fontSize'] ?? 12 }}pt;
            @else
                font-size: {{ $isID5 ? '11pt' : ($template->id == 3 ? '16px' : '12pt') }};
            @endif
            font-weight: bold;
            text-transform: uppercase;
            white-space: nowrap;
            overflow: hidden;
        }

        /* Details */
        .details-box {
            position: absolute;
            @if($hasSettings)
                top: {{ $settings['details']['top'] ?? 51 }}mm;
                left: {{ $settings['details']['left'] ?? 4 }}mm;
                font-size: {{ $settings['details']['fontSize'] ?? 9 }}pt;
            @else
                top: {{ $isID5 ? '56.5mm' : ($template->id == 4 ? '56.55mm' : ($isID2 ? '62mm' : '51mm')) }};
                left: {{ ($template->id == 4 || $template->id == 5) ? '3mm' : '4mm' }};
                font-size: {{ ($template->id == 4 || $template->id == 5) ? '8pt' : '9pt' }};
            @endif
            right: 2mm;
            color: {{ $template->data_color ?? '#000000' }};
            z-index: 1;
        }

        .sub-info {
            font-size: inherit;
            line-height: {{ ($template->id == 4 || $template->id == 5) ? '1.35' : '1.4' }};
            white-space: nowrap;
            font-weight: 600;
        }

        .label-col {
            width: {{ $isID5 ? '14mm' : '13mm' }};
            vertical-align: top;
        }

        /* Signature */
        .sig-box {
            position: absolute;
            @if($hasSettings)
                top: {{ $settings['signature']['top'] ?? 75 }}mm;
                left: {{ $settings['signature']['left'] ?? 35 }}mm;
            @else
                top: 75mm;
                left: 35mm;
            @endif
            z-index: 3;
        }

        .signature-img {
            @if($hasSettings)
                width: {{ $settings['signature']['width'] ?? 18 }}mm;
            @else
                width: 18mm;
            @endif
            height: auto;
        }
    </style>
</head>

<body>

    @php
        $bgUrl = 'file://' . public_path('storage/' . $template->background_image);
    @endphp

    @php
        $cardsPerPage = 1;
        $cardsPerRow = 1;
        if (isset($layout)) {
            if ($layout === 'dice_36') {
                $cardsPerPage = 36;
                $cardsPerRow = 6;
            } elseif ($layout === 'dice_90') {
                $cardsPerPage = 90;
                $cardsPerRow = 10;
            }
        }
        $rowsPerPage = $cardsPerRow > 0 ? ($cardsPerPage / $cardsPerRow) : 1;
    @endphp

    @foreach($students->chunk($cardsPerRow) as $row)
        @foreach($row as $student)
            <div class="id-card">
                {{-- Background image (img tag = better quality in DomPDF) --}}
                <img src="{{ public_path('storage/' . $template->background_image) }}" class="bg-img">

                {{-- Photo --}}
                <div class="photo-box">
                    @if($student->photo)
                        <img src="{{ public_path('storage/' . $student->photo) }}" class="photo">
                    @else
                        <div class="photo"></div>
                    @endif
                </div>

                {{-- Name --}}
                <div class="name-box">
                    @php
                        $computedNameSize = ($template->id == 3) ? '16px' : '12pt';
                        if ($isID5) {
                            $len = strlen($student->name);
                            if ($len > 25) $computedNameSize = '8pt';
                            elseif ($len > 20) $computedNameSize = '9pt';
                            elseif ($len > 15) $computedNameSize = '10pt';
                            else $computedNameSize = '11pt';
                        }
                    @endphp
                    <div class="name" style="font-size: {{ $computedNameSize }};">{{ $student->name }}</div>
                </div>

                {{-- Details --}}
                <div class="details-box">
                    <table style="width:100%; border-collapse:collapse;">
                        @if($template->id == 4)
                            <tr>
                                <td class="sub-info label-col">F.Name</td>
                                <td class="sub-info">: {{ $student->father_name }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info">M. Name</td>
                                <td class="sub-info">: {{ $student->mother_name }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info">Class</td>
                                <td class="sub-info">: {{ $student->class }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info">Roll</td>
                                <td class="sub-info">: {{ $student->roll }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info">DOB</td>
                                <td class="sub-info">: {{ $student->dob }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info">ID No</td>
                                <td class="sub-info">: {{ $student->student_id }}</td>
                            </tr>
                        @elseif($template->id == 5)
                            @php
                                $fNameLen = strlen($student->father_name);
                                $mNameLen = strlen($student->mother_name);
                                $fFontSize = $fNameLen > 25 ? '6.5pt' : ($fNameLen > 20 ? '7.2pt' : '8pt');
                                $mFontSize = $mNameLen > 25 ? '6.5pt' : ($mNameLen > 20 ? '7.2pt' : '8pt');
                            @endphp
                            <tr>
                                <td class="sub-info label-col" style="width: 14mm;">F Name</td>
                                <td class="sub-info" style="font-size: {{ $fFontSize }};">: {{ $student->father_name }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info">M Name</td>
                                <td class="sub-info" style="font-size: {{ $mFontSize }};">: {{ $student->mother_name }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info">Reg.No</td>
                                <td class="sub-info">: {{ $student->registration_no }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info">Roll</td>
                                <td class="sub-info">: {{ $student->roll }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info">Session</td>
                                <td class="sub-info">: {{ $student->session }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info">BG</td>
                                <td class="sub-info">: {{ $student->blood_group ?? 'N/A' }}</td>
                            </tr>
                        @elseif($template->id == 3)

                            <tr>
                                <td class="sub-info label-col" style="width: 15mm;">ID</td>
                                <td class="sub-info">: {{ $student->student_id }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info" style="width: 15mm;">Blood Group</td>
                                <td class="sub-info">: {{ $student->blood_group ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info" style="width: 15mm;">Contact No</td>
                                <td class="sub-info">: {{ $student->contact_no }}</td>
                            </tr>
                        @elseif($template->id == 1)
                            <tr>
                                <td class="sub-info label-col">ID NO</td>
                                <td class="sub-info">: {{ $student->student_id }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info">Class</td>
                                <td class="sub-info">: {{ $student->class }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info">Roll</td>
                                <td class="sub-info">: {{ $student->roll }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info">Mobile</td>
                                <td class="sub-info">: {{ $student->contact_no }}</td>
                            </tr>
                        @else
                            <tr>
                                <td class="sub-info label-col">ID NO</td>
                                <td class="sub-info">: {{ $student->student_id }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info">Class</td>
                                <td class="sub-info">: {{ $student->class }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info">Roll</td>
                                <td class="sub-info">: {{ $student->roll }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info">Mobile</td>
                                <td class="sub-info">: {{ $student->contact_no }}</td>
                            </tr>
                        @endif
                    </table>
                </div>

                {{-- Signature --}}
                @if($template->school && $template->school->signature)
                    <div class="sig-box">
                        <img src="{{ public_path('storage/' . $template->school->signature) }}" class="signature-img">
                    </div>
                @endif
            </div>
        @endforeach

        @if($loop->iteration % $rowsPerPage == 0 && !$loop->last)
            <div style="page-break-after: always; clear: both;"></div>
        @else
            <div style="clear: both;"></div>
        @endif
    @endforeach

</body>

</html>