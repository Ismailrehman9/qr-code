<?php

namespace App\Http\Controllers;

use App\Models\QRCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class QRPrintController extends Controller
{

    public function preview(Request $request)
    {
        $start = $request->query('start');
        $end   = $request->query('end');

        $qrCodes = QRCode::whereBetween('seat_number', [$start, $end])
                         ->orderBy('seat_number')
                         ->get();

        if ($qrCodes->isEmpty()) {
            return redirect()->back()->with('error', 'No QR codes found.');
        }

        // Build full URL
        $baseUrl = config('app.url') . '/form?id=';
        foreach ($qrCodes as $qr) {
            $qr->url = $baseUrl . $qr->code;
        }


        // Chunk into 2 per page
        $pages = $qrCodes->chunk(2);

        return view('print.qr-batch', [
            'pages' => $pages,
            'batchRange' => "#{$start} – #{$end}",
            'totalCodes' => $qrCodes->count(),
        ]);
    }
}