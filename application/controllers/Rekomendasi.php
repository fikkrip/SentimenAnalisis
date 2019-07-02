<?php
class Rekomendasi extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->model('login_model');
        $this->load->model('DataProduk_model');
    }

    function index(){
        if($this->session->userdata('masuk') != TRUE){
            $this->load->view('login/v_login');
        }else{
            $data['get_result'] = 0;
            $this->load->view('rekomendasi/v_rekomendasi', $data);
        }
    }

    function transformCriteria($harga_min, $harga_max){
        $produk_criteria_array = array();
        $query_produk = $this->DataProduk_model->getProdukByRangeHarga($harga_min, $harga_max);

        if($query_produk->num_rows() == null){
            return "300";
        }else{
            foreach ($query_produk->result() as $produk){
                if ($produk->m_produk_ram == '512'){
                    $produk->m_produk_ram = '0.5';
                }

                $internal = max($produk->m_produk_mem_internal, $produk->m_produk_mem_internal1, $produk->m_produk_mem_internal2);
                $sentimen = $produk->data_pos - $produk->data_neg;

                $produk_criteria_layer = array(
                    "id_produk" => $produk->m_produk_id,
                    "harga" => $produk->m_produk_harga,
                    "ram" => $produk->m_produk_ram,
                    "internal" => $internal,
                    "kamera" => $produk->m_produk_camera,
                    "layar" => $produk->m_produk_screen_size,
                    "baterai" => $produk->m_produk_battery,
                    "sentimen" => $sentimen
                );
                array_push($produk_criteria_array, $produk_criteria_layer);
            }
            return $produk_criteria_array;
        }
    }

    function transformCriteriaTesting(){
        $produk_criteria_array = array();
        $query_produk = $this->DataProduk_model->getProdukById();

        if($query_produk->num_rows() == null){
            return "300";
        }else{
            $i = 0;
            foreach ($query_produk->result() as $produk){
                if ($produk->m_produk_ram == '512'){
                    $produk->m_produk_ram = '0.5';
                }

                $internal = max($produk->m_produk_mem_internal, $produk->m_produk_mem_internal1, $produk->m_produk_mem_internal2);
                $sentimen = $produk->data_pos - $produk->data_neg;

                $produk_criteria_layer = array(
                    "id_produk" => $produk->m_produk_id,
                    "harga" => $produk->m_produk_harga,
                    "ram" => $produk->m_produk_ram,
                    "internal" => $internal,
                    "kamera" => $produk->m_produk_camera,
                    "layar" => $produk->m_produk_screen_size,
                    "baterai" => $produk->m_produk_battery,
                    "sentimen" => $sentimen
                );
                array_push($produk_criteria_array, $produk_criteria_layer);
                $i++;
            }
//            var_dump($produk_criteria_array);
//            die();
            return $produk_criteria_array;
        }
    }

    function criteriaToFuzzy($criteria){
        $produk_fuzzy = array();
        foreach ($criteria as $key => $produk) {
            $produk_fuzzy_layer = array(
                "harga" => $this->fuzzyHarga($produk['harga']),
                "ram" => $this->fuzzyRam($produk['ram']),
                "internal" => $this->fuzzyInternal($produk['internal']),
                "kamera" => $this->fuzzyKamera($produk['kamera']),
                "layar" => $this->fuzzyLayar($produk['layar']),
                "baterai" => $this->fuzzyBaterai($produk['baterai']),
                "sentimen" => $this->fuzzySentimen($produk['sentimen'])
            );
            array_push($produk_fuzzy, $produk_fuzzy_layer);
        }

        return $produk_fuzzy;
    }

    function fuzzyHarga($x){
        if($x < 1000000){
            return 1;
        }else if($x >= 1000000 && $x <= 1499000){
            return 2;
        }else if($x >= 1500000 && $x <= 1999000){
            return 3;
        }else if($x >= 2000000 && $x <= 2499000){
            return 4;
        }else if($x >= 2500000 && $x <= 2999000){
            return 5;
        }else if($x >= 3000000 && $x <= 3499000){
            return 6;
        }else if($x >= 3500000 && $x <= 3999000){
            return 7;
        }else if($x >= 4000000 && $x <= 4499000){
            return 8;
        }else if($x >= 4500000 && $x <= 4999000){
            return 9;
        }else if($x >= 5000000 && $x <= 5999000){
            return 10;
        }else if($x >= 6000000 && $x <= 8000000){
            return 11;
        }else{
            return 12;
        }
    }

    function fuzzyRam($x){
        if($x < 1){
            return 1;
        }else if($x >= 1 && $x <= 1.9){
            return 2;
        }else if($x >= 2 && $x <= 2.9){
            return 3;
        }else if($x >= 3 && $x <= 3.9){
            return 4;
        }else if($x >= 4 && $x <= 4.9){
            return 5;
        }else if($x >= 5 && $x <= 6){
            return 6;
        }else{
            return 7;
        }
    }

    function fuzzyInternal($x){
        if($x < 8){
            return 1;
        }else if($x >= 8 && $x <= 15.9){
            return 2;
        }else if($x >= 16 && $x <= 31.9){
            return 3;
        }else if($x >= 32 && $x <= 64){
            return 4;
        }else{
            return 5;
        }
    }

    function fuzzyKamera($x){
        if($x < 2){
            return 1;
        }else if($x >= 2 && $x <= 2.9){
            return 2;
        }else if($x >= 3 && $x <= 4.9){
            return 3;
        }else if($x >= 5 && $x <= 7.9){
            return 4;
        }else if($x >= 8 && $x <= 12.9){
            return 5;
        }else if($x >= 13 && $x <= 20){
            return 6;
        }else{
            return 7;
        }
    }

    function fuzzyLayar($x){
        if($x < 3){
            return 1;
        }else if($x >= 3 && $x <= 3.9){
            return 2;
        }else if($x >= 4 && $x <= 4.9){
            return 3;
        }else if($x >= 5 && $x <= 6){
            return 4;
        }else{
            return 5;
        }
    }

    function fuzzyBaterai($x){
        if($x <= 1000){
            return 1;
        }else if($x > 1000 && $x <= 1499){
            return 2;
        }else if($x >= 1500 && $x <= 1999){
            return 3;
        }else if($x >= 2000 && $x <= 2499){
            return 4;
        }else if($x >= 2500 && $x <= 2999){
            return 5;
        }else if($x >= 3000 && $x <= 3499){
            return 6;
        }else if($x >= 3500 && $x <= 3999){
            return 7;
        }else if($x >= 4000 && $x <= 4499){
            return 8;
        }else if($x >= 4500 && $x <= 5000){
            return 9;
        }else{
            return 10;
        }
    }

    function fuzzySentimen($x){
        if($x <= 10){
            return 1;
        }else if($x >= 11 && $x <= 20){
            return 2;
        }else if($x >= 21 && $x <= 30){
            return 3;
        }else if($x >= 31 && $x <= 40){
            return 4;
        }else if($x >= 41 && $x <= 50){
            return 5;
        }else if($x >= 51 && $x <= 60){
            return 6;
        }else if($x >= 61 && $x <= 70){
            return 7;
        }else if($x >= 71 && $x <= 80){
            return 8;
        }else if($x >= 81 && $x <= 90){
            return 9;
        }else{
            return 10;
        }
    }

    function getMinMax($fuzzy_matrix){
        $minmax_temp = array(12,0,0,0,0,0,0);
        foreach ($fuzzy_matrix as $key => $item){
            if($item['harga'] < $minmax_temp[0]){
                $minmax_temp[0] = $item['harga'];
            }

            if($item['ram'] > $minmax_temp[1]){
                $minmax_temp[1] = $item['ram'];
            }

            if($item['internal'] > $minmax_temp[2]){
                $minmax_temp[2] = $item['internal'];
            }

            if($item['kamera'] > $minmax_temp[3]){
                $minmax_temp[3] = $item['kamera'];
            }

            if($item['layar'] > $minmax_temp[4]){
                $minmax_temp[4] = $item['layar'];
            }

            if($item['baterai'] > $minmax_temp[5]){
                $minmax_temp[5] = $item['baterai'];
            }

            if($item['sentimen'] > $minmax_temp[6]){
                $minmax_temp[6] = $item['sentimen'];
            }
        }

        $minmax = array(
            "harga" => $minmax_temp[0],
            "ram" => $minmax_temp[1],
            "internal" => $minmax_temp[2],
            "kamera" => $minmax_temp[3],
            "layar" => $minmax_temp[4],
            "baterai" => $minmax_temp[5],
            "sentimen" => $minmax_temp[6]
        );

        return $minmax;
    }

    function normalisasi($matrix, $minmax){
        $normalisasi = array();
        foreach ($matrix as $key => $item){
            $normalisasi_temp = array(
                "harga" => $minmax['harga'] / $item['harga'],
                "ram" => $item['ram'] / $minmax['ram'],
                "internal" => $item['internal'] / $minmax['internal'],
                "kamera" => $item['kamera'] / $minmax['kamera'],
                "layar" => $item['layar'] / $minmax['layar'],
                "baterai" => $item['baterai'] / $minmax['baterai'],
                "sentimen" => $item['sentimen'] / $minmax['sentimen']
            );
            array_push($normalisasi, $normalisasi_temp);
        }

        return $normalisasi;
    }

    //dikali pembobotan
    function ranking($matrix, $dataForm){
        $ranking = array();
        foreach ($matrix as $key => $item){
            $harga_val = $item['harga'] * $dataForm['p_harga'];
            $ram_val = $item['ram'] * $dataForm['p_ram'];
            $internal_val = $item['internal'] * $dataForm['p_internal'];
            $kamera_val = $item['kamera'] * $dataForm['p_kamera'];
            $layar_val = $item['layar'] * $dataForm['p_layar'];
            $baterai_val = $item['baterai'] * $dataForm['p_baterai'];
            $sentimen_val = $item['sentimen'] * $dataForm['p_sentimen'];

            $total_val = $harga_val + $ram_val + $internal_val + $kamera_val + $layar_val + $baterai_val + $sentimen_val ;
            array_push($ranking, $total_val);
        }

        return $ranking;
    }

    function ranking_without_sentiment($matrix){
        $ranking = array();
        foreach ($matrix as $key => $item){
            $harga_val = $item['harga'] * 15;
            $ram_val = $item['ram'] * 15;
            $internal_val = $item['internal'] * 15;
            $kamera_val = $item['kamera'] * 15;
            $layar_val = $item['layar'] * 10;
            $baterai_val = $item['baterai'] * 15;
            $sentimen_val = $item['sentimen'] * 15;

            $total_val = $harga_val + $ram_val + $internal_val + $kamera_val + $layar_val + $baterai_val + $sentimen_val;
            array_push($ranking, $total_val);
        }

        return $ranking;
    }

    function recommended($recommendation_score, $produk_criteria)
    {
        arsort($recommendation_score);

        $highest_rec = array();
        foreach ($recommendation_score as $key => $item) {
            $highest_rec_temp = array(
                "id_produk" => $produk_criteria[$key]['id_produk'],
                "score_produk" => $item,
            );

            array_push($highest_rec, $highest_rec_temp);
        }

        return $highest_rec;
    }

    function rekomendasi($dataForm){
        $produk_criteria_array = $this->transformCriteria($dataForm['harga_min'], $dataForm['harga_max']);
//        var_dump($produk_criteria_array);

        if($produk_criteria_array != "300"){
            $produk_fuzzy = $this->criteriaToFuzzy($produk_criteria_array);
//             var_dump($produk_fuzzy);

            $produk_minmax = $this->getMinMax($produk_fuzzy);
//            var_dump($produk_minmax);

            $produk_normalisasi = $this->normalisasi($produk_fuzzy, $produk_minmax);
//            var_dump($produk_normalisasi);

            $produk_score = $this->ranking($produk_normalisasi, $dataForm);
//            var_dump($produk_score);

            $recommended_value = $this->recommended($produk_score, $produk_criteria_array);
            return $recommended_value;
        }else{
            return '300';
        }
    }

    function rekomendasiTesting(){
        $produk_criteria_array = $this->transformCriteriaTesting();
//        var_dump($produk_criteria_array);

        if($produk_criteria_array != "300"){
            $produk_fuzzy = $this->criteriaToFuzzy($produk_criteria_array);
//             var_dump($produk_fuzzy);

            $produk_minmax = $this->getMinMax($produk_fuzzy);
//            var_dump($produk_minmax);

            $produk_normalisasi = $this->normalisasi($produk_fuzzy, $produk_minmax);
//            var_dump($produk_normalisasi);

            $produk_score = $this->ranking_without_sentiment($produk_normalisasi);
//            var_dump($produk_score);

            $recommended_value = $this->recommended($produk_score, $produk_criteria_array);
            return $recommended_value;
        }else{
            return '300';
        }
    }

    function tampilHasilRekomendasi(){
        $dataForm = array(
            'harga_min'  => $_POST['harga_min'],
            'harga_max'  => $_POST['harga_max'],
            'p_harga'    => $_POST['p_harga'],
            'p_layar'    => $_POST['p_layar'],
            'p_internal' => $_POST['p_internal'],
            'p_ram'      => $_POST['p_ram'],
            'p_baterai'  => $_POST['p_baterai'],
            'p_kamera'   => $_POST['p_kamera'],
            'p_sentimen' => $_POST['p_sentimen']
        );

        $hasil = $this->rekomendasi($dataForm);
//        $hasil = $this->rekomendasiTesting();
//        var_dump($hasil);
//        die();
        if($hasil != '300'){
            $id_hasil = array();
            $skor_hasil = array();
            foreach ($hasil as $row){
                array_push($id_hasil, $row['id_produk']);
                array_push($skor_hasil, array($row['id_produk'],$row['score_produk']));
            }
            $string_id = implode(",",$id_hasil);

            //        var_dump(implode(",",$id_hasil));
            //        die();

//            var_dump($hasil);
//            die();

            $data['query'] = $this->DataProduk_model->getHasil($string_id);
            $data['dataForm'] = $dataForm;
            $data['string_id'] = $string_id;
            $data['skor'] = $skor_hasil;
            $data['sort_by'] = 0;
            $this->load->view('rekomendasi/v_tampilhasil', $data);
        }else{
            $output = 'Data tidak ditemukan silahkan, '.'<a href="'. base_url() .'rekomendasi">coba lagi!!!</a>';
            print ($output);
        }
    }

    function print_r_reverse($in) {
        $lines = explode("\n", trim($in));
        if (trim($lines[0]) != 'Array') {
            // bottomed out to something that isn't an array
            return $in;
        } else {
            // this is an array, lets parse it
            if (preg_match("/(\s{5,})\(/", $lines[1], $match)) {
                // this is a tested array/recursive call to this function
                // take a set of spaces off the beginning
                $spaces = $match[1];
                $spaces_length = strlen($spaces);
                $lines_total = count($lines);
                for ($i = 0; $i < $lines_total; $i++) {
                    if (substr($lines[$i], 0, $spaces_length) == $spaces) {
                        $lines[$i] = substr($lines[$i], $spaces_length);
                    }
                }
            }
            array_shift($lines); // Array
            array_shift($lines); // (
            array_pop($lines); // )
            $in = implode("\n", $lines);
            // make sure we only match stuff with 4 preceding spaces (stuff for this array and not a nested one)
            preg_match_all("/^\s{4}\[(.+?)\] \=\> /m", $in, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
            $pos = array();
            $previous_key = '';
            $in_length = strlen($in);
            // store the following in $pos:
            // array with key = key of the parsed array's item
            // value = array(start position in $in, $end position in $in)
            foreach ($matches as $match) {
                $key = $match[1][0];
                $start = $match[0][1] + strlen($match[0][0]);
                $pos[$key] = array($start, $in_length);
                if ($previous_key != '') $pos[$previous_key][1] = $match[0][1] - 1;
                $previous_key = $key;
            }
            $ret = array();
            foreach ($pos as $key => $where) {
                // recursively see if the parsed out value is an array too
                $ret[$key] = $this->print_r_reverse(substr($in, $where[0], $where[1] - $where[0]));
            }
            return $ret;
        }
    }

    function tampilHasilSort(){
//        var_dump($_POST);
//        die();
        $sort_by = $_POST['sort_by'];
        $string_id = $_POST['string_id'];
        $skor_hasil = $_POST['skor'];

        $skor_hasil_new = $this->print_r_reverse($skor_hasil);

        if($sort_by == 0){
            $data['query'] = $this->DataProduk_model->getHasil($string_id);
        }else{
            $data['query'] = $this->DataProduk_model->getHasilSort($string_id, $sort_by);
        }

        $data['sort_by'] = $sort_by;
        $data['string_id'] = $string_id;
        $data['skor'] = $skor_hasil_new;
        var_dump($data);
        die();
        $this->load->view('rekomendasi/v_tampilhasil', $data);
    }
}
