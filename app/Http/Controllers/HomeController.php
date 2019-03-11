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
    public $spreadsheet, $worksheet, $cell_feed;

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
        $this->getSheetByName('Google sheets reports');

        // $spreadsheet contains the list of worksheets select the worksheet which you want to select by name
        $this->getWorkSheetByName('Master report');
        
        $this->cell_feed = $this->worksheet->getCellFeed();

        $cell = $this->cell_feed->getCell(3, 1);
        $cell->update(rand());

        dd('updated');
    }
    
    private function getWorkSheetByName(string $title)
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
    }

    private function getClient()
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
    }
}
