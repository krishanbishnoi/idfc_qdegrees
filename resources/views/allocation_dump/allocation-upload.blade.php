<!DOCTYPE html>
<html>

<head>
    <title>Allocation Dump Upload</title>
</head>

<body>

    <h2>Upload Allocation Dump Excel</h2>

    @if (session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <form action="{{ route('allocation.upload.file') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label>Select Excel File:</label>
        <input type="file" name="file" accept=".csv,.xlsx,.xls" required>

        @error('file')
            <p style="color:red">{{ $message }}</p>
        @enderror

        <br><br>
        <button type="submit">Upload</button>
    </form>

</body>

</html>
