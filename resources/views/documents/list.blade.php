<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uploaded Files</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Uploaded Files</h2>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Formation ID</th>
                <th>Download</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $file) <!-- Use $file here instead of $data for better clarity -->
                <tr>
                    <td>{{ $file->titre }}</td>
                    <td>{{ $file->formation_id }}</td>
                    <td>{{ $file->url }}</td>
                    
                        <td>
                            <a href="{{ asset('storage/' . $file->url) }}" download>Download</a>
                        </td>
                        
                    
                </tr>
            @endforeach
        </tbody>
        
        @if($data->isEmpty()) <!-- Check if $data is empty -->
            <p style="color: red;">No files uploaded yet.</p>
        @endif

</body>
</html>
