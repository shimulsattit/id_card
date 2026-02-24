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
            margin: 10mm;
        }

        body {
            font-family: 'SolaimanLipi', sans-serif;
        }

        .container {
            width: 100%;
        }

        .id-card {
            width: 155px;
            height: 244px;
            border: 1px solid #000;
            position: relative;
            background: #fff;
            overflow: hidden;
            display: inline-block;
            margin: 5mm;
            float: left;
        }

        .header {
            background-color: #f97316;
            /* Orange-500 */
            color: white;
            height: 12mm;
            line-height: 12mm;
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .photo-container {
            text-align: center;
            margin-top: 5px;
        }

        .photo {
            height: 20mm;
            width: 20mm;
            border-radius: 10px;
            /* Rounded square instead of circle */
            border: 2px solid #f97316;
            object-fit: cover;
        }

        .details {
            text-align: center;
            margin-top: 2px;
        }

        .name {
            font-size: 12px;
            font-weight: bold;
            color: #c2410c;
            /* Orange-700 */
            text-transform: uppercase;
            margin: 2px 0;
        }

        .info {
            font-size: 10px;
            color: #374151;
            margin: 0;
        }

        .roll {
            font-size: 10px;
            color: #dc2626;
            /* red */
            font-weight: bold;
            margin: 1px 0;
        }

        .blood-group {
            position: absolute;
            bottom: 5px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #dc2626;
        }

        /* Back Side */
        .back-details {
            padding: 10px;
            font-size: 9px;
            line-height: 1.2;
        }

        .label {
            font-weight: bold;
        }

        .signature {
            position: absolute;
            bottom: 12mm;
            /* adjusted */
            left: 5px;
            text-align: center;
            width: 20mm;
        }

        .signature img {
            height: 8mm;
            max-width: 100%;
        }

        .signature-line {
            border-top: 1px solid #000;
            font-size: 7px;
        }

        .qrcode {
            position: absolute;
            bottom: 5px;
            right: 5px;
        }

        .valid {
            position: absolute;
            bottom: 5px;
            left: 5px;
            font-size: 7px;
            color: #555;
        }

        .page-break {
            clear: both;
            page-break-after: always;
        }
    </style>
</head>

<body>
    @php $count = 0; @endphp
    @foreach($students as $student)
        <!-- Front -->
        <div class="id-card">
            <div class="header">
                {{ Str::limit($student->school->name, 35) }}
            </div>
            <div class="photo-container">
                @if($student->photo)
                    <!-- Use public_path for DomPDF local file access if possible, or simple asset if http enabled -->
                    <!-- Usually public_path() is better for CLI/DomPDF internal -->
                    <img src="{{ public_path('storage/' . $student->photo) }}" class="photo">
                @else
                    <div class="photo" style="background:#ddd;"></div>
                @endif
            </div>
            <div class="details">
                <div class="name">{{ $student->name }}</div>
                <div class="info">Class: {{ $student->class }} | Sec: {{ $student->section }}</div>
                <div class="roll">Roll: {{ $student->roll }}</div>
            </div>
            <div class="blood-group">
                {{ $student->blood_group }}
            </div>
        </div>

        <!-- Back -->
        <div class="id-card">
            <div class="back-details">
                <p><span class="label">Father:</span> {{ $student->father_name }}</p>
                <p><span class="label">Mother:</span> {{ $student->mother_name }}</p>
                <p><span class="label">Contact:</span> {{ $student->contact_no }}</p>
                <p><span class="label">Address:</span><br>{{ Str::limit($student->school->address, 60) }}</p>
            </div>

            <div class="signature">
                @if($student->signature)
                    <img src="{{ public_path('storage/' . $student->signature) }}">
                @endif
                <div class="signature-line">Signature</div>
            </div>

            <div class="valid">Valid: {{ date('Y') }}</div>

            <div class="qrcode">
                <img src="data:image/svg+xml;base64,{{ $student->qrcode }}" width="45" height="45">
            </div>
        </div>

        @php $count++; @endphp
        @if($count % 4 == 0)
            <!-- 4 cards (2 students front/back) per page roughly fits A4 portrait? Or landscape. using float left -->
            <div class="page-break"></div>
        @endif
    @endforeach
</body>

</html>