<?php

namespace App\Services;

use App\Models\QRCode;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;

class QRCodeService
{
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

        for ($i = 1; $i <= $count; $i++) {
            $code = str_pad($i, 3, '0', STR_PAD_LEFT);
            $url = "{$baseUrl}/form?id={$code}";
            
            $qrCode = QRCode::create([
                'code' => $code,
                'seat_number' => $i,
                'is_active' => true,
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
