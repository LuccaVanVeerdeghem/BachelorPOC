<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

class Call_Log extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        // Retrieve telephone number from query parameter
        $telephoneNumber = $this->request->getGet('number');

        // Proxy request to call log microservice to fetch call logs for the specified telephone number
        $log_data = $this->fetchCallLogsFromMicroservice($telephoneNumber);

        if ($log_data ['status'] == 'success') {

            return view('usage', ['callLogs' => $log_data['logs'], 'totalMinutes' => $log_data['totalMinutes'], 'totalMessages' => $log_data['totalMessages'], 'totalData'=>$log_data['totalData']]);
        } else {
            // Return error response if call logs are not found
            return view('error/404');
        }
    }

    private function fetchCallLogsFromMicroservice($telephoneNumber)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'http://apache2/public/api/call-logs/' . $telephoneNumber,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ]
        ]);
        
        $result = json_decode(curl_exec($curl), true);
        return $result;
    }
}
