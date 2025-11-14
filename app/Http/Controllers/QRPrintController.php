<?php

namespace App\Http\Controllers;

use App\Models\QRCode;
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

        $baseUrl = config('app.url') . '/form?id=';
        foreach ($qrCodes as $qr) {
            $qr->url = $baseUrl . $qr->code;
        }

        // 1 QR code per page → no chunk
        $pages = $qrCodes; // each item is one page

        return view('print.qr-batch', [
            'pages' => $pages,
            'batchRange' => "#{$start} – #{$end}",
            'totalCodes' => $qrCodes->count(),
        ]);
    }
}