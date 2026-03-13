<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn hàng mới</title>
</head>
<body style="margin:0;padding:24px;background:#f4f8ff;font-family:Arial,Helvetica,sans-serif;color:#1f2d3d;">
<div style="max-width:700px;margin:0 auto;background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #dbe6ff;">
    <div style="padding:20px 24px;background:linear-gradient(135deg,#1f7ae0,#4a97ff);color:#ffffff;">
        <h1 style="margin:0;font-size:22px;">Thông báo đơn hàng mới</h1>
        <p style="margin:8px 0 0;font-size:14px;opacity:.95;">Mã đơn: <strong>{{ $order->order_number }}</strong></p>
    </div>

    <div style="padding:20px 24px;">
        <h3 style="margin:0 0 10px;font-size:17px;">Thông tin khách hàng</h3>
        <p style="margin:4px 0;">Họ tên: <strong>{{ $order->customer_name }}</strong></p>
        <p style="margin:4px 0;">Email: <strong>{{ $order->customer_email }}</strong></p>
        <p style="margin:4px 0;">Số điện thoại: <strong>{{ $order->customer_phone }}</strong></p>
        <p style="margin:4px 0;">Địa chỉ: <strong>{{ $order->shipping_address }}</strong></p>

        <h3 style="margin:18px 0 10px;font-size:17px;">Sản phẩm trong đơn</h3>
        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
            <thead>
            <tr>
                <th align="left" style="padding:10px;border-bottom:1px solid #e8efff;">Tên sản phẩm</th>
                <th align="center" style="padding:10px;border-bottom:1px solid #e8efff;">SL</th>
                <th align="right" style="padding:10px;border-bottom:1px solid #e8efff;">Đơn giá</th>
                <th align="right" style="padding:10px;border-bottom:1px solid #e8efff;">Thành tiền</th>
            </tr>
            </thead>
            <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td style="padding:10px;border-bottom:1px solid #f0f4ff;">{{ $item->product_name }}</td>
                    <td align="center" style="padding:10px;border-bottom:1px solid #f0f4ff;">{{ $item->quantity }}</td>
                    <td align="right" style="padding:10px;border-bottom:1px solid #f0f4ff;">{{ number_format($item->unit_price, 0, ',', '.') }}đ</td>
                    <td align="right" style="padding:10px;border-bottom:1px solid #f0f4ff;">{{ number_format($item->line_total, 0, ',', '.') }}đ</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div style="margin-top:14px;padding-top:10px;border-top:2px solid #e8efff;">
            <p style="margin:5px 0;">Tạm tính: <strong>{{ number_format($order->subtotal, 0, ',', '.') }}đ</strong></p>
            <p style="margin:5px 0;">Phí vận chuyển: <strong>{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</strong></p>
            <p style="margin:5px 0;font-size:18px;color:#245bbb;">Tổng cộng: <strong>{{ number_format($order->grand_total, 0, ',', '.') }}đ</strong></p>
        </div>
    </div>
</div>
</body>
</html>
