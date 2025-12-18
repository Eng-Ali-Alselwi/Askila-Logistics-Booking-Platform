<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تأكيد الحجز</title>
    <style>
        body { font-family: Tahoma, Arial, sans-serif; background:#f7f7f9; color:#222; margin:0; padding:0; direction: rtl; unicode-bidi: plaintext; text-align: right; }
        .container { max-width:600px; margin:24px auto; background:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 6px 18px rgba(0,0,0,.06); direction: rtl; text-align: right; }
        .header { background:#16a34a; color:#fff; padding:16px 24px; font-size:18px; font-weight:bold; }
        .content { padding:24px; line-height:1.8; direction: rtl; text-align: right; }
        .code { display:inline-block; background:#f1f5f9; color:#0f172a; padding:8px 14px; border-radius:6px; font-weight:700; letter-spacing:0.5px; }
        .meta { margin-top:16px; font-size:14px; color:#475569; direction: rtl; text-align: right; }
        .footer { padding:16px 24px; font-size:12px; color:#64748b; border-top:1px solid #e2e8f0; background:#fafafa; }
    </style>
    </head>
<body>
    <div class="container">
        <div class="header">ASKILA - تأكيد الحجز</div>
        <div class="content">
            <p>مرحباً {{ $booking->passenger_name }},</p>
            <p><strong>لقد تم تأكيد حجزك بنجاح.</strong></p>
            <p>رقم الحجز: <span class="code">{{ $booking->booking_reference }}</span></p>
            <div class="meta">
                <div>رحلة: {{ optional($booking->flight)->flight_number }} — {{ optional($booking->flight)->airline }}</div>
                <div>عدد المسافرين: {{ $booking->number_of_passengers }}</div>
                <div>التاريخ: {{ optional($booking->travel_date)?->format('Y-m-d') }}</div>
            </div>
        </div>
        <div class="footer">
            نتمنى لك رحلة موفقة.
        </div>
    </div>
    </body>
</html>


