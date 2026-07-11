<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $student->name }} — Student of the Year</title>

    <meta property="og:title" content="{{ $student->name }} — Student of the Year">
    <meta property="og:description" content="Check out my Student of the Year banner!">
    <meta property="og:image" content="{{ route('banner.image', $student) }}">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #4f46e5, #8b5cf6);
            padding: 24px;
        }
        .card {
            background: #fff;
            border-radius: 24px;
            padding: 24px;
            max-width: 640px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
            text-align: center;
        }
        .card img {
            max-width: 100%;
            border-radius: 16px;
        }
        .card h1 {
            margin-top: 20px;
            font-size: 20px;
            color: #1f2937;
        }
    </style>
</head>
<body>
    <div class="card">
        <img src="{{ route('banner.image', $student) }}" alt="{{ $student->name }}">
        <h1>{{ $student->name }} is a Student of the Year Participant!</h1>
    </div>
</body>
</html>
