<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;
use App\Models\Submission;

class GoogleSheetsService
{
    protected $client;
    protected $service;
    protected $spreadsheetId;

    public function __construct()
    {
        $this->spreadsheetId = config('services.google.sheets_id');
        $this->initializeClient();
    }

    protected function initializeClient()
    {
        $this->client = new Client();
        $this->client->setApplicationName('Interactive Giveaway System');
        $this->client->setScopes([Sheets::SPREADSHEETS]);
        $this->client->setAuthConfig(config('services.google.credentials_path'));
        $this->client->setAccessType('offline');

        $this->service = new Sheets($this->client);
    }

    public function appendSubmission(Submission $submission)
    {
        $values = [[
            $submission->submitted_at->format('Y-m-d H:i:s'),
            $submission->seat_qr_id,
            $submission->name,
            $submission->phone,
            $submission->email,
            $submission->date_of_birth->format('Y-m-d'),
            $submission->whatsapp_optin ? 'Yes' : 'No',
            $submission->joke ?? '',
        ]];

        $body = new Sheets\ValueRange([
            'values' => $values
        ]);

        $params = [
            'valueInputOption' => 'RAW'
        ];

        try {
            $result = $this->service->spreadsheets_values->append(
                $this->spreadsheetId,
                'Sheet1!A:H',
                $body,
                $params
            );

            return $result->getUpdates()->getUpdatedCells();
        } catch (\Exception $e) {
            \Log::error('Google Sheets API Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function initializeSheet()
    {
        $headers = [[
            'Timestamp',
            'Seat/QR ID',
            'Name',
            'Phone',
            'Email',
            'Date of Birth',
            'WhatsApp Opt-in',
            'Joke'
        ]];

        $body = new Sheets\ValueRange([
            'values' => $headers
        ]);

        $params = [
            'valueInputOption' => 'RAW'
        ];

        try {
            $this->service->spreadsheets_values->update(
                $this->spreadsheetId,
                'Sheet1!A1:H1',
                $body,
                $params
            );
        } catch (\Exception $e) {
            \Log::error('Failed to initialize sheet: ' . $e->getMessage());
        }
    }
}
