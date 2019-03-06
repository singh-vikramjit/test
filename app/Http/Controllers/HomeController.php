<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Google_Client;
// use Google\Spreadsheet\DefaultServiceRequest;
// use Google\Spreadsheet\ServiceRequestFactory;
// use Google\Spreadsheet\SpreadsheetService;
// use Google_Service_Sheets;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*$client = $this->getClient();
        //$service = new Google_Service_Sheets($client);

        $spreadsheetService = new SpreadsheetService();
        $spreadsheetFeed = $spreadsheetService->getSpreadsheetFeed();
        dd($spreadsheetFeed);
        $spreadsheet = $spreadsheetFeed->getByTitle('Google sheets reports');

        $spreadsheetId = 'OpZSICGAfcxFhMIjuf6jP5U9pveLhLAxH9DK5A2__1o';
        $range = 'Master report!A1:D9';
        dd($client);
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        dd($response);
        $values = $response->getValues();*/

        return view('home');
    }

    /*private function getClient()
    {
        /*$client = new Google_Client();
        $client->setApplicationName('Google Sheets');
        $client->setScopes([Google_Service_Sheets::DRIVE, Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');*/

        /*$client = new Google_Client();

        $client->setApplicationName("My Application");
        $client->setDeveloperKey("25c7d390d04e7cebba0f170815caff00ee366f59");*/

        // $client->setAuthConfig(storage_path('client_secret.json'));
        // $client->setScopes([Google_Service_Sheets::DRIVE, Google_Service_Sheets::SPREADSHEETS]);

        // if ($client->isAccessTokenExpired()) {
        //     $client->refreshTokenWithAssertion();
        // }

        /*$accessToken = $client->fetchAccessTokenWithAssertion()["access_token"];
        ServiceRequestFactory::setInstance(
            new DefaultServiceRequest($accessToken)
        );
        dd($client);
        //return $client;
    }*/
}
