<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <?php $this->load->view('head');?>
</head>
<body>
    <?php $this->load->view('header');?>
<!-- start banner Area -->
<section class="banner-area" id="home">
    <div class="container">
        <div class="row fullscreen d-flex align-items-center">
            <div class="container" align="center">
                <h1 class="text-uppercase text-black mt-10">Selamat Datang <?php echo $this->session->userdata('ses_nama');?> pada Dashboard Website</h1>
            </div>
        </div>
    </div>
</section>
<!-- End banner Area -->
<?php $this->load->view('footer');?>
</body>
</html>