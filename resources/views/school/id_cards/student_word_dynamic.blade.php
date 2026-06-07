<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"
    xmlns:w="urn:schemas-microsoft-com:office:word" xmlns:m="http://schemas.microsoft.com/office/2004/12/omml"
    xmlns="http://www.w3.org/TR/REC-html40">

<head>
    <meta http-equiv=Content-Type content="text/html; charset=utf-8">
    <style>
        @page {
            size: A4;
            margin: 1in;
        }

        body {
            font-family: sans-serif;
        }

        .card-container {
            width: 155px;
            /* approx 1.6 inch */
            height: 244px;
            /* approx 2.5 inch */
            position: relative;
        }

        /* Simple CSS fallback for browser, though Word uses VML */
        .card-content {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .text-layer {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
    <!--[if !mso]>
<style>
v\:* {behavior:url(#default#VML);}
o\:* {behavior:url(#default#VML);}
w\:* {behavior:url(#default#VML);}
.shape {behavior:url(#default#VML);}
</style>
<![endif]-->
</head>

<body>

    @foreach($students as $student)
        <div class="card-container">
            <!-- VML Background Image for Word -->
            <!--[if gte mso 9]>
                                                    <v:rect style='width:116pt;height:183pt;position:absolute;top:0;left:0;z-index:-1;' strokeweight="0">
                                                        <v:imagedata src="{{ $template->bg_mhtml }}" />
                                                    </v:rect>
                                                    <![endif]-->

            <!-- Content Layer -->
            <!-- Using a table for layout inside the card -->
            @if($template->id == 8)
                <table border="0" cellspacing="0" cellpadding="0" style="width:155px; height:244px; border-collapse:collapse;">
                    <!-- Row 1: Photo (Top Padding 54px, Height 114px) -->
                    <tr>
                        <td align="center" valign="top" style="padding-top:74px; height:53px; text-align: center;">
                            @if($student->photo_mhtml)
                                <img src="{{ $student->photo_mhtml }}" width="74" height="85"
                                    style="border:2px solid {{ $template->photo_border_color ?? '#000' }}; display:block;">
                            @else
                                <div style="width:74px; height:85px; border:1px solid #000; display:inline-block;"></div>
                            @endif
                        </td>
                    </tr>
                    <!-- Row 2: Name -->
                    <tr>
                        <td align="center" valign="top" style="padding-top:3px; height:19px;">
                            @php
                                $wordNameSize = '13px';
                                $len = mb_strlen($student->name);
                                if ($len > 22)
                                    $wordNameSize = '9px';
                                elseif ($len > 18)
                                    $wordNameSize = '10px';
                                elseif ($len > 14)
                                    $wordNameSize = '11px';
                                else
                                    $wordNameSize = '13px';
                            @endphp
                            <span
                                style="font-size:{{ $wordNameSize }}; font-weight:bold; text-transform:uppercase; color:{{ $template->name_color ?? '#148bc9' }};">
                                {{ $student->name }}
                            </span>
                        </td>
                    </tr>
                    <!-- Row 3: Details -->
                    <tr>
                        <td align="left" valign="top" style="padding-left:15px; padding-top:2px;">
                            <table cellspacing="0" cellpadding="0"
                                style="font-size:11px; font-weight:bold; color:{{ $template->data_color ?? '#000' }}; line-height: 1.2;">
                                <tr>
                                    <td width="70">Student ID</td>
                                    <td>: {{ $student->student_id }}</td>
                                </tr>
                                <tr style="color: red;">
                                    <td>Blood Group</td>
                                    <td>: {{ $student->blood_group }}</td>
                                </tr>
                                <tr>
                                    <td>Guardian Mobile</td>
                                    <td>: {{ $student->contact_no }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- Filler row -->
                    <tr>
                        <td height="*"></td>
                    </tr>
                </table>
            @else
                <table border="0" cellspacing="0" cellpadding="0" style="width:155px; height:244px; border-collapse:collapse;">
                    <!-- Row 1: Photo (Top Padding 62px, Height 75px) -->
                    <tr>
                        <td align="center" valign="top" style="padding-top:62px; height:75px;">
                            @if($student->photo_mhtml)
                                <img src="{{ $student->photo_mhtml }}" width="75" height="75"
                                    style="border-radius:5px; border:2px solid {{ $template->text_color }}; display:block;">
                            @else
                                <div style="width:75px; height:75px; border:1px solid #000; display:inline-block;"></div>
                            @endif
                        </td>
                    </tr>
                    <!-- Row 2: Name (Vertical alignment fix, roughly 5px gap) -->
                    <tr>
                        <td align="center" valign="top" style="padding-top:5px; height:20px;">
                            <span
                                style="font-size:11px; font-weight:bold; text-transform:uppercase; letter-spacing: 1.5px; color:{{ $template->text_color }};">
                                {{ $student->name }}
                            </span>
                        </td>
                    </tr>
                    <!-- Row 3: Details -->
                    <tr>
                        <td align="left" valign="top" style="padding-left:15px; padding-top:5px;">
                            <table cellspacing="0" cellpadding="0"
                                style="font-size:9px; font-weight:bold; color:{{ $template->text_color }};">
                                <tr>
                                    <td width="42">ID NO</td>
                                    <td>: {{ $student->student_id }}</td>
                                </tr>
                                <tr>
                                    <td>Class</td>
                                    <td>: {{ $student->class }}</td>
                                </tr>
                                <tr>
                                    <td>Roll</td>
                                    <td>: {{ $student->roll }}</td>
                                </tr>
                                <tr>
                                    <td>Mobile</td>
                                    <td>: {{ $student->contact_no }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- Filler row -->
                    <tr>
                        <td height="*"></td>
                    </tr>
                </table>
            @endif
        </div>

        <!-- Page Break after each card -->
        <br clear="all" style="page-break-before:always; mso-break-type:section-break" />
    @endforeach

</body>

</html>