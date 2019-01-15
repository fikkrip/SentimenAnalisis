<?php

class SentimentProduk extends CI_Controller{

    function __construct()
    {
        parent::__construct();
        $this->load->library('PorterStemmer');
        $this->load->model('DataProduk_model');
        $this->load->model('DataReview_model');
    }

    function index()
    {
        if($this->session->userdata('masuk') != TRUE){
            $this->load->view('login/v_login');
        }else{
            $data = array();
            $data['produk'] = $this->DataProduk_model->getproduk();
            $this->load->view('sentiment_produk/v_sentiment_produk', $data);
        }
    }

    function history()
    {
        $data = array();
        $data['content'] = $this->load->view('history',$data,TRUE);
        $this->load->view('main', $data, FALSE);
    }

    function cek_kata()
    {
        $data = array();
        $data['content'] = $this->load->view('cek_kata',$data,TRUE);
        $this->load->view('main', $data, FALSE);
    }

    function text_processing()
    {
        $data = array();
        $data['content'] = $this->load->view('text_processing',$data,TRUE);
        $this->load->view('main', $data, FALSE);
    }

    function text_processing_proses()
    {
        $word = $this->input->post('kata');
        $getstopword = $this->db->get('stopwords');
        $liststopword = array();
        foreach ($getstopword->result() as $r) {
            array_push($liststopword, $r->stopword);
        }
        $word = $this->m_klasifikasi->preprocessing($word);
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
            $r_stem = $this->porterstemmer->stem($value);
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

    function detailhasil($idproduk=0)
    {
        $data = array();
        $namatgl = $this->DataReview_model->get_nama_produk(array('m_produk_id'=>$idproduk));
        $data['idproduk'] = $idproduk;
        $data['namaproduk'] = $namatgl->m_produk_nama;
        $data['tglsentiment'] = date('d-m-Y', strtotime($namatgl->tanggal_sentiment));
        $data['datareview'] = $this->DataReview_model->get_data_reviews(array('m_review_id_produk'=>$idproduk));
//        var_dump($data);
//        die();
        $this->load->view('sentiment_produk/v_detail_review', $data);
    }

    function testing($word='')
    {
        if (!$word) {
            $word = 'Another mention for Apple Store: http://t.co/fiIOApKt - RT @floridamike Once again getting great customer service from the @apple #store ...';
        }
        $word = strtolower($word);
        $word = preg_replace('((www\.[^\s]+)|(https?://[^\s]+))', 'URL', $word); //hapus url
        $word = preg_replace('(@[^\s]+)','AT_USER', $word); // hapus mention
        $word = preg_replace('([\s]+)', ' ', $word); // hapus withe spaces
        $word = preg_replace('(#([^\s]+))', '\1', $word); // hapus #
        echo $word;
    }

    public function hitungsentiment()
    {
        $msg = array();
        $dataparam = array();
        $dataparam['q'] = $this->input->post('q');
        $dataparam['count'] = $this->input->post('count');
        $dataproduk = $this->db->query("SELECT * from data_produk where UPPER(m_produk_nama) = '".strtoupper($dataparam['q'])."'")->row();
        $urlreviews = $dataproduk->m_produk_url_reviews;
        $sisa = $dataparam['count'] % 20;
        $jmlhal = ($dataparam['count'] - $sisa) / 20;
        $jmltotal = $jmlhal+1;
        $idproduk = $dataproduk->m_produk_id;

        $result = $this->DataReview_model->get_data_review(array('m_review_id_produk'=>$idproduk));

        if($result != false){
            $this->DataReview_model->delete_data_review(array('m_review_id_produk'=>$idproduk));
        }

        $run_count = 0;
        for($i=1;$i<=$jmltotal;$i++){
            $urlreviewss='';
            if ($i==1) {
                $urlreviewss = $urlreviews;
            }else{
                $urlreviewss = str_replace(".php", "p".$i.".php", $urlreviews);
            }
            $grab = file_get_contents($urlreviewss);
            $grab = explode('<div id="all-opinions">', $grab);
            $grab = explode('</div></div><div class="sub-footer no-margin-bottom">',$grab[1]);
            $grab = explode('<div class="user-thread"', $grab[0]);

            $j = 0;
            foreach ($grab as $key => $value) {
                if ($j == 0) {
                    $j++;
                    continue;
                }
                if ($run_count == $dataparam['count']) {
                    break;
                }
                $grabkomen = $this->getBetween($value,'<p class="uopin">','</p>');
                $grabkomen = explode("</span>", $grabkomen);
                $grabkomen = $grabkomen[(count($grabkomen)-1)];
                $grabnamauser = $this->getBetween($value,'<li class="uname">','</li>');
                if (!$grabnamauser) {
                    $grabnamauser = $this->getBetween($value,'<li class="uname2">','</li>');
                }
                $tgl = $this->getBetween($value,'<time>','</time>');

                $datareview = array();
                $datareview['text'] = $grabkomen;
                $datareview['user'] = $grabnamauser;
                $datareview['idproduk'] = $idproduk;
                $datareview['datepost'] = date('Y-m-d',strtotime($tgl));
                $this->DataReview_model->save_review($datareview);
                $run_count++;
                $j++;
            }
        }
        $msg['tipe'] = 'success';
        $msg['msg'] = 'Berhasil Mengambil Review';
        $msg['idproduk'] = $idproduk;
        $this->output->set_content_type('application/json')->set_output(json_encode($msg));
    }

    function getBetween($content,$start,$end){
        $r = explode($start, $content);
        if (isset($r[1])){
            $r = explode($end, $r[1]);
            return str_replace('<br>', " ", $r[0]);
        }
        return '';
    }

    public function hitungdata_bayes($idproduk='')
    {
        error_reporting(-1);
        // $idsearch = 3;
        $result = $this->DataReview_model->analyzereview_bayes($idproduk);
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function cek_kata_single()
    {
        $word = $this->input->post('kata');
        $word = $this->m_klasifikasi->preprocessing($word);
        $hitung['sentiword'] = $this->m_klasifikasi->text_classification_single($word);
        $hitung['naivebayes'] = $this->m_klasifikasi->text_classification_bayes_single($word);
        $this->output->set_content_type('application/json')->set_output(json_encode($hitung));
    }

    public function learning()
    {
        $totaltrain = $this->db->query("select count(*) as jumlah from data_train")->row()->jumlah;
        $totalpos = $this->db->query("select count(*) as jumlah from data_train where train_sentiment_desc = 'POSITIF'")->row()->jumlah;
        $totalneg = $this->db->query("select count(*) as jumlah from data_train where train_sentiment_desc = 'NEGATIF'")->row()->jumlah;
        $totalnet = $this->db->query("select count(*) as jumlah from data_train where train_sentiment_desc = 'NETRAL'")->row()->jumlah;
        $getdata = $this->db->query("select * from data_train where train_learn = 0 limit 10");
        $getdata = $getdata->result();
        foreach ($getdata as $r) {
            $word = explode(" ", $r->train_phrase);
            foreach ($word as $key => $value) {
                $sudahlearn = $this->db->query("select * from data_learn where data_learn_word = '".str_replace("'", "''", $value)."'");
                if ($sudahlearn->num_rows() > 0) {
                    continue;
                }
                $ppos = $this->db->query("select count(*) as jumlah from data_train where train_phrase like '%".str_replace("'", "''", $value)."%' and train_sentiment_desc = 'POSITIF'")->row()->jumlah;
                $pneg = $this->db->query("select count(*) as jumlah from data_train where train_phrase like '%".str_replace("'", "''", $value)."%' and train_sentiment_desc = 'NEGATIF'")->row()->jumlah;
                $pnet = $this->db->query("select count(*) as jumlah from data_train where train_phrase like '%".str_replace("'", "''", $value)."%' and train_sentiment_desc = 'NETRAL'")->row()->jumlah;
                $probabilitypos = ($ppos/$totalpos) * ($totalpos/$totaltrain);
                $probabilityneg = ($pneg/$totalneg) * ($totalneg/$totaltrain);
                $probabilitynet = ($pnet/$totalnet) * ($totalnet/$totaltrain);
                $tertinggi = max(array($probabilitynet,$probabilitypos,$probabilityneg));
                $finalsentiment='';
                if ($tertinggi == $probabilitypos) {
                    $finalsentiment = 'POSITIF';
                }else if($tertinggi == $probabilityneg){
                    $finalsentiment = 'NEGATIF';
                }else if($tertinggi == $probabilitynet){
                    $finalsentiment = 'NETRAL';
                }
                $this->db->query("insert into data_learn values ('','".str_replace("'", "''", $value)."','POSITIF','".$probabilitypos."','".$finalsentiment."')");
                $this->db->query("insert into data_learn values ('','".str_replace("'", "''", $value)."','NEGATIF','".$probabilityneg."','".$finalsentiment."')");
                $this->db->query("insert into data_learn values ('','".str_replace("'", "''", $value)."','NETRAL','".$probabilitynet."','".$finalsentiment."')");
            }
            $this->db->query("update data_train set train_learn = 1 where train_id = ".$r->train_id);
        }
    }

    public function pdfsentriword($idsearch=0)
    {
        require_once __DIR__."/../third_party/mpdf60/mpdf.php";
        // echo __DIR__."/../third_party/mpdf60/mpdf.php";
        $mpdf=new mPDF('c');
        $data = $this->m_klasifikasi->_get_list_tweet_pdf($idsearch);
        $template = '
			<!DOCTYPE html>
			<html>
			<head>
			</head>
			<body>
				<table border="1" style="border-collapse:collapsed;">
					<thead>
						<tr>
							<th>No</th>
                            <th>Komentar </th>
                            <th>Pos </th>
                            <th>Neg </th>
                            <th>Net </th>
                            <th>Kesimpulan </th>
						</tr>
					</thead>
					<tbody>';
        if ($data->num_rows() > 0) {
            $i=1;
            foreach ($data->result() as $r) {
                $template.= '<tr>
							<td>'.$i++.'</td>
							<td>'.$r->data_tweet_text.'</td>
							<td>'.$r->data_tweet_index_pos.'</td>
							<td>'.$r->data_tweet_index_neg.'</td>
							<td>'.$r->data_tweet_index_net.'</td>
							<td>'.$r->data_tweet_sentiment.'</td>
						</tr>';
            }
        }
        $template.=	'</tbody>
				</table>
			</body>
			</html>';
        $mpdf->WriteHTML($template);
        $mpdf->Output();
    }

    public function pdfbayes($idsearch=0)
    {
        require_once __DIR__."/../third_party/mpdf60/mpdf.php";
        // echo __DIR__."/../third_party/mpdf60/mpdf.php";
        $mpdf=new mPDF('c');
        $data = $this->m_klasifikasi->_get_list_tweet_bayes_pdf($idsearch);
        $template = '
			<!DOCTYPE html>
			<html>
			<head>
			</head>
			<body>
				<table border="1" style="border-collapse:collapsed;">
					<thead>
						<tr>
							<th>No</th>
                            <th>Komentar </th>
                            <th>Pos </th>
                            <th>Neg </th>
                            <th>Net </th>
                            <th>Kesimpulan </th>
						</tr>
					</thead>
					<tbody>';
        if ($data->num_rows() > 0) {
            $i=1;
            foreach ($data->result() as $r) {
                $template.= '<tr>
							<td>'.$i++.'</td>
							<td>'.$r->data_tweet_text.'</td>
							<td>'.$r->data_tweet_index_pos.'</td>
							<td>'.$r->data_tweet_index_neg.'</td>
							<td>'.$r->data_tweet_index_net.'</td>
							<td>'.$r->data_tweet_sentiment.'</td>
						</tr>';
            }
        }
        $template.=	'</tbody>
				</table>
			</body>
			</html>';
        $mpdf->WriteHTML($template);
        $mpdf->Output();
    }

}