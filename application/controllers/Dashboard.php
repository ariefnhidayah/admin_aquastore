<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public $user;
    public $model;

    public function __construct() {
        parent::__construct();
        $this->user = $this->session->userdata('user');
        if (!$this->user) {
            redirect(base_url('auth'));
        }
    }

	public function index() {
		$data = [
			'judul' => 'Admin Dashboard',
			'deskripsi' => 'Halaman Dashboard',
			'content' => 'dashboard/index',
			'menu' => 'dashboard',
			// 'javascript' => [
            //     base_url('assets/admin/js/dashboard.js'),
            // ],
			'user' => $this->user
		];

        $this->load->view('layout/full', $data);
	}
}
