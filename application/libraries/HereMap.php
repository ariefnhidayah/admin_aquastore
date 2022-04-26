<?php 

defined('BASEPATH') or exit('No direct script access allowed');

class HereMap {
    public $CI;
    private $apiKey = 'X4HRYcF4fu6gY3rahgAXuwesTvpT8FF3kPQ_n4Je64E';

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->library("api");
    }

    public function geocode($address) {
        $request = [
            'q' => $address,
            'apiKey' => $this->apiKey
        ];
        $response = $this->CI->api->get('https://discover.search.hereapi.com/v1/geocode', $request);

        return $response['items'][0];
    }
}