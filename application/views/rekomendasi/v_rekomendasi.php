<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <?php $this->load->view('head');?>
    <link href="<?php echo base_url().'assets/DataTables/datatables.min.css'?>" rel="stylesheet">
    <link href="<?php echo base_url().'assets/css/custom.css'?>" rel="stylesheet">
</head>
<body style="background-color: #f5f5f5">
<?php $this->load->view('header');?>
<!-- start banner Area -->
<section id="home">
    <div class="container mt-30 mb-30" style="background-color: #ffffff;border: 1px solid transparent;border-color: #0277bd;padding-right: 0px;padding-left: 0px;">
        <div class="mb-50" style="background-color: #0277bd">
            <h5 align="center" style="padding-top: 20px; padding-bottom: 20px;color: white">SISTEM REKOMENDASI</h5>
        </div>
        <div class="container">
            <form id="formsearch" data-parsley-validate action="rekomendasi/tampilHasilRekomendasi" method="POST" class="form-horizontal">
                <div class="form-group col-md-12">
                    <label for="harga_min">MASUKKAN HARGA MINIMAL:</label>
                    <input type="number" class="form-control" name="harga_min" id="harga_min" required>
                </div>
                <div class="form-group col-md-12">
                    <label for="harga_max">MASUKKAN HARGA MAKSIMAL:</label>
                    <input type="number" class="form-control" name="harga_max" id="harga_max" required>
                </div>
                <div class="form-group col-md-12">
                    <button id="search" type="submit" class="genric-btn primary radius mb-30">CARI REKOMENDASI</button>
                </div>
            </form>
            <div id="loading" style="margin-bottom: 20px"></div>
        </div>
    </div>
</section>
<!-- End banner Area -->
<?php $this->load->view('footer');?>
</body>
</html>