<!DOCTYPE html>
<html>
<head>
    <title>AI Question Solver</title>
</head>
<body>
    <h2>Upload Your Question Images</h2>
    <form method="POST" action="/upload" enctype="multipart/form-data">
        @csrf
        <input type="file" name="images[]" multiple required>
        <button type="submit">Get Answers</button>
    </form>

    @if(!empty($answers))
        <h3>Results:</h3>
        @foreach($answers as $entry)
            <div style="margin-bottom: 30px;">
                <strong>Question:</strong>
                <pre>{{ $entry['question'] }}</pre>
                <strong>Answer:</strong>
                <pre>{{ $entry['answer'] }}</pre>
            </div>
        @endforeach
    @endif
</body>
</html>