<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Balance_seller extends CI_Controller {
    private $user;

    public function __construct() {
        parent::__construct();
        $this->load->model('Balance_seller_model', 'balance_seller');
        $this->load->library('api');
        $this->user = $this->session->userdata('user');
        if (!$this->user) {
            redirect(base_url('auth'));
        }
    }

    public function index() {
        $data = [
            'judul' => "Admin",
            'deskripsi' => "Saldo Seller",
            'content' => 'balance_seller/index',
            'menu' => 'balance_seller',
            'javascripts' => [base_url('assets/admin/js/balance_seller.js')],
            'user' => $this->user,
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
        $datas = $this->balance_seller->get_all($start, $length, $search, $order, $filter['from'], $filter['to']);
        if ($datas) {
            foreach ($datas->result() as $data) {
                $output['data'][] = [
                    $data->created_at,
                    $data->store_name,
                    rupiah($data->amount),
                    ucfirst($data->status),
                    $data->note ? $data->note : 'Tidak Ada',
                    $data->status == 'request' ? 
                    '<button class="btn btn-success btn-sm" data-bank="'. $data->bank_name .'" data-account_number="' . $data->account_number . '" data-account_holder="' . $data->account_holder . '" onclick="openModal('. $data->id .', this)">Accept</button>' .
                    '<a href="' . base_url('balance_seller/rejected/' . $data->id) . '" class="btn btn-danger btn-sm ml-2" onclick="rejectBalance(this, event)">Reject</a>' 
                    : '-'
                ];
            }
        }
        $output['draw'] = $draw++;
        $output['recordsTotal'] = $this->balance_seller->count_all();
        $output['recordsFiltered'] = $this->balance_seller->count_all($search);
        echo json_encode($output);
    }

    public function accepted() {
        $this->input->is_ajax_request() or exit("No direct post submit allowed!");

        $data = $this->input->post(null, true);
        $id = $data['id'];

        $request_image = [
            'image' => 'data:image/jpeg;base64,' . $data['image']
        ];

        $response = $this->api->post($this->api->url_media . 'media', $request_image);
        $return = [
            'status' => 'error',
            'message' => "Something wen't wrong!"
        ];
        if ($response['status'] == 'success') {
            $seller_balance = $this->main->get('seller_balances', ['id' => $id]);
            if ($seller_balance) {
                $seller = $this->main->get('sellers', ['id' => $seller_balance->seller_id]);
                if ($seller) {
                    if ($seller->balance >= $seller_balance->amount) {
                        $this->main->update('seller_balances', ['status' => 'accepted', 'confirm_image' => $response['data']['image']], ['id' => $id]);
                        $new_balance = $seller->balance - $seller_balance->amount;
                        $this->main->update('sellers', ['balance' => $new_balance], ['id' => $seller->id]);

                        $return = [
                            'status' => 'success',
                            'message' => 'Success!'
                        ];
                    }
                }
            }
        } else {
            $return = $response;
        }
        echo json_encode($return);
    }

    public function rejected($id) {
        $this->input->is_ajax_request() or exit("No direct post submit allowed!");

        $seller_balance = $this->main->get('seller_balances', ['id' => $id]);
        $return = [
            'status' => "error",
            'message' => "Something wen't wrong!"
        ];
        if ($seller_balance) {
            $this->main->update('seller_balances', ['status' => 'rejected'], ['id' => $seller_balance->id]);
            $return = [
                'status' => 'success',
                'message' => 'Success!'
            ];
        }
        echo json_encode($return);
    }
}