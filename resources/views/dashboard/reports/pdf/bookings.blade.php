@extends('dashboard.reports.pdf.layout')

@section('title', $title)

@section('content')
    <div class="header">
        <div class="logo">نظام إدارة الحجوزات</div>
        <div class="title">{{ $title }}</div>
        <div class="subtitle">تقرير شامل لجميع الحجوزات</div>
    </div>
    
    <div class="report-info">
        <div>
            <strong>من تاريخ:</strong><br>
            {{ \Carbon\Carbon::parse($dateFrom)->format('Y-m-d') }}
        </div>
        <div>
            <strong>إلى تاريخ:</strong><br>
            {{ \Carbon\Carbon::parse($dateTo)->format('Y-m-d') }}
        </div>
        <div>
            <strong>عدد الحجوزات:</strong><br>
            {{ $data->count() }}
        </div>
        <div>
            <strong>تاريخ التقرير:</strong><br>
            {{ $generatedAt }}
        </div>
    </div>
    
    <div class="summary">
        <h3>ملخص الإحصائيات</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <strong>{{ $data->where('status', 'confirmed')->count() }}</strong>
                <span>مؤكدة</span>
            </div>
            <div class="summary-item">
                <strong>{{ $data->where('status', 'pending')->count() }}</strong>
                <span>في الانتظار</span>
            </div>
            <div class="summary-item">
                <strong>{{ $data->where('payment_status', 'paid')->count() }}</strong>
                <span>مدفوعة</span>
            </div>
            <div class="summary-item">
                <strong>{{ number_format($data->where('payment_status', 'paid')->sum(function($booking) { return $booking->total_amount + $booking->tax_amount + $booking->service_fee; }), 2) }} ريال</strong>
                <span>إجمالي الإيرادات</span>
            </div>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>رقم الحجز</th>
                <th>اسم الراكب</th>
                <th>البريد الإلكتروني</th>
                <th>رقم الهاتف</th>
                <th>رقم الرحلة</th>
                <th>الوجهة</th>
                <th>تاريخ السفر</th>
                <th>فئة المقعد</th>
                <th>عدد الركاب</th>
                <th>المبلغ الإجمالي</th>
                <th>حالة الحجز</th>
                <th>حالة الدفع</th>
                <th>تاريخ الحجز</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $booking)
            <tr>
                <td>{{ $booking->booking_reference }}</td>
                <td>{{ $booking->passenger_name }}</td>
                <td>{{ $booking->passenger_email }}</td>
                <td>{{ $booking->passenger_phone }}</td>
                <td>{{ $booking->flight->flight_number ?? 'غير محدد' }}</td>
                <td>{{ ($booking->flight->departure_city ?? 'غير محدد') . ' - ' . ($booking->flight->arrival_city ?? 'غير محدد') }}</td>
                <td>{{ $booking->flight->departure_time ? $booking->flight->departure_time->format('Y-m-d H:i') : 'غير محدد' }}</td>
                <td>{{ ucfirst($booking->seat_class) }}</td>
                <td>{{ $booking->number_of_passengers }}</td>
                <td>{{ number_format($booking->total_amount + $booking->tax_amount + $booking->service_fee, 2) }} ريال</td>
                <td>
                    <span class="status status-{{ $booking->status }}">
                        @switch($booking->status)
                            @case('confirmed')
                                مؤكدة
                                @break
                            @case('pending')
                                في الانتظار
                                @break
                            @case('cancelled')
                                ملغية
                                @break
                            @case('completed')
                                مكتملة
                                @break
                            @default
                                {{ $booking->status }}
                        @endswitch
                    </span>
                </td>
                <td>
                    <span class="payment-status payment-{{ $booking->payment_status }}">
                        @switch($booking->payment_status)
                            @case('paid')
                                مدفوعة
                                @break
                            @case('pending')
                                في الانتظار
                                @break
                            @case('failed')
                                فشلت
                                @break
                            @default
                                {{ $booking->payment_status }}
                        @endswitch
                    </span>
                </td>
                <td>{{ $booking->created_at->format('Y-m-d H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="13" style="text-align: center; padding: 20px; color: #666;">
                    لا توجد حجوزات في الفترة المحددة
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
@endsection
