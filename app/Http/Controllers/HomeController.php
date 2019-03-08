<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;
use Google\Spreadsheet\SpreadsheetService;
use Google_Service_Sheets;

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
        //create connection
        $this->getClient();

        //get sheet by name of the sheet
        $spreadsheet = $this->getSheetByName('Google sheets reports');

        // $spreadsheet contains the list of worksheets select the worksheet which you want to select by name
        $worksheet = $this->getWorkSheetByName($spreadsheet, 'Master report');
        
        $cellFeed = $worksheet->getCellFeed();
        $cell = $cellFeed->getCell(3, 1);
        $cell->update(rand());

        dd('done');
        return view('home');
    }

    private function getWorkSheetByName(array $spreadsheet, string $title)
    {
        foreach ($spreadsheet as $sheet) {
            if ($sheet->getTitle() == trim($title)) {
                return $sheet;
            }
        }
        return false;
    }

    private function getSheetByName(string $title)
    {
        $spreadsheet = (new SpreadsheetService)->getSpreadsheetFeed()->getByTitle(trim($title));
        return $spreadsheet->getWorksheetFeed()->getEntries();
    }

    private function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Sheets');
        $client->setScopes([Google_Service_Sheets::DRIVE, Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $client->setAuthConfig(storage_path('client_secret.json'));
        if ($client->isAccessTokenExpired()) {
            $client->refreshTokenWithAssertion();
        }
        $accessToken = $client->fetchAccessTokenWithAssertion()["access_token"];
        ServiceRequestFactory::setInstance(
            new DefaultServiceRequest($accessToken)
        );
    }
}
