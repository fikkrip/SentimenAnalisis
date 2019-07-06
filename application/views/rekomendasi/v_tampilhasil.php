<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <?php $this->load->view('head');?>
    <link href="<?php echo base_url().'assets/DataTables/datatables.min.css'?>" rel="stylesheet">
</head>
<body style="background-color: #f5f5f5">
<?php $this->load->view('header');?>
<!-- start banner Area -->
<section id="home">
    <div class="container" style="margin-bottom: 20px">
        <div style="margin-top: 20px">
            <button class="btn btn-info btn-small"><a style="color: white;" href="<?= base_url()?>rekomendasi">Coba Rekomendasi Lain</a></button>
            <form action="<?php echo base_url().'rekomendasi/tampilHasilSort'?>" method="POST">
                <label style="margin-top: 20px" for="sort_by">Sort by: </label>
                <select class="form-control" id="sort_by" name="sort_by" onchange="this.form.submit();">
                    <option class="form-control" value="0" <?= ($sort_by == 0) ? 'selected' : ''?> >Skor Rekomendasi</option>
                    <option class="form-control" value="1" <?= ($sort_by == 1) ? 'selected' : ''?> >Baterai</option>
                    <option class="form-control" value="2" <?= ($sort_by == 2) ? 'selected' : ''?> >Kamera</option>
                    <option class="form-control" value="3" <?= ($sort_by == 3) ? 'selected' : ''?> >Layar</option>
                    <option class="form-control" value="4" <?= ($sort_by == 4) ? 'selected' : ''?> >RAM</option>
                </select>
                <input type="hidden" name="string_id" value="<?= $string_id ?>" />
                <input type="hidden" name="skor" value="<?php  print_r($skor) ?>" />
            </form>
        </div>
        <h2 style="margin-top: 10px; margin-bottom: 20px; text-align: center">Tabel Hasil Rekomendasi</h2>

        <hr style="border: 0; border-top: 3px double #8c8c8c; ">
        <h5>Range Harga = <?= "Rp " . number_format($dataForm['harga_min'],2,',','.')." - "."Rp " . number_format($dataForm['harga_max'],2,',','.'); ?></h5>
        <h5 style="margin-top: 10px; color: #0000FF">Prosentase Prioritas</h5>
        <h6>Harga           = <?= $dataForm['p_harga'] ?>%</h6>
        <h6>Layar           = <?= $dataForm['p_layar'] ?>%</h6>
        <h6>RAM             = <?= $dataForm['p_ram'] ?>%</h6>
        <h6>Memori Internal = <?= $dataForm['p_internal'] ?>%</h6>
        <h6>Kamera          = <?= $dataForm['p_kamera'] ?>%</h6>
        <h6>Baterai         = <?= $dataForm['p_baterai'] ?>%</h6>
        <h6 style="margin-bottom: 20px">Nilai Sentimen  = <?= $dataForm['p_sentimen'] ?>%</h6>
        <hr style="border: 0; border-top: 3px double #8c8c8c; ">

        <table id="tabelsmartphone" class="table table-striped responsive-utilities jambo_table">
            <thead>
            <tr style="color: white" bgcolor="#0277bd">
                <th>Peringkat</th>
                <th width="100px">Nama Produk </th>
                <th>Harga </th>
                <th>Skor Rekomendasi </th>
                <th>Spesifikasi </th>
                <th>Kategorisasi </th>
                <th>Sentiment </th>
                <th>Action </th>
            </tr>
            </thead>
          <tbody style="color: black" bgcolor="#ffffff">
            <?php
            $i=0;
            foreach($query as $row){
                $jrb=array(0,0,0);
                $jrl=array(0,0,0);
                $jrk=array(0,0,0);
                $jrm=array(0,0,0);
                $reviewbat = $this->db->query('SELECT * FROM data_review where m_review_bat > 0 AND m_review_id_produk = '.$row->m_produk_id);
                foreach ($reviewbat->result() as $rb) {
                    if($rb->m_review_sentiment == 'POSITIF'){
                        $jrb[0]++;
                    }elseif ($rb->m_review_sentiment == 'NETRAL'){
                        $jrb[1]++;
                    }elseif ($rb->m_review_sentiment == 'NEGATIF'){
                        $jrb[2]++;
                    }
                }

                $reviewlyr = $this->db->query('SELECT * FROM data_review where m_review_lyr > 0 AND m_review_id_produk = '.$row->m_produk_id);
                foreach ($reviewlyr->result() as $rl) {
                    if($rl->m_review_sentiment == 'POSITIF'){
                        $jrl[0]++;
                    }elseif ($rl->m_review_sentiment == 'NETRAL'){
                        $jrl[1]++;
                    }elseif ($rl->m_review_sentiment == 'NEGATIF'){
                        $jrl[2]++;
                    }
                }

                $reviewkmr = $this->db->query('SELECT * FROM data_review where m_review_kmr > 0 AND m_review_id_produk = '.$row->m_produk_id);
                foreach ($reviewkmr->result() as $rk) {
                    if($rk->m_review_sentiment == 'POSITIF'){
                        $jrk[0]++;
                    }elseif ($rk->m_review_sentiment == 'NETRAL'){
                        $jrk[1]++;
                    }elseif ($rk->m_review_sentiment == 'NEGATIF'){
                        $jrk[2]++;
                    }
                }

                $reviewmsn = $this->db->query('SELECT * FROM data_review where m_review_msn > 0 AND m_review_id_produk = '.$row->m_produk_id);
                foreach ($reviewmsn->result() as $rm) {
                    if ($rm->m_review_sentiment == 'POSITIF') {
                        $jrm[0]++;
                    } elseif ($rm->m_review_sentiment == 'NETRAL') {
                        $jrm[1]++;
                    } elseif ($rm->m_review_sentiment == 'NEGATIF') {
                        $jrm[2]++;
                    }
                }

                if(strlen($row->m_produk_ram) > 2){
                    $ram = $row->m_produk_ram." Mb";
                }else{
                    $ram = $row->m_produk_ram." Gb";
                }
                ?>
                <tr>
                    <td><?php echo $i+1; $i++;?></td>
                    <td><a target="_blank" href="<?= $row->m_produk_url ?>"><?= $row->m_produk_nama ?></a></td>
                    <td><?php echo "Rp " . number_format($row->m_produk_harga,2,',','.'); ?></td>
                    <td><?php
                            foreach ($skor as $s){
                                if (floatval($s[0]) == $row->m_produk_id){
                                    echo number_format($s[1],2);
                                }
                            }
                        ?></td>
                    <td><?php echo "Ukuran Layar ".$row->m_produk_screen_size." Inch <br>
                                    Ram ".$ram."<br>
                                    Baterai ".$row->m_produk_battery." Mah <br>
                                    Memori Internal ".$row->m_produk_mem_internal." Gb <br>
                                    Kamera ".$row->m_produk_camera." Mp"; ?>
                    </td>
                    <td><?php echo ($row->kategorisasi_done == 0) ? "<a class='btn btn-warning btn-sm' onclick='kategorisasi(".$row->m_produk_id.")'>Hitung Kategorisasi</a>" : "Baterai<br>
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
                        ?>
                    </td>
                    <td>
                        <?php echo "Positif = ".$row->data_pos."<br>
                                    Netral = ".$row->data_net."<br>
                                    Negatif = ".$row->data_neg."<br><br>
                                    <b>Kesimpulan</b><br>Skor = ".($row->data_pos - $row->data_neg)."<br>".$row->data_sentiment;
                        ?>
                    </td>
                    <td>
                        <?php echo '<a class="btn btn-success btn-sm" target="_blank" href="'.base_url().'sentimentproduk/detailhasil/'.$row->m_produk_id.'">Detail Review</a>' ?>
                    </td>
                </tr>
            <?php } ?>
          </tbody>
        </table>
    </div>
</section>
<!-- End banner Area -->
<?php $this->load->view('footer');?>
</body>
</html>
<script src="<?php echo base_url().'assets/DataTables/datatables.min.js'?>"></script>
<script>
    var table = $('#tabelsmartphone').DataTable({
        language:{
            'url': '//cdn.datatables.net/plug-ins/1.10.19/i18n/Indonesian.json'
        },
        "columns": [
            {"orderable":false },
            {"orderable":false },
            {"orderable":false },
            {"orderable":false },
            {"orderable":false },
            {"orderable":false },
            {"orderable":false }
        ]
        // pagingType: "bootstrapPager",
        // "sDom": "Rfrtlip",
        // pagerSettings: {
        //     searchOnEnter: true,
        //     language: "Halaman ~ Dari ~"
        // },
        // paginate: true
    });

    table.on('xhr.dt', function (e, settings, json) {
        setTimeout(function () {
            //initEvent();
        }, 500);
    });

    function kategorisasi(id) {
        // alert(id);
        event.preventDefault();
        $.ajax({
            url: '<?=base_url()."kategorisasi/kategorisasi_proses";?>',
            type: 'POST',
            data: {data: id}
        })
            .done(function(resp) {
                NotifikasiToast({
                    type : resp.tipe,
                    msg : resp.msg,
                    title: 'Informasi'
                });
                location.reload();
                // console.log(resp.msg);
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
        return false;
    }
    // HTML document is loaded. DOM is ready.
    // $(function() {
    //     $('.table').DataTable({
    //         'language':{
    //             'url': '//cdn.datatables.net/plug-ins/1.10.19/i18n/Indonesian.json'
    //         }
    //     });
    // });
</script>