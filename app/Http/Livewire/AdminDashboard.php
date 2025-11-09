<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Submission;
use App\Models\QRCode;
use Carbon\Carbon;

class AdminDashboard extends Component
{
    public $totalSubmissions;
    public $todaySubmissions;
    public $whatsappOptIns;
    public $uniqueSeats;
    public $recentSubmissions;
    public $ageBracketStats;
    public $submissionsByHour;
    public $searchTerm = '';
    public $filterDate;

    protected $listeners = ['refreshDashboard' => '$refresh'];

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        // Total submissions
        $this->totalSubmissions = Submission::count();

        // Today's submissions
        $this->todaySubmissions = Submission::whereDate('submitted_at', today())->count();

        // WhatsApp opt-ins
        $this->whatsappOptIns = Submission::where('whatsapp_optin', true)->count();

        // Unique seats used
        $this->uniqueSeats = Submission::distinct('seat_qr_id')->count();

        // Recent submissions (last 10)
        $this->recentSubmissions = Submission::orderBy('submitted_at', 'desc')
            ->take(10)
            ->get();

        // Age bracket statistics
        $this->ageBracketStats = $this->calculateAgeBracketStats();

        // Submissions by hour (last 24 hours)
        $this->submissionsByHour = $this->getSubmissionsByHour();
    }

    protected function calculateAgeBracketStats()
    {
        $submissions = Submission::all();
        $brackets = [
            'Under 18' => 0,
            '18-24' => 0,
            '25-34' => 0,
            '35-44' => 0,
            '45-54' => 0,
            '55-64' => 0,
            '65+' => 0,
        ];

        foreach ($submissions as $submission) {
            $age = Carbon::parse($submission->date_of_birth)->age;
            
            if ($age < 18) $brackets['Under 18']++;
            elseif ($age < 25) $brackets['18-24']++;
            elseif ($age < 35) $brackets['25-34']++;
            elseif ($age < 45) $brackets['35-44']++;
            elseif ($age < 55) $brackets['45-54']++;
            elseif ($age < 65) $brackets['55-64']++;
            else $brackets['65+']++;
        }

        return $brackets;
    }

    protected function getSubmissionsByHour()
    {
        $hours = [];
        $startTime = now()->subHours(24);

        for ($i = 0; $i < 24; $i++) {
            $hourStart = $startTime->copy()->addHours($i);
            $hourEnd = $hourStart->copy()->addHour();
            
            $count = Submission::whereBetween('submitted_at', [$hourStart, $hourEnd])->count();
            
            $hours[] = [
                'hour' => $hourStart->format('H:00'),
                'count' => $count,
            ];
        }

        return $hours;
    }

    public function exportToCSV()
    {
        $submissions = Submission::all();
        
        $filename = 'giveaway_submissions_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($submissions) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'Timestamp',
                'Seat/QR ID',
                'Name',
                'Phone',
                'Email',
                'Date of Birth',
                'Age',
                'WhatsApp Opt-in',
                'Joke',
            ]);

            // Data
            foreach ($submissions as $submission) {
                fputcsv($file, [
                    $submission->submitted_at->format('Y-m-d H:i:s'),
                    $submission->seat_qr_id,
                    $submission->name,
                    $submission->phone,
                    $submission->email,
                    $submission->date_of_birth->format('Y-m-d'),
                    $submission->age,
                    $submission->whatsapp_optin ? 'Yes' : 'No',
                    $submission->joke,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function resetQRCodes()
    {
        $count = QRCode::where('reset_at', '<=', now())->count();
        QRCode::where('reset_at', '<=', now())->update([
            'is_active' => true,
            'reset_at' => null,
        ]);

        session()->flash('message', "{$count} QR codes have been reset successfully!");
        $this->loadDashboardData();
    }

    public function render()
    {
        return view('livewire.admin-dashboard');
    }
}
