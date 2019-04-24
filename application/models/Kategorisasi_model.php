<?php 

class Kategorisasi_model extends CI_Model{

    function __construct(){
        parent::__construct();
        $this->load->library('Porter2');
        $this->load->library('PorterStemmer');
    }

	function tampil_data(){
        return $this->db->get('kategorisasi');
	}

    public function getPerIdPelayanan($id){
        $query = $this->db->query("SELECT keyword FROM kategorisasi WHERE id_kategori = '".$id."'");
        return $query;
    }

    public function analyzereview_bayes($idproduk='')
    {
        $this->db->where('m_review_id_produk', $idproduk);
        $datareview = $this->db->get('data_review');
        $reviewclear = array();
        foreach ($datareview->result() as $r) {
            $data['id'] = $r->m_review_id;
            $data['word'] = $this->preprocessing($r->m_review_text);
            array_push($reviewclear, $data);
        }
        $this->kategorisasi_naivebayes($reviewclear, $idproduk);
        $result = $this->db->query("SELECT data_pos,data_neg,data_net,data_sentiment from data_produk where m_produk_id = ".$idproduk);
        return $result->row();
    }

    function preprocessing($word='')
    {
        if (!$word) {
            $word = 'You dont want iOS 11, believe me. Theres too many things wrong with the software. And honestly, even iOS 12 isnt as great as Id thought it would be. Unfortunately.';
        }
        $word = strtolower($word);
        $word = preg_replace('((www\.[^\s]+)|(https?://[^\s]+))', '', $word); //hapus url
        $word = preg_replace('(@[^\s]+)','', $word); // hapus mention
        $word = str_replace(array('.',',',"!","?",";","/","_","`","~","(",")","'"),array('','','','','','','','','','','',''), $word);
        $word = preg_replace('(#([^\s]+))', '', $word); // hapus #
        return $word;
    }

    function kategorisasi_naivebayes($data=array(), $idproduk)
    {
//        var_dump($data);
//        die();
        $getstopword = $this->db->get('stopwords');
        $stopword = array();
        foreach ($getstopword->result() as $r) {
            array_push($stopword, $r->stopword);
        }

        $totaltrain = (int)$this->db->query("select count(*) as jumlah from kategorisasi")->row()->jumlah;
        $totalbaterai = (int)$this->db->query("select count(*) as jumlah from kategorisasi where kategori = 'baterai'")->row()->jumlah;
        $totallayar = (int)$this->db->query("select count(*) as jumlah from kategorisasi where kategori = 'layar'")->row()->jumlah;
        $totalkamera = (int)$this->db->query("select count(*) as jumlah from kategorisasi where kategori = 'kamera'")->row()->jumlah;
        $totalmesin = (int)$this->db->query("select count(*) as jumlah from kategorisasi where kategori = 'mesin'")->row()->jumlah;

        foreach ($data as $key => $value) {
            $word = explode(" ", $value['word']);
            $totalbat = 0;
            $totallyr = 0;
            $totalkmr = 0;
            $totalmsn = 0;
            $totalunc = 0;
            $scorebayes = 0;
            foreach ($word as $key1 => $value1) {
                if (in_array($value1, $stopword)) { //jika stopword maka lewati
                    continue;
                }
                $pbaterai = (int)$this->db->query("select count(*) as jumlah from kategorisasi where keyword = '".$value1."' and kategori = 'baterai'")->row()->jumlah;
                $playar = (int)$this->db->query("select count(*) as jumlah from kategorisasi where keyword = '".$value1."' and kategori = 'layar'")->row()->jumlah;
                $pkamera = (int)$this->db->query("select count(*) as jumlah from kategorisasi where keyword = '".$value1."' and kategori = 'kamera'")->row()->jumlah;
                $pmesin = (int)$this->db->query("select count(*) as jumlah from kategorisasi where keyword = '".$value1."' and kategori = 'mesin'")->row()->jumlah;

                if ($pbaterai == 0) {
                    $probabilitybat = 0;
                }else{
                    $probabilitybat = (double)(($pbaterai/$totalbaterai) * ($totalbaterai/$totaltrain));
                }
                if ($playar == 0) {
                    $probabilitylyr = 0;
                }else{
                    $probabilitylyr = (double)(($playar/$totallayar) * ($totallayar/$totaltrain));
                }
                if ($pkamera == 0) {
                    $probabilitykmr = 0;
                }else{
                    $probabilitykmr = (double)(($pkamera/$totalkamera) * ($totalkamera/$totaltrain));
                }
                if ($pmesin == 0) {
                    $probabilitymsn = 0;
                }else{
                    $probabilitymsn = (double)(($pmesin/$totalmesin) * ($totalmesin/$totaltrain));
                }
                $tertinggi = max(array($probabilitybat,$probabilitylyr,$probabilitykmr,$probabilitymsn));
                if ($tertinggi == 0) {
                    $totalunc++;
                }else{
                    if ($tertinggi == $probabilitybat) {
                        $totalbat++;
                    }else if($tertinggi == $probabilitylyr){
                        $totallyr++;
                    }else if($tertinggi == $probabilitykmr){
                        $totalkmr++;
                    }else if($tertinggi == $probabilitymsn){
                        $totalmsn++;
                    }else{
                        $totalunc++;
                    }
                }
                $scorebayes+=$tertinggi;
            }

            $dataupdate = array();
            $dataupdate['m_review_bat'] = $totalbat;
            $dataupdate['m_review_lyr'] = $totallyr;
            $dataupdate['m_review_kmr'] = $totalkmr;
            $dataupdate['m_review_msn'] = $totalmsn;

            $this->db->where('m_review_id', $value['id']);
            $this->db->update('data_review', $dataupdate);
        }

        $dataup['kategorisasi_done'] = 1;
        $this->db->where('m_produk_id', $idproduk);
        $this->db->update('data_produk', $dataup);

        return true;
    }

    function kategorisasi_bayes_single($word='')
    {
        $result = array();
        $result['data'] = array();
        $getstopword = $this->db->get('stopwords');
        $stopword = array();
        foreach ($getstopword->result() as $r) {
            array_push($stopword, $r->stopword);
        }

        $totaltrain = (int)$this->db->query("select count(*) as jumlah from kategorisasi")->row()->jumlah;
        $totalbaterai = (int)$this->db->query("select count(*) as jumlah from kategorisasi where kategori = 'baterai'")->row()->jumlah;
        $totallayar = (int)$this->db->query("select count(*) as jumlah from kategorisasi where kategori = 'layar'")->row()->jumlah;
        $totalkamera = (int)$this->db->query("select count(*) as jumlah from kategorisasi where kategori = 'kamera'")->row()->jumlah;
        $totalmesin = (int)$this->db->query("select count(*) as jumlah from kategorisasi where kategori = 'mesin'")->row()->jumlah;

        $word = explode(" ", $word);
        $word2 = array_unique($word);

        $totalbat = 0;
        $totallyr = 0;
        $totalkmr = 0;
        $totalmsn = 0;
        $totalunc = 0;
        foreach ($word2 as $key1 => $value1) {
            if (in_array($value1, $stopword)) { //jika stopword maka lewati
                continue;
            }
            $r_stem = $this->porter2->stem($value1);
            if ($r_stem != $value1) {
                $value1 = $r_stem;
            }

            $cekkata = $this->db->query("select * from final_sentiword where final_sentiword_word = '".$value1."'");
            if ($cekkata->num_rows() == 0) {
                $cekunknown = $this->db->query("select normal_word from normalization where word = '".$value1."'");
                $cu = $cekunknown->result();
                if ($cekunknown->num_rows() != 0) {
                    $value1 = $cu[0]->normal_word;
                }
            }

            $pbaterai = (int)$this->db->query("select count(*) as jumlah from kategorisasi where keyword = '".$value1."' and kategori = 'baterai'")->row()->jumlah;
            $playar = (int)$this->db->query("select count(*) as jumlah from kategorisasi where keyword = '".$value1."' and kategori = 'layar'")->row()->jumlah;
            $pkamera = (int)$this->db->query("select count(*) as jumlah from kategorisasi where keyword = '".$value1."' and kategori = 'kamera'")->row()->jumlah;
            $pmesin = (int)$this->db->query("select count(*) as jumlah from kategorisasi where keyword = '".$value1."' and kategori = 'mesin'")->row()->jumlah;

            if ($pbaterai == 0) {
                $probabilitybat = 0;
            }else{
                $probabilitybat = (double)(($pbaterai/$totalbaterai) * ($totalbaterai/$totaltrain));
            }
            if ($playar == 0) {
                $probabilitylyr = 0;
            }else{
                $probabilitylyr = (double)(($playar/$totallayar) * ($totallayar/$totaltrain));
            }
            if ($pkamera == 0) {
                $probabilitykmr = 0;
            }else{
                $probabilitykmr = (double)(($pkamera/$totalkamera) * ($totalkamera/$totaltrain));
            }
            if ($pmesin == 0) {
                $probabilitymsn = 0;
            }else{
                $probabilitymsn = (double)(($pmesin/$totalmesin) * ($totalmesin/$totaltrain));
            }
            $tertinggi = max(array($probabilitybat,$probabilitylyr,$probabilitykmr,$probabilitymsn));
            $finalsentiment='';
            if ($tertinggi == 0) {
                $sentiment = 'Uncategorized';
                $totalunc++;
            }else{
                if ($tertinggi == $probabilitybat) {
                    $sentiment = 'Baterai';
                    $totalbat++;
                }else if($tertinggi == $probabilitylyr){
                    $sentiment = 'Layar';
                    $totallyr++;
                }else if($tertinggi == $probabilitykmr){
                    $sentiment = 'Kamera';
                    $totalkmr++;
                }else if($tertinggi == $probabilitymsn){
                    $sentiment = 'Mesin';
                    $totalmsn++;
                }else{
                    $sentiment = 'Uncategorized';
                    $totalunc++;
                }
            }

            $tempresult = array();
            $tempresult['kata'] = $value1;
            $tempresult['index'] = $tertinggi;
            $tempresult['sentiment'] = $sentiment;
            array_push($result['data'], $tempresult);
        }

        ($totalbat != 0) ? $result['kesimpulan'] = " Baterai = ".$totalbat." " : "";
        ($totallyr != 0) ? $result['kesimpulan'] = $result['kesimpulan'] . " Layar = ".$totallyr." " : "";
        ($totalkmr != 0) ? $result['kesimpulan'] = $result['kesimpulan'] . " Kamera = ".$totalkmr." " : "";
        ($totalmsn != 0) ? $result['kesimpulan'] = $result['kesimpulan'] . " Mesin = ".$totalmsn." " : "";

        return $result;
    }
}