<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seller extends CI_Controller {

    private $user;
    private $table = 'sellers';

    public function __construct() {
        parent::__construct();
        $this->load->model('Seller_model', 'seller');
        $this->user = $this->session->userdata('user');
        $this->load->library("HereMap", "heremap");
        if (!$this->user) {
            redirect(base_url('auth'));
        }
    }

    public function index() {
        $data = [
            'judul' => 'Admin',
            'deskripsi' => 'Halaman Penjual',
            'content' => 'seller/index',
            'menu' => 'seller',
            'javascripts' => [base_url('assets/admin/js/seller.js')],
            'user' => $this->user
        ];

        $this->load->view('layout/full', $data);
    }

    public function get_list() {
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');

        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $order = $this->input->post('order')[0];
        $search = $this->input->post('search')['value'];
        $draw = intval($this->input->post('draw'));

        $output['data'] = [];
        $datas = $this->seller->get_all($start, $length, $search, $order);
        if ($datas) {
            foreach ($datas->result() as $data) {
                $output['data'][] = [
                    $data->store_name,
                    $data->phone,
                    $data->address,
                    $data->status == 'active' ? 'Aktif' : 'Tidak Aktif',
                    '<a href="' . base_url('seller/form/' . $data->id) . '" class="btn btn-warning btn-sm">Ubah</a>'
                ];
            }
        }
        $output['draw'] = $draw++;
        $output['recordsTotal'] = $this->seller->count_all();
        $output['recordsFiltered'] = $this->seller->count_all($search);
        echo json_encode($output);
    }

    public function form($id = '') {
        $data = [
            'judul' => 'Admin',
            'content' => 'seller/form',
            'menu' => 'seller',
            'error' => '',
            'javascripts' => [base_url('assets/admin/js/seller.js?t=' . time())],
            'data' => '',
        ];

        $data['provincies'] = $this->main->gets('provincies');
        $data['cities'] = false;
        $data['districts'] = false;
        $data['shippings'] = ['pos', 'jne', 'tiki'];
        if ($id == '') {
            $data['deskripsi'] = 'Tambah Penjual';
        } else {
            $data['deskripsi'] = 'Ubah Penjual';
            $data['data'] = $this->main->get($this->table, ['id' => $id]);
            if (!$data['data']) {
                redirect(base_url('seller'));
            }
            $data['cities'] = $this->main->gets('cities', ['province' => $data['data']->province_id]);
            $data['districts'] = $this->main->gets('districts', ['city' => $data['data']->city_id]);
        }

        $this->form_validation->set_rules('store_name', 'Nama Toko', 'required|trim');
        $this->form_validation->set_rules('name', 'Nama Pemilik', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        if ($id == '') {
            $this->form_validation->set_rules('password', 'Password', 'required|trim');
        }
        $this->form_validation->set_rules('phone', 'No Handphone', 'required|trim');
        $this->form_validation->set_rules('status', 'Status', 'required|trim');
        // $this->form_validation->set_rules('courier', 'Kurir', '');
        $this->form_validation->set_rules('bank_name', 'Bank', 'required|trim');
        $this->form_validation->set_rules('account_number', 'Nomor Akun', 'required|trim');
        $this->form_validation->set_rules('account_holder', 'Nama Akun', 'required|trim');

        $this->form_validation->set_rules('address', 'Alamat', 'required|trim');
        $this->form_validation->set_rules('province_id', 'Provinsi', 'required|trim');
        $this->form_validation->set_rules('city_id', 'Kota / Kabupaten', 'required|trim');
        $this->form_validation->set_rules('district_id', 'Kecamatan', 'required|trim');
        $this->form_validation->set_rules('postcode', 'Kode Pos', 'required|trim');
        $this->form_validation->set_rules('seo_url', 'Seo URL', 'required|trim');

        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
        $this->form_validation->set_message('required', '%s harus diisi!');

        if ($this->form_validation->run() === FALSE) {
            var_dump(validation_errors());die;
            $this->load->view('layout/full', $data);
        } else {
            $post = $this->input->post(null, true);

            $province = $this->main->get('provincies', ['id' => $post['province_id']]);
            $city = $this->main->get('cities', ['id' => $post['city_id']]);
            $district = $this->main->get('districts', ['id' => $post['district_id']]);

            $full_address = $post['address'] . ' ' . $district->name . ', ' . $city->type . ' ' . $city->name . ', ' . $province->name . ', ' . $post['postcode'];
            $location = $this->heremap->geocode($full_address);
            $post['latitude'] = $location['position']['lat'];
            $post['longitude'] = $location['position']['lng'];
            if ($post['password']) {
                $post['password'] = password_hash($post['password'], PASSWORD_DEFAULT);
            }

            $post['courier'] = json_encode($post['courier']);
            if ($id != '') {
                $post['updated_at'] = date('Y-m-d H:i:s');
                $this->main->update($this->table, $post, ['id' => $id]);
                $this->session->set_flashdata('message', 'Penjual berhasil diubah!');
                redirect(base_url('seller'));
            } else {
                $post['created_at'] = date('Y-m-d H:i:s');
                $post['updated_at'] = date('Y-m-d H:i:s');
                $this->main->insert($this->table, $post);
                $this->session->set_flashdata('message', 'Penjual berhasil ditambah!');
                redirect(base_url('seller'));
            }
        }
    }

    public function delete($id) {
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');
        $this->main->delete($this->table, ['id' => $id]);
        $return = array('message' => 'Penjual berhasil dihapus', 'status' => 'success');
        echo json_encode($return);
    }

    public function get_city() {
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');

        $post = $this->input->post(null, true);
        $cities = $this->main->gets('cities', ['province' => $post['province']]);
        echo json_encode([
            'data' => $cities->result()
        ]);die;
    }

    public function get_district() {
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');

        $post = $this->input->post(null, true);
        $districts = $this->main->gets('districts', ['city' => $post['city']]);
        echo json_encode([
            'data' => $districts->result()
        ]);die;
    }

}