<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print QR - {{ $batchRange }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;600&family=Inter:wght@500;600&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }

        * { 
            box-sizing: border-box; 
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: white;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* A4 Page - Exact Size */
        .page {
            width: 21cm;
            height: 29.7cm;
            page-break-after: always;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            padding: 1.8cm 1.5cm 1.5cm;
            position: relative;
            background: white;
            margin: 0 auto;
            overflow: hidden;
        }

        /* Heading - Perfect Center */
        .heading {
            font-family: 'Poppins', sans-serif;
            font-size: 42px;
            font-weight: 700;
            color: #111827;
            text-align: center;
            margin-bottom: 0.8cm;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            width: 100%;
        }

        /* QR Container - Full Width, No Extra Padding */
        .qr-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            padding:  0; /* Removed side padding */
        }

        /* QR Item - Fixed Width, Auto Margin */
        .qr-item {
            text-align: center;
            width: 100%;
            max-width: 500px;
            margin: 0 auto; /* Forces perfect center */
        }

        .qr-item img {
            width: 100%;
            height: auto;
            max-width: 500px;
            max-height: 500px;
            image-rendering: crisp-edges;
            border: 4px solid #e5e7eb;
            border-radius: 16px;
            padding: 18px;
            background: white;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.1);
            display: block;
            margin: 0 auto; /* Double center */
        }

        .qr-item p {
            margin-top: 16px;
            font-size: 24px;
            font-weight: 600;
            color: #1f2937;
            text-align: center;
            font-family: 'Inter', sans-serif;
        }

        /* Page Number - Dead Center */
        .page-number {
            position: absolute;
            bottom: 1.2cm;
            left: 50%;
            transform: translateX(-50%);
            font-size: 13px;
            color: #6b7280;
            font-weight: 500;
            font-family: 'Inter', sans-serif;
        }

        /* Print Settings */
        @media print {
            .no-print { display: none !important; }
            body, .page { 
                background: white !important; 
                margin: 0 !important;
                padding: 0 !important;
            }
            .page {
                padding: 1.8cm 1.5cm 1.5cm !important;
            }
        }

        .no-print {
            text-align: center;
            padding: 20px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }

        .btn {
            padding: 12px 28px;
            font-size: 17px;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin: 0 8px;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
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

    @foreach($pages as $index => $qr)
        <div class="page">
            <!-- Heading -->
            <div class="heading">Scan Now</div>

            <!-- QR Code - DEAD CENTER -->
            <div class="qr-container">
                <div class="qr-item">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=900x900&data={{ urlencode($qr->url) }}" 
                         alt="QR {{ $qr->seat_number }}">
                    <p>#{{ $qr->seat_number }} – {{ $qr->code }}</p>
                </div>
            </div>

            <!-- Page Number -->
            <div class="page-number">
                Page {{ $index + 1 }} of {{ $pages->count() }}
            </div>
        </div>
    @endforeach

    <!-- Print & Close Buttons -->
    <div class="no-print">
        <button class="btn btn-print" onclick="window.print()">Print All</button>
        <button class="btn btn-close" onclick="window.close()">Close</button>
    </div>

</body>
</html>