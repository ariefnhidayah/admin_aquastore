<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Api {

    public $url_media = 'http://localhost:8081/';

    public function post($url, $data = array()) {
        $curl = curl_init();

        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';

        $payload = json_encode($data);

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        curl_close($curl);

        $responseObject = json_decode($response, true);
        $return = [
            'status' => 'error',
            'message' => "Something wen't wrong!"
        ];
        if ($responseObject) {
            $return = $responseObject;
        } 
        return $return;
    }

    public function get($url, $data = array()) {
        $curl = curl_init();

        $headers = array();
        $headers[] = 'Content-Type: application/json';

        $end_point = $url . '?' . http_build_query($data, '', '&');

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_URL, $end_point);
        curl_setopt($curl, CURLOPT_POST, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        curl_close($curl);

        $responseObject = json_decode($response, true);
        $return = [
            'status' => 'error',
            'message' => "Something wen't wrong!"
        ];
        if ($responseObject) {
            $return = $responseObject;
        } 
        return $return;
    }

}