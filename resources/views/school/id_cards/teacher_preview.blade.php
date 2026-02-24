<!DOCTYPE html>
<html>

<head>
    <title>Teacher ID Card Preview</title>
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
    </style>
</head>

<body class="bg-gray-100 p-8">
    <div class="mb-4 no-print text-center">
        <button onclick="window.print()" class="bg-blue-500 text-white px-4 py-2 rounded">Print / Save as PDF</button>
    </div>

    @foreach($teachers as $teacher)
        <div class="mb-8 border-b pb-8 text-center">
            <!-- Front Side -->
            <div class="id-card text-center shadow-lg relative bg-green-50">
                <div class="h-12 bg-green-600 flex items-center justify-center text-white font-bold text-xs uppercase p-1">
                    @if($teacher->school->logo)
                        <img src="{{ asset('storage/' . $teacher->school->logo) }}"
                            class="h-8 w-8 mr-2 rounded-full absolute left-2 top-2 bg-white p-0.5">
                    @endif
                    <span class="ml-8">{{ $teacher->school->name }}</span>
                </div>

                <div class="mt-2">
                    @if($teacher->photo)
                        <img src="{{ asset('storage/' . $teacher->photo) }}"
                            class="h-20 w-20 rounded-full mx-auto border-2 border-green-600 object-cover">
                    @else
                        <div class="h-20 w-20 rounded-full mx-auto border-2 border-gray-300 bg-gray-200"></div>
                    @endif
                </div>

                <div class="mt-1">
                    <h3 class="font-bold text-green-900 text-sm uppercase">{{ $teacher->name }}</h3>
                    <p class="text-xs font-semibold text-gray-700">{{ $teacher->designation }}</p>
                    <div class="absolute bottom-2 left-0 right-0 text-center">
                        <span
                            class="text-[10px] bg-red-100 px-2 rounded-full text-red-600 font-bold">{{ $teacher->blood_group }}</span>
                    </div>
                </div>
            </div>

            <!-- Back Side -->
            <div class="id-card text-center shadow-lg relative bg-white ml-4">
                <div class="p-4 text-left text-[10px] leading-tight mt-2">
                    <p><span class="font-bold">Mobile:</span> {{ $teacher->mobile }}</p>
                    <p><span class="font-bold">Joined:</span> {{ $teacher->joining_date }}</p>
                    <p class="mt-2"><span class="font-bold">School Address:</span><br>{{ $teacher->school->address }}</p>
                </div>

                <div class="absolute bottom-12 left-4">
                    @if($teacher->signature)
                        <img src="{{ asset('storage/' . $teacher->signature) }}" class="h-6 object-contain">
                    @endif
                    <p class="text-[8px] border-t border-gray-400 w-16 text-center">Signature</p>
                </div>

                <div class="absolute bottom-2 right-2">
                    <img src="data:image/svg+xml;base64,{{ $teacher->qrcode }}" class="h-12 w-12">
                </div>
                <div class="absolute bottom-2 left-4 text-[8px] text-gray-500">
                    Valid: {{ date('Y') }}
                </div>
            </div>
        </div>
    @endforeach
</body>

</html>