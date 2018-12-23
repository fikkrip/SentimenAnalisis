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

    function ujiCoba(){
        if(!empty($_POST)){
            $text = $this->input->post('kalimat');

            $this->session->set_userdata('behaviour', 'text');

            $result = $this->kataPenting($text, TRUE);

            $data['result'] = $result[0];
            $data['stoplist'] = $result[1];
            $data['imbuhan'] = $result[2];
            $data['text'] = $text;
            $data['get_result'] = 1;

            $this->load->view('textmining/v_textmining', $data);
        }
    }

    public function kataPenting($text, $testing = NULL)
    {
        $text = str_replace("\r\n",'', $text);
        $text = str_replace("!",'', $text);
        $text = str_replace(".",'', $text);
        $text = str_replace(",",'', $text);
        $text = str_replace("/",'', $text);
        $text = str_replace('-', ' ', $text);
        $text = str_replace('--', ' ', $text);
        $text = str_replace('"', '', $text);
        $text = str_replace('============', '', $text);
        // $text = str_replace("&",'', $text);

        $text = explode(" ", $text);
        $stoplists = NULL;
        $imbuhan = NULL;
        for ($index = 0; $index < count($text); $index++)
        {
            if ($this->document_model->cekStopList($text[$index]))
            {
                $hasil[$index] = strtolower($this->nazief($text[$index]));
                if(strtolower($text[$index]) != $hasil[$index] && $hasil[$index] != '')
                    $imbuhan[$index] = strtolower($text[$index]).' = '.$hasil[$index];
                if(is_null($hasil[$index]) || $hasil[$index] == '' || preg_match('/^[0-9]{1,}$/', $hasil[$index]))
                {
                    unset($hasil[$index]);
                }
            }
            else
            {
                $stoplists[$index] = strtolower($text[$index]);
            }
        }
        if(is_null($testing))
            return $hasil;
        else
            return array($hasil, $stoplists, $imbuhan);
    }

    function nazief($kata)
    {
        $kataAsal = $kata;
        if(!$this->document_model->cekKataDasar($kata))
        {
            $kataAsal = $kata;
            $kata = str_replace('(', '', $kata);
            $kata = str_replace(')', '', $kata);

            $kata = $this->deleteInflectionSuffixes($kata);
            // echo '1. '.$kata;
            if(!$this->document_model->cekKataDasar($kata))
            {
                $kata = $this->deleteDerivationSuffixes($kata);
                // echo ' 2. '.$kata;
            }
            if(!$this->document_model->cekKataDasar($kata))
            {
                $kata = $this->deleteDerivationPrefixes($kata);
                // echo ' 3. '.$kata;
            }
        }

        if(strlen($kata) < 3 || preg_match('/^[0-9]{1,}$/',$kata))
            return '';
        else
        {
            // echo $kata.'<br/>';
            return $kata;
        }
    }

    function deleteInflectionSuffixes($kata)
    {
        $kataAsal = $kata;
        if (preg_match('/([km]u|nya|[kl]ah|pun)$/i', $kata))
        {
            $__kata = preg_replace('/(nya|[kl]ah|pun)$/i', '', $kata);
            if (preg_match('/([klt]ah|pun)$/i', $kata))
            {
                if (preg_match('/([km]u|nya)$/i', $kata))
                {
                    $__kata__ = preg_replace('/([km]u|nya)$/i', '', $kata);
                    return $__kata__;
                }
            }
            return $__kata;
        }
        return $kataAsal;
    }

    function deleteDerivationSuffixes($kata)
    {
        $kataAsal = $kata;
        if (preg_match('/(i|an)$/i', $kata))
        {

            $__kata = preg_replace('/(i|an)$/i', '', $kata);
            if ($this->document_model->cekKataDasar($__kata))
            {
                return $__kata;
            }

            if (preg_match('/(kan)$/i', $kata))
            {
                $__kata__ = preg_replace('/(kan)$/i', '', $kata);
                if ($this->document_model->cekKataDasar($__kata__))
                {
                    return $__kata__;
                }
            }
            if ($this->checkPrefixDisallowedSuffixes($kata))
            {
                return $kataAsal;
            }
        }
        return $kataAsal;
    }

    function deleteDerivationPrefixes($kata)
    {
        $kataAsal = $kata;
        // Jika di-,ke-,se-
        if (preg_match('/^(di|[ks]e)/i', $kata))
        {
            $__kata = preg_replace('/^(di|[ks]e)/i', '', $kata);

            if ($this->document_model->cekKataDasar($__kata))
            {
                return $__kata;
            }

            $__kata__ = $this->deleteDerivationSuffixes($__kata);
            if ($this->document_model->cekKataDasar($__kata__))
            {
                return $__kata__;
            }

            if (preg_match('/^(diper)/i', $kata))
            {
                $__kata = preg_replace('/^(diper)/i', '', $kata);
                if ($this->document_model->cekKataDasar($__kata))
                {
                    return $__kata;
                }

                $__kata__ = $this->deleteDerivationSuffixes($__kata);
                if ($this->document_model->cekKataDasar($__kata__))
                {
                    return $__kata__;
                }

                $__kata = preg_replace('/^(diper)/i', 'r', $kata);
                if ($this->document_model->cekKataDasar($__kata))
                {
                    return $__kata; // Jika ada balik
                }

                $__kata__ = $this->deleteDerivationSuffixes($__kata);
                if ($this->document_model->cekKataDasar($__kata__))
                {
                    return $__kata__;
                }
            }
        }

        if (preg_match('/^([tmbp]e)/i', $kata))
        {

            if (preg_match('/^(te)/i', $kata))
            {
                if (preg_match('/^(terr)/i', $kata))
                {
                    return $kata;
                }

                if (preg_match('/^(ter)[aiueo]/i', $kata))
                {
                    $__kata = preg_replace('/^(ter)/i', '', $kata);
                    if ($this->document_model->cekKataDasar($__kata))
                    {
                        return $__kata;
                    }

                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->document_model->cekKataDasar($__kata__))
                    {
                        return $__kata__;
                    }
                }

                if (preg_match('/^(ter[^aiueor]er[aiueo])/i', $kata))
                {
                    $__kata = preg_replace('/^(ter)/i', '', $kata);
                    if ($this->document_model->cekKataDasar($__kata))
                    {
                        return $__kata;
                    }

                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->document_model->cekKataDasar($__kata__))
                    {
                        return $__kata__;
                    }
                }

                if (preg_match('/^(ter[^aiueor]er[^aiueo])/i', $kata))
                {
                    $__kata = preg_replace('/^(ter)/i', '', $kata);
                    if ($this->document_model->cekKataDasar($__kata))
                    {
                        return $__kata; // Jika ada balik
                    }

                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->document_model->cekKataDasar($__kata__))
                    {
                        return $__kata__;
                    }
                }

                if (preg_match('/^(ter[^aiueor][^(er)])/i', $kata))
                {
                    $__kata = preg_replace('/^(ter)/i', '', $kata);
                    if ($this->document_model->cekKataDasar($__kata))
                    {
                        return $__kata;
                    }

                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->document_model->cekKataDasar($__kata__))
                    {
                        return $__kata__;
                    }
                }

                if (preg_match('/^(te[^aiueor]er[aiueo])/i', $kata))
                {
                    return $kata;
                }

                if (preg_match('/^(te[^aiueor]er[^aiueo])/i', $kata))
                {
                    $__kata = preg_replace('/^(te)/i', '', $kata);
                    if ($this->document_model->cekKataDasar($__kata))
                    {
                        return $__kata; // Jika ada balik
                    }

                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->document_model->cekKataDasar($__kata__))
                    {
                        return $__kata__;
                    }
                }
            }

            if (preg_match('/^(me)/i', $kata))
            {
                if (preg_match('/^(meng)[aiueokghq]/i', $kata))
                {
                    $__kata = preg_replace('/^(meng)/i', '', $kata);
                    if ($this->document_model->cekKataDasar($__kata))
                    {
                        return $__kata;
                    }

                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->document_model->cekKataDasar($__kata__))
                    {
                        return $__kata__;
                    }

                    $__kata = preg_replace('/^(meng)/i', 'k', $kata);
                    if ($this->document_model->cekKataDasar($__kata))
                    {
                        return $__kata;
                    }

                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->document_model->cekKataDasar($__kata__))
                    {
                        return $__kata__;
                    }
                }

                if (preg_match('/^(meny)/i', $kata))
                {
                    $__kata = preg_replace('/^(meny)/i', 's', $kata);
                    if ($this->document_model->cekKataDasar($__kata))
                    {
                        return $__kata;
                    }

                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->document_model->cekKataDasar($__kata__))
                    {
                        return $__kata__;
                    }
                }

                if (preg_match('/^(mem)[bfpv]/i', $kata))
                { // 3.
                    $__kata = preg_replace('/^(mem)/i', '', $kata);

                    if ($this->document_model->cekKataDasar($__kata))
                    {
                        return $__kata; // Jika ada balik
                    }

                    $__kata__ = $this->deleteDerivationSuffixes($__kata);

                    if ($this->document_model->cekKataDasar($__kata__))
                    {
                        return $__kata__;
                    }

                    $__kata = preg_replace('/^(mem)/i', 'p', $kata);
                    if ($this->document_model->cekKataDasar($__kata))
                    {
                        return $__kata; // Jika ada balik
                    }

                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->document_model->cekKataDasar($__kata__))
                    {
                        return $__kata__;
                    }

                    $__kata = preg_replace('/^(mempek)/i', 'k', $kata);
                    if ($this->document_model->cekKataDasar($__kata))
                    {
                        return $__kata; // Jika ada balik
                    }

                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->document_model->cekKataDasar($__kata__))
                    {
                        return $__kata__;
                    }
                }

                if (preg_match('/^(men)[cdjsz]/i', $kata))
                {
                    $__kata = preg_replace('/^(men)/i', '', $kata);
                    if ($this->document_model->cekKataDasar($__kata))
                    {
                        return $__kata; // Jika ada balik
                    }

                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->document_model->cekKataDasar($__kata__))
                    {
                        return $__kata__;
                    }
                }

                if (preg_match('/^(me)/i', $kata))
                {
                    $__kata = preg_replace('/^(me)/i', '', $kata);
                    if ($this->document_model->cekKataDasar($__kata))
                    {
                        return $__kata; // Jika ada balik
                    }

                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->document_model->cekKataDasar($__kata__))
                    {
                        return $__kata__;
                    }

                    $__kata = preg_replace('/^(men)/i', 't', $kata);
                    if ($this->document_model->cekKataDasar($__kata))
                    {
                        return $__kata; // Jika ada balik
                    }

                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->document_model->cekKataDasar($__kata__))
                    {
                        return $__kata__;
                    }

                    $__kata = preg_replace('/^(mem)/i', 'p', $kata);
                    if ($this->document_model->cekKataDasar($__kata))
                    {
                        return $__kata; // Jika ada balik
                    }

                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->document_model->cekKataDasar($__kata__))
                    {
                        return $__kata__;
                    }
                }
            }

            if (preg_match('/^(be)/i', $kata))
            {
                if (preg_match('/^(ber)[aiueo]/i', $kata))
                {
                    $__kata = preg_replace('/^(ber)/i', '', $kata);
                    if ($this->document_model->cekKataDasar($__kata))
                    {
                        return $__kata;
                    }

                    $__kata = preg_replace('/^(ber)/i', 'r', $kata);
                    if ($this->document_model->cekKataDasar($__kata))
                    {
                        return $__kata; // Jika ada balik
                    }

                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->document_model->cekKataDasar($__kata__))
                    {
                        return $__kata__;
                    }
                }

                if (preg_match('/(ber)[^aiueo]/i', $kata))
                { // 2.
                    $__kata = preg_replace('/(ber)/i', '', $kata);
                    if ($this->document_model->cekKataDasar($__kata))
                    {
                        return $__kata;
                    }

                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->document_model->cekKataDasar($__kata__)) {
                        return $__kata__;
                    }
                }
                if (preg_match('/^(be)[k]/i', $kata))
                {
                    $__kata = preg_replace('/^(be)/i', '', $kata);
                    if ($this->document_model->cekKataDasar($__kata))
                    {
                        return $__kata; // Jika ada balik
                    }

                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->document_model->cekKataDasar($__kata__))
                    {
                        return $__kata__;
                    }
                }
            }

            if (preg_match('/^(pe)/i', $kata))
            {
                if (preg_match('/^(peng)[aiueokghq]/i', $kata))
                {
                    $__kata = preg_replace('/^(peng)/i', '', $kata);
                    if ($this->document_model->cekKataDasar($__kata))
                    {
                        return $__kata; // Jika ada balik
                    }

                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->document_model->cekKataDasar($__kata__))
                    {
                        return $__kata__;
                    }
                }

                if (preg_match('/^(peny)/i', $kata))
                {
                    $__kata = preg_replace('/^(peny)/i', 's', $kata);
                    if ($this->document_model->cekKataDasar($__kata))
                    {
                        return $__kata; // Jika ada balik
                    }

                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->document_model->cekKataDasar($__kata__))
                    {
                        return $__kata__;
                    }
                }

                if (preg_match('/^(pem)[bfpv]/i', $kata))
                {
                    $__kata = preg_replace('/^(pem)/i', '', $kata);
                    if ($this->document_model->cekKataDasar($__kata))
                    {
                        return $__kata; // Jika ada balik
                    }

                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->document_model->cekKataDasar($__kata__))
                    {
                        return $__kata__;
                    }
                }

                if (preg_match('/^(pen)[cdjsz]/i', $kata))
                {
                    $__kata = preg_replace('/^(pen)/i', '', $kata);
                    if ($this->document_model->cekKataDasar($__kata))
                    {
                        return $__kata; // Jika ada balik
                    }

                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->document_model->cekKataDasar($__kata__))
                    {
                        return $__kata__;
                    }

                    $__kata = preg_replace('/^(pem)/i', 'p', $kata);
                    if ($this->document_model->cekKataDasar($__kata))
                    {
                        return $__kata; // Jika ada balik
                    }
                }

                if (preg_match('/^(pen)[aiueo]/i', $kata))
                {
                    $__kata = preg_replace('/^(pen)/i', 't', $kata);
                    if ($this->document_model->cekKataDasar($__kata))
                    {
                        return $__kata; // Jika ada balik
                    }

                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->document_model->cekKataDasar($__kata__))
                    {
                        return $__kata__;
                    }
                }

                if (preg_match('/^(per)/i', $kata))
                {
                    $__kata = preg_replace('/^(per)/i', '', $kata);
                    if ($this->document_model->cekKataDasar($__kata))
                    {
                        return $__kata; // Jika ada balik
                    }

                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->document_model->cekKataDasar($__kata__))
                    {
                        return $__kata__;
                    }

                    $__kata = preg_replace('/^(per)/i', 'r', $kata);
                    if ($this->document_model->cekKataDasar($__kata))
                    {
                        return $__kata; // Jika ada balik
                    }

                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->document_model->cekKataDasar($__kata__))
                    {
                        return $__kata__;
                    }
                }

                if (preg_match('/^(pe)/i', $kata))
                {
                    $__kata = preg_replace('/^(pe)/i', '', $kata);
                    if ($this->document_model->cekKataDasar($__kata))
                    {
                        return $__kata; // Jika ada balik
                    }

                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->document_model->cekKataDasar($__kata__))
                    {
                        return $__kata__;
                    }
                }
            }

            if (preg_match('/^(memper)/i', $kata))
            {
                $__kata = preg_replace('/^(memper)/i', '', $kata);
                if ($this->document_model->cekKataDasar($__kata))
                {
                    return $__kata; // Jika ada balik
                }

                $__kata__ = $this->deleteDerivationSuffixes($__kata);
                if ($this->document_model->cekKataDasar($__kata__))
                {
                    return $__kata__;
                }

                $__kata = preg_replace('/^(memper)/i', 'r', $kata);
                if ($this->document_model->cekKataDasar($__kata))
                {
                    return $__kata; // Jika ada balik
                }

                $__kata__ = $this->deleteDerivationSuffixes($__kata);
                if ($this->document_model->cekKataDasar($__kata__))
                {
                    return $__kata__;
                }
            }
        }

        /* --- Cek Ada Tidaknya Prefik/Awalan ------ */
        if (preg_match('/^(di|[kstbmp]e)/i', $kata) == FALSE)
        {
            return $kataAsal;
        }

    }

    function checkPrefixDisallowedSuffixes($kata)
    {
        // be- dan -i
        if (preg_match('/^(be)[[:alpha:]]+(i)$/i', $kata))
        {
            return true;
        }

        // di- dan -an
        if (preg_match('/^(di)[[:alpha:]]+(an)$/i', $kata))
        {
            return true;
        }

        // ke- dan -i,-kan
        if (preg_match('/^(ke)[[:alpha:]]+(i|kan)$/i', $kata))
        {
            return true;
        }

        // me- dan -an
        if (preg_match('/^(me)[[:alpha:]]+(an)$/i', $kata))
        {
            return true;
        }

        // se- dan -i,-kan
        if (preg_match('/^(se)[[:alpha:]]+(i|kan)$/i', $kata))
        {
            return true;
        }

        return FALSE;
    }
}
