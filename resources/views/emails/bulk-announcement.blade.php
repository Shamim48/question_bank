<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; background: #f8fafc; padding: 24px;">
    <div style="max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 16px; padding: 32px; border: 1px solid #e5e7eb;">
        <h2 style="color: #4f46e5; margin-top: 0;">{{ config('app.name') }}</h2>
        <div style="font-size: 14px; line-height: 1.6;">
            {!! nl2br(e($body)) !!}
        </div>
    </div>
</body>
</html>
