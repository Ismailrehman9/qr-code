<?php

namespace App\Jobs;

use App\Models\QRCode;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class GenerateQRCodeImage implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $qrCodeId;
    public $tries = 3;
    public $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct($qrCodeId)
    {
        $this->qrCodeId = $qrCodeId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->batch() && $this->batch()->cancelled()) {
            return;
        }

        $qrCode = QRCode::find($this->qrCodeId);

        if (!$qrCode) {
            return;
        }

        $url = config('app.url') . '/form?id=' . $qrCode->code;
        $qrImageUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=' . urlencode($url);

        try {
            $response = Http::timeout(30)->get($qrImageUrl);

            if ($response->successful()) {
                $filename = "qr_codes/QR_Seat_{$qrCode->seat_number}_{$qrCode->code}.png";
                Storage::put($filename, $response->body());

                $qrCode->update([
                    'qr_image_path' => $filename,
                    'qr_generated' => true,
                ]);
            }
        } catch (\Exception $e) {
            $this->fail($e);
        }
    }
}
