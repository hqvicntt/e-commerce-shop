<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation Invoice</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 40px 0; color: #333333;">

    <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden; padding: 30px;">
        <tr>
            <td>
                <!-- Logo & Brand Header -->
                <h2 style="color: #dc3545; font-weight: bold; margin: 0 0 10px 0; text-transform: uppercase;">E-SHOP GLOBAL</h2>
                <h3 style="color: #212529; margin: 0 0 20px 0;">Thank You for Your Order!</h3>
                <p style="font-size: 14px; line-height: 1.6; color: #6c757d; margin-bottom: 30px;">
                    Hello, <strong>{{ $order->user->name }}</strong>. We are pleased to confirm that your purchase transaction has been successfully processed and recorded.
                </p>

                <!-- Order Reference Data Metadata Table -->
                <table width="100%" style="background-color: #f1f3f5; padding: 15px; border-radius: 6px; margin-bottom: 30px; font-size: 14px;">
                    <tr>
                        <td><strong>Order Reference:</strong> #{{ $order->id }}</td>
                        <td align="right"><strong>Date:</strong> {{ $order->created_at->format('M d, Y - H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Payment Gateway:</strong> Session Wallet</td>
                        <td align="right"><strong>Status:</strong> <span style="color: #d63384; font-weight: bold; text-transform: uppercase;">{{ $order->status }}</span></td>
                    </tr>
                </table>

                <!-- Summary Breakdown Data List -->
                <h4 style="color: #212529; text-transform: uppercase; margin: 0 0 15px 0; border-bottom: 2px solid #343a40; padding-bottom: 5px;">Purchased Items Specification</h4>
                <p style="font-size: 15px; font-family: monospace; color: #495057; line-height: 1.8; margin-bottom: 30px; background: #fafafa; padding: 10px; border: 1px solid #e9ecef;">
                    {{ $order->product_names }}
                </p>

                <!-- Financial Total Pricing Block -->
                <table width="100%" style="border-top: 1px solid #dee2e6; padding-top: 15px;">
                    <tr>
                        <td style="font-size: 16px; color: #495057;"><strong>Grand Total Amount Paid:</strong></td>
                        <td align="right" style="font-size: 22px; color: #0d6efd; font-weight: bold;">${{ number_format($order->total_price, 2) }}</td>
                    </tr>
                </table>

                <!-- Footer Sign-off Notation Footer -->
                <hr style="border: 0; border-top: 1px solid #e9ecef; margin: 40px 0 20px 0;">
                <p align="center" style="font-size: 12px; color: #adb5bd; margin: 0;">
                    This is an automated operational notification message dispatch sheet. Please do not reply directly to this mail box.
                </p>
            </td>
        </tr>
    </table>

</body>
</html>
