<?php

class Kategorisasi extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('PorterStemmer');
        $this->load->library('Porter2');
        $this->load->model('textmining_model');
        $this->load->model('kategorisasi_model');
    }

    function index(){
        if($this->session->userdata('masuk') != TRUE){
            $this->load->view('login/v_login');
        }else{
            $data['get_result'] = 0;
            $this->load->view('kategorisasi/v_kategorisasi', $data);
        }
    }

    public function kategorisasi_kata()
    {
        $word = $this->input->post('kata');
        $word = $this->textmining_model->preprocessing($word);
        $hitung['naivebayes'] = $this->kategorisasi_model->kategorisasi_bayes_single($word);
        $this->output->set_content_type('application/json')->set_output(json_encode($hitung));
    }

    function kategorisasi_proses()
    {
        $query = array();
        $baterai = array();
        $layar = array();
        $kamera = array();
        $string = "filter camera zoom battery resolution";
        $query_explode = explode(" ", $string);
        $query_size = count($query_explode);

        $query_baterai = $this->kategorisasi_model->getPerIdPelayanan('1');
        $jumlah_baterai = $query_baterai->num_rows();
        $i = 0;
        foreach ($query_baterai->result() as $row)
        {
            $baterai[$i]['keyword'] = $row->keyword;
            $baterai[$i]['tf'] = 1/$jumlah_baterai;

            $df1 = (int)$this->db->query("select count(*) as jumlah from kategorisasi where id_kategori = 1 AND keyword = '".$row->keyword."'")->row()->jumlah;
            $df2 = (int)$this->db->query("select count(*) as jumlah from kategorisasi where id_kategori = 2 AND keyword = '".$row->keyword."'")->row()->jumlah;
            $df3 = (int)$this->db->query("select count(*) as jumlah from kategorisasi where id_kategori = 3 AND keyword = '".$row->keyword."'")->row()->jumlah;

            $df = $df1+$df2+$df3;

            $baterai[$i]['idf'] = 1+log(3/$df);
            $baterai[$i]['tf_idf'] = $baterai[$i]['tf']*$baterai[$i]['idf'];

            $i++;
        }

        $query_layar = $this->kategorisasi_model->getPerIdPelayanan('2');
        $jumlah_layar = $query_layar->num_rows();
        $i = 0;
        foreach ($query_layar->result() as $row)
        {
            $layar[$i]['keyword'] = $row->keyword;
            $layar[$i]['tf'] = 1/$jumlah_layar;

            $df1 = (int)$this->db->query("select count(*) as jumlah from kategorisasi where id_kategori = 1 AND keyword = '".$row->keyword."'")->row()->jumlah;
            $df2 = (int)$this->db->query("select count(*) as jumlah from kategorisasi where id_kategori = 2 AND keyword = '".$row->keyword."'")->row()->jumlah;
            $df3 = (int)$this->db->query("select count(*) as jumlah from kategorisasi where id_kategori = 3 AND keyword = '".$row->keyword."'")->row()->jumlah;

            $df = $df1+$df2+$df3;

            $layar[$i]['idf'] = 1+log(3/$df);
            $layar[$i]['tf_idf'] = $layar[$i]['tf']*$layar[$i]['idf'];

            $i++;
        }

        $query_kamera = $this->kategorisasi_model->getPerIdPelayanan('3');
        $jumlah_kamera = $query_kamera->num_rows();
        $i = 0;
        foreach ($query_kamera->result() as $row)
        {
            $kamera[$i]['keyword'] = $row->keyword;
            $kamera[$i]['tf'] = 1/$jumlah_kamera;

            $df1 = (int)$this->db->query("select count(*) as jumlah from kategorisasi where id_kategori = 1 AND keyword = '".$row->keyword."'")->row()->jumlah;
            $df2 = (int)$this->db->query("select count(*) as jumlah from kategorisasi where id_kategori = 2 AND keyword = '".$row->keyword."'")->row()->jumlah;
            $df3 = (int)$this->db->query("select count(*) as jumlah from kategorisasi where id_kategori = 3 AND keyword = '".$row->keyword."'")->row()->jumlah;

            $df = $df1+$df2+$df3;

            $kamera[$i]['idf'] = 1+log(3/$df);
            $kamera[$i]['tf_idf'] = $kamera[$i]['tf']*$kamera[$i]['idf'];

            $i++;
        }

        $jumlah_kata = array_count_values($query_explode);
        $jumlah_query = count($query_explode);
        $dot_baterai = 0;
        $dot_layar = 0;
        $dot_kamera = 0;

        $jml_temp_kuadrat_query_baterai = 0;
        $jml_temp_kuadrat_dokumen_baterai = 0;

        $jml_temp_kuadrat_query_layar = 0;
        $jml_temp_kuadrat_dokumen_layar = 0;

        $jml_temp_kuadrat_query_kamera = 0;
        $jml_temp_kuadrat_dokumen_kamera = 0;

        $cosine_similarity_baterai = 0;
        $cosine_similarity_layar = 0;
        $cosine_similarity_kamera = 0;

        for($i=0; $i<$query_size; $i++){
            $query[$i]['keyword'] = $query_explode[$i];
            $query[$i]['tf'] = $jumlah_kata[$query_explode[$i]]/$jumlah_query;

            foreach ($baterai as $row){
                if($row['keyword'] == $query_explode[$i]){
                    $query[$i]['idf_query_baterai'] = $row['idf'];
                    $query[$i]['tfidf_baterai'] = $row['tf_idf'];
                    $query[$i]['tfidf_query_baterai'] = $query[$i]['tf']*$row['idf'];

                    $temp_dot_baterai =  $query[$i]['tfidf_baterai'] *  $query[$i]['tfidf_query_baterai'] ;
                    $dot_baterai = $dot_baterai + $temp_dot_baterai;

                    $temp_kuadrat_query_baterai = pow($query[$i]['tfidf_query_baterai'],2);
                    $jml_temp_kuadrat_query_baterai = $jml_temp_kuadrat_query_baterai + $temp_kuadrat_query_baterai;


                    $temp_kuadrat_dokumen_baterai = pow($query[$i]['tfidf_baterai'],2);
                    $jml_temp_kuadrat_dokumen_baterai = $jml_temp_kuadrat_dokumen_baterai + $temp_kuadrat_dokumen_baterai;

                }

                if(empty($query[$i]['idf_query_baterai'])){
                    $query[$i]['idf_query_baterai'] = 0;
                    $query[$i]['tfidf_baterai'] = 0;
                    $query[$i]['tfidf_query_baterai'] = 0;
                }
            }

            foreach ($layar as $row){
                if($row['keyword'] == $query_explode[$i]){
                    $query[$i]['idf_query_layar'] = $row['idf'];
                    $query[$i]['tfidf_layar'] = $row['tf_idf'];
                    $query[$i]['tfidf_query_layar'] = $query[$i]['tf']*$row['idf'];

                    $temp_dot_layar =  $query[$i]['tfidf_layar'] *  $query[$i]['tfidf_query_layar'] ;
                    $dot_layar = $dot_layar + $temp_dot_layar;

                    $temp_kuadrat_query_layar = pow($query[$i]['tfidf_query_layar'],2);
                    $jml_temp_kuadrat_query_layar = $jml_temp_kuadrat_query_layar + $temp_kuadrat_query_layar;

                    $temp_kuadrat_dokumen_layar = pow($query[$i]['tfidf_layar'],2);
                    $jml_temp_kuadrat_dokumen_layar = $jml_temp_kuadrat_dokumen_layar + $temp_kuadrat_dokumen_layar;
                }

                if(empty($query[$i]['idf_query_layar'])){
                    $query[$i]['idf_query_layar'] = 0;
                    $query[$i]['tfidf_layar'] = 0;
                    $query[$i]['tfidf_query_layar'] = 0;
                }
            }

            foreach ($kamera as $row){
                if($row['keyword'] == $query_explode[$i]){
                    $query[$i]['idf_query_kamera'] = $row['idf'];
                    $query[$i]['tfidf_kamera'] = $row['tf_idf'];
                    $query[$i]['tfidf_query_kamera'] = $query[$i]['tf']*$row['idf'];

                    $temp_dot_kamera =  $query[$i]['tfidf_kamera'] *  $query[$i]['tfidf_query_kamera'] ;
                    $dot_kamera = $dot_kamera + $temp_dot_kamera;

                    $temp_kuadrat_query_kamera = pow($query[$i]['tfidf_query_kamera'],2);
                    $jml_temp_kuadrat_query_kamera = $jml_temp_kuadrat_query_kamera + $temp_kuadrat_query_kamera;

                    $temp_kuadrat_dokumen_kamera = pow($query[$i]['tfidf_kamera'],2);
                    $jml_temp_kuadrat_dokumen_kamera = $jml_temp_kuadrat_dokumen_kamera + $temp_kuadrat_dokumen_kamera;
                }

                if(empty($query[$i]['idf_query_kamera'])){
                    $query[$i]['idf_query_kamera'] = 0;
                    $query[$i]['tfidf_kamera'] = 0;
                    $query[$i]['tfidf_query_kamera'] = 0;
                }
            }
        }

        $dot = array(
            'dot_baterai' => $dot_baterai,
            'dot_layar' => $dot_layar,
            'dot_kamera' => $dot_kamera
        );

//        $term = array_merge($baterai, $layar, $kamera);

        if(((sqrt($jml_temp_kuadrat_query_baterai)) * (sqrt($jml_temp_kuadrat_dokumen_baterai))) != 0)
            $cosine_similarity_baterai = $dot_baterai / ((sqrt($jml_temp_kuadrat_query_baterai)) * (sqrt($jml_temp_kuadrat_dokumen_baterai)));

        if(((sqrt($jml_temp_kuadrat_query_layar)) * (sqrt($jml_temp_kuadrat_dokumen_layar))) != 0)
            $cosine_similarity_layar = $dot_layar / ((sqrt($jml_temp_kuadrat_query_layar)) * (sqrt($jml_temp_kuadrat_dokumen_layar)));

        if(((sqrt($jml_temp_kuadrat_query_kamera)) * (sqrt($jml_temp_kuadrat_dokumen_kamera))) != 0)
            $cosine_similarity_kamera = $dot_kamera / ((sqrt($jml_temp_kuadrat_query_kamera)) * (sqrt($jml_temp_kuadrat_dokumen_kamera)));

        $cosine_similarity = array(
            'cosine_similarity_baterai' => $cosine_similarity_baterai,
            'cosine_similarity_layar' => $cosine_similarity_layar,
            'cosine_similarity_kamera' => $cosine_similarity_kamera
        );

        $data = array(
            'cosine_similarity' => $cosine_similarity,
            'dot' => $dot,
            'query' => $query,
            'baterai' => $baterai,
            'layar' => $layar,
            'kamera' => $kamera
        );

        var_dump($data);
        die();
    }
}
