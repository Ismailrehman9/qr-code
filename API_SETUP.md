# API Integration Guide

## Google Sheets API Setup

### Step 1: Create Google Cloud Project

1. Visit [Google Cloud Console](https://console.cloud.google.com/)
2. Click "New Project"
3. Name it "Interactive Giveaway System"
4. Click "Create"

### Step 2: Enable Google Sheets API

1. In the Cloud Console, go to "APIs & Services" > "Library"
2. Search for "Google Sheets API"
3. Click on it and press "Enable"

### Step 3: Create Service Account

1. Go to "APIs & Services" > "Credentials"
2. Click "Create Credentials" > "Service Account"
3. Fill in details:
   - Name: "Giveaway System Service Account"
   - Role: "Editor"
4. Click "Done"

### Step 4: Create and Download Key

1. Click on the created service account
2. Go to "Keys" tab
3. Click "Add Key" > "Create New Key"
4. Choose "JSON" format
5. Download and save the file securely

### Step 5: Share Spreadsheet

1. Open your Google Sheet
2. Click "Share"
3. Add the service account email (found in the JSON file)
4. Give "Editor" permissions

### Step 6: Get Spreadsheet ID

The spreadsheet ID is in the URL:
```
https://docs.google.com/spreadsheets/d/SPREADSHEET_ID_HERE/edit
```

### Step 7: Configure Laravel

Update `.env`:
```env
GOOGLE_SHEETS_SPREADSHEET_ID=your_spreadsheet_id_here
GOOGLE_APPLICATION_CREDENTIALS=/path/to/your/credentials.json
```

## OpenAI API Setup

### Step 1: Create Account

1. Visit [OpenAI Platform](https://platform.openai.com/)
2. Sign up or log in
3. Go to API Keys section

### Step 2: Create API Key

1. Click "Create new secret key"
2. Name it "Giveaway System"
3. Copy the key (you won't see it again!)

### Step 3: Configure Laravel

Update `.env`:
```env
OPENAI_API_KEY=sk-your_api_key_here
```

### Usage Limits and Pricing

- GPT-3.5-turbo: ~$0.002 per 1K tokens
- Monitor usage in OpenAI dashboard
- Set up billing alerts to avoid surprises

## Testing the Integrations

### Test Google Sheets

```bash
php artisan tinker

# In tinker:
$service = new App\Services\GoogleSheetsService();
$service->initializeSheet();
```

### Test OpenAI

```bash
php artisan tinker

# In tinker:
$service = new App\Services\JokeGeneratorService();
$joke = $service->generate('John', '25-34');
echo $joke;
```

## Error Handling

### Google Sheets Errors

**Error: "The caller does not have permission"**
- Solution: Make sure service account email has Editor access to the sheet

**Error: "Unable to parse range"**
- Solution: Check that your sheet name is "Sheet1" or update the code

### OpenAI Errors

**Error: "Invalid API key"**
- Solution: Verify API key in .env file

**Error: "Rate limit exceeded"**
- Solution: Implement rate limiting or upgrade plan

## Alternative APIs

### Joke Generation Alternatives

If you don't want to use OpenAI:

#### 1. JokeAPI (Free)
```php
public function generate(string $name, string $ageBracket): string
{
    $response = Http::get('https://v2.jokeapi.dev/joke/Any', [
        'safe-mode' => true
    ]);
    
    $data = $response->json();
    
    if ($data['type'] === 'single') {
        return "Hey {$name}, " . $data['joke'];
    }
    
    return "Hey {$name}, " . $data['setup'] . ' ' . $data['delivery'];
}
```

#### 2. Custom Jokes Database
Store pre-written jokes in database and randomize selection.

### Google Sheets Alternatives

#### 1. Export to CSV
```php
// In AdminDashboard Livewire component
public function exportToCSV()
{
    // Already implemented
}
```

#### 2. Direct Database Queries
No external API needed - just query the database directly for reporting.

## Security Best Practices

1. **Never commit credentials to Git**
   - Add to .gitignore
   - Use environment variables

2. **Rotate API keys regularly**
   - Every 90 days minimum
   - Immediately if compromised

3. **Monitor API usage**
   - Set up alerts for unusual activity
   - Review logs weekly

4. **Limit API permissions**
   - Use least privilege principle
   - Restrict to only necessary scopes

## Rate Limiting

### Google Sheets
- 500 requests per 100 seconds per project
- 100 requests per 100 seconds per user

### OpenAI
- Tier-based limits
- GPT-3.5-turbo: 3 RPM (free tier)
- Upgrade for higher limits

## Troubleshooting

### Google Sheets Connection Test

```bash
php artisan tinker

use Google\Client;
use Google\Service\Sheets;

$client = new Client();
$client->setAuthConfig(config('services.google.credentials_path'));
$client->setScopes([Sheets::SPREADSHEETS]);
$service = new Sheets($client);

$spreadsheetId = config('services.google.sheets_id');
$range = 'Sheet1!A1';
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
echo "Connected successfully!";
```

### OpenAI Connection Test

```bash
php artisan tinker

use OpenAI\Laravel\Facades\OpenAI;

$result = OpenAI::chat()->create([
    'model' => 'gpt-3.5-turbo',
    'messages' => [
        ['role' => 'user', 'content' => 'Say "Connection successful!"'],
    ],
]);

echo $result->choices[0]->message->content;
```

## Support

For API-related issues:
- Google Sheets: [Google Workspace Support](https://support.google.com/a/)
- OpenAI: [OpenAI Help Center](https://help.openai.com/)
