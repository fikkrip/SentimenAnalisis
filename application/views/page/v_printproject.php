<!DOCTYPE html>
<html>
<head>
    <title>Print Project</title>
<body onload="window.print()">
<!--<body>-->
<?php //$this->load->view('header');?>
<!-- start banner Area -->
<section>
    <div align="right">
        <a href="<?php echo base_url().'index.php/page'?>"><img src="<?php echo base_url().'assets/repair/img/Pertamina.png'?>" style="width: 160px;height: 38px" alt="" title="" /></a>
    </div>
    <h3 align="center" style="font-family: Calibri;">FORM LAPORAN KERUSAKAN / PERBAIKAN SARANA & FASILITAS<br>LOKASI : TERMINAL BBM SURABAYA GROUP </h3>
    <p align="right" style="margin-right: 25%; font-family: Calibri; -webkit-margin-after: unset"><b>No Laporan (diisi oleh LIP): </b></p>
    <div style="font-family: Calibri;">
        <table border="1" style="border-color: white" align="center" width="100%">
            <tr>
                <td width="15%">BAGIAN</td>
                <td width="35%"><b><?= $query['bagian'] ?></b></td>
                <td width="15%">TGL. LAPORAN</td>
                <td width="35%"><b>
                        <?php
                            $bulan = array (1 =>   'Januari',
                                'Februari',
                                'Maret',
                                'April',
                                'Mei',
                                'Juni',
                                'Juli',
                                'Agustus',
                                'September',
                                'Oktober',
                                'November',
                                'Desember'
                            );
                            $split = explode('-', $query['tgl_laporan']);
                            echo $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
                        ?>
                    </b>
                </td>
            </tr>
            <tr>
                <td width="15%">NO. LAPORAN</td>
                <td width="35%"><b><?= $query['no_laporan'] ?></b></td>
                <td width="15%">MERK/TAHUN</td>
                <td width="35%"><b><?= $query['merk_tahun'] ?></b></td>
            </tr>
            <tr>
                <td width="15%">NAMA SARFAS</td>
                <td width="35%"><b><?= $query['nama_sarfas'] ?></b></td>
                <td width="15%">NO. ASET</td>
                <td width="35%"><b><?= $query['no_aset'] ?></b></td>
            </tr>
            <tr>
                <td>LOKASI</td>
                <td><b><?= $query['lokasi'] ?></b></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4" align="center"><b>JENIS KERUSAKAN</b></td>
            </tr>
            <tr>
                <td colspan="4">
                    <b>PERMINTAAN PERBAIKAN:</b>
                    <ol><li><p><?= $query['nama_proyek'] ?></p></li></ol>
                    <p align="center"><img src="<?= base_url().'images/'.$query['file_foto'] ?>" height="170" width="240"></p>
                </td>
            </tr>
        </table>

        <table align="center" width="100%">
            <tr>
                <td>
                    <p style="-webkit-margin-after: unset;">Surabaya, <?= $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0]; ?></p>
                    <p style="-webkit-margin-before: unset; margin-bottom: 70px">Pelapor,</p>
                    <p style="-webkit-margin-before: unset; -webkit-margin-after: unset;"><b><u><?= $query['nama_user'] ?></u></b></p>
                    <p style="-webkit-margin-before: unset;"><?= $query['jabatan_user'] ?></p>
                </td>
                <td align="right">
                    <p style="-webkit-margin-before: unset; margin-bottom: 70px">Penerima Laporan,</p>
                    <p style="-webkit-margin-before: unset;">......................................</p>
                </td>
            </tr>
        </table>

        <table align="center" width="100%" border="1" style="border-color: white">
            <tr>
                <th>
                    TINDAK LANJUT / PERBAIKAN
                </th>
            </tr>
            <tr>
                <td>
                    <p style="-webkit-margin-before: unset; margin-left: 10px; margin-right: 10px;"><?= $query['catatan'] == "" ? "<br><br>" : $query['catatan'] ?></p>
                    <p style="-webkit-margin-after: unset;">Selesai Tanggal :
                        <?php
                        if ($query['estimasi_selesai'] != null){
                            $bulan = array (1 =>   'Januari',
                                'Februari',
                                'Maret',
                                'April',
                                'Mei',
                                'Juni',
                                'Juli',
                                'Agustus',
                                'September',
                                'Oktober',
                                'November',
                                'Desember'
                            );
                            $split = explode('-', $query['estimasi_selesai']);
                            echo $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
                        }else{
                            echo '-';
                        }
                        ?>
                    </p>
                </td>
            </tr>
        </table>

        <table align="center" width="100%">
            <tr>
                <td width="33%">
                    <p style="margin-bottom: 70px">Ditindaklanjuti oleh,</p>
                    <p style="-webkit-margin-before: unset; -webkit-margin-after: unset;"><b><u><?= $query['nama_teknik'] ?></u></b></p>
                    <p style="-webkit-margin-before: unset;"><?= $query['jabatan_teknik'] ?></p>
                </td>
                <td width="33%" align="center">
                    <p style="margin-bottom: 70px">Disetujui oleh,</p>
                    <p style="-webkit-margin-before: unset; -webkit-margin-after: unset;"><b><u><?= $query['nama_pengawas'] ?></u></b></p>
                    <p style="-webkit-margin-before: unset;"><?= $query['jabatan_pengawas'] ?></p>
                </td>
                <td width="33%" align="right">
                    <p style="margin-bottom: 70px">Mengetahui,</p>
                    <p style="-webkit-margin-before: unset; -webkit-margin-after: unset;"><b><u><?= $query['nama_oh'] ?></u></b></p>
                    <p style="-webkit-margin-before: unset;"><?= $query['jabatan_oh'] ?></p>
                </td>
            </tr>
        </table>
    </div>
</section>
<!--    --><?php //$this->load->view('footer');?>
</body>
</html>