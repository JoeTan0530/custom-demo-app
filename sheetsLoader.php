<?php
// Manually include the Google API client
require_once __DIR__ . '/google-api-php-client/vendor/autoload.php';

// Your function to get sheet data
function getGoogleSheetData($spreadsheetId, $range) {
    $client = new Google_Client();

    // Disable SSL verification for local development
    $httpClient = new GuzzleHttp\Client([
        'verify' => false, // This disables SSL verification
    ]);
    $client->setHttpClient($httpClient);

    $client->setAuthConfig('credentials.json');
    $client->addScope(Google_Service_Sheets::SPREADSHEETS_READONLY);

    $service = new Google_Service_Sheets($client);

    try {
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        return $response->getValues();
    } catch (Exception $e) {
        return ["Error: " . $e->getMessage()];
    }
}

// Usage
$spreadsheetId = '1Dvt52HYCRUpqfWxz2BXUeOUlV9tMVGlJ-v9zTkTdOBA';
$range = 'Data Template!A2:F';
$data = getGoogleSheetData($spreadsheetId, $range);
?>