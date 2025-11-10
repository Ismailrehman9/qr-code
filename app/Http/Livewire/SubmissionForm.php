<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Submission;
use App\Models\QRCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class SubmissionForm extends Component
{
    public $seat_qr_id;
    public $qrCode;
    public $name = '';
    public $email = '';
    public $phone = '';
    public $date_of_birth = '';
    public $whatsapp_optin = false;
    public $showSuccess = false;
    public $joke = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:submissions,email',
        'phone' => 'required|string|max:20|unique:submissions,phone',
        'date_of_birth' => 'required|date|before:today',
    ];

    protected $messages = [
        'name.required' => 'Please enter your name',
        'email.required' => 'Please enter your email',
        'email.email' => 'Please enter a valid email',
        'email.unique' => 'This email has already been registered. Each person can only participate once.',
        'phone.required' => 'Please enter your phone number',
        'phone.unique' => 'This phone number has already been registered. Each person can only participate once.',
        'date_of_birth.required' => 'Please select your date of birth',
        'date_of_birth.before' => 'Date of birth must be in the past',
    ];

    public function mount()
    {
        $id = request()->query('id');
        
        if (!$id) {
            abort(404, 'QR code not found. Please scan a valid QR code.');
        }

        $this->qrCode = QRCode::where('code', $id)->first();

        if (!$this->qrCode) {
            abort(404, 'Invalid QR code');
        }

        if (!$this->qrCode->is_active) {
            abort(403, 'This QR code has already been used. Please contact support.');
        }

        $this->seat_qr_id = $this->qrCode->code;
    }

    private function calculateAgeBracket($dateOfBirth)
    {
        $age = Carbon::parse($dateOfBirth)->age;

        if ($age >= 18 && $age <= 24) return '18-24';
        if ($age >= 25 && $age <= 34) return '25-34';
        if ($age >= 35 && $age <= 44) return '35-44';
        if ($age >= 45 && $age <= 54) return '45-54';
        if ($age >= 55 && $age <= 64) return '55-64';
        if ($age >= 65) return '65+';
        
        return 'Under 18';
    }

    private function fetchJoke()
    {
        try {
            $response = Http::timeout(3)->get('https://v2.jokeapi.dev/joke/Any', [
                'safe-mode' => true,
                'type' => 'single'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['joke'])) {
                    return $data['joke'];
                }
            }
        } catch (\Exception $e) {
            \Log::error('Joke API Error: ' . $e->getMessage());
        }

        $fallbackJokes = [
            "Why don't scientists trust atoms? Because they make up everything! 😄",
            "Why did the scarecrow win an award? He was outstanding in his field! 🌾",
            "What do you call a fake noodle? An impasta! 🍝",
            "Why don't eggs tell jokes? They'd crack each other up! 🥚",
            "What's the best thing about Switzerland? I don't know, but the flag is a big plus! 🇨🇭",
            "Why did the bicycle fall over? It was two tired! 🚲",
            "What do you call a bear with no teeth? A gummy bear! 🐻",
            "How do you organize a space party? You planet! 🪐",
            "Why did the math book look so sad? Because it had too many problems! 📚",
            "What did one wall say to the other? I'll meet you at the corner! 🏠",
        ];

        return $fallbackJokes[array_rand($fallbackJokes)];
    }

    public function submit()
    {
        try {
            // Validate - this will throw ValidationException if fails
            $this->validate();

            // Calculate age bracket from date of birth
            $ageBracket = $this->calculateAgeBracket($this->date_of_birth);

            // Save to database
            $submission = Submission::create([
                'seat_qr_id' => $this->seat_qr_id,
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'date_of_birth' => $this->date_of_birth,
                'age' => $ageBracket,
                'whatsapp_optin' => $this->whatsapp_optin,
                'submitted_at' => now(),
            ]);

            // Mark QR code as used
            QRCode::where('code', $this->seat_qr_id)->update([
                'is_active' => false,
                'last_used_at' => now(),
                'reset_at' => now()->addHours(24),
            ]);

            // Fetch a joke for the success screen
            $this->joke = $this->fetchJoke();

            $this->showSuccess = true;
            $this->reset(['name', 'email', 'phone', 'date_of_birth', 'whatsapp_optin']);

        } catch (ValidationException $e) {
            // Re-throw validation errors so they show on the form
            throw $e;
            
        } catch (\Exception $e) {
            \Log::error('Submission Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            // Check if it's a duplicate entry database error
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                if (str_contains($e->getMessage(), 'email')) {
                    $this->addError('email', 'This email has already been registered. Each person can only participate once.');
                } elseif (str_contains($e->getMessage(), 'phone')) {
                    $this->addError('phone', 'This phone number has already been registered. Each person can only participate once.');
                } else {
                    session()->flash('error', 'This information has already been registered. Each person can only participate once.');
                }
            } else {
                session()->flash('error', 'An error occurred while submitting. Please try again or contact support.');
            }
        }
    }

    public function render()
    {
        return view('livewire.submission-form');
    }
}