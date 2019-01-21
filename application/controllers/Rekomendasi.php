<?php
class Rekomendasi extends CI_Controller{
  function __construct(){
    parent::__construct();
    $this->load->model('login_model');
  }

  function index(){
      $this->load->view('rekomendasi/v_rekomendasi');
  }
}
