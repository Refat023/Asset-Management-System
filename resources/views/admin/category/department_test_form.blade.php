<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Departments</title>
</head>
<body>
    <h2>Departments from Database</h2>
    <ul>
        @foreach($departments as $department)
            <li>{{ $department->department_name }}</li>
        @endforeach
    </ul>
</body>
</html>
