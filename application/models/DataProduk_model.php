<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DataProduk_model extends CI_Model
{
    function selectwithname($productname)
    {
        $query = $this->db->query("SELECT * FROM data_produk WHERE m_produk_nama = '".$productname."'");
        return $query;
    }

    function update($data,$namaproduk, $keyword)
    {
        $storage = explode("/", $data['storage']);
        $query = $this->db->query("UPDATE data_produk SET 
                            m_produk_keyword = '".$keyword."',
                            m_produk_screen_size = '".$data['size']."',
                            m_produk_camera = '".$data['camera']."',
                            m_produk_ram = '".$data['ram']."',
                            m_produk_battery = '".(is_numeric($data['battery']) ? $data['battery'] : 0)."',
                            m_produk_sensors = '".$data['sensors']."',
                            m_produk_mem_internal = '".(@$storage[0] ? $storage[0] : 0)."',
                            m_produk_mem_internal1 = '".(@$storage[1] ? $storage[1] : 0)."',
                            m_produk_mem_internal2 = '".(@$storage[2] ? $storage[2] : 0)."',
                            m_produk_harga = '".$data['price']."',
                            kategorisasi_done = 0 where m_produk_nama = '".$namaproduk."'");
        return $query;
    }

    function insert($data, $namaproduk, $keyword, $url, $urlreviews)
    {
        $storage = explode("/", $data['storage']);
        $query = $this->db->query("INSERT INTO data_produk VALUES ('','".$namaproduk."','".$keyword."','".$url."','".$urlreviews."',
							'".$data['size']."',
							'".$data['camera']."',
							'".$data['ram']."',
							'".(is_numeric($data['battery']) ? $data['battery'] : 0)."',
							'".$data['sensors']."',
							'".(@$storage[0] ? $storage[0] : 0)."',
							'".(@$storage[1] ? $storage[1] : 0)."',
							'".(@$storage[2] ? $storage[2] : 0)."',
							'".$data['price']."',
							'',
							'',
							'',
							NULL,
							NULL,
							0)");
        return $query;
    }

    function _get(){
        $dataorder = array();

        $search = $this->input->post("search");
        $iDisplayLength = intval($_REQUEST['length']);
        $start = intval($_REQUEST['start']);
        $order = $this->input->post('order');

        $query = "SELECT * FROM data_produk";
        if($search['value'] != ""){
            $query .=preg_match("/WHERE/i",$query)? " AND ":" WHERE ";
            $query .= "(m_produk_nama LIKE '%". $search['value'] ."%')";
        }

        if($order[0]['column']){
            $query.= " order by
                ".$dataorder[$order[0]["column"]]." ".$order[0]["dir"];
        }

        $iTotalRecords = $this->db->query("SELECT COUNT(*) AS JUMLAH FROM (".$query.") A")->row()->JUMLAH;

        $query .= " LIMIT ". ($start) .",".($iDisplayLength);

        $data = $this->db->query($query)->result_array();
        $i = $start + 1;
        $result = array();
        foreach ($data as $d) {
            $jrb=array(0,0,0);
            $jrl=array(0,0,0);
            $jrk=array(0,0,0);
            $jrm=array(0,0,0);
            $reviewbat = $this->db->query('SELECT * FROM data_review where m_review_bat > 0 AND m_review_id_produk = '.$d['m_produk_id']);
            foreach ($reviewbat->result() as $rb) {
                if($rb->m_review_sentiment == 'POSITIF'){
                    $jrb[0]++;
                }elseif ($rb->m_review_sentiment == 'NETRAL'){
                    $jrb[1]++;
                }elseif ($rb->m_review_sentiment == 'NEGATIF'){
                    $jrb[2]++;
                }
            }

            $reviewlyr = $this->db->query('SELECT * FROM data_review where m_review_lyr > 0 AND m_review_id_produk = '.$d['m_produk_id']);
            foreach ($reviewlyr->result() as $rl) {
                if($rl->m_review_sentiment == 'POSITIF'){
                    $jrl[0]++;
                }elseif ($rl->m_review_sentiment == 'NETRAL'){
                    $jrl[1]++;
                }elseif ($rl->m_review_sentiment == 'NEGATIF'){
                    $jrl[2]++;
                }
            }

            $reviewkmr = $this->db->query('SELECT * FROM data_review where m_review_kmr > 0 AND m_review_id_produk = '.$d['m_produk_id']);
            foreach ($reviewkmr->result() as $rk) {
                if($rk->m_review_sentiment == 'POSITIF'){
                    $jrk[0]++;
                }elseif ($rk->m_review_sentiment == 'NETRAL'){
                    $jrk[1]++;
                }elseif ($rk->m_review_sentiment == 'NEGATIF'){
                    $jrk[2]++;
                }
            }

            $reviewmsn = $this->db->query('SELECT * FROM data_review where m_review_msn > 0 AND m_review_id_produk = '.$d['m_produk_id']);
            foreach ($reviewmsn->result() as $rm) {
                if($rm->m_review_sentiment == 'POSITIF'){
                    $jrm[0]++;
                }elseif ($rm->m_review_sentiment == 'NETRAL'){
                    $jrm[1]++;
                }elseif ($rm->m_review_sentiment == 'NEGATIF'){
                    $jrm[2]++;
                }
            }

            if(strlen($d['m_produk_ram']) > 2){
                $ram = $d['m_produk_ram']." Mb";
            }else{
                $ram = $d['m_produk_ram']." Gb";
            }
            $r = array();
            $r[0] = $i;
            $r[1] = '<a target="_blank" href="'.$d['m_produk_url'].'" target="_blank">'.$d['m_produk_nama'].'</a>';
            $r[2] = $this->rupiah($d['m_produk_harga']);
            $r[3] = "Ukuran Layar ".$d['m_produk_screen_size']." Inch <br>
                    Ram ".$ram."<br>
                    Baterai ".$d['m_produk_battery']." Mah <br>
                    Memori Internal ".$d['m_produk_mem_internal']." Gb <br>
                    Kamera ".$d['m_produk_camera']." Mp";
            ($d['kategorisasi_done'] == 0) ? $r[4] = "<a class='btn btn-warning btn-sm' onclick='kategorisasi(".$d['m_produk_id'].")'>Hitung Kategorisasi</a>" : $r[4] = "Baterai<br>
                                                                                                                                                                Positif = ".$jrb[0]."<br>
                                                                                                                                                                Netral = ".$jrb[1]."<br>
                                                                                                                                                                Negatif = ".$jrb[2]."<br><br>
                                                                                                                                                                Layar<br>
                                                                                                                                                                Positif = ".$jrl[0]."<br>
                                                                                                                                                                Netral = ".$jrl[1]."<br>
                                                                                                                                                                Negatif = ".$jrl[2]."<br><br>
                                                                                                                                                                Kamera<br>
                                                                                                                                                                Positif = ".$jrk[0]."<br>
                                                                                                                                                                Netral = ".$jrk[1]."<br>
                                                                                                                                                                Negatif = ".$jrk[2]."<br><br>
                                                                                                                                                                Mesin<br>
                                                                                                                                                                Positif = ".$jrm[0]."<br>
                                                                                                                                                                Netral = ".$jrm[1]."<br>
                                                                                                                                                                Negatif = ".$jrm[2];
            $r[5] = "Positif = ".$d['data_pos']."<br>
                     Netral = ".$d['data_net']."<br>
                     Negatif = ".$d['data_neg']."<br><br>
                     <b>Kesimpulan</b><br>Skor = ".($d['data_pos']-$d['data_neg'])."<br>".$d['data_sentiment'];
            $r[6] = '<a class="btn btn-success btn-sm" target="_blank" href="sentimentproduk/detailhasil/'.$d['m_produk_id'].'">Detail Review</a>';
            array_push($result, $r);
            $i++;
        }

        $records["data"] = $result;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return $records;
    }

    function rupiah($angka){

        $hasil_rupiah = "Rp " . number_format($angka,2,',','.');
        return $hasil_rupiah;

    }

    public function getproduk()
    {
        $data = $this->db->get('data_produk');
        return $data;
    }

    public function getProdukByRangeHarga($harga_min, $harga_max)
    {
        $query = $this->db->query("SELECT * FROM data_produk WHERE m_produk_harga BETWEEN '".$harga_min."' AND '".$harga_max."' ORDER BY m_produk_harga");
        return $query;
    }

    public function getProdukById()
    {
        $query = $this->db->query("SELECT * FROM data_produk WHERE m_produk_id IN (123, 125, 127, 129, 155, 157, 160, 162, 165, 169) ORDER BY m_produk_harga");
        return $query;
    }

    function getHasil($id_hasil){
        $query = $this->db->query("SELECT * FROM data_produk WHERE m_produk_id IN (".$id_hasil.") ORDER BY FIELD(m_produk_id,".$id_hasil.")");
        return $query->result();
    }

    function getHasilSort($id_hasil,$id_order_by){
        $order_by = '';

        if($id_order_by == 1){
            $order_by = 'm_produk_battery';
        }elseif ($id_order_by == 2){
            $order_by = 'm_produk_camera';
        }elseif ($id_order_by == 3){
            $order_by = 'm_produk_screen_size';
        }elseif ($id_order_by == 4){
            $order_by = 'm_produk_ram';
        }
        $query = $this->db->query("SELECT * FROM data_produk WHERE m_produk_id IN (".$id_hasil.") ORDER BY ".$order_by." DESC");
        return $query->result();
    }
}