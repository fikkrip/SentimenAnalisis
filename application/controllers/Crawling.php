<?php
class Crawling extends CI_Controller{
    function __construct(){
        parent::__construct();
    }

    function index(){
        $sumber = 'http://localhost:8080/crawl/pr--PRI-014226-00/2/25';
        $konten = file_get_contents($sumber);
//        $data = json_decode($konten, true);
        echo $konten;
    }

}
