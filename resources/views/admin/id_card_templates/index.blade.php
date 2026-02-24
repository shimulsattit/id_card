@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">ID Card Templates</h1>
            <a href="{{ route('admin.id-card-templates.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-200">
                + Add New Template
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($templates as $template)
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 flex flex-col">
                    <div class="p-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-bold text-lg text-gray-800">
                            {{ $template->name }} (ID: {{ $template->id }})
                        </h3>
                        <span
                            class="text-xs font-semibold px-2 py-1 rounded {{ $template->type == 'student' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800' }}">
                            {{ ucfirst($template->type) }}
                        </span>
                    </div>

                    <div class="p-4 flex-grow flex flex-wrap justify-center gap-6">
                        <div class="space-y-2">
                            <p class="text-xs font-bold text-gray-500 uppercase text-center mb-2">Front Side</p>
                            <!-- Live Preview Container -->
                            <!-- Live Preview Container -->
                            @php
                                $thumbSettings = $template->settings;
                                $scale = 155 / 58;

                                // Helper: returns custom style string OR null
                                $getThumbValue = function ($key) use ($thumbSettings, $scale) {
                                    if (!$thumbSettings || !isset($thumbSettings[$key]))
                                        return null;
                                    $s = $thumbSettings[$key];
                                    $style = "top: " . ($s['top'] * $scale) . "px; left: " . ($s['left'] * $scale) . "px;";
                                    if (isset($s['width']))
                                        $style .= " width: " . ($s['width'] * $scale) . "px;";
                                    if (isset($s['fontSize']))
                                        $style .= " font-size: " . ($s['fontSize'] * $scale * 0.4) . "px;";
                                    if (($s['left'] ?? 0) == 0)
                                        $style .= " width: 100%; text-align: center; left: 0;";
                                    return $style;
                                };

                                $isID2 = ($template->id == 2 || $template->id == 3 || stripos($template->name, 'ID-2') !== false);
                                $isID5 = ($template->id == 5);
                             @endphp

                            <div class="relative mx-auto shadow-md overflow-hidden rounded-lg user-select-none"
                                style="width: 155px; height: 244px; background-image: url('{{ asset('storage/' . $template->background_image) }}'); background-size: cover; background-position: center;">

                                <!-- Dummy Photo -->
                                @php $photoStyle = $getThumbValue('photo'); @endphp
                                <div class="absolute {{ $photoStyle ? '' : ($isID5 ? 'top-[45px]' : ($isID2 ? 'top-[64px]' : ($template->id == 4 ? 'top-[70px]' : 'top-[42px]'))) }} left-0 right-0 text-center"
                                    style="{{ $photoStyle }}">
                                    <div class="mx-auto rounded-[5px] bg-gray-200"
                                        style="width: {{ $photoStyle ? 'inherit' : ($template->id == 4 ? '67px' : '65px') }}; height: {{ $photoStyle ? 'inherit' : ($template->id == 4 ? '65px' : '65px') }}; border: 2px solid {{ $template->photo_border_color ?? '#000000' }}; display: flex; align-items: center; justify-content: center;">
                                        <span class="text-[8px] opacity-50">PHOTO</span>
                                    </div>
                                </div>

                                <!-- Dummy Name -->
                                @php $nameStyle = $getThumbValue('name'); @endphp
                                <div class="absolute {{ $nameStyle ? '' : ($isID5 ? 'top-[115px] left-0 right-0' : ($isID2 ? ($template->id == 4 ? 'top-[144px] left-0 right-0' : ($template->id == 3 ? 'top-[148px]' : 'top-[152px]') . ' left-[28px] right-[28px] py-[2px]') : 'top-[122px] left-0 right-0')) }} text-center leading-tight"
                                    style="{{ $nameStyle }}">
                                    <h2 class="font-bold uppercase"
                                        style="color: {{ ($isID2 && !$nameStyle && $template->id != 3) ? '#000000' : ($template->name_color ?? '#148bc9') }}; 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           font-size: {{ $nameStyle ? 'inherit' : ($template->id == 4 ? '11.5px' : ($template->id == 3 ? '12px' : ($isID2 ? '10px' : '12px'))) }}; 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       white-space: nowrap; overflow: hidden; letter-spacing: 1px;">
                                        STD TEM-{{ str_pad($template->id, 2, '0', STR_PAD_LEFT) }}
                                    </h2>
                                </div>

                                <!-- Dummy Details -->
                                @php $detailsStyle = $getThumbValue('details'); @endphp
                                <div class="absolute {{ $detailsStyle ? '' : ($isID5 ? 'top-[132px] left-[8px] right-1' : ($isID2 ? ($template->id == 4 ? 'top-[160px] left-[2mm] right-1' : 'top-[168px] left-4 right-1') : 'top-[142px] left-4 right-1')) }} text-left"
                                    style="{{ $detailsStyle }}">
                                    <table
                                        class="w-full font-bold {{ ($template->id == 4 || $template->id == 5) ? 'leading-none' : 'leading-tight' }}"
                                        style="color: {{ $template->data_color ?? '#000000' }}; font-size: {{ $detailsStyle ? 'inherit' : ($isID5 ? '7px' : ($template->id == 4 ? '6.5px' : ($isID2 ? '8px' : '9px'))) }};">
                                        @if($template->id == 4)
                                            <tr>
                                                <td class="align-top w-[38px] whitespace-nowrap">F.Name</td>
                                                <td class="align-top whitespace-nowrap">: Rahim Uddin</td>
                                            </tr>
                                            <tr>
                                                <td class="align-top w-[38px] whitespace-nowrap">M. Name</td>
                                                <td class="align-top whitespace-nowrap">: Sultana Begum</td>
                                            </tr>
                                            <tr>
                                                <td class="align-top w-[38px] whitespace-nowrap">Class</td>
                                                <td class="align-top whitespace-nowrap">: Ten</td>
                                            </tr>
                                            <tr>
                                                <td class="align-top w-[38px] whitespace-nowrap">Roll</td>
                                                <td class="align-top whitespace-nowrap">: 01</td>
                                            </tr>
                                            <tr>
                                                <td class="align-top w-[38px] whitespace-nowrap">DOB</td>
                                                <td class="align-top whitespace-nowrap">: 01/01/2010</td>
                                            </tr>
                                            <tr>
                                                <td class="align-top w-[38px] whitespace-nowrap">ID No</td>
                                                <td class="align-top whitespace-nowrap">: 123456</td>
                                            </tr>
                                        @elseif($template->id == 5)
                                            <tr>
                                                <td class="align-top w-[42px] whitespace-nowrap">F Name</td>
                                                <td class="align-top whitespace-nowrap">: Rahim Uddin</td>
                                            </tr>
                                            <tr>
                                                <td class="align-top w-[42px] whitespace-nowrap">M Name</td>
                                                <td class="align-top whitespace-nowrap">: Sultana Begum</td>
                                            </tr>

                                            <tr>
                                                <td class="align-top w-[42px] whitespace-nowrap">Reg.No</td>
                                                <td class="align-top whitespace-nowrap">: {{ $student->registration_no ?? 'N/A' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="align-top w-[42px] whitespace-nowrap">Roll</td>
                                                <td class="align-top whitespace-nowrap">: 01</td>
                                            </tr>
                                            <tr>
                                                <td class="align-top w-[42px] whitespace-nowrap">Session</td>
                                                <td class="align-top whitespace-nowrap">: 2024-25</td>
                                            </tr>
                                            <tr>
                                                <td class="align-top w-[42px] whitespace-nowrap">BG</td>
                                                <td class="align-top whitespace-nowrap">: A+</td>
                                            </tr>
                                            <tr>
                                                <td class="align-top w-[42px] whitespace-nowrap">BG</td>
                                                <td class="align-top whitespace-nowrap">: A+</td>
                                            </tr>
                                        @elseif($template->id == 3)

                                            <tr>
                                                <td class="align-top w-[38px] whitespace-nowrap">ID</td>
                                                <td class="align-top whitespace-nowrap">: 250267</td>
                                            </tr>
                                            <tr>
                                                <td class="align-top w-[38px] whitespace-nowrap">Blood Group</td>
                                                <td class="align-top whitespace-nowrap" style="font-size: 0.9em;">: A+</td>
                                            </tr>
                                            <tr>
                                                <td class="align-top w-[38px] whitespace-nowrap">Contact No</td>
                                                <td class="align-top whitespace-nowrap">: 01700...</td>
                                            </tr>
                                        @elseif($template->id == 1)
                                            <tr>
                                                <td class="align-top whitespace-nowrap" style="width: 30px;">ID NO</td>
                                                <td class="align-top">: 250267</td>
                                            </tr>
                                            <tr>
                                                <td class="align-top whitespace-nowrap">Class</td>
                                                <td class="align-top">: Ten</td>
                                            </tr>
                                            <tr>
                                                <td class="align-top whitespace-nowrap">Roll</td>
                                                <td class="align-top">: 01</td>
                                            </tr>
                                            <tr>
                                                <td class="align-top whitespace-nowrap">Mobile</td>
                                                <td class="align-top">: 01700...</td>
                                            </tr>
                                        @elseif($isID2)
                                            <tr>
                                                <td class="align-top whitespace-nowrap" style="width: 30px;">F Name</td>
                                                <td class="align-top">: Father Name</td>
                                            </tr>
                                            <tr>
                                                <td class="align-top whitespace-nowrap">M Name</td>
                                                <td class="align-top">: Mother Name</td>
                                            </tr>
                                            <tr>
                                                <td class="align-top whitespace-nowrap">Mobile</td>
                                                <td class="align-top">: 01700...</td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td class="align-top whitespace-nowrap" style="width: 40px;">ID NO</td>
                                                <td class="align-top">: 123456</td>
                                            </tr>
                                            <tr>
                                                <td class="align-top whitespace-nowrap">Class</td>
                                                <td class="align-top">: Ten</td>
                                            </tr>
                                            <tr>
                                                <td class="align-top whitespace-nowrap">Roll</td>
                                                <td class="align-top">: 01</td>
                                            </tr>
                                            <tr>
                                                <td class="align-top whitespace-nowrap">Mobile</td>
                                                <td class="align-top">: 01700...</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                        @if($template->background_image_back)
                            <div class="space-y-2">
                                <p class="text-xs font-bold text-gray-500 uppercase text-center mb-2">Back Side</p>
                                <div class="relative mx-auto shadow-md overflow-hidden rounded-lg user-select-none"
                                    style="width: 155px; height: 244px; background-image: url('{{ asset('storage/' . $template->background_image_back) }}'); background-size: cover; background-position: center;">
                                </div>
                            </div>
                        @else
                            <div class="space-y-2">
                                <p class="text-xs font-bold text-gray-500 uppercase text-center mb-2">Back Side</p>
                                <div class="relative mx-auto shadow-sm overflow-hidden rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 flex flex-col justify-center items-center"
                                    style="width: 155px; height: 244px;">
                                    <span class="text-xs text-gray-400">No Back Side</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="p-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                        <div></div>

                        <div class="flex space-x-2">
                            <a href="{{ route('admin.id-card-templates.edit', $template->id) }}"
                                class="text-blue-600 hover:text-blue-800 font-medium text-sm">Edit</a>

                            <form action="{{ route('admin.id-card-templates.destroy', $template->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-red-600 hover:text-red-800 font-medium text-sm">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($templates->isEmpty())
            <div class="text-center py-12 bg-white rounded-xl shadow-sm border border-gray-100">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <p class="text-gray-500 text-lg">No templates found.</p>
                <p class="text-gray-400 text-sm mt-1">Click "Add New Template" to create one.</p>
            </div>
        @endif
    </div>
@endsection