<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận đơn hàng</title>
</head>
<body style="margin:0;padding:24px;background:#f2fbf8;font-family:Arial,Helvetica,sans-serif;color:#1e3a3f;">
<div style="max-width:680px;margin:0 auto;background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #d9efea;">
    <div style="padding:20px 24px;background:linear-gradient(135deg,#00bba7,#33d1bf);color:#ffffff;">
        <h1 style="margin:0;font-size:22px;">Cảm ơn bạn đã đặt hàng tại BabyMart Plus</h1>
        <p style="margin:8px 0 0;font-size:14px;opacity:.95;">Mã đơn hàng: <strong>{{ $order->order_number }}</strong></p>
    </div>

    <div style="padding:20px 24px;">
        <p style="margin-top:0;">Xin chào {{ $order->customer_name }}, đơn hàng của bạn đã được ghi nhận thành công.</p>
        <p>Chúng tôi sẽ liên hệ qua số <strong>{{ $order->customer_phone }}</strong> để xác nhận giao hàng sớm nhất.</p>

        <h3 style="margin:20px 0 10px;font-size:17px;">Chi tiết đơn hàng</h3>
        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
            <thead>
            <tr>
                <th align="left" style="padding:10px;border-bottom:1px solid #e5f3ef;">Sản phẩm</th>
                <th align="center" style="padding:10px;border-bottom:1px solid #e5f3ef;">SL</th>
                <th align="right" style="padding:10px;border-bottom:1px solid #e5f3ef;">Thành tiền</th>
            </tr>
            </thead>
            <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td style="padding:10px;border-bottom:1px solid #eef7f4;">{{ $item->product_name }}</td>
                    <td align="center" style="padding:10px;border-bottom:1px solid #eef7f4;">{{ $item->quantity }}</td>
                    <td align="right" style="padding:10px;border-bottom:1px solid #eef7f4;">{{ number_format($item->line_total, 0, ',', '.') }}đ</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div style="margin-top:14px;padding-top:10px;border-top:2px solid #e5f3ef;">
            <p style="margin:5px 0;">Tạm tính: <strong>{{ number_format($order->subtotal, 0, ',', '.') }}đ</strong></p>
            <p style="margin:5px 0;">Phí vận chuyển: <strong>{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</strong></p>
            <p style="margin:5px 0;font-size:18px;color:#0e766e;">Tổng thanh toán: <strong>{{ number_format($order->grand_total, 0, ',', '.') }}đ</strong></p>
        </div>

        <p style="margin:18px 0 0;color:#4d6e72;">Địa chỉ giao hàng: {{ $order->shipping_address }}</p>
    </div>
</div>
</body>
</html>
