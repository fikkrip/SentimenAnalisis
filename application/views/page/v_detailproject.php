<!DOCTYPE html>
<html>
<head>
    <title>Detail Project</title>
    <head>
        <?php $this->load->view('head');?>
    </head>
<body style="background-color: #f5f5f5">
<?php $this->load->view('header');?>
<!-- start banner Area -->
<section id="home">
    <div class="container mt-30 mb-30" style="background-color: #ffffff;border: 1px solid transparent;border-color: #0277bd;padding-right: 0px;padding-left: 0px;">
        <div class="mb-50" style="background-color: #0277bd">
            <h3 align="center" style="padding-top: 20px; color: white">FORM LAPORAN KERUSAKAN / PERBAIKAN SARANA & FASILITAS</h3>
            <h5 align="center" style="padding-bottom: 20px;color: white">LOKASI : TERMINAL BBM SURABAYA GROUP</h5>
        </div>
        <div class="container">
            <?php echo form_open("page/insert_proyek", array('enctype'=>'multipart/form-data', 'class'=>'form-horizontal')); ?>
                <div style="color: red;"><?php echo $this->session->flashdata('msg');?></div>
                <div class="form-row col-md-12">
                    <div class="form-group col-md-6">
                        <label class="control-label" for="no_laporan">NO LAPORAN:</label>
                        <input type="text" class="form-control" name="no_laporan" id="no_laporan" readonly value="<?= $query->no_laporan ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label" for="nama_sarfas">NAMA SARFAS:</label>
                        <input type="text" class="form-control" name="nama_sarfas" id="nama_sarfas" readonly value="<?= $query->nama_sarfas ?>">
                    </div>
                </div>
                <div class="form-row col-md-12">
                    <div class="form-group col-md-12">
                        <label class="control-label" for="lokasi">LOKASI:</label>
                        <input type="text" class="form-control" name="lokasi" id="lokasi" readonly value="<?= $query->lokasi ?>">
                    </div>
                </div>
                <div class="form-row col-md-12">
                    <div class="form-group col-md-12">
                        <label class="control-label" for="tanggal_laporan">TANGGAL LAPORAN:</label>
                        <input type="date" class="form-control" name="tanggal_laporan" id="tanggal_laporan" readonly value="<?= $query->tanggal_laporan ?>">
                    </div>
                </div>
                <div class="form-row col-md-12">
                    <div class="form-group col-md-12">
                        <label class="control-label" for="merk_tahun">MERK/TAHUN:</label>
                        <input type="text" class="form-control" name="merk_tahun" id="merk_tahun" readonly value="<?= $query->merk_tahun ?>">
                    </div>
                </div>
                <div class="form-row col-md-12">
                    <div class="form-group col-md-12">
                        <label class="control-label" for="no_aset">NO ASET:</label>
                        <input type="text" class="form-control" name="no_aset" id="no_aset" readonly value="<?= $query->no_aset ?>">
                    </div>
                </div>
                <div class="form-row col-md-12">
                    <div class="form-group col-md-12">
                        <label class="control-label" for="nama_proyek">PERMINTAAN PENGADAAN/PERBAIKAN:</label>
                        <input type="text" class="form-control" name="nama_proyek" id="nama_proyek" readonly value="<?= $query->nama_proyek ?>">
                    </div>
                </div>
                <div class="form-row col-md-12">
                    <div class="form-group col-md-12">
                        <label class="control-label" for="file_foto">FOTO:</label>
                        <div class="form-group col-md-12">
                            <img src="<?= base_url().'images/'.$query->file_foto ?>">
                            <!-- <input type="file" class="form-control" name="file_foto" id="file_foto" readonly> -->
                        </div>
                    </div>
                </div>
                <div class="form-row col-md-12">
                    <a class="genric-btn info radius mb-20" href="<?= '../print_proyek2/'.$query->id ?>" target="_blank" rel="noopener noreferrer" tabindex=-1>CETAK</a>
                </div>
        </div>
    </div>
        <?= form_close() ?>
</section>
    <?php $this->load->view('footer');?>
</body>
</html>