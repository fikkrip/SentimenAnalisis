<?php

class SentimentAnalysis extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->library('PorterStemmer');
        $this->load->library('Porter2');
        $this->load->model('datatrain_model');
        $this->load->model('textmining_model');
    }

    function index(){
        if($this->session->userdata('masuk') != TRUE){
            $this->load->view('login/v_login');
        }else{
            $data['get_result'] = 0;
            $this->load->view('sentiment/v_sentiment', $data);
        }
    }

    public function cek_kata_single()
    {
        $word = $this->input->post('kata');
        $word = $this->textmining_model->preprocessing($word);
        $hitung['naivebayes'] = $this->datatrain_model->text_classification_bayes_single($word);
        $this->output->set_content_type('application/json')->set_output(json_encode($hitung));
    }
}
