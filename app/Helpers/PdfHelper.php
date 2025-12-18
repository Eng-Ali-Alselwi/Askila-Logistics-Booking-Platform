<?php

namespace App\Helpers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

class PdfHelper
{
    /**
     * إنشاء PDF للتقرير
     */
    public static function generateReportPdf($data, $type, $title, $dateFrom, $dateTo)
    {
        $pdf = Pdf::loadView("dashboard.reports.pdf.{$type}", [
            'data' => $data,
            'title' => $title,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'generatedAt' => now()->format('Y-m-d H:i:s')
        ]);
        
        // إعدادات PDF محسنة للعربية
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
            'defaultFont' => 'DejaVu Sans',
            'fontDir' => storage_path('fonts'),
            'fontCache' => storage_path('fonts'),
            'tempDir' => sys_get_temp_dir(),
            'chroot' => realpath(base_path()),
            'logOutputFile' => null,
            'defaultMediaType' => 'screen',
            'defaultPaperSize' => 'a4',
            'defaultPaperOrientation' => 'landscape',
            'dpi' => 96,
            'enablePhp' => false,
            'enableJavascript' => true,
            'enableRemote' => false,
            'fontHeightRatio' => 1.1,
            'enableHtml5Parser' => true,
        ]);
        
        return $pdf;
    }
    
    /**
     * تحميل PDF
     */
    public static function downloadPdf($data, $type, $title, $dateFrom, $dateTo)
    {
        $pdf = self::generateReportPdf($data, $type, $title, $dateFrom, $dateTo);
        $filename = "{$type}_report_" . now()->format('Y-m-d_H-i-s') . '.pdf';
        
        return $pdf->download($filename);
    }
    
    /**
     * عرض PDF في المتصفح
     */
    public static function streamPdf($data, $type, $title, $dateFrom, $dateTo)
    {
        $pdf = self::generateReportPdf($data, $type, $title, $dateFrom, $dateTo);
        $filename = "{$type}_report_" . now()->format('Y-m-d_H-i-s') . '.pdf';
        
        return $pdf->stream($filename);
    }
}
