<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تأكيد استلام طلب الحجز</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7fa; color: #333; margin: 0; padding: 0; direction: rtl; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f4f7fa; padding: 40px 0; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05); }
        .header { background: linear-gradient(135deg, #0284c7, #0369a1); padding: 30px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 24px; font-weight: bold; }
        .content { padding: 40px; line-height: 1.6; }
        .welcome-text { font-size: 18px; margin-bottom: 20px; color: #1e293b; }
        .reference-box { background-color: #f0f9ff; border: 2px dashed #0ea5e9; border-radius: 8px; padding: 20px; text-align: center; margin: 25px 0; }
        .reference-label { font-size: 14px; color: #0369a1; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
        .reference-code { font-size: 32px; font-weight: 800; color: #0c4a6e; font-family: monospace; letter-spacing: 2px; }
        .details-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .details-table td { padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
        .details-label { color: #64748b; font-size: 14px; }
        .details-value { font-weight: 600; color: #1e293b; text-align: left; }
        .instructions { background-color: #fffbeb; border-right: 4px solid #f59e0b; padding: 15px; margin: 20px 0; font-size: 14px; color: #92400e; }
        .footer { background-color: #f8fafc; padding: 20px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0; }
        .btn { display: inline-block; padding: 12px 30px; background-color: #0ea5e9; color: #ffffff !important; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>{{ config('app.name') }}</h1>
                <p>تأكيد استلام طلب الحجز</p>
            </div>
            
            <div class="content">
                <p class="welcome-text">مرحباً {{ $booking->passenger_name }}،</p>
                <p>شكراً لاختيارك {{ config('app.name') }}. لقد تلقينا طلب الحجز الخاص بك وهو الآن قيد المعالجة.</p>
                
                <div class="reference-box">
                    <div class="reference-label">رقم مرجع الحجز</div>
                    <div class="reference-code">{{ $booking->booking_reference }}</div>
                </div>

                <div class="instructions">
                    @if($booking->payment_method === 'whatsapp')
                        <strong>ملاحظة:</strong> لقد اخترت الدفع عبر الواتساب. سيقوم فريقنا بالتواصل معك قريباً لإكمال إجراءات الدفع وتأكيد الحجز.
                    @elseif($booking->payment_method === 'on_arrival')
                        <strong>ملاحظة:</strong> لقد اخترت الدفع عند الحضور. يرجى التوجه إلى مكتبنا مع رقم مرجع الحجز لإتمام عملية الدفع.
                    @endif
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
                        <td class="details-value">{{ optional($booking->travel_date)?->format('Y-m-d') ?? 'سيتم تحديده لاحقاً' }}</td>
                    </tr>
                    <tr>
                        <td class="details-label">إجمالي المبلغ:</td>
                        <td class="details-value">{{ number_format($booking->total_amount + $booking->tax_amount + $booking->service_fee) }} ريال سعودي</td>
                    </tr>
                </table>
            </div>

            <div class="footer">
                <p>هذا البريد الإلكتروني مرسل بشكل تلقائي، يرجى عدم الرد عليه.</p>
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </div>
</body>
</html>
