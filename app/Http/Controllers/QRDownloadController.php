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
            ->where('qr_generated', true)
            ->orderBy('seat_number')
            ->get();

        if ($qrCodes->isEmpty()) {
            return back()->with('error', 'No QR codes found or QR codes are still being generated. Please wait and try again.');
        }

        // Create ZIP file
        $zipFileName = "QR_Codes_Seat_{$start}_to_{$end}.zip";
        $zipFilePath = storage_path('app/' . $zipFileName);

        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($qrCodes as $qrCode) {
                if ($qrCode->qr_image_path && \Storage::exists($qrCode->qr_image_path)) {
                    $filePath = storage_path('app/' . $qrCode->qr_image_path);
                    $zip->addFile($filePath, basename($qrCode->qr_image_path));
                }
            }
            $zip->close();
        }

        // Download the ZIP file
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }
}
