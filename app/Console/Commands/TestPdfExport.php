<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\PdfHelper;
use App\Models\Shipment;
use App\Models\Booking;

class TestPdfExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:pdf-export {type=shipments}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test PDF export functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->argument('type');
        
        $this->info("Testing PDF export for type: {$type}");
        
        try {
            if ($type === 'shipments') {
                $data = Shipment::with(['customer', 'branch', 'destinationBranch'])->limit(10)->get();
                $title = 'تقرير الشحنات التجريبي';
            } elseif ($type === 'bookings') {
                $data = Booking::with(['flight', 'customer'])->limit(10)->get();
                $title = 'تقرير الحجوزات التجريبي';
            } else {
                $this->error('Invalid type. Use: shipments or bookings');
                return 1;
            }
            
            $dateFrom = now()->startOfMonth()->format('Y-m-d');
            $dateTo = now()->format('Y-m-d');
            
            $this->info("Generating PDF with {$data->count()} records...");
            
            $pdf = PdfHelper::generateReportPdf($data, $type, $title, $dateFrom, $dateTo);
            
            $filename = storage_path("app/test_{$type}_report.pdf");
            $pdf->save($filename);
            
            $this->info("PDF generated successfully: {$filename}");
            $this->info("File size: " . number_format(filesize($filename) / 1024, 2) . " KB");
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("Error generating PDF: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
            return 1;
        }
    }
}