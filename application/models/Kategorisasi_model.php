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