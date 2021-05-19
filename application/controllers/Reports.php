<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reports extends CI_Controller {
    private $user;

    public function __construct() {
        parent::__construct();
        $this->load->model('Reports_model', 'reports');
        $this->user = $this->session->userdata('user');
        if (!$this->user) {
            redirect(base_url('auth'));
        }
    }

    public function index() {
        $data = [
            'judul' => 'Admin',
            'deskripsi' => 'Halaman Laporan',
            'content' => 'report/index',
            'menu' => 'report',
            'javascripts' => [base_url('assets/admin/js/report.js')],
            'user' => $this->user
        ];

        $this->load->view('layout/full', $data);
    }

    public function get_list() {
        $this->input->is_ajax_request() or exit("No direct post submit allowed!");

        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $order = $this->input->post('order')[0];
        $search = $this->input->post('search')['value'];
        $draw = intval($this->input->post('draw'));
        $filter = $this->input->post('filter', true);

        $output['data'] = [];
        $datas = $this->reports->get_all($start, $length, $search, $order, $filter['from'], $filter['to']);
        if ($datas) {
            foreach ($datas->result() as $data) {
                $output['data'][] = [
                    $data->created_at,
                    $data->code,
                    $data->user_name,
                    $data->store_name,
                    rupiah($data->subtotal),
                    rupiah($data->shipping_cost),
                    rupiah($data->total)
                ];
            }
        }
        $output['draw'] = $draw++;
        $output['recordsTotal'] = $this->reports->count_all();
        $output['recordsFiltered'] = $this->reports->count_all($search);
        echo json_encode($output);
    }
}