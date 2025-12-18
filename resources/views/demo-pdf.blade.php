<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار تصدير PDF</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
            direction: rtl;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #2563eb;
        }
        .title {
            font-size: 24px;
            color: #2563eb;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #666;
            font-size: 16px;
        }
        .demo-section {
            margin-bottom: 30px;
        }
        .demo-section h3 {
            color: #333;
            margin-bottom: 15px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin: 5px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #1d4ed8;
        }
        .btn-success {
            background: #10b981;
        }
        .btn-success:hover {
            background: #059669;
        }
        .btn-danger {
            background: #ef4444;
        }
        .btn-danger:hover {
            background: #dc2626;
        }
        .info-box {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-right: 4px solid #2563eb;
        }
        .info-box h4 {
            margin: 0 0 10px 0;
            color: #2563eb;
        }
        .info-box p {
            margin: 5px 0;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="title">اختبار تصدير التقارير إلى PDF</h1>
            <p class="subtitle">نظام إدارة الشحنات والحجوزات</p>
        </div>

        <div class="info-box">
            <h4>معلومات النظام</h4>
            <p><strong>الحالة:</strong> ✅ تم إعداد النظام بنجاح</p>
            <p><strong>المكتبة المستخدمة:</strong> Laravel DomPDF</p>
            <p><strong>الدعم العربي:</strong> ✅ مدعوم بالكامل</p>
            <p><strong>حجم الورق:</strong> A4 أفقي</p>
        </div>

        <div class="demo-section">
            <h3>اختبار تصدير الشحنات</h3>
            <p>اضغط على الأزرار أدناه لاختبار تصدير تقارير الشحنات:</p>
            <a href="{{ route('demo.pdf', 'shipments') }}" class="btn btn-danger" target="_blank">
                📄 تصدير تقرير الشحنات (PDF)
            </a>
        </div>

        <div class="demo-section">
            <h3>اختبار تصدير الحجوزات</h3>
            <p>اضغط على الأزرار أدناه لاختبار تصدير تقارير الحجوزات:</p>
            <a href="{{ route('demo.pdf', 'bookings') }}" class="btn btn-danger" target="_blank">
                📄 تصدير تقرير الحجوزات (PDF)
            </a>
        </div>

        <div class="demo-section">
            <h3>الوصول إلى لوحة التحكم</h3>
            <p>للوصول إلى نظام التقارير الكامل:</p>
            <a href="/dashboard/reports" class="btn btn-success">
                🏠 الذهاب إلى لوحة التحكم
            </a>
        </div>

        <div class="info-box">
            <h4>ملاحظات مهمة</h4>
            <p>• يتم إنشاء PDF في الوقت الفعلي</p>
            <p>• يدعم اللغة العربية بالكامل</p>
            <p>• يمكن طباعة أو حفظ الملف</p>
            <p>• التصميم محسن للطباعة</p>
        </div>
    </div>
</body>
</html>
