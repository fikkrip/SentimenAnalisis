<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Document_model extends MY_Model
{

    private $katadasar;
    private $stoplist;

    function __construct()
    {
        parent::__construct();
        $this->katadasar = 'word_dictionary';
        $this->stoplist = 'stoplist';
    }

    function cekKataDasar($kata)
    {
        $query = $this->get_single($this->katadasar, 'katadasar', $kata);
        if ($query)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    function cekStopList($kata)
    {
        $query = $this->get_single($this->stoplist, 'stoplist', $kata);
        if ($query)
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
}