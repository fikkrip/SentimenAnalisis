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
                            m_produk_harga = '".$data['price']."' where m_produk_nama = '".$namaproduk."'");
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
							NULL)");
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
            if(strlen($d['m_produk_ram']) > 2){
                $ram = $d['m_produk_ram']." Mb";
            }else{
                $ram = $d['m_produk_ram']." Gb";
            }
            $r = array();
            $r[0] = $i;
            $r[1] = '<a href="'.$d['m_produk_url'].'" target="_blank">'.$d['m_produk_nama'].'</a>';
            $r[2] = $this->rupiah($d['m_produk_harga']);
            $r[3] = "Ukuran Layar ".$d['m_produk_screen_size']."Inch <br>
                    Ram ".$ram."<br>
                    Baterai ".$d['m_produk_battery']." Mah <br>
                    Jumlah Sensor ".$d['m_produk_sensors']."<br>
                    Memori Internal ".$d['m_produk_mem_internal']." Gb <br>
                    Kamera ".$d['m_produk_camera']." Mp";
            ($d['kategorisasi_done'] == 0) ? $r[4] = '<a href="'.$d['m_produk_url'].'" target="_blank"> Hitung Kategorisasi </a>' : $r[4] = 0;
            $r[5] = "Positif = ".$d['data_pos']."<br>
                     Netral = ".$d['data_net']."<br>
                     Negatif = ".$d['data_neg']."<br><br>
                     <b>Kesimpulan</b> ".$d['data_sentiment'];
            $r[6] = '<a class="btn btn-info" href="sentimentproduk/detailhasil/'.$d['m_produk_id'].'">Detail Review</a>"';
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
}