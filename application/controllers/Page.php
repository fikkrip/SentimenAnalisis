<?php
class Page extends CI_Controller{
  function __construct(){
    parent::__construct();
    $this->load->model('login_model');

  }

  function index(){
      $this->load->view('page/v_dashboard');
  }

    function index2(){
        $this->load->view('login/v_login');
    }
}
