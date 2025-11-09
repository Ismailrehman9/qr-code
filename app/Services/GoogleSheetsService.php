<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;

class GoogleSheetsService
{
    protected $client;
    protected $service;
    protected $spreadsheetId;

    public function __construct()
    {
        $this->spreadsheetId = config('google.sheet_id');
        
        $credPath = config('google.credentials');
        if ($credPath && file_exists($credPath)) {
            $this->client = new Client();
            $this->client->setApplicationName('Laravel Giveaway');
            $this->client->setScopes([Sheets::SPREADSHEETS]);
            $this->client->setAuthConfig($credPath);
            $this->service = new Sheets($this->client);
        }
    }

    public function appendRow($data)
    {
        if (!$this->service) {
            throw new \Exception('Google Sheets not configured');
        }

        $range = 'Sheet1!A:G';
        $values = [$data];
        $body = new Sheets\ValueRange(['values' => $values]);
        
        $params = ['valueInputOption' => 'RAW'];
        
        return $this->service->spreadsheets_values->append(
            $this->spreadsheetId,
            $range,
            $body,
            $params
        );
    }

    public function getSheetUrl()
    {
        if (!$this->spreadsheetId) {
            return '#';
        }
        return "https://docs.google.com/spreadsheets/d/{$this->spreadsheetId}/edit";
    }

    public function isConfigured()
    {
        return !empty($this->spreadsheetId) && 
               !empty(config('google.credentials')) && 
               file_exists(config('google.credentials'));
    }
}