<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>رسالة تواصل جديدة</title>
    <style>
        body { font-family: Tahoma, Arial, sans-serif; background:#f7f7f9; color:#222; margin:0; padding:0; direction: rtl; unicode-bidi: plaintext; text-align: right; }
        .container { max-width:600px; margin:24px auto; background:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 6px 18px rgba(0,0,0,.06); direction: rtl; text-align: right; }
        .header { background:#0ea5e9; color:#fff; padding:16px 24px; font-size:18px; font-weight:bold; }
        .content { padding:24px; line-height:1.8; direction: rtl; text-align: right; }
        .meta { margin-top:16px; font-size:14px; color:#475569; direction: rtl; text-align: right; }
        .footer { padding:16px 24px; font-size:12px; color:#64748b; border-top:1px solid #e2e8f0; background:#fafafa; }
        .pill { display:inline-block; background:#f1f5f9; color:#0f172a; padding:6px 10px; border-radius:999px; font-weight:600; }
        pre { white-space:pre-wrap; word-wrap:break-word; background:#f8fafc; padding:12px; border-radius:8px; border:1px solid #e2e8f0; }
    </style>
    </head>
<body>
    <div class="container">
        <div class="header">ASKILA - رسالة تواصل جديدة</div>
        <div class="content">
            <p>لديك رسالة جديدة من نموذج التواصل.</p>
            <div class="meta">
                <div>المرسل: <span class="pill">{{ $senderName }}</span></div>
                <div>البريد: <span class="pill">{{ $senderEmail }}</span></div>
            </div>
            <h3 style="margin-top:18px; font-size:16px;">نص الرسالة:</h3>
            <pre>{{ $messageBody }}</pre>
        </div>
        <div class="footer">
            يمكن الرد مباشرة على هذه الرسالة وسيتم توجيهها إلى بريد المرسل عبر Reply-To.
        </div>
    </div>
    </body>
</html>


