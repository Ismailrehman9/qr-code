<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Submission;
use App\Models\QRCode;
use App\Services\GoogleSheetsService;
use App\Services\JokeGeneratorService;
use Illuminate\Support\Facades\Validator;

class SubmissionForm extends Component
{
    public $seatQrId;
    public $name;
    public $phone;
    public $email;
    public $dateOfBirth;
    public $whatsappOptin = false;
    public $joke;
    public $submitted = false;
    public $error;

    protected $rules = [
        'name' => 'required|string|min:2|max:255',
        'phone' => 'required|string|regex:/^[0-9+\-\s()]+$/|min:10|max:20',
        'email' => 'required|email|max:255',
        'dateOfBirth' => 'required|date|before:today|after:1900-01-01',
        'whatsappOptin' => 'boolean',
    ];

    protected $messages = [
        'name.required' => 'Please enter your name',
        'phone.required' => 'Please enter your phone number',
        'phone.regex' => 'Please enter a valid phone number',
        'email.required' => 'Please enter your email address',
        'email.email' => 'Please enter a valid email address',
        'dateOfBirth.required' => 'Please enter your date of birth',
        'dateOfBirth.before' => 'Date of birth must be before today',
    ];

    public function mount($id)
    {
        $this->seatQrId = $id;
        
        // Validate QR code exists and is active
        $qrCode = QRCode::where('code', $id)->first();
        
        if (!$qrCode) {
            $this->error = 'Invalid QR code. Please check your seat number.';
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function submit()
    {
        // Validate form data
        $this->validate();

        // Check if phone number already exists
        $existingSubmission = Submission::where('phone', $this->phone)->first();
        
        if ($existingSubmission) {
            $this->error = "You've already participated — only one entry per guest!";
            return;
        }

        try {
            // Generate personalized joke
            $jokeService = new JokeGeneratorService();
            $ageBracket = $this->calculateAgeBracket($this->dateOfBirth);
            $this->joke = $jokeService->generate($this->name, $ageBracket);

            // Create submission
            $submission = Submission::create([
                'seat_qr_id' => $this->seatQrId,
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->email,
                'date_of_birth' => $this->dateOfBirth,
                'whatsapp_optin' => $this->whatsappOptin,
                'joke' => $this->joke,
                'submitted_at' => now(),
            ]);

            // Send to Google Sheets
            try {
                $sheetsService = new GoogleSheetsService();
                $sheetsService->appendSubmission($submission);
            } catch (\Exception $e) {
                \Log::error('Failed to append to Google Sheets: ' . $e->getMessage());
            }

            // Mark QR code as used
            $qrCode = QRCode::where('code', $this->seatQrId)->first();
            if ($qrCode) {
                $qrCode->markAsUsed();
            }

            $this->submitted = true;

        } catch (\Exception $e) {
            \Log::error('Submission error: ' . $e->getMessage());
            $this->error = 'Something went wrong. Please try again.';
        }
    }

    protected function calculateAgeBracket($dateOfBirth)
    {
        $age = \Carbon\Carbon::parse($dateOfBirth)->age;
        
        if ($age < 18) return 'Under 18';
        if ($age < 25) return '18-24';
        if ($age < 35) return '25-34';
        if ($age < 45) return '35-44';
        if ($age < 55) return '45-54';
        if ($age < 65) return '55-64';
        return '65+';
    }

    public function render()
    {
        return view('livewire.submission-form');
    }
}
