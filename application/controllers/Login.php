<?php
class Login extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->model('login_model');
	}

	function index(){
        if($this->session->userdata('masuk') != TRUE){
            $this->load->view('login/v_login');
        }else{
            $url=base_url();
            redirect($url.'page');
        }
	}

	function auth(){
        $username=htmlspecialchars($this->input->post('username',TRUE),ENT_QUOTES);
        $password=htmlspecialchars($this->input->post('password',TRUE),ENT_QUOTES);

        $cek_login=$this->login_model->auth_login($username,$password);

        if($cek_login->num_rows() > 0){ //jika login sebagai dosen
            $url=base_url();
            $data=$cek_login->row_array();
            $this->session->set_userdata('masuk',TRUE);
            $this->session->set_userdata('ses_nama',$data['username']);
            redirect($url);
        }else{  // jika username dan password tidak ditemukan atau salah
            echo $this->session->set_flashdata('msg','Username Atau Password Salah');
            redirect('login');
        }

    }

    function logout(){
        $this->session->sess_destroy();
        $url=base_url('');
        redirect($url);
    }

}
