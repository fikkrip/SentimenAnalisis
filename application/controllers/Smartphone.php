<?php
class Smartphone extends CI_Controller {
    function __construct(){
        parent::__construct();
        $this->load->model('DataProduk_model');
    }

    function index(){
        if($this->session->userdata('masuk') != TRUE){
            $this->load->view('login/v_login');
        }else{
            $this->load->view('dataproduk/v_smartphone');
        }
    }

    public function dataSmartphone()
    {
        $records = $this->DataProduk_model->_get();
        $this->output->set_content_type('application/json')->set_output(json_encode($records));
    }

	public function ambilSmartphone()
	{
		require_once APPPATH."/libraries/simple_html_dom.php";
		$keyword = strtoupper($this->input->post('keyword'));
//        $keyword = "REALME 2";
		$url = "https://www.gsmarena.com/results.php3?sQuickSearch=yes&sName=".$keyword;
		$url = str_replace(" ", "%20", $url);
//		var_dump($url);
//        die();
        $grab = file_get_contents($url);
		$grab = explode('<div class="makers">
<ul>', $grab);
		if (@$grab[1]) {
			$grab = explode('</ul>
<br class="clear">', $grab[1]);
			if (@$grab[0]) {
				$grab = explode('</li>', $grab[0]);
				if (count($grab) > 0) {
					foreach ($grab as $key => $value) {
						$namaproduk = $this->getBetween($value,'<span>','</span>');
						$url = $this->getBetween($value,'<li><a href="','"><img');
						$e_url = explode("-", $url);
						$url = 'http://www.gsmarena.com/'.$url;
						if (@$e_url[0]) {
							$urlreviews = 'http://www.gsmarena.com/'.$e_url[0].'-reviews-'.$e_url[1];
//							var_dump($urlreviews);
//							die();
						}else{
							continue;
						}
						if (!$namaproduk) {
							continue;
						}

						//get spesifikasi
						$html = file_get_contents($url);
						$grab1 = explode("specs-spotlight-features", $html);
						$grab1 = explode("article-info-line page-specs", $grab1[1]);
						$data['size'] = $this->getBetween($grab1[0],'<span data-spec="displaysize-hl">','"</span>');
						$data['camera'] = $this->getBetween($grab1[0],'<strong class="accent accent-camera"><span data-spec="camerapixels-hl">','</span>');
						$data['ram'] = $this->getBetween($grab1[0],'<strong class="accent accent-expansion"><span data-spec="ramsize-hl">','</span>');
						$data['battery'] = $this->getBetween($grab1[0],'<strong class="accent accent-battery"><span data-spec="batsize-hl">','</span>');
						$data['storage'] = $this->getBetween($grab1[0],'<i class="head-icon icon-sd-card-0"></i><span data-spec="storage-hl">','GB storage');
						$data['sensors'] = $this->getBetween($grab1[1],'<td class="nfo" data-spec="sensors">','</td>');
                        $data['price'] = $this->getBetween($grab1[1],'<td class="nfo" data-spec="price">About ','</td>');
						$sensor = explode(",", $data['sensors']);
						if(!empty($data['price'])){
                            $price = explode(" ", $data['price']);
                            if($price[1] == 'EUR'){
                                $data['price'] = $data['price'] * 16375;
                            }elseif ($price[1] == 'INR'){
                                $data['price'] = $data['price'] * 201;
                            }
                            $data['sensors'] = count($sensor);
//    						var_dump($data);
//    						die();
                            // end get spek
                            $cekexist = $this->DataProduk_model->selectwithname($namaproduk);
                            if ($cekexist->num_rows() > 0) {
                                $success = $this->DataProduk_model->update($data,$namaproduk,$keyword);
                                if($success){
                                    $msg['tipe'] = 'success';
                                    $msg['msg'] = 'Data berhasil diperbarui';
                                }else{
                                    $msg['tipe'] = 'error';
                                    $msg['msg'] = 'Gagal memperbarui data';
                                }
                            }else{
                                $success = $this->DataProduk_model->insert($data,$namaproduk,$keyword,$url,$urlreviews);
                                if($success){
                                    $msg['tipe'] = 'success';
                                    $msg['msg'] = 'Data berhasil disimpan';
                                }else{
                                    $msg['tipe'] = 'error';
                                    $msg['msg'] = 'Gagal menyimpan data';
                                }
                            }
                        }else{
                            $msg['tipe'] = 'error';
                            $msg['msg'] = 'Harga produk tidak ditemukan';
                        }
					}
				}
			}else{
				$msg['tipe'] = 'error';
				$msg['msg'] = 'Keyword tidak ditemukan';
			}
		}else{
			$msg['tipe'] = 'error';
			$msg['msg'] = 'Keyword tidak ditemukan';
		}
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

	function test_simpledom()
	{
		$html = file_get_contents('http://www.gsmarena.com/nokia_c1-6885.php');
		$grab1 = explode("specs-spotlight-features", $html);
		$grab1 = explode("article-info-line page-specs", $grab1[1]);
		$data['size'] = $this->getBetween($grab1[0],'<i class="head-icon icon-touch-1"></i><strong class="accent">','</strong>');
		$data['camera'] = $this->getBetween($grab1[0],'<strong class="accent accent-camera">','<span>');
		$data['ram'] = $this->getBetween($grab1[0],'<strong class="accent accent-expansion">','<span>');
		$data['battery'] = $this->getBetween($grab1[0],'<strong class="accent accent-battery">','<span>');
		$data['storage'] = $this->getBetween($grab1[0],'<i class="head-icon icon-sd-card-0"></i>','GB storage');
		$data['sensors'] = $this->getBetween($grab1[1],'Sensors</a></td><td class="nfo">','</td></tr><tr>');
		var_dump($data);
	}

}