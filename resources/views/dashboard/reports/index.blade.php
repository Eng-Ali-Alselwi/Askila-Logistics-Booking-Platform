@extends('dashboard.layout.admin', ['title' => t('Reports')])

@section('title', t('Reports & Analytics'))

@section('content')


    <x-dashboard.outer-card :title="t('Reports')">
        <x-slot:header>
            <div class="px-4 border-b-1 border-b-gray-500 py-4 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ t('Reports & Analytics') }}</h2>
                <div class="inline-flex items-center gap-2">
                    <a class="btn btn-success" href="{{ route('dashboard.reports.export', array_merge(['type' => 'shipments', 'format' => 'excel'], request()->only(['date_from', 'date_to']))) }}">{{ t('Export Shipments (Excel)') }}</a>
                    <a class="btn btn-success" href="{{ route('dashboard.reports.export', array_merge(['type' => 'bookings', 'format' => 'excel'], request()->only(['date_from', 'date_to']))) }}">{{ t('Export Bookings (Excel)') }}</a>
                    <a class="btn btn-outline-danger" href="{{ route('dashboard.reports.export', array_merge(['type' => 'shipments', 'format' => 'pdf'], request()->only(['date_from', 'date_to']))) }}">{{ t('Export Shipments (PDF)') }}</a>
                    <a class="btn btn-outline-danger" href="{{ route('dashboard.reports.export', array_merge(['type' => 'bookings', 'format' => 'pdf'], request()->only(['date_from', 'date_to']))) }}">{{ t('Export Bookings (PDF)') }}</a>
                    <button onclick="printReport()" class="btn btn-outline-primary">{{ t('Print Report') }}</button>
                </div>
            </div>
        </x-slot:header>

        <div class="p-4 md:p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div>
                    <label class="block text-sm mb-1">{{ t('From Date') }}</label>
                    <input type="date" name="date_from" class="form-input w-full" value="{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}" />
                </div>
                <div>
                    <label class="block text-sm mb-1">{{ t('To Date') }}</label>
                    <input type="date" name="date_to" class="form-input w-full" value="{{ request('date_to', now()->format('Y-m-d')) }}" />
                </div>
                <div>
                    <label class="block text-sm mb-1">{{ t('Branch') }}</label>
                    <select name="branch_id" class="form-select w-full">
                        <option value="">{{ t('All Branches') }}</option>
                        @foreach(\App\Models\Branch::orderBy('name')->get(['id','name']) as $b)
                            <option value="{{ $b->id }}" @selected(request('branch_id') == $b->id)>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm mb-1">{{ t('Status') }}</label>
                    <select name="status" class="form-select w-full">
                        <option value="">{{ t('All') }}</option>
                        @foreach(\App\Enums\ShipmentStatus::cases() as $case)
                            <option value="{{ $case->value }}" @selected(request('status') == $case->value)>{{ $case->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm mb-1">{{ t('Report Type') }}</label>
                    <select name="report_type" class="form-select w-full">
                        <option value="all" {{ request('report_type') == 'all' ? 'selected' : '' }}>{{ t('All Reports') }}</option>
                        <option value="shipments" {{ request('report_type') == 'shipments' ? 'selected' : '' }}>{{ t('Shipments Only') }}</option>
                        <option value="bookings" {{ request('report_type') == 'bookings' ? 'selected' : '' }}>{{ t('Bookings Only') }}</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="btn btn-primary">{{ t('Update') }}</button>
                    <a href="{{ route('dashboard.reports.index') }}" class="btn btn-outline-secondary">{{ t('Reset') }}</a>
                </div>
            </form>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-4">
                    <div class="text-sm text-primary-600 mb-2">{{ t('Total Shipments') }}</div>
                    <div class="text-2xl font-bold">{{ $shipmentsCount ?? 0 }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-4">
                    <div class="text-sm text-green-600 mb-2">{{ t('Total Bookings') }}</div>
                    <div class="text-2xl font-bold">{{ $bookingsCount ?? 0 }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-4">
                    <div class="text-sm text-cyan-600 mb-2">{{ t('Total Revenue') }}</div>
                    <div class="text-2xl font-bold">{{ number_format($totalRevenue ?? 0) }} {{ t('SAR') }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-4">
                    <div class="text-sm text-amber-600 mb-2">{{ t('New Customers') }}</div>
                    <div class="text-2xl font-bold">{{ $newCustomersCount ?? 0 }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mb-6">
                <div class="xl:col-span-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-4">
                    <div class="text-sm font-semibold mb-3">{{ t('Shipments Statistics') }}</div>
                    <div class="relative h-72">
                        <canvas id="shipmentsChart"></canvas>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-4">
                    <div class="text-sm font-semibold mb-3">{{ t('Bookings Status') }}</div>
                    <div class="relative h-72">
                        <canvas id="bookingsChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-4">
                    <div class="text-sm font-semibold mb-3">{{ t('Recent Shipments') }}</div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-3 py-2 text-right">{{ t('Tracking Number') }}</th>
                                    <th class="px-3 py-2 text-right">{{ t('Sender') }}</th>
                                    <th class="px-3 py-2 text-right">{{ t('Status') }}</th>
                                    <th class="px-3 py-2 text-right">{{ t('Date') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($recentShipments ?? [] as $shipment)
                                    <tr>
                                        <td class="px-3 py-2">{{ $shipment->tracking_number }}</td>
                                        <td class="px-3 py-2">{{ $shipment->sender_name }}</td>
                                        <td class="px-3 py-2">
                                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $shipment->current_status === 'delivered' ? 'bg-green-100 text-green-800 dark:bg-green-800/20 dark:text-green-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-800/20 dark:text-amber-300' }}">
                                                {{ $shipment->current_status_label }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2">{{ $shipment->created_at->format('Y-m-d') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-3 py-6 text-center text-gray-500">{{ t('No shipments found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-4">
                    <div class="text-sm font-semibold mb-3">{{ t('Recent Bookings') }}</div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-3 py-2 text-right">{{ t('Booking Reference') }}</th>
                                    <th class="px-3 py-2 text-right">{{ t('Passenger') }}</th>
                                    <th class="px-3 py-2 text-right">{{ t('Status') }}</th>
                                    <th class="px-3 py-2 text-right">{{ t('Amount') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($recentBookings ?? [] as $booking)
                                    <tr>
                                        <td class="px-3 py-2">{{ $booking->booking_reference }}</td>
                                        <td class="px-3 py-2">{{ $booking->passenger_name }}</td>
                                        <td class="px-3 py-2">
                                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800 dark:bg-green-800/20 dark:text-green-300' : ($booking->status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-800/20 dark:text-red-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-800/20 dark:text-amber-300') }}">
                                                {{ $booking->status === 'confirmed' ? t('Confirmed') : ($booking->status === 'cancelled' ? t('Cancelled') : t('Pending')) }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2">{{ number_format($booking->total_amount + $booking->tax_amount + $booking->service_fee) }} {{ t('SAR') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-3 py-6 text-center text-gray-500">{{ t('No bookings found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </x-dashboard.outer-card>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Shipments Chart
    const shipmentsCtx = document.getElementById('shipmentsChart').getContext('2d');
    const shipmentsChart = new Chart(shipmentsCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($shipmentsChartData['labels'] ?? []) !!},
            datasets: [{
                label: '{{ t("Shipments") }}',
                data: {!! json_encode($shipmentsChartData['data'] ?? []) !!},
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Bookings Chart
    const bookingsCtx = document.getElementById('bookingsChart').getContext('2d');
    const bookingsChart = new Chart(bookingsCtx, {
        type: 'doughnut',
        data: {
            labels: ['{{ t("Confirmed") }}', '{{ t("Pending") }}', '{{ t("Cancelled") }}'],
            datasets: [{
                data: {!! json_encode($bookingsChartData ?? [10, 5, 2]) !!},
                backgroundColor: [
                    'rgb(34, 197, 94)',
                    'rgb(251, 191, 36)',
                    'rgb(239, 68, 68)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
        });
    </script>
    
    <script>
        function printReport() {
            // إنشاء نافذة جديدة للطباعة
            const printWindow = window.open('', '_blank');
            
            // محتوى التقرير للطباعة
            const reportContent = `
                <!DOCTYPE html>
                <html dir="rtl" lang="ar">
                <head>
                    <meta charset="UTF-8">
                    <title>تقرير الأسكلة</title>
                    <style>
                        body {
                            font-family: "Arial", "Tahoma", sans-serif;
                            margin: 20px;
                            color: #333;
                            direction: rtl;
                        }
                        .header {
                            text-align: center;
                            margin-bottom: 30px;
                            border-bottom: 3px solid #2563eb;
                            padding-bottom: 20px;
                        }
                        .logo {
                            font-size: 24px;
                            font-weight: bold;
                            color: #2563eb;
                            margin-bottom: 10px;
                        }
                        .title {
                            font-size: 20px;
                            font-weight: bold;
                            margin-bottom: 5px;
                        }
                        .subtitle {
                            font-size: 14px;
                            color: #666;
                        }
                        .report-info {
                            background: #f8fafc;
                            padding: 15px;
                            border-radius: 8px;
                            margin-bottom: 20px;
                            border-right: 4px solid #2563eb;
                        }
                        .info-row {
                            display: flex;
                            justify-content: space-between;
                            margin-bottom: 8px;
                        }
                        .info-label {
                            font-weight: bold;
                            color: #374151;
                        }
                        .info-value {
                            color: #6b7280;
                        }
                        .stats-grid {
                            display: grid;
                            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                            gap: 15px;
                            margin-bottom: 30px;
                        }
                        .stat-card {
                            background: white;
                            border: 1px solid #e5e7eb;
                            border-radius: 8px;
                            padding: 15px;
                            text-align: center;
                            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                        }
                        .stat-number {
                            font-size: 20px;
                            font-weight: bold;
                            color: #2563eb;
                            margin-bottom: 5px;
                        }
                        .stat-label {
                            font-size: 12px;
                            color: #6b7280;
                        }
                        .section-title {
                            font-size: 16px;
                            font-weight: bold;
                            color: #374151;
                            margin-bottom: 15px;
                            padding-bottom: 8px;
                            border-bottom: 2px solid #e5e7eb;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-bottom: 20px;
                            background: white;
                            font-size: 12px;
                        }
                        th {
                            background: #f3f4f6;
                            color: #374151;
                            font-weight: bold;
                            padding: 8px 6px;
                            text-align: right;
                            border: 1px solid #d1d5db;
                        }
                        td {
                            padding: 6px;
                            border: 1px solid #d1d5db;
                            text-align: right;
                        }
                        tr:nth-child(even) {
                            background: #f9fafb;
                        }
                        .status-badge {
                            padding: 2px 6px;
                            border-radius: 4px;
                            font-size: 10px;
                            font-weight: bold;
                        }
                        .status-confirmed {
                            background: #dcfce7;
                            color: #166534;
                        }
                        .status-pending {
                            background: #fef3c7;
                            color: #92400e;
                        }
                        .status-cancelled {
                            background: #fee2e2;
                            color: #991b1b;
                        }
                        .status-delivered {
                            background: #dcfce7;
                            color: #166534;
                        }
                        .status-shipped {
                            background: #dbeafe;
                            color: #1e40af;
                        }
                        .footer {
                            margin-top: 40px;
                            text-align: center;
                            font-size: 10px;
                            color: #6b7280;
                            border-top: 1px solid #e5e7eb;
                            padding-top: 20px;
                        }
                        @media print {
                            body { margin: 0; }
                            .header { page-break-after: avoid; }
                            .stats-grid { page-break-inside: avoid; }
                            table { page-break-inside: avoid; }
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <div class="logo">مجموعة الأسكلة</div>
                        <div class="title">تقرير شامل</div>
                        <div class="subtitle">تقرير مفصل ومفصل</div>
                    </div>

                    <div class="report-info">
                        <div class="info-row">
                            <span class="info-label">فترة التقرير:</span>
                            <span class="info-value">من ${document.querySelector('input[name="date_from"]').value} إلى ${document.querySelector('input[name="date_to"]').value}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">تاريخ الإصدار:</span>
                            <span class="info-value">${new Date().toLocaleString('ar-SA')}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">نوع التقرير:</span>
                            <span class="info-value">${document.querySelector('select[name="report_type"]').selectedOptions[0].text}</span>
                        </div>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-number">${document.querySelector('.text-2xl.font-bold').textContent}</div>
                            <div class="stat-label">إجمالي الشحنات</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">${document.querySelectorAll('.text-2xl.font-bold')[1].textContent}</div>
                            <div class="stat-label">إجمالي الحجوزات</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">${document.querySelectorAll('.text-2xl.font-bold')[2].textContent}</div>
                            <div class="stat-label">إجمالي الإيرادات</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">${document.querySelectorAll('.text-2xl.font-bold')[3].textContent}</div>
                            <div class="stat-label">العملاء الجدد</div>
                        </div>
                    </div>

                    <div class="section-title">ملخص التقرير</div>
                    <p>هذا التقرير يحتوي على جميع البيانات والإحصائيات للفترة المحددة. يمكن استخدام هذا التقرير للمراجعة والتحليل.</p>

                    <div class="footer">
                        <p>تم إنشاء هذا التقرير بواسطة نظام إدارة الأسكلة</p>
                        <p>جميع الحقوق محفوظة © ${new Date().getFullYear()}</p>
                    </div>
                </body>
                </html>
            `;
            
            printWindow.document.write(reportContent);
            printWindow.document.close();
            
            // انتظار تحميل المحتوى ثم طباعة
            printWindow.onload = function() {
                printWindow.print();
                printWindow.close();
            };
        }
    </script>
@endsection