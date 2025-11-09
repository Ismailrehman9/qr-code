<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\QRCodeService;

class ResetExpiredQRCodes extends Command
{
    protected $signature = 'qr:reset';
    protected $description = 'Reset expired QR codes (older than 24 hours)';

    public function handle()
    {
        $qrCodeService = new QRCodeService();
        $count = $qrCodeService->resetExpiredCodes();
        
        $this->info("Reset {$count} expired QR codes successfully!");
        
        return 0;
    }
}
