<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
/*use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;
use Google\Spreadsheet\SpreadsheetService;*/
use Google_Service_Sheets;
use Google_Service_Sheets_ValueRange;
use Google_Service_Sheets_BatchUpdateValuesRequest;

class HomeController extends Controller
{
    public $spreadsheet, $worksheet, $cell_feed, $token_path;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->token_path = public_path('token.json');
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // with service account

        /*//create connection
        $this->getClient();

        //get sheet by name of the sheet
        $this->getSheetByName('Google sheets reports');

        // $spreadsheet contains the list of worksheets select the worksheet which you want to select by name
        $this->getWorkSheetByName('Master report');

        $this->cell_feed = $this->worksheet->getCellFeed();

        $cell = $this->cell_feed->getCell(3, 1);
        $cell->update(rand());*/

        // with OAuth 2

        $client = $this->getAccessToken();
        $service = new Google_Service_Sheets($client);
        $spreadsheetId = '1OpZSICGAfcxFhMIjuf6jP5U9pveLhLAxH9DK5A2__1o';
        $range = 'Master report!A3:B3';
        
        /*// get value from spreadsheets
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();*/

        //update single column
        /*$values = [
                    [
                        rand()
                    ],
                ];
        $body = new Google_Service_Sheets_ValueRange(['values' => $values]);
        $params = ['valueInputOption' => 'USER_ENTERED'];
        $result = $service->spreadsheets_values->update($spreadsheetId, $range,
        $body, $params);*/

        // update bacth of columns
        $values = [
            [
                rand() ,  rand()
            ],
            // Additional rows ...
        ];
        $data = [];
        $data[] = new Google_Service_Sheets_ValueRange([
            'range' => $range,
            'values' => $values
        ]);
        // Additional ranges to update ...
        $body = new Google_Service_Sheets_BatchUpdateValuesRequest([
            'valueInputOption' => 'USER_ENTERED',
            'data' => $data
        ]);
        $result = $service->spreadsheets_values->batchUpdate($spreadsheetId, $body);
        printf("%d cells updated.", $result->getTotalUpdatedCells());
        die;
    }
    
    /*private function getWorkSheetByName(string $title)
    {
        foreach ($this->spreadsheet as $sheet) {
            if ($sheet->getTitle() == trim($title)) {
                return $this->worksheet = $sheet;
            }
        }
        return false;
    }

    private function getSheetByName(string $title)
    {
        $spreadsheet = (new SpreadsheetService)->getSpreadsheetFeed()->getByTitle(trim($title));
        return $this->spreadsheet = $spreadsheet->getWorksheetFeed()->getEntries();
    }*/

    private function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Sheets');
        $client->setScopes([Google_Service_Sheets::DRIVE, Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');

        $client->setDeveloperKey(env('GOOGLE_API_KEY'));
        
        $client->setApprovalPrompt('force');
        $client->setRedirectUri(url('getCode'));
        $client->setClientId(env('GOOGLE_OAUTH_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_OAUTH_CLIENT_SECRET'));
        
        return $client;
    }

    /*private function getClient() // with service account
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Sheets');
        $client->setScopes([Google_Service_Sheets::DRIVE, Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $client->setAuthConfig(config('sheet.auth'));
        if ($client->isAccessTokenExpired()) {
            $client->refreshTokenWithAssertion();
        }
        $access_token = $client->fetchAccessTokenWithAssertion()["access_token"];
        ServiceRequestFactory::setInstance(
            new DefaultServiceRequest($access_token)
        );
    }*/

    private function getAccessToken()
    {
        $client = $this->getClient();
        if (file_exists($this->token_path)) {
            $access_token = json_decode(file_get_contents($this->token_path), true);
            $client->setAccessToken($access_token);
        }
        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $this->saveAccesstoken($client->getAccessToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
                die;
            }
        }
        return $client;
    }

    public function getCode(Request $request){
        $this->authenticateCode($request);
        return redirect()->route('sheet');
    }

    public function saveAccesstoken($code){
        file_put_contents($this->token_path, json_encode($code));
    }

    private function authenticateCode($request){
        $client = $this->getClient();
        $access_token = $client->fetchAccessTokenWithAuthCode(trim($request->code));
        // Check to see if there was an error.
        if (array_key_exists('error', $access_token)) {
            dd('Error: '.join(', ', $access_token));
        }
        $client->setAccessToken($access_token);
        $this->saveAccesstoken($client->getAccessToken());
    }
}
