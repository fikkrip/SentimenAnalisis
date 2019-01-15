<?php

class TextMining extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('document_model');
        $this->load->library('PorterStemmer');
        $this->load->library('Porter2');
        $this->load->model('textmining_model');
    }

    function index(){
        if($this->session->userdata('masuk') != TRUE){
            $this->load->view('login/v_login');
        }else{
            $data['get_result'] = 0;
            $this->load->view('textmining/v_textmining', $data);
        }
    }

    function text_processing_proses()
    {
        $word = $this->input->post('kata');
        $getstopword = $this->db->get('stopwords');
        $liststopword = array();
        foreach ($getstopword->result() as $r) {
            array_push($liststopword, $r->stopword);
        }
        $word = $this->textmining_model->preprocessing($word);
        $tokenizing = explode(" ", $word);
        $stemming = array();
        $stopword = array();
        $lastword = array();
        $unknown = array();
        // echo $coba->stem('connecting');
        foreach ($tokenizing as $key => $value) {
            if (in_array($value, $liststopword)) { //jika stopword maka lewati
                array_push($stopword, $value);
                continue;
            }
            $r_stem = $this->porter2->stem($value);

            if ($r_stem != $value) {
                array_push($stemming, array('kataawal'=>$value,'kataakhir'=>$r_stem));
                $value = $r_stem;
            }
            $cekkata = $this->db->query("select * from final_sentiword where final_sentiword_word = '".$value."'");
            if ($cekkata->num_rows() == 0) {
                array_push($unknown, $value);
            }
            array_push($lastword, $value);
        }
        $data['tokenizing'] = $tokenizing;
        $data['stemming'] = $stemming;
        $data['stopword'] = $stopword;
        $data['unknown'] = $unknown;
        $data['lastword'] = $lastword;
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}
