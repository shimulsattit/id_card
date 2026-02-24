<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        @font-face {
            font-family: 'SolaimanLipi';
            src: url('file://{{ public_path('fonts/SolaimanLipi.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

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

        @php
            $defaults = [
                'photo' => ['top' => 37, 'left' => 15.2, 'width' => 25.5, 'height' => 25.5],
                'name' => ['top' => 54, 'left' => 10, 'fontSize' => 10],
                'details' => ['top' => 58, 'left' => 5, 'fontSize' => 8],
                'signature' => ['top' => 80, 'left' => 25, 'width' => 20],
            ];
            $settings = array_merge($defaults, $template->settings ?? []);
        @endphp

        /* Photo border frame - TPL 02 */
        .photo-frame {
            position: absolute;
            top:
                {{ $settings['photo']['top'] }}
                mm;
            left:
                {{ $settings['photo']['left'] }}
                mm;
            width:
                {{ $settings['photo']['width'] }}
                mm;
            height:
                {{ $settings['photo']['height'] }}
                mm;
            border: 0.5mm solid
                {{ $template->photo_border_color ?? '#148bc9' }}
            ;
            border-radius: 1mm;
            overflow: hidden;
            z-index: 2;
        }

        .photo-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Name Box - Yellow/Gold background */
        .name-box {
            position: absolute;
            top:
                {{ $settings['name']['top'] }}
                mm;
            left:
                {{ $settings['name']['left'] }}
                mm;
            right: 1mm;
            text-align: center;
            z-index: 2;
        }

        .student-name {
            font-size:
                {{ $settings['name']['fontSize'] }}
                pt;
            font-weight: bold;
            color:
                {{ $template->name_color ?? '#000000' }}
            ;
            text-transform: uppercase;
            margin: 0;
            padding: 0;
            white-space: nowrap;
        }

        /* Details position for TPL 02 - Blue background area */
        .details-box {
            position: absolute;
            top:
                {{ $settings['details']['top'] }}
                mm;
            left:
                {{ $settings['details']['left'] }}
                mm;
            right: 2mm;
            color:
                {{ $template->data_color ?? '#ffffff' }}
            ;
            z-index: 2;
        }

        .data-table {
            width: 100%;
            font-size:
                {{ $settings['details']['fontSize'] }}
                pt;
            font-weight: bold;
            border-collapse: collapse;
        }

        /* Signature */
        .signature-box {
            position: absolute;
            top:
                {{ $settings['signature']['top'] }}
                mm;
            left:
                {{ $settings['signature']['left'] }}
                mm;
            width:
                {{ $settings['signature']['width'] }}
                mm;
            text-align: center;
            z-index: 2;
        }

        .signature-img {
            height: 6mm;
            max-width: 20mm;
        }

        .signature-label {
            font-size: 2.2mm;
            color: #ffffff;
            border-top: 0.2mm solid #ffffff;
            margin-top: 0.5mm;
            padding-top: 0.5mm;
        }
    </style>
</head>

<body>
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
                <img src="{{ public_path('storage/' . $template->background_image) }}" class="bg-img">

                <div class="photo-box">
                    @if($student->photo)
                        <img src="{{ public_path('storage/' . $student->photo) }}" class="photo">
                    @else
                        <div class="photo"></div>
                    @endif
                </div>

                <div class="name-box">
                    <div class="name">{{ $student->name }}</div>
                </div>

                <div class="details-box">
                    <table style="width:100%; border-collapse:collapse;">
                        <tr>
                            <td class="sub-info label-col">F Name</td>
                            <td class="sub-info">: {{ $student->father_name }}</td>
                        </tr>
                        <tr>
                            <td class="sub-info">M Name</td>
                            <td class="sub-info">: {{ $student->mother_name }}</td>
                        </tr>
                        <tr>
                            <td class="sub-info">Roll No</td>
                            <td class="sub-info">: {{ $student->roll }}</td>
                        </tr>
                        <tr>
                            <td class="sub-info">Session</td>
                            <td class="sub-info">: {{ $student->session ?? '2024-2025' }}</td>
                        </tr>
                        <tr>
                            <td class="sub-info">BG</td>
                            <td class="sub-info">: {{ $student->blood_group ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="sub-info">Mobile</td>
                            <td class="sub-info">: {{ $student->contact_no }}</td>
                        </tr>
                    </table>
                </div>

                <div class="signature-box">
                    @if($student->signature)
                        <img src="{{ public_path('storage/' . $student->signature) }}" class="signature-img">
                    @endif
                    <div class="signature-label">Head of the Department</div>
                </div>
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