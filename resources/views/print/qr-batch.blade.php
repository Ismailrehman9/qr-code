<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print QR Batch {{ $batchRange }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.5cm;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: #f9f9f9;
        }

        .print-container {
            width: 100%;
            max-width: 21cm;
            margin: 0 auto;
            background: white;
        }

        .page {
            height: 29.7cm;
            width: 21cm;
            page-break-after: always;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2cm 1.5cm;
            position: relative;
        }

        .header {
            position: absolute;
            top: 1cm;
            left: 1.5cm;
            right: 1.5cm;
            text-align: center;
            font-size: 14px;
            color: #4b5563;
            font-weight: 600;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
        }

        .qr-grid {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-top: 3cm;
            gap: 1.5cm;
        }

        .qr-item {
            flex: 1;
            text-align: center;
            max-width: 48%;
        }

        .qr-item img {
            width: 100%;
            max-width: 220px;
            height: 220px;
            image-rendering: crisp-edges;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 8px;
            background: white;
        }

        .qr-item p {
            margin: 12px 0 0;
            font-weight: bold;
            font-size: 16px;
            color: #1f2937;
        }

        .page-number {
            position: absolute;
            bottom: 1cm;
            font-size: 12px;
            color: #9ca3af;
        }

        /* Hide buttons when printing */
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
        }

        .no-print {
            text-align: center;
            padding: 20px;
            background: #f3f4f6;
        }

        .btn {
            padding: 12px 28px;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin: 0 8px;
            transition: 0.2s;
        }

        .btn-print {
            background: #4f46e5;
            color: white;
        }

        .btn-print:hover { background: #4338ca; }

        .btn-close {
            background: #6b7280;
            color: white;
        }

        .btn-close:hover { background: #4b5563; }
    </style>
</head>
<body onload="window.print()">

<div class="print-container">

    @foreach($pages as $index => $pair)
        <div class="page">
            <div class="header">
                QR Batch: {{ $batchRange }} | Page {{ $index + 1 }} of {{ $pages->count() }}
            </div>

            <div class="qr-grid">
                @foreach($pair as $qr)
                    <div class="qr-item">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data={{ urlencode($qr->url) }}" 
                             alt="QR {{ $qr->seat_number }}">
                        <p>#{{ $qr->seat_number }} – {{ $qr->code }}</p>
                    </div>
                @endforeach

                @if($pair->count() == 1)
                    <div class="qr-item"></div>
                @endif
            </div>

            <div class="page-number">Page {{ $index + 1 }}</div>
        </div>
    @endforeach

</div>

<!-- Print & Close Buttons (only on screen) -->
<div class="no-print">
    <button class="btn btn-print" onclick="window.print()">Print Now</button>
    <button class="btn btn-close" onclick="window.close()">Close</button>
</div>

</body>
</html>