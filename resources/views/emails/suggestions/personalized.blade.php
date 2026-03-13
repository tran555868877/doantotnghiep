<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Gợi ý sản phẩm</title>
</head>
<body style="margin:0;padding:24px;background:#f2fbf8;font-family:Arial,Helvetica,sans-serif;color:#1e3a3f;">
<div style="max-width:680px;margin:0 auto;background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #d9efea;">
    <div style="padding:20px 24px;background:linear-gradient(135deg,#00bba7,#33d1bf);color:#ffffff;">
        <h1 style="margin:0;font-size:22px;">Gợi ý sản phẩm cho {{ $user->name }}</h1>
        <p style="margin:8px 0 0;font-size:14px;opacity:.95;">Segment: <strong>{{ strtoupper($score->segment) }}</strong> | Retention: <strong>{{ $score->retention_probability }}%</strong></p>
    </div>

    <div style="padding:20px 24px;">
        <p style="margin-top:0;">Dựa trên hành vi mua sắm gần đây, BabyMart Plus gợi ý một số sản phẩm phù hợp cho bạn:</p>

        @foreach($products as $product)
            <div style="padding:12px;border:1px solid #e6f4ef;border-radius:10px;margin-bottom:10px;">
                <div style="font-weight:700;color:#143f44;">{{ $product->name }}</div>
                <div style="font-size:14px;color:#5b7a7f;">{{ $product->brand ?: 'Hàng chính hãng' }}</div>
                <div style="font-size:16px;color:#0f766e;font-weight:700;margin-top:4px;">{{ number_format($product->final_price, 0, ',', '.') }}đ</div>
            </div>
        @endforeach

        <p style="margin:14px 0 0;color:#4d6e72;">Truy cập website để xem thêm ưu đãi dành riêng cho bạn.</p>
    </div>
</div>
</body>
</html>
