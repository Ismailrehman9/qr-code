<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Submission;
use App\Models\QRCode;
use App\Services\GeminiJokeService;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class SubmissionForm extends Component
{
    public $seat_qr_id;
    public $qrCode;
    public $name = '';
    public $email = '';
    public $phone = '';
    public $date_of_birth = '';
    public $dob_day = '';
    public $dob_month = '';
    public $dob_year = '';
    public $whatsapp_optin = false;
    public $showSuccess = false;
    public $numerologyReading = '';
    protected GeminiJokeService $geminiJokeService;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'dob_day' => 'required',
        'dob_month' => 'required',
        'dob_year' => 'required',
    ];

    protected $messages = [
        'name.required' => 'Please enter your name',
        'email.required' => 'Please enter your email',
        'email.email' => 'Please enter a valid email',
        'phone.required' => 'Please enter your phone number',
        'dob_day.required' => 'Please select day',
        'dob_month.required' => 'Please select month',
        'dob_year.required' => 'Please select year',
    ];

    public function boot(GeminiJokeService $geminiJokeService)
    {
        $this->geminiJokeService = $geminiJokeService;
    }

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

        if ($age >= 18 && $age <= 24)
            return '18-24';
        if ($age >= 25 && $age <= 34)
            return '25-34';
        if ($age >= 35 && $age <= 44)
            return '35-44';
        if ($age >= 45 && $age <= 54)
            return '45-54';
        if ($age >= 55 && $age <= 64)
            return '55-64';
        if ($age >= 65)
            return '65+';
        return 'Under 18';
    }

    private function fetchNumerologyReading(int $age, ?string $name = null)
    {
        $reading = $this->geminiJokeService->generateForAge($age, $name);

        if ($reading) {
            return $reading;
        }

        // Fallback numerology readings
        $nameGreeting = $name ? $name . ', ' : '';
        $fallbackReadings = [
            "{$nameGreeting}Your age suggests a year of new beginnings and opportunities. Embrace the change!",
            "{$nameGreeting}You're in a cycle of growth and creativity. A great time to start new projects!",
            "{$nameGreeting}This is a period for building strong foundations for your future. Stay focused!",
            "{$nameGreeting}Your energy is high for adventure and experiencing new things. Enjoy the journey!",
            "{$nameGreeting}A time for reflection and connecting with your inner wisdom. Trust your intuition.",
        ];

        return $fallbackReadings[array_rand($fallbackReadings)];
    }

    public function submit()
    {
        try {
            // Validate - this will throw ValidationException if fails
            $this->validate();

            // Construct Date of Birth
            if (!checkdate($this->dob_month, $this->dob_day, $this->dob_year)) {
                $this->addError('dob_day', 'Invalid date selected.');
                return;
            }

            $this->date_of_birth = Carbon::createFromDate($this->dob_year, $this->dob_month, $this->dob_day)->format('Y-m-d');

            if (Carbon::parse($this->date_of_birth)->isFuture()) {
                $this->addError('dob_year', 'Date of birth must be in the past.');
                return;
            }

            // Custom Validation: Check for recent submissions (within 24 hours)
            $recentSubmission = Submission::where(function ($query) {
                $query->where('email', $this->email)
                    ->orWhere('phone', $this->phone);
            })
                ->where('submitted_at', '>=', now()->subHours(24))
                ->first();

            if ($recentSubmission) {
                if ($recentSubmission->email === $this->email) {
                    $this->addError('email', 'This email has already been used for a submission in the last 24 hours.');
                }
                if ($recentSubmission->phone === $this->phone) {
                    $this->addError('phone', 'This phone number has already been used for a submission in the last 24 hours.');
                }
                return;
            }

            // Calculate age bracket and actual age
            $ageBracket = $this->calculateAgeBracket($this->date_of_birth);
            $age = Carbon::parse($this->date_of_birth)->age;

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

            // NEW: Save SAME DATA to Google Sheets (RIGHT AFTER DATABASE SAVE)
            $this->saveToGoogleSheet([
                'seat_qr_id' => $this->seat_qr_id,
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'date_of_birth' => $this->date_of_birth,
                'age' => $ageBracket,
                'whatsapp_optin' => $this->whatsapp_optin,
                'submitted_at' => now()->toDateTimeString()
            ]);


            // Mark QR code as used
            QRCode::where('code', $this->seat_qr_id)->update([
                'is_active' => false,
                'last_used_at' => now(),
                'reset_at' => now()->addHours(24),
            ]);

            // Fetch a numerology reading for the success screen (with personalized name)
            $firstName = explode(' ', trim($this->name))[0]; // Get first name only
            $this->numerologyReading = $this->fetchNumerologyReading($age, $firstName);

            $this->showSuccess = true;
            $this->reset(['name', 'email', 'phone', 'date_of_birth', 'whatsapp_optin']);
        } catch (ValidationException $e) {
            // Re-throw validation errors so they show on the form
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Submission Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            session()->flash('error', 'An error occurred while submitting. Please try again or contact support.');
        }
    }

    public function render()
    {
        return view('livewire.submission-form');
    }

    // ===================================================================
    // GOOGLE SHEETS INTEGRATION
    // ===================================================================

    private function saveToGoogleSheet($data)
    {
        // $credentialsPath = $this->getCredentialsPath();
        $credentialsPath = config('services.google.credentials_path');
        $spreadsheetId = env('GOOGLE_SHEET_ID');

        if (!$credentialsPath || !file_exists($credentialsPath)) {
            \Log::error('Google credentials file not found: ' . $credentialsPath);
            return false;
        }

        $credentials = json_decode(file_get_contents($credentialsPath), true);
        $token = $this->getGoogleAccessToken($credentials);

        if (!$token) {
            \Log::error('Failed to obtain Google access token');
            return false;
        }

        $values = [
            [
                $data['seat_qr_id'],
                $data['name'],
                $data['email'],
                $data['phone'],
                $data['date_of_birth'],
                $data['age'],
                $data['whatsapp_optin'] ? 'Yes' : 'No',
                $data['submitted_at']
            ]
        ];

        $url = "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheetId}/values/Sheet1!A:H:append?valueInputOption=RAW";

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode(['values' => $values]),
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            \Log::info('Google Sheet row added successfully');
            return true;
        }

        \Log::error("Google Sheets API error (HTTP {$httpCode}): " . $response);
        return false;
    }

    private function getGoogleAccessToken($credentials)
    {
        $url = 'https://oauth2.googleapis.com/token';

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $this->createJWT($credentials)
            ]),
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);
        return $result['access_token'] ?? null;
    }

    private function createJWT($credentials)
    {
        $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
        $now = time();

        $claim = json_encode([
            'iss' => $credentials['client_email'],
            'scope' => 'https://www.googleapis.com/auth/spreadsheets https://www.googleapis.com/auth/drive',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now,
        ]);

        $headerEncoded = $this->base64UrlEncode($header);
        $claimEncoded = $this->base64UrlEncode($claim);
        $dataToSign = $headerEncoded . '.' . $claimEncoded;

        // --- FIXED: Use private_key from JSON ---
        $privateKeyPem = $credentials['private_key'];

        // Some keys are raw base64 – wrap in PEM if needed
        if (!str_contains($privateKeyPem, '-----BEGIN')) {
            $privateKeyPem = "-----BEGIN PRIVATE KEY-----\n" .
                chunk_split($privateKeyPem, 64, "\n") .
                "-----END PRIVATE KEY-----\n";
        }

        $pkey = openssl_pkey_get_private($privateKeyPem);
        if ($pkey === false) {
            \Log::error('OpenSSL failed to load private key: ' . openssl_error_string());
            return false;
        }

        $signature = '';
        $signed = openssl_sign($dataToSign, $signature, $pkey, OPENSSL_ALGO_SHA256);
        openssl_pkey_free($pkey);

        if (!$signed) {
            \Log::error('JWT signing failed: ' . openssl_error_string());
            return false;
        }

        $signatureEncoded = $this->base64UrlEncode($signature);
        return $dataToSign . '.' . $signatureEncoded;
    }

    private function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    // NEW: Get credentials path from .env
    private function getCredentialsPath()
    {
        $path = env('GOOGLE_APPLICATION_CREDENTIALS');

        if (!$path) {
            $path = env('GOOGLE_CREDENTIALS_PATH');
        }

        if (!$path) {
            \Log::error('No Google credentials path defined in .env');
            return null;
        }


        // If path is relative (like storage/app/file.json), convert to absolute
        if (!str_starts_with($path, '/') && !str_starts_with($path, 'C:\\')) {
            $path = base_path($path);
        }

        return $path;
    }
}
