<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DataTrain_model extends CI_Model
{
    function text_classification_bayes_single($word='')
    {
        $result = array();
        $result['data'] = array();
        $getstopword = $this->db->get('stopwords');
        $stopword = array();
        foreach ($getstopword->result() as $r) {
            array_push($stopword, $r->stopword);
        }

        $totaltrain = (int)$this->db->query("select count(*) as jumlah from data_train")->row()->jumlah;
        $totalposs = (int)$this->db->query("select count(*) as jumlah from data_train where train_sentiment_desc = 'POSITIF'")->row()->jumlah;
        $totalnegg = (int)$this->db->query("select count(*) as jumlah from data_train where train_sentiment_desc = 'NEGATIF'")->row()->jumlah;
        $totalnett = (int)$this->db->query("select count(*) as jumlah from data_train where train_sentiment_desc = 'NETRAL'")->row()->jumlah;

        $word = explode(" ", $word);
        $totalpos = 0;
        $totalneg = 0;
        $totalnet = 0;
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
            $finalsentiment='';
            if ($tertinggi == 0) {
                $sentiment = 'Netral';
                $totalnet++;
            }else{
                if ($tertinggi == $probabilitypos) {
                    $totalpos++;
                    $sentiment = 'Positif';
                }else if($tertinggi == $probabilityneg){
                    $sentiment = 'Negatif';
                    $totalneg++;
                }else if($tertinggi == $probabilitynet){
                    $sentiment = 'Netral';
                    $totalnet++;
                }else{
                    $sentiment = 'Netral';
                    $totalnet++;
                }
            }

            $tempresult = array();
            $tempresult['kata'] = $value1;
            $tempresult['index'] = $tertinggi;
            $tempresult['sentiment'] = $sentiment;
            array_push($result['data'], $tempresult);
        }
        if ($totalpos == $totalneg) {
            $result['kesimpulan'] = 'Netral';
        }else if($totalpos > $totalneg){
            $result['kesimpulan'] = 'Positif';
        }else if($totalpos < $totalneg){
            $result['kesimpulan'] = 'Negatif';
        }

        return $result;
    }
}