<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Balance_user extends CI_Controller {
    private $user;

    public function __construct() {
        parent::__construct();
        $this->load->model('Balance_user_model', 'balance_user');
        $this->load->library('api');
        $this->user = $this->session->userdata('user');
        if (!$this->user) {
            redirect(base_url('auth'));
        }
    }

    public function index() {
        $data = [
            'judul' => "Admin",
            'deskripsi' => "Saldo User",
            'content' => 'balance_user/index',
            'menu' => 'balance_user',
            'javascripts' => [base_url('assets/admin/js/balance_user.js')],
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
        $datas = $this->balance_user->get_all($start, $length, $search, $order, $filter['from'], $filter['to']);
        if ($datas) {
            foreach ($datas->result() as $data) {
                $output['data'][] = [
                    $data->created_at,
                    $data->name,
                    rupiah($data->amount),
                    ucfirst($data->status),
                    $data->note ? $data->note : 'Tidak Ada',
                    $data->status == 'request' ? 
                    '<button class="btn btn-success btn-sm" data-bank="'. $data->bank_name .'" data-account_number="' . $data->account_number . '" data-account_holder="' . $data->account_holder . '" onclick="openModal('. $data->id .', this)">Accept</button>' .
                    '<a href="' . base_url('balance_user/rejected/' . $data->id) . '" class="btn btn-danger btn-sm ml-2" onclick="rejectBalance(this, event)">Reject</a>' 
                    : '-'
                ];
            }
        }
        $output['draw'] = $draw++;
        $output['recordsTotal'] = $this->balance_user->count_all();
        $output['recordsFiltered'] = $this->balance_user->count_all($search);
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
            $user_balance = $this->main->get('user_balances', ['id' => $id]);
            if ($user_balance) {
                $user = $this->main->get('users', ['id' => $user_balance->user_id]);
                if ($user) {
                    if ($user->balance >= $user_balance->amount) {
                        $this->main->update('user_balances', ['status' => 'accepted', 'confirm_image' => $response['data']['image']], ['id' => $id]);
                        $new_balance = $user->balance - $user_balance->amount;
                        $this->main->update('users', ['balance' => $new_balance], ['id' => $user->id]);

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

        $user_balance = $this->main->get('user_balances', ['id' => $id]);
        $return = [
            'status' => "error",
            'message' => "Something wen't wrong!"
        ];
        if ($user_balance) {
            $this->main->update('user_balances', ['status' => 'rejected'], ['id' => $user_balance->id]);
            $return = [
                'status' => 'success',
                'message' => 'Success!'
            ];
        }
        echo json_encode($return);
    }
}