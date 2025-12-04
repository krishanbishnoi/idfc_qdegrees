<h2>Upload DAC Dump</h2>

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

@if(session('error'))
    <p style="color:red">{{ session('error') }}</p>
@endif

<form action="{{ route('dac.upload.file') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label>Select file:</label>
    <input type="file" name="file" required>

    <button type="submit">Upload</button>
</form>
