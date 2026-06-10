<?php

namespace App\Services;

use App\Models\QRCode;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;
use Carbon\Carbon;

class QRCodeService
{
    private function makeSeatCode(Carbon $batchDate, int $seatNumber): string
    {
        return substr(hash('sha1', $batchDate->format('c') . '-' . $seatNumber), 0, 10);
    }

    public function generateQRCode(string $code, string $url): string
    {
        return QrCodeGenerator::size(300)
            ->format('png')
            ->merge('/public/logo.png', 0.3, true)
            ->errorCorrection('H')
            ->generate($url);
    }

    public function generateBulkQRCodes(int $count = 500)
    {
        $baseUrl = config('app.url');
        $qrCodes = [];
        $batchDate = now();

        for ($i = 1; $i <= $count; $i++) {
            $code = $this->makeSeatCode($batchDate, $i);
            $url = "{$baseUrl}/form?id={$code}";
            
            $qrCode = QRCode::create([
                'code' => $code,
                'seat_number' => $i,
                'is_active' => true,
                'generated_for_date' => $batchDate,
            ]);

            $qrImage = $this->generateQRCode($code, $url);
            
            $qrCodes[] = [
                'code' => $code,
                'seat_number' => $i,
                'url' => $url,
                'image' => $qrImage,
            ];
        }

        return $qrCodes;
    }

    public function resetExpiredCodes()
    {
        $expired = QRCode::where('reset_at', '<=', now())->get();
        
        foreach ($expired as $qrCode) {
            $qrCode->reset();
        }

        return $expired->count();
    }

    public function validateCode(string $code): ?QRCode
    {
        return QRCode::where('code', $code)
            ->where('is_active', true)
            ->first();
    }

    public function downloadQRCodesPDF()
    {
        // This would generate a PDF with all QR codes
        // For A5 flyer format with seat information
        $qrCodes = QRCode::all();
        
        // Implementation would use a PDF library
        // Return PDF download
    }
}
