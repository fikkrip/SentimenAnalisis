<!DOCTYPE html>
<html>
<head>
    <title>Form Work Order</title>
    <head>
        <?php $this->load->view('head');?>
    </head>
<body style="background-color: #f5f5f5">
<?php $this->load->view('header');?>
<!-- start banner Area -->
<section>
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
                    <label for="bagian">BAGIAN:</label>
                    <input type="text" class="form-control" name="bagian" id="bagian" readonly value="<?php echo $this->session->userdata('ses_bagian');?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="no_laporan">NO LAPORAN:</label>
                    <input type="text" class="form-control" name="no_laporan" id="no_laporan" readonly
                            <?php
                            if($this->session->userdata('ses_bagian') == 'RSD Perak'){
                                if (isset($max_id)){
                                    echo 'value="'.$max_id.'/WO/RSDP/'.date('Y').'"';
                                }
                            }elseif ($this->session->userdata('ses_bagian') == 'RSD Bandaran'){
                                if (isset($max_id)){
                                    echo 'value="'.$max_id.'/WO/RSDB/'.date('Y').'"';
                                }
                            }else{
                                if (isset($max_id)){
                                    echo 'value="'.$max_id.'/WO/'.$this->session->userdata("ses_bagian").'/'.date('Y').'"';
                                }
                            }
                            ?>
                    >
                </div>
            </div>
            <div class="form-row col-md-12">
                <div class="form-group col-md-6">
                    <label for="nama_sarfas">NAMA SARFAS:</label>
                    <input type="text" class="form-control" name="nama_sarfas" id="nama_sarfas" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="lokasi">LOKASI:</label>
                    <input type="text" class="form-control" name="lokasi" id="lokasi" required>
                </div>
            </div>
            <div class="form-row col-md-12">
                <div class="form-group col-md-4">
                    <label for="tanggal_laporan">TANGGAL LAPORAN:</label>
                    <input type="date" class="form-control" name="tanggal_laporan" id="tanggal_laporan" required>
                </div>
                <div class="form-group col-md-8">
                    <label for="merk_tahun">MERK/TAHUN:</label>
                    <input type="text" class="form-control" name="merk_tahun" id="merk_tahun" required>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label for="no_aset">NO ASET:</label>
                <input type="text" class="form-control" name="no_aset" id="no_aset" required>
            </div>
            <div class="form-group col-md-12">
                <label for="nama_proyek">PERMINTAAN PENGADAAN/PERBAIKAN:</label>
                <textarea type="text" class="form-control" name="nama_proyek" id="nama_proyek" rows="6" required></textarea>
            </div>
            <div class="form-group col-md-3">
                <label for="file_foto">UPLOAD FOTO:</label>
                <input type="file" class="form-control" name="file_foto" id="file_foto" required>
            </div>
            <div class="form-group col-md-12">
                <button type="submit" class="genric-btn primary radius mb-30">SUBMIT</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</section>
<?php $this->load->view('footer');?>
</body>
<script>
    $('#myfiles').bind('change', function() {
        if(this.files[0].size/1024/1024 >= 1) alert('File terlalu besar, Maks. 1 MB');
        // var name = $('#myfiles').val();
        // window.history.pushState(null, '', "http://localhost:8080/SpringWebMVC/formUpload.jsp?id=<%= id %>&filename="+name);
    });
</script>
</html>
