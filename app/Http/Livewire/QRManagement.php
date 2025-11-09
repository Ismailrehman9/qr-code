<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\QRCode;
use Illuminate\Support\Facades\DB;

class QRManagement extends Component
{
    public $qrCount = 100;
    public $showModal = false;
    public $showEditModal = false;
    public $editBatchId;
    public $editStartSeat;
    public $editEndSeat;
    public $totalQRCodes;
    public $activeQRCodes;
    public $usedQRCodes;
    public $qrBatches;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->totalQRCodes = QRCode::count();
        $this->activeQRCodes = QRCode::where('is_active', true)->count();
        $this->usedQRCodes = QRCode::whereNotNull('last_used_at')->count();
        
        // Get batches grouped by creation timestamp (hour granularity for separate entries)
        $this->qrBatches = QRCode::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i") as batch_time'),
            DB::raw('MIN(id) as batch_id'),
            DB::raw('COUNT(*) as total_codes'),
            DB::raw('SUM(CASE WHEN last_used_at IS NOT NULL THEN 1 ELSE 0 END) as used_codes'),
            DB::raw('MIN(seat_number) as first_seat'),
            DB::raw('MAX(seat_number) as last_seat'),
            DB::raw('MAX(created_at) as created_at')
        )
        ->groupBy('batch_time')
        ->orderBy('created_at', 'desc')
        ->get();
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function generateQRCodes()
    {
        $this->validate([
            'qrCount' => 'required|integer|min:1|max:1000'
        ]);

        $lastSeat = QRCode::max('seat_number') ?? 0;

        for ($i = 1; $i <= $this->qrCount; $i++) {
            $seatNumber = $lastSeat + $i;
            $code = str_pad($seatNumber, 3, '0', STR_PAD_LEFT);
            
            QRCode::create([
                'code' => $code,
                'seat_number' => $seatNumber,
                'is_active' => true,
            ]);
        }

        session()->flash('success', "{$this->qrCount} QR codes generated successfully!");
        $this->showModal = false;
        $this->qrCount = 100;
        $this->loadData();
    }

    public function deleteBatch($firstSeat, $lastSeat)
    {
        QRCode::whereBetween('seat_number', [$firstSeat, $lastSeat])->delete();
        
        session()->flash('success', 'Batch deleted successfully!');
        $this->loadData();
    }

    public function openEditModal($firstSeat, $lastSeat, $batchId)
    {
        $this->editBatchId = $batchId;
        $this->editStartSeat = $firstSeat;
        $this->editEndSeat = $lastSeat;
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editBatchId = null;
        $this->editStartSeat = null;
        $this->editEndSeat = null;
    }

    public function updateBatch()
    {
        $this->validate([
            'editStartSeat' => 'required|integer|min:1',
            'editEndSeat' => 'required|integer|min:' . $this->editStartSeat,
        ]);

        // Get current batch QR codes
        $qrCodes = QRCode::whereBetween('seat_number', [$this->editStartSeat, $this->editEndSeat])
            ->orderBy('seat_number')
            ->get();

        // Update seat numbers and codes
        foreach ($qrCodes as $index => $qrCode) {
            $newSeatNumber = $this->editStartSeat + $index;
            $qrCode->update([
                'seat_number' => $newSeatNumber,
                'code' => str_pad($newSeatNumber, 3, '0', STR_PAD_LEFT),
            ]);
        }

        session()->flash('success', 'Batch updated successfully!');
        $this->closeEditModal();
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.qr-management');
    }
}
