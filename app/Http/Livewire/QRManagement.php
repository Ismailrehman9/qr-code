<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\QRCode;
use App\Jobs\GenerateQRCodeImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Bus;
use Carbon\Carbon;

class QRManagement extends Component
{
    public $qrCount = 100;
    public $showModal = false;
    public $showEditModal = false;
    public $editBatchId;
    public $editBatchTime;
    public $editStartSeat;
    public $editEndSeat;
    public $totalQRCodes;
    public $activeQRCodes;
    public $usedQRCodes;
    public $qrBatches;
    public $generationDate;

    public function mount()
    {
        $this->generationDate = now()->format('Y-m-d\TH:i');
        $this->loadData();
    }

    public function loadData()
    {
        $this->totalQRCodes = QRCode::count();
        $this->activeQRCodes = QRCode::where('is_active', true)->count();
        $this->usedQRCodes = QRCode::whereNotNull('last_used_at')->count();

        $batchExpression = 'COALESCE(generated_for_date, created_at)';

        // Group by the show timestamp so each show can reuse seat numbers from 1.
        $this->qrBatches = QRCode::select(
            DB::raw("{$batchExpression} as batch_time"),
            DB::raw('MIN(id) as batch_id'),
            DB::raw('COUNT(*) as total_codes'),
            DB::raw('SUM(CASE WHEN last_used_at IS NOT NULL THEN 1 ELSE 0 END) as used_codes'),
            DB::raw('SUM(CASE WHEN qr_generated = 1 THEN 1 ELSE 0 END) as generated_codes'),
            DB::raw('MIN(seat_number) as first_seat'),
            DB::raw('MAX(seat_number) as last_seat'),
            DB::raw('MAX(created_at) as created_at'),
            DB::raw('MAX(generated_for_date) as generated_for_date')
        )
        ->groupBy(DB::raw($batchExpression))
        ->orderBy('created_at', 'desc')
        ->get();
    }

    private function makeSeatCode(Carbon $batchDate, int $seatNumber): string
    {
        return substr(hash('sha1', $batchDate->format('c') . '-' . $seatNumber), 0, 10);
    }

    public function openModal()
    {
        $this->showModal = true;
        $this->generationDate = now()->format('Y-m-d\TH:i');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->generationDate = now()->format('Y-m-d\TH:i');
    }

    public function generateQRCodes()
    {
        $this->validate([
            'qrCount' => 'required|integer|min:1|max:1000',
            'generationDate' => 'required|date_format:Y-m-d\TH:i',
        ]);

        $generationDateTime = Carbon::createFromFormat('Y-m-d\TH:i', $this->generationDate);

        for ($i = 1; $i <= $this->qrCount; $i++) {
            $seatNumber = $i;
            $code = $this->makeSeatCode($generationDateTime, $seatNumber);

            $qrCode = QRCode::create([
                'code' => $code,
                'seat_number' => $seatNumber,
                'is_active' => true,
                'generated_for_date' => $generationDateTime,
                'qr_generated' => false,
            ]);

            GenerateQRCodeImage::dispatch($qrCode->id);
        }

        session()->flash('success', "{$this->qrCount} QR codes are being generated in the background!");
        $this->showModal = false;
        $this->qrCount = 100;
        $this->generationDate = now()->format('Y-m-d\TH:i');
        $this->loadData();
    }

    public function deleteBatch($batchTime)
    {
        QRCode::where('generated_for_date', Carbon::parse($batchTime))->delete();
        
        session()->flash('success', 'Batch deleted successfully!');
        $this->loadData();
    }

    public function openEditModal($firstSeat, $lastSeat, $batchTime, $batchId)
    {
        $this->editBatchId = $batchId;
        $this->editBatchTime = $batchTime;
        $this->editStartSeat = $firstSeat;
        $this->editEndSeat = $lastSeat;
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editBatchId = null;
        $this->editBatchTime = null;
        $this->editStartSeat = null;
        $this->editEndSeat = null;
    }

    public function updateBatch()
    {
        $this->validate([
            'editBatchTime' => 'required|date',
            'editStartSeat' => 'required|integer|min:1',
            'editEndSeat' => 'required|integer|min:' . $this->editStartSeat,
        ]);

        $batchDate = Carbon::parse($this->editBatchTime);

        // Get current batch QR codes
        $qrCodes = QRCode::where('generated_for_date', $batchDate)
            ->orderBy('seat_number')
            ->get();

        // Update seat numbers and codes
        foreach ($qrCodes as $index => $qrCode) {
            $newSeatNumber = $this->editStartSeat + $index;
            $qrCode->update([
                'seat_number' => $newSeatNumber,
                'code' => $this->makeSeatCode($batchDate, $newSeatNumber),
            ]);
        }

        session()->flash('success', 'Batch updated successfully!');
        $this->closeEditModal();
        $this->loadData();
    }

    public function regenerateQRCodes($batchTime)
    {
        $batchDate = Carbon::parse($batchTime);
        $qrCodes = QRCode::where('generated_for_date', $batchDate)->get();

        if ($qrCodes->isEmpty()) {
            session()->flash('error', 'No QR codes found in this range.');
            return;
        }

        foreach ($qrCodes as $qrCode) {
            $qrCode->update([
                'qr_generated' => false,
                'qr_image_path' => null,
            ]);

            GenerateQRCodeImage::dispatch($qrCode->id);
        }

        session()->flash('success', count($qrCodes) . ' QR codes are being regenerated in the background!');
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.qr-management');
    }
}
