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
            $isID6 = ($template->id == 6);
            $isID7 = ($template->id == 7);
            $isID8 = ($template->id == 8);
            $isID9 = ($template->id == 9);
            $isID10 = ($template->id == 10);
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
                top:
                    {{ $settings['photo']['top'] ?? 15.2 }}
                    mm;
                left:
                    {{ $settings['photo']['left'] ?? 0 }}
                    mm;
                width: 100%;
                @if(($settings['photo']['left'] ?? 0) == 0)
                    text-align: center;
            @endif @else top:
                {{ $isID10 ? '20.0mm' : ($isID9 ? '27.6mm' : ($isID8 ? '27.0mm' : ($isID7 ? '18mm' : ($isID6 ? '22.1mm' : ($isID5 ? '19.2mm' : ($template->id == 4 ? '24.9mm' : ($isID2 ? '23mm' : '15.2mm'))))))) }}
                ;
                @if($isID7)
                    left: 24.8mm;
                @elseif($isID9)
                    left: 0;
                    right: 0;
                    text-align: center;
                @else left: 0;
                    right: 0;
                    text-align: center;
            @endif @endif z-index: 1;
        }

        .photo {
            @if($hasSettings)
                width:
                    {{ $settings['photo']['width'] ?? 27 }}
                    mm;
                height:
                    {{ $settings['photo']['width'] ?? 27 }}
                    mm;
            @else width:
                {{ $isID10 ? '22.5mm' : ($isID9 ? '19mm' : ($isID8 ? '19.7mm' : ($isID6 ? '22.6mm' : ($template->id == 4 ? '23.7mm' : ($isID5 ? '23mm' : '27mm'))))) }}
                ;
                height:
                    {{ $isID10 ? '22.5mm' : ($isID9 ? '23mm' : ($isID8 ? '22.5mm' : ($isID6 ? '24.5mm' : ($template->id == 4 ? '22.9mm' : ($isID5 ? '25mm' : '27mm'))))) }}
                ;
            @endif border:
            {{ ($isID8 || $isID9) ? '0.8mm' : '1.0mm' }}
            solid
            {{ $isID7 ? '#f15b2a' : ($template->photo_border_color ?? '#000000') }}
            ;
            border-radius:
                {{ $isID7 ? '50%' : '1mm' }}
            ;
            object-fit: cover;
            background: #eee;
            display: inline-block;
        }

        /* Vertical ID No for 9 */
        .vertical-id-box {
            position: absolute;
            top: 39.0mm;
            left: 40.5mm;
            width: 8mm;
            height: 23mm;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .vertical-id {
            transform: rotate(-90deg);
            transform-origin: center;
            white-space: nowrap;
            font-size: 10pt;
            font-weight: bold;
            color:
                {{ $isID9 ? '#f16522' : '#000' }}
            ;
        }

        /* Name (Visible for all) */
        .name-box {
            display: block;
            position: absolute;
            @if($hasSettings)
                top:
                    {{ $settings['name']['top'] ?? 45.3 }}
                    mm;
                left:
                    {{ $settings['name']['left'] ?? 0 }}
                    mm;
                width: 100%;
                @if(($settings['name']['left'] ?? 0) == 0)
                    text-align: center;
            @endif @else top:
                {{ $isID9 ? '53.2mm' : ($isID8 ? '52.27mm' : ($isID6 ? '49.3mm' : ($isID5 ? '47.3mm' : ($template->id == 4 ? '50.5mm' : ($template->id == 3 ? '52.5mm' : ($isID2 ? '54.5mm' : '50.5mm')))))) }}
;
                left: 0;
                right: 0;
                text-align: center;
                @if($isID7)
                    display: none;
            @endif @endif color:
            {{ $template->name_color ?? '#148bc9' }}
            ;
            z-index: 2;
        }

        .name {
            @if($hasSettings)
                font-size:
                    {{ $settings['name']['fontSize'] ?? 12 }}
                    pt;
            @else font-size: 10pt;

                    {
                        {
                        -- Fallback,
                        overridden inline --
                    }
                }

            @endif font-weight: bold;
            @if($isID6)
                font-family: "Franklin Gothic Demi",
                "Franklin Gothic",
                "Arial Black",
                sans-serif;
            @endif text-transform: uppercase;
            white-space: nowrap;
            overflow: hidden;
        }

        /* Details */
        .details-box {
            position: absolute;
            @if($hasSettings)
                top:
                    {{ $settings['details']['top'] ?? 51 }}
                    mm;
                left:
                    {{ $settings['details']['left'] ?? 4 }}
                    mm;
                font-size:
                    {{ $settings['details']['fontSize'] ?? 9 }}
                    pt;
            @else top:
                {{ $isID10 ? '56.0mm' : ($isID9 ? '59.1mm' : ($isID8 ? '59.45mm' : ($isID7 ? '45.03mm' : ($isID6 ? '55.0mm' : ($isID5 ? '52.2mm' : ($template->id == 4 ? '56.0mm' : ($isID2 ? '62mm' : '55mm'))))))) }}
                ;
                left:
                    {{ ($isID9 || $isID10) ? '3.5mm' : (($isID8) ? '6.0mm' : ($isID7 ? '6.14mm' : ($isID6 ? '2.4mm' : (($template->id == 4 || $template->id == 5) ? '1.5mm' : '4mm')))) }}
                ;
                right:
                    {{ ($isID9 || $isID10) ? '0' : '2mm' }}
                ;
                font-size:
                    {{ ($isID9) ? '7.5pt' : ($isID10 ? '8pt' : '9pt') }}
                ;
                @if($isID9 || $isID10)
                    text-align: left;
            @endif @endif right: 2mm;
            color:
                {{ ($isID9 || $isID10) ? ($isID9 ? '#213d78' : ($template->data_color ?? '#213d78')) : ($template->data_color ?? '#000000') }}
            ;
            z-index: 1;
        }

        .sub-info {
            font-size: inherit;
            line-height:
                {{ ($template->id == 4 || $template->id == 5) ? '1.35' : ($isID10 ? '1.3' : '1.4') }}
            ;
            white-space: nowrap;
            font-weight: 600;
        }

        .label-col {
            width:
                {{ ($isID5 || $isID8) ? '17mm' : ($isID10 ? '14mm' : '13mm') }}
            ;
            vertical-align: top;
        }

        /* Signature */
        .sig-box {
            position: absolute;
            @if($hasSettings)
                top:
                    {{ $settings['signature']['top'] ?? 75 }}
                    mm;
                left:
                    {{ $settings['signature']['left'] ?? 35 }}
                    mm;
            @else top: 80.5mm;
                left: 35mm;
            @endif z-index: 3;
        }

        .signature-img {
            @if($hasSettings)
                width:
                    {{ $settings['signature']['width'] ?? 18 }}
                    mm;
            @else width: 18mm;
            @endif height: auto;
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
                        $computedNameSize = '10pt';
                        if ($isID5) {
                            $len = strlen($student->name);
                            if ($len > 25)
                                $computedNameSize = '7pt';
                            elseif ($len > 20)
                                $computedNameSize = '8pt';
                            elseif ($len > 15)
                                $computedNameSize = '9pt';
                            else
                                $computedNameSize = '10pt';
                        } elseif ($isID6) {
                            $len = mb_strlen($student->name);
                            if ($len > 25)
                                $computedNameSize = '7pt';
                            elseif ($len > 20)
                                $computedNameSize = '8pt';
                            elseif ($len > 15)
                                $computedNameSize = '9pt';
                            else
                                $computedNameSize = '10pt';
                        } elseif ($template->id == 4) {
                            $len = mb_strlen($student->name);
                            if ($len > 25)
                                $computedNameSize = '7pt';
                            elseif ($len > 20)
                                $computedNameSize = '8pt';
                            elseif ($len > 15)
                                $computedNameSize = '9pt';
                            else
                                $computedNameSize = '10pt';
                        } elseif ($isID8) {
                            $len = mb_strlen($student->name);
                            if ($len > 22)
                                $computedNameSize = '8pt';
                            elseif ($len > 18)
                                $computedNameSize = '9pt';
                            elseif ($len > 14)
                                $computedNameSize = '11pt';
                            else
                                $computedNameSize = '13pt';
                        } elseif ($isID9) {
                            $len = mb_strlen($student->name);
                            if ($len > 24)
                                $computedNameSize = '8pt';
                            elseif ($len > 18)
                                $computedNameSize = '9pt';
                            else
                                $computedNameSize = '9.75pt';
                        } elseif ($isID10) {
                            $len = mb_strlen($student->name);
                            if ($len > 24)
                                $computedNameSize = '8pt';
                            elseif ($len > 18)
                                $computedNameSize = '9pt';
                            else
                                $computedNameSize = '10pt';
                        }
                    @endphp
                    <div class="name"
                        style="font-size: {{ $computedNameSize }}; color: {{ ($isID9 || $isID10) ? ($isID9 ? '#a70063' : ($template->name_color ?? '#e62128')) : 'inherit' }}; {{ ($isID9 || $isID10 || $isID6 || $template->id == 4) ? 'font-weight: bold;' : '' }} {{ ($isID10 || $isID6 || $template->id == 4) ? 'text-transform: uppercase;' : '' }}">
                        {{ ($isID10 || $isID6 || $template->id == 4) ? strtoupper($student->name) : $student->name }}
                    </div>
                </div>

                {{-- Vertical ID for 9 --}}
                @if($isID9)
                    <div class="vertical-id-box">
                        <div class="vertical-id">{{ $student->student_id }}</div>
                    </div>
                @endif

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
                                $fFontSize = $fNameLen > 25 ? '6pt' : ($fNameLen > 20 ? '6.7pt' : '7.5pt');
                                $mFontSize = $mNameLen > 25 ? '6pt' : ($mNameLen > 20 ? '6.7pt' : '7.5pt');
                            @endphp
                            <tr>
                                <td class="sub-info label-col" style="width: 12.3mm;">F Name</td>
                                <td class="sub-info" style="font-size: {{ $fFontSize }};">: {{ $student->father_name }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info">M Name</td>
                                <td class="sub-info" style="font-size: {{ $mFontSize }};">: {{ $student->mother_name }}</td>
                            </tr>
                            <tr style="font-size: 8pt;">
                                <td class="sub-info">Reg. No</td>
                                <td class="sub-info">: {{ $student->registration_no }}</td>
                            </tr>
                            <tr style="font-size: 8pt;">
                                <td class="sub-info">Roll</td>
                                <td class="sub-info">: {{ $student->roll }}</td>
                            </tr>
                            @if($student->blood_group)
                                <tr style="font-size: 8pt;">
                                    <td class="sub-info">BG</td>
                                    <td class="sub-info">: {{ $student->blood_group }}</td>
                                </tr>
                            @endif
                            <tr style="font-size: 8pt;">
                            <tr style="font-size: 8pt;">
                                <td class="sub-info">Valid up to</td>
                                <td class="sub-info">: <span style="color: red;">2030-2031 (Session)</span></td>
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
                        @elseif($template->id == 6)
                            <tr>
                                <td class="sub-info label-col" style="width: 15mm;">Father</td>
                                <td class="sub-info">: {{ $student->father_name }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info">ID No</td>
                                <td class="sub-info">: {{ $student->student_id }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info">Class</td>
                                <td class="sub-info">: {{ $student->class }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info">Session</td>
                                <td class="sub-info">: 2026</td>
                            </tr>
                            <tr style="font-size: 8.5pt;">
                                <td class="sub-info">Emg. Con</td>
                                <td class="sub-info">: 01622661353</td>
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
                        @elseif($isID7)
                            <tr>
                                <td colspan="2" class="sub-info" style="font-size: 5.7pt; line-height: 0.8; font-weight: normal;">
                                    Name:</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="sub-info"
                                    style="font-size: 10pt; color: #27265f; line-height: 1.2; font-weight: bold;">
                                    {{ $student->name }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="sub-info" style="font-size: 5.7pt; line-height: 0.8; font-weight: normal;">ID
                                    No:</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="sub-info"
                                    style="font-size: 10pt; color: #27265f; line-height: 1.2; font-weight: bold;">
                                    {{ $student->student_id }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="sub-info" style="font-size: 5.7pt; line-height: 0.8; font-weight: normal;">
                                    CLASS:</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="sub-info"
                                    style="font-size: 10pt; color: #27265f; line-height: 1.2; font-weight: bold;">
                                    {{ $student->class }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="sub-info" style="font-size: 5.7pt; line-height: 0.8; font-weight: normal;">
                                    BLOOD GROUP:</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="sub-info"
                                    style="font-size: 10pt; color: #27265f; line-height: 1.2; font-weight: bold;">
                                    @if($student->blood_group)
                                        {{ $student->getBloodGroup($student->blood_group) }} ({{ $student->blood_group }})
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="sub-info" style="font-size: 5.7pt; line-height: 0.8; font-weight: normal;">
                                    MOBILE NO:</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="sub-info"
                                    style="font-size: 10pt; color: #27265f; line-height: 1.2; font-weight: bold;">
                                    {{ $student->contact_no }}
                                </td>
                            </tr>
                        @elseif($isID8)
                            <tr>
                                <td class="sub-info label-col">Student ID</td>
                                <td class="sub-info">: {{ $student->student_id }}</td>
                            </tr>
                            <tr style="color: red;">
                                <td class="sub-info">Blood Group</td>
                                <td class="sub-info">: {{ $student->blood_group }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info">Guardian Mobile</td>
                                <td class="sub-info">: {{ $student->contact_no }}</td>
                            </tr>
                        @elseif($isID9)
                            <tr>
                                <td class="sub-info" style="color: #213d78; width: 14mm;">FATHER</td>
                                <td class="sub-info" style="color: #213d78;">: {{ $student->father_name }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info" style="color: #213d78; width: 14mm;">MOTHER</td>
                                <td class="sub-info" style="color: #213d78;">: {{ $student->mother_name }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info" style="color: #213d78; width: 14mm;">MOBILE</td>
                                <td class="sub-info" style="color: #213d78;">: {{ $student->contact_no }}</td>
                            </tr>
                        @elseif($isID10)
                            <tr>
                                <td class="sub-info label-col">F. Name</td>
                                <td class="sub-info">: {{ $student->father_name }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info label-col">M. Name</td>
                                <td class="sub-info">: {{ $student->mother_name }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info label-col">Class</td>
                                <td class="sub-info">: {{ $student->class }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info label-col">Roll</td>
                                <td class="sub-info">: {{ $student->roll }}</td>
                            </tr>
                            <tr>
                                <td class="sub-info label-col">DOB</td>
                                <td class="sub-info">:
                                    {{ $student->dob ? \Carbon\Carbon::parse($student->dob)->format('d/m/Y') : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="sub-info label-col">ID No</td>
                                <td class="sub-info">: {{ $student->student_id }}</td>
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