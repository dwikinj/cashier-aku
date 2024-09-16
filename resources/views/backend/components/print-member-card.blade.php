<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Cards</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .card {
            width: 100%;
            max-width: 400px;
            margin: 20px auto;
            page-break-inside: avoid;
        }
        img {
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    @foreach($cards as $card)
        <div class="card">
            <img src="{{ $card }}" alt="Member Card">
        </div>
    @endforeach
</body>
</html>