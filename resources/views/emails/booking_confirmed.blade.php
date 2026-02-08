<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تم تأكيد حجزك بنجاح</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7fa; color: #333; margin: 0; padding: 0; direction: rtl; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f4f7fa; padding: 40px 0; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05); }
        .header { background: linear-gradient(135deg, #16a34a, #15803d); padding: 30px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 24px; font-weight: bold; }
        .content { padding: 40px; line-height: 1.6; }
        .welcome-text { font-size: 18px; margin-bottom: 20px; color: #1e293b; }
        .status-box { background-color: #f0fdf4; border: 2px solid #bbf7d0; border-radius: 8px; padding: 20px; text-align: center; margin: 25px 0; }
        .status-label { font-size: 14px; color: #166534; font-weight: bold; margin-bottom: 5px; }
        .reference-code { font-size: 28px; font-weight: 800; color: #14532d; font-family: monospace; }
        .details-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .details-table td { padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
        .details-label { color: #64748b; font-size: 14px; }
        .details-value { font-weight: 600; color: #1e293b; text-align: left; }
        .footer { background-color: #f8fafc; padding: 20px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0; }
        .btn { display: inline-block; padding: 12px 30px; background-color: #16a34a; color: #ffffff !important; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>{{ config('app.name') }}</h1>
                <p>تم تأكيد الحجز بنجاح</p>
            </div>
            
            <div class="content">
                <p class="welcome-text">مرحباً {{ $booking->passenger_name }}،</p>
                <p>يسعدنا إبلاغك بأنه قد تم تأكيد حجزك رسمياً. يمكنك الآن تتبع تفاصيل رحلتك عبر موقعنا.</p>
                
                <div class="status-box">
                    <div class="status-label">حالة الحجز: مؤكد ✅</div>
                    <div class="reference-code">{{ $booking->booking_reference }}</div>
                </div>

                <table class="details-table">
                    <tr>
                        <td class="details-label">رقم الرحلة:</td>
                        <td class="details-value">{{ optional($booking->flight)->flight_number }}</td>
                    </tr>
                    <tr>
                        <td class="details-label">الوجهة:</td>
                        <td class="details-value">{{ optional($booking->flight)->departure_city }} ➔ {{ optional($booking->flight)->arrival_city }}</td>
                    </tr>
                    <tr>
                        <td class="details-label">عدد الركاب:</td>
                        <td class="details-value">{{ $booking->number_of_passengers }}</td>
                    </tr>
                    <tr>
                        <td class="details-label">تاريخ السفر:</td>
                        <td class="details-value">{{ optional($booking->travel_date)?->format('Y-m-d') }}</td>
                    </tr>
                </table>

                <div style="text-align: center;">
                    <a class="btn" href="{{ route('booking.track.form', ['booking_reference' => $booking->booking_reference]) }}">عرض التذكرة والتفاصيل</a>
                </div>
            </div>

            <div class="footer">
                <p>نتمنى لك رحلة سعيدة وموفقة.</p>
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </div>
</body>
</html>
