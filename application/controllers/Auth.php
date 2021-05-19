<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct() {
        parent::__construct();
    }    

	public function index()
	{
		if ($this->session->userdata('user')) {
            redirect(base_url());
        }

        $data = [
            'judul' => 'Login Admin',
            'content' => 'auth/login'
        ];

        $this->form_validation->set_rules('email', 'Email', 'trim|required', [
            'required' => 'Email harus diisi!'
        ]);
        $this->form_validation->set_rules('password', 'Password', 'trim|required', [
            'required' => 'Password harus diisi!'
        ]);

        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('auth/layout', $data);
        } else {
            $post = $this->input->post(null, true);
            // cek email
            $check_email = $this->main->get('admins', ['email' => $post['email']]);
            if ($check_email) {
                if (password_verify($post['password'], $check_email->password)) {
                    $this->session->set_userdata(['user' => $check_email]);
                    redirect(base_url(''));
                } else {
                    $this->session->set_flashdata('error', 'Password salah!');
                    redirect(base_url('auth'));
                }
            } else {
                $this->session->set_flashdata('error', 'Email tidak terdaftar!');
                redirect(base_url('auth'));
            }
        }
	}

    public function logout() {
        $this->session->unset_userdata('user');
        redirect(base_url('auth'));
    }

    public function manual_register() {
        die;
        $email = $this->input->get('email');
        $password = $this->input->get('password');
        $name = $this->input->get('name');

        $new_password = password_hash($password, PASSWORD_DEFAULT);

        $this->main->insert('admins', [
            'email' => $email,
            'password' => $new_password,
            'name' => $name,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}
