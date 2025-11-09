<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download QR Codes - Seat #{{ $start }} to #{{ $end }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            padding: 20px;
        }
        
        .controls {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background: #4f46e5;
            color: white;
        }
        
        .btn-primary:hover {
            background: #4338ca;
        }
        
        .btn-secondary {
            background: #6b7280;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #4b5563;
        }
        
        .qr-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .qr-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            break-inside: avoid;
        }
        
        .seat-number {
            font-size: 24px;
            font-weight: bold;
            color: #4f46e5;
            margin-bottom: 15px;
        }
        
        .qr-code {
            margin: 15px 0;
        }
        
        .qr-code img {
            width: 180px;
            height: 180px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
        }
        
        .url {
            font-size: 11px;
            color: #6b7280;
            word-break: break-all;
            margin-top: 10px;
        }
        
        .instructions {
            font-size: 13px;
            color: #374151;
            margin-top: 10px;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .controls {
                display: none;
            }
            
            .qr-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 15px;
            }
            
            .qr-card {
                box-shadow: none;
                border: 1px solid #e5e7eb;
            }
        }
    </style>
</head>
<body>
    <div class="controls">
        <div>
            <h1 style="font-size: 20px; color: #1f2937; margin-bottom: 5px;">QR Codes: Seat #{{ $start }} to #{{ $end }}</h1>
            <p style="color: #6b7280; font-size: 14px;">Total: {{ $qrCodes->count() }} QR codes</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" class="btn btn-primary">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print
            </button>
            <a href="{{ route('admin.qr-codes') }}" class="btn btn-secondary">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
        </div>
    </div>

    <div class="qr-grid">
        @foreach($qrCodes as $qrCode)
            <div class="qr-card">
                <div class="seat-number">Seat #{{ $qrCode->seat_number }}</div>
                
                <div class="qr-code">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode(config('app.url') . '/form?id=' . $qrCode->code) }}" 
                         alt="QR Code {{ $qrCode->code }}">
                </div>
                
                <div class="instructions">
                    📱 Scan to Enter
                </div>
                
                <div class="url">
                    {{ config('app.url') }}/form?id={{ $qrCode->code }}
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>
