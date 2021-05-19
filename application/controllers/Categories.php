<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Categories extends CI_Controller
{
    private $user;
    private $table = 'categories';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Categories_model', 'categories');
        $this->user = $this->session->userdata('user');
        if (!$this->user) {
            redirect(base_url('auth'));
        }
    }

    public function index()
    {
        $data = [
            'judul' => 'Admin',
            'deskripsi' => 'Halaman Kategori',
            'content' => 'category/index',
            'menu' => 'kategori',
            'javascripts' => [base_url('assets/admin/js/categories.js')],
            'user' => $this->user
        ];

        $this->load->view('layout/full', $data);
    }

    public function get_list()
    {
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');

        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $order = $this->input->post('order')[0];
        $search = $this->input->post('search')['value'];
        $draw = intval($this->input->post('draw'));

        $output['data'] = [];
        $datas = $this->categories->get_all($start, $length, $search, $order);
        if ($datas) {
            foreach ($datas->result() as $data) {
                $output['data'][] = [
                    $data->name,
                    $data->status == 1 ? 'Aktif' : 'Tidak Aktif',
                    // '<a href="' . base_url('categories/form/' . $data->id) . '" class="btn btn-warning btn-sm">Ubah</a> <a href="' . base_url('categories/delete/' . $data->id) . '" class="btn btn-danger btn-sm delete">Hapus</a>'
                    '<a href="' . base_url('categories/form/' . $data->id) . '" class="btn btn-warning btn-sm">Ubah</a>'
                ];
            }
        }
        $output['draw'] = $draw++;
        $output['recordsTotal'] = $this->categories->count_all();
        $output['recordsFiltered'] = $this->categories->count_all($search);
        echo json_encode($output);
    }

    public function form($id = '')
    {
        $data = [
            'judul' => 'Admin',
            'content' => 'category/form',
            'menu' => 'kategori',
            'error' => '',
            'javascripts' => [base_url('assets/admin/js/categories.js')],
            'data' => '',
        ];

        if ($id == '') {
            $data['deskripsi'] = 'Tambah Kategori';
        } else {
            $data['deskripsi'] = 'Ubah Kategori';
            $data['data'] = $this->main->get($this->table, ['id' => $id]);
            if (!$data['data']) {
                redirect(base_url('categories'));
            }
        }

        $this->form_validation->set_rules('name', 'Nama Kategori', 'required|trim');
        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
        $this->form_validation->set_message('required', '%s harus diisi!');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('layout/full', $data);
        } else {
            $post = $this->input->post(null, true);
            if ($id != '') {
                $this->main->update($this->table, $post, ['id' => $id]);
                $this->session->set_flashdata('message', 'Kategori berhasil diubah!');
                redirect(base_url('categories'));
            } else {
                $this->main->insert($this->table, $post);
                $this->session->set_flashdata('message', 'Kategori berhasil ditambah!');
                redirect(base_url('categories'));
            }
        }
    }

    public function delete($id)
    {
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');

        $this->main->delete($this->table, ['id' => $id]);

        $return = array('message' => 'Kategori berhasil dihapus', 'status' => 'success');

        echo json_encode($return);
    }
}
