@extends('dashboard.reports.pdf.layout')

@section('title', $title)

@section('content')
    <div class="header">
        <div class="logo">نظام إدارة الشحنات</div>
        <div class="title">{{ $title }}</div>
        <div class="subtitle">تقرير شامل لجميع الشحنات</div>
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
            <strong>عدد الشحنات:</strong><br>
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
                <strong>{{ $data->where('status', 'pending')->count() }}</strong>
                <span>في الانتظار</span>
            </div>
            <div class="summary-item">
                <strong>{{ $data->where('status', 'processing')->count() }}</strong>
                <span>قيد المعالجة</span>
            </div>
            <div class="summary-item">
                <strong>{{ $data->where('status', 'shipped')->count() }}</strong>
                <span>تم الشحن</span>
            </div>
            <div class="summary-item">
                <strong>{{ $data->where('status', 'delivered')->count() }}</strong>
                <span>تم التسليم</span>
            </div>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>رقم الشحنة</th>
                <th>اسم العميل</th>
                <th>رقم الهاتف</th>
                <th>الفرع المرسل</th>
                <th>الفرع المستقبل</th>
                <th>نوع الشحنة</th>
                <th>الوزن (كجم)</th>
                <th>القيمة</th>
                <th>الحالة</th>
                <th>تاريخ الإنشاء</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $shipment)
            <tr>
                <td>{{ $shipment->tracking_number }}</td>
                <td>{{ $shipment->customer->name ?? 'غير محدد' }}</td>
                <td>{{ $shipment->customer->phone ?? 'غير محدد' }}</td>
                <td>{{ $shipment->branch->name ?? 'غير محدد' }}</td>
                <td>{{ $shipment->destinationBranch->name ?? 'غير محدد' }}</td>
                <td>{{ $shipment->type }}</td>
                <td>{{ $shipment->weight }}</td>
                <td>{{ number_format($shipment->value, 2) }} ريال</td>
                <td>
                    <span class="status status-{{ $shipment->status }}">
                        @switch($shipment->status)
                            @case('pending')
                                في الانتظار
                                @break
                            @case('processing')
                                قيد المعالجة
                                @break
                            @case('shipped')
                                تم الشحن
                                @break
                            @case('delivered')
                                تم التسليم
                                @break
                            @case('cancelled')
                                ملغى
                                @break
                            @default
                                {{ $shipment->status }}
                        @endswitch
                    </span>
                </td>
                <td>{{ $shipment->created_at->format('Y-m-d H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align: center; padding: 20px; color: #666;">
                    لا توجد شحنات في الفترة المحددة
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
@endsection
