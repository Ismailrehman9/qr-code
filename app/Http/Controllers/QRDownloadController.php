<?php

namespace App\Http\Controllers;

use App\Models\QRCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class QRDownloadController extends Controller
{
    public function download(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');
        $batch = $request->get('batch');

        $query = QRCode::whereBetween('seat_number', [$start, $end])
            ->where('qr_generated', true);

        if ($batch) {
            $query->where('generated_for_date', Carbon::parse($batch));
        }

        $qrCodes = $query->orderBy('seat_number')->get();

        if ($qrCodes->isEmpty()) {
            return back()->with('error', 'No QR codes found or QR codes are still being generated. Please wait and try again.');
        }

        // Create ZIP file
        $zipFileName = $batch
            ? 'QR_Codes_' . Carbon::parse($batch)->format('Ymd_His') . '.zip'
            : "QR_Codes_Seat_{$start}_to_{$end}.zip";
        $zipFilePath = storage_path('app/' . $zipFileName);

        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($qrCodes as $qrCode) {
                if ($qrCode->qr_image_path && Storage::exists($qrCode->qr_image_path)) {
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
