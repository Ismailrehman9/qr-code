<?php

namespace App\Http\Controllers;

use App\Models\QRCode;
use Illuminate\Http\Request;
use ZipArchive;

class QRDownloadController extends Controller
{
    public function download(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');

        $qrCodes = QRCode::whereBetween('seat_number', [$start, $end])
            ->orderBy('seat_number')
            ->get();

        if ($qrCodes->isEmpty()) {
            return back()->with('error', 'No QR codes found in this range.');
        }

        // Create temporary directory
        $tempDir = storage_path('app/temp_qr_' . time());
        mkdir($tempDir, 0755, true);

        // Generate QR code images
        foreach ($qrCodes as $qrCode) {
            $url = config('app.url') . '/form?id=' . $qrCode->code;
            $qrImageUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=' . urlencode($url);
            
            $imageContent = file_get_contents($qrImageUrl);
            $filename = "QR_Seat_{$qrCode->seat_number}_{$qrCode->code}.png";
            file_put_contents($tempDir . '/' . $filename, $imageContent);
        }

        // Create ZIP file
        $zipFileName = "QR_Codes_Seat_{$start}_to_{$end}.zip";
        $zipFilePath = storage_path('app/' . $zipFileName);

        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $files = glob($tempDir . '/*.png');
            foreach ($files as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();
        }

        // Clean up temp directory
        array_map('unlink', glob($tempDir . '/*.png'));
        rmdir($tempDir);

        // Download the ZIP file
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }
}
