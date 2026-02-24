<!DOCTYPE html>
<html>

<head>
    <title>ID Card Preview</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .id-card {
            width: 85.6mm;
            height: 53.98mm;
            border: 1px solid #ccc;
            position: relative;
            background: #fff;
            overflow: hidden;
            display: inline-block;
            margin: 10px;
            page-break-inside: avoid;
        }

        .front,
        .back {
            display: inline-block;
            vertical-align: top;
        }
    </style>
</head>

<body class="bg-gray-100 p-8">
    <div class="mb-4 no-print text-center">
        <button onclick="window.print()" class="bg-blue-500 text-white px-4 py-2 rounded">Print / Save as PDF</button>
    </div>

    @foreach($students as $student)
        <div class="mb-8 border-b pb-8 text-center">
            <!-- Front Side -->
            <div class="id-card text-center shadow-lg relative bg-blue-50">
                <!-- Header/Logo -->
                <div class="h-12 bg-blue-600 flex items-center justify-center text-white font-bold text-xs uppercase p-1">
                    @if($student->school->logo)
                        <img src="{{ asset('storage/' . $student->school->logo) }}"
                            class="h-8 w-8 mr-2 rounded-full absolute left-2 top-2 bg-white p-0.5">
                    @endif
                    <span class="ml-8">{{ $student->school->name }}</span>
                </div>

                <!-- Photo -->
                <div class="mt-2">
                    @if($student->photo)
                        <img src="{{ asset('storage/' . $student->photo) }}"
                            class="h-20 w-20 rounded-full mx-auto border-2 border-blue-600 object-cover">
                    @else
                        <div class="h-20 w-20 rounded-full mx-auto border-2 border-gray-300 bg-gray-200"></div>
                    @endif
                </div>

                <!-- Details -->
                <div class="mt-1">
                    <h3 class="font-bold text-blue-900 text-sm uppercase">{{ $student->name }}</h3>
                    <p class="text-xs font-semibold text-gray-700">Class: {{ $student->class }} | Sec:
                        {{ $student->section }}</p>
                    <p class="text-xs text-red-600 font-bold">Roll: {{ $student->roll }}</p>
                    <div class="absolute bottom-2 left-0 right-0 text-center">
                        <span
                            class="text-[10px] bg-red-100 px-2 rounded-full text-red-600 font-bold">{{ $student->blood_group }}</span>
                    </div>
                </div>
            </div>

            <!-- Back Side -->
            <div class="id-card text-center shadow-lg relative bg-white ml-4">
                <div class="p-4 text-left text-[10px] leading-tight mt-2">
                    <p><span class="font-bold">Father:</span> {{ $student->father_name }}</p>
                    <p><span class="font-bold">Mother:</span> {{ $student->mother_name }}</p>
                    <p><span class="font-bold">Contact:</span> {{ $student->contact_no }}</p>
                    <p class="mt-1"><span class="font-bold">Address:</span><br>{{ $student->school->address }}</p>
                </div>

                <div class="absolute bottom-12 left-4">
                    @if($student->signature)
                        <img src="{{ asset('storage/' . $student->signature) }}" class="h-6 object-contain">
                    @endif
                    <p class="text-[8px] border-t border-gray-400 w-16 text-center">Signature</p>
                </div>

                <div class="absolute bottom-2 right-2">
                    <img src="data:image/svg+xml;base64,{{ $student->qrcode }}" class="h-12 w-12">
                </div>
                <div class="absolute bottom-2 left-4 text-[8px] text-gray-500">
                    Valid: {{ date('Y') }}
                </div>
            </div>
        </div>
    @endforeach
</body>

</html>