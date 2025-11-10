<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Submission;
use App\Models\QRCode;
use Illuminate\Support\Facades\DB;

class AdminDashboard extends Component
{
    public $totalSubmissions;
    public $todaySubmissions;
    public $whatsappOptIns;
    public $uniqueSeats;
    public $totalQRCodes;
    public $ageBracketStats;
    public $submissionsByHour;
    public $recentSubmissions;

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $this->totalSubmissions = Submission::count();
        $this->todaySubmissions = Submission::whereDate('submitted_at', today())->count();
        $this->whatsappOptIns = Submission::where('whatsapp_optin', true)->count();
        $this->uniqueSeats = Submission::distinct('seat_qr_id')->count();
        $this->totalQRCodes = QRCode::count();

        // Age bracket statistics
        $ageBrackets = Submission::select('age', DB::raw('count(*) as count'))
            ->groupBy('age')
            ->pluck('count', 'age')
            ->toArray();

        // Ensure all age brackets are present
        $allBrackets = ['18-24', '25-34', '35-44', '45-54', '55-64', '65+'];
        $this->ageBracketStats = [];
        foreach ($allBrackets as $bracket) {
            $this->ageBracketStats[$bracket] = $ageBrackets[$bracket] ?? 0;
        }

        // Submissions by hour (last 24 hours)
        $this->submissionsByHour = Submission::select(
                DB::raw('HOUR(submitted_at) as hour'),
                DB::raw('count(*) as count')
            )
            ->where('submitted_at', '>=', now()->subHours(24))
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->map(function($item) {
                return [
                    'hour' => str_pad($item->hour, 2, '0', STR_PAD_LEFT) . ':00',
                    'count' => $item->count
                ];
            })
            ->toArray();

        // Fill missing hours with 0
        $hours = [];
        for ($i = 0; $i < 24; $i++) {
            $hourStr = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
            $found = false;
            foreach ($this->submissionsByHour as $submission) {
                if ($submission['hour'] === $hourStr) {
                    $hours[] = $submission;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $hours[] = ['hour' => $hourStr, 'count' => 0];
            }
        }
        $this->submissionsByHour = $hours;

        // Recent submissions
        $this->recentSubmissions = Submission::orderBy('submitted_at', 'desc')
            ->take(10)
            ->get();
    }

    public function exportToCSV()
    {
        $submissions = Submission::all();
        
        $filename = 'submissions_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($submissions) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, ['Submitted At', 'Seat', 'Name', 'Email', 'Phone', 'Date of Birth', 'Age', 'WhatsApp Opt-in']);
            
            // Add data rows
            foreach ($submissions as $submission) {
                fputcsv($file, [
                    $submission->submitted_at,
                    $submission->seat_qr_id,
                    $submission->name,
                    $submission->email,
                    $submission->phone,
                    $submission->date_of_birth,
                    $submission->age,
                    $submission->whatsapp_optin ? 'Yes' : 'No',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function resetQRCodes()
    {
        QRCode::where('is_active', false)
            ->where('reset_at', '<=', now())
            ->update([
                'is_active' => true,
                'last_used_at' => null,
                'reset_at' => null,
            ]);

        session()->flash('message', 'Expired QR codes have been reset successfully!');
        $this->loadDashboardData();
    }

    public function render()
    {
        return view('livewire.admin-dashboard');
    }
}