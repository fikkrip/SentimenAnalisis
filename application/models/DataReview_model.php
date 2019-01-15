<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DataReview_model extends CI_Model
{
    function save_review($dataparam=array())
    {
        extract($dataparam);
        $data['m_review_text'] = $text;
        $data['m_review_user'] = $user;
        $data['m_review_id_produk'] = $idproduk;
        $data['m_review_date_post'] = $datepost;
        $this->db->insert('data_review', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        }else{
            return false;
        }
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
        $this->text_classification_naivebayes($reviewclear, $idproduk);
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
        $word = str_replace(array('.',',',"!","?",";","/","_","`","~","(",")"),array('','','','','','','','','','','',''), $word);
        $word = preg_replace('(#([^\s]+))', '', $word); // hapus #
        return $word;
    }

    function text_classification_naivebayes($data=array(), $idproduk)
    {
        $searchpos = 0;
        $searchneg = 0;
        $searchnet = 0;
        $getstopword = $this->db->get('stopwords');
        $stopword = array();
        foreach ($getstopword->result() as $r) {
            array_push($stopword, $r->stopword);
        }

        $totaltrain = (int)$this->db->query("select count(*) as jumlah from data_train")->row()->jumlah;
        $totalposs = (int)$this->db->query("select count(*) as jumlah from data_train where train_sentiment_desc = 'POSITIF'")->row()->jumlah;
        $totalnegg = (int)$this->db->query("select count(*) as jumlah from data_train where train_sentiment_desc = 'NEGATIF'")->row()->jumlah;
        $totalnett = (int)$this->db->query("select count(*) as jumlah from data_train where train_sentiment_desc = 'NETRAL'")->row()->jumlah;

        foreach ($data as $key => $value) {
            $word = explode(" ", $value['word']);
            $totalpos = 0;
            $totalneg = 0;
            $totalnet = 0;
            $scorebayes = 0;
            foreach ($word as $key1 => $value1) {
                if (in_array($value1, $stopword)) { //jika stopword maka lewati
                    continue;
                }
                $ppos = (int)$this->db->query("select count(*) as jumlah from data_train where train_phrase like '%".str_replace("'", "''", $value1)."%' and train_sentiment_desc = 'POSITIF'")->row()->jumlah;
                $pneg = (int)$this->db->query("select count(*) as jumlah from data_train where train_phrase like '%".str_replace("'", "''", $value1)."%' and train_sentiment_desc = 'NEGATIF'")->row()->jumlah;
                $pnet = (int)$this->db->query("select count(*) as jumlah from data_train where train_phrase like '%".str_replace("'", "''", $value1)."%' and train_sentiment_desc = 'NETRAL'")->row()->jumlah;

                if ($ppos == 0) {
                    $probabilitypos = 0;
                }else{
                    $probabilitypos = (double)(($ppos/$totalposs) * ($totalposs/$totaltrain));
                }
                if ($pneg == 0) {
                    $probabilityneg = 0;
                }else{
                    $probabilityneg = (double)(($pneg/$totalnegg) * ($totalnegg/$totaltrain));
                }
                if ($pnet == 0) {
                    $probabilitynet = 0;
                }else{
                    $probabilitynet = (double)(($pnet/$totalnett) * ($totalnett/$totaltrain));
                }
                $tertinggi = max(array($probabilitynet,$probabilitypos,$probabilityneg));
                if ($tertinggi == 0) {
                    $totalnet++;
                }else{
                    if ($tertinggi == $probabilitypos) {
                        $totalpos++;
                    }else if($tertinggi == $probabilityneg){
                        $totalneg++;
                    }else if($tertinggi == $probabilitynet){
                        $totalnet++;
                    }else{
                        $totalnet++;
                    }
                }
                $scorebayes+=$tertinggi;
            }
            $dataupdate = array();
            $dataupdate['m_review_index_pos'] = $totalpos;
            $dataupdate['m_review_index_neg'] = $totalneg;
            $dataupdate['m_review_index_net'] = $totalnet;
            $dataupdate['m_review_score'] = $scorebayes;
            if ($totalpos == $totalneg) {
                $dataupdate['m_review_sentiment'] = 'NETRAL';
                $searchnet++;
            }else if($totalpos > $totalneg){
                $dataupdate['m_review_sentiment'] = 'POSITIF';
                $searchpos++;
            }else if($totalpos < $totalneg){
                $dataupdate['m_review_sentiment'] = 'NEGATIF';
                $searchneg++;
            }
            $this->db->where('m_review_id', $value['id']);
            $this->db->update('data_review', $dataupdate);
        }
        $tertinggi = max(array($searchpos,$searchneg,$searchnet));
        if ($tertinggi == $searchpos) {
            $sentiment = 'POSITIF';
        }else if($tertinggi == $searchneg) {
            $sentiment = 'NEGATIF';
        }else if($tertinggi == $searchnet) {
            $sentiment = 'NETRAL';
        }
        $dataupdate = array();
        $dataupdate['data_pos'] = $searchpos;
        $dataupdate['data_neg'] = $searchneg;
        $dataupdate['data_net'] = $searchnet;
        $dataupdate['data_sentiment'] = $sentiment;
        date_default_timezone_set("Asia/Bangkok");
        $dataupdate['tanggal_sentiment'] = date("Y-m-d");
        $this->db->where('m_produk_id', $idproduk);
        $this->db->update('data_produk', $dataupdate);
        return true;
    }

    public function get_data_review($where=array())
    {
        if (count($where) > 0) {
            $this->db->where($where);
        }
        $result = $this->db->get('data_review');
        if ($result->num_rows() > 0) {
            return $result->row();
        }else{
            return false;
        }
    }

    public function get_data_reviews($where=array())
    {
        if (count($where) > 0) {
            $this->db->where($where);
        }
        $result = $this->db->get('data_review');
        if ($result->num_rows() > 0) {
            return $result->result();
        }else{
            return false;
        }
    }

    public function get_nama_produk($where=array())
    {
        $this->db->select('m_produk_nama, tanggal_sentiment');
        if (count($where) > 0) {
            $this->db->where($where);
        }
        $result = $this->db->get('data_produk');
//        var_dump($result->result());
//        die();
        if ($result->num_rows() > 0) {
            $data = $result->result();
            return $data[0];
        }else{
            return false;
        }
    }

    public function delete_data_review($where=array())
    {
        if (count($where) > 0) {
            $this->db->delete('data_review', $where);
        }
    }
}