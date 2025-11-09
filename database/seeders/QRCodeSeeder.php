<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QRCode;

class QRCodeSeeder extends Seeder
{
    public function run(): void
    {
        echo "Generating 500 QR codes...\n";

        for ($i = 1; $i <= 500; $i++) {
            QRCode::create([
                'code' => str_pad($i, 3, '0', STR_PAD_LEFT),
                'seat_number' => $i,
                'is_active' => true,
            ]);

            if ($i % 50 == 0) {
                echo "Created {$i} QR codes...\n";
            }
        }

        echo "Successfully created 500 QR codes!\n";
    }
}
