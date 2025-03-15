<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Upload</title>
</head>
<body>
    <form action="{{ route('documents.upload') }}" method="post" enctype="multipart/form-data">
        @csrf
        <label for="title">Title</label>
        <br>
        <input type="text" name="titre" required>
        <br>
        <label for="formation_id">Formation ID</label>
        <br>
        <input type="text" name="formation_id" required>
        <br>
        <label for="file">Choose file to upload</label>
        <br>
        <input type="file" name="document" required>
        <button type="submit">Upload</button>
    </form>
    <a href="{{ route('documents.list') }}">View Uploaded Files</a>
</body>
</html>
