<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TextMining_model extends CI_Model
{
    function preprocessing($word='')
    {
        if (!$word) {
//            $word = 'You dont want iOS 11, believe me. Theres too many things wrong with the software. And honestly, even iOS 12 isnt as great as Id thought it would be. Unfortunately.';
            $word = 'Facing a weird problem in this phone in battery consumption details under battery section wifi is consuming battery but however i am not using wifi but it shows power consumed by wifi. P.S - I am using mobile data for internet.';
        }
        $word = strtolower($word);
        $word = preg_replace('((www\.[^\s]+)|(https?://[^\s]+))', '', $word); //hapus url
        $word = preg_replace('(@[^\s]+)','', $word); // hapus mention
        $word = str_replace(array('.',',',"!","?",";","/","_","`","~","(",")"),array('','','','','','','','','','','',''), $word);
        // $word = str_replace(array('.',',',"'","!"),array('','',"''",''), $word);
        // $word = preg_replace('([\s]+)', '', $word); // hapus withe spaces
        $word = preg_replace('(#([^\s]+))', '', $word); // hapus #
        return $word;
    }

}