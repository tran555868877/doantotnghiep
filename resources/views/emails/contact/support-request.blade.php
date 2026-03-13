<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Liên hệ mới</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; background: #f7f8fa; padding: 24px; color: #1f2937;">
<div style="max-width: 680px; margin: 0 auto; background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden;">
    <div style="padding: 16px 20px; background: #111827; color: #fff;">
        <h1 style="margin: 0; font-size: 20px;">Yêu cầu liên hệ mới</h1>
    </div>
    <div style="padding: 20px;">
        <p style="margin: 0 0 10px;"><strong>Họ và tên:</strong> {{ $payload['name'] }}</p>
        <p style="margin: 0 0 10px;"><strong>Số điện thoại:</strong> {{ $payload['phone'] }}</p>
        <p style="margin: 0 0 10px;"><strong>Email:</strong> {{ $payload['email'] }}</p>
        <p style="margin: 0 0 10px;"><strong>Chủ đề:</strong> {{ $payload['topic'] }}</p>
        <p style="margin: 0 0 6px;"><strong>Nội dung:</strong></p>
        <div style="white-space: pre-line; padding: 12px; border-radius: 8px; background: #f9fafb; border: 1px solid #e5e7eb;">{{ $payload['message'] }}</div>
    </div>
</div>
</body>
</html>
