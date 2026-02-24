<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000000;
            padding: 8px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .header {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        tr {
            height: 70px;
        }

        .photo-cell {
            text-align: center;
            width: 70px;
            height: 70px;
        }

        img {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }
    </style>
</head>

<body>
    @if($school)
        <div class="header">
            {{ $school->name }}<br>
            Student Report
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Student ID</th>
                <th>Registration No</th>
                <th>Photo</th>
                <th>Name</th>
                <th>Class</th>
                <th>Section</th>
                <th>Roll</th>
                <th>Session</th>
                <th>Father's Name</th>
                <th>Mother's Name</th>
                <th>Contact No</th>
                <th>Blood Group</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $key => $student)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $student->student_id }}</td>
                    <td>{{ $student->registration_no }}</td>
                    <td class="photo-cell">
                        @if($student->excel_photo)
                            <img src="{{ $student->excel_photo }}" width="60" height="60" alt="Photo">
                        @else
                            <span style="color:red; font-size:10px;">No Image</span>
                        @endif
                    </td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->class }}</td>
                    <td>{{ $student->section }}</td>
                    <td>{{ $student->roll }}</td>
                    <td>{{ $student->session ?? '2024-2025' }}</td>
                    <td>{{ $student->father_name }}</td>
                    <td>{{ $student->mother_name }}</td>
                    <td>{{ $student->contact_no }}</td>
                    <td>{{ $student->blood_group }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>