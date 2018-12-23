<nav class="navbar navbar-inverse">
<div class="container-fluid">
<!-- Collect the nav links, forms, and other content for toggling -->
<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
  <ul class="nav navbar-nav">
  <!--Akses Menu Untuk User-->
  <?php if($this->session->userdata('ses_roles')=='1'):?>
      <li class="active"><a href="<?php echo base_url().'index.php/page'?>">HOME</a></li>
      <li class="active"><a href="<?php echo base_url().'index.php/page/pending'?>">PENDING PROJECT</a></li>
      <li class="active"><a href="<?php echo base_url().'index.php/page/ongoing'?>">ONGOING PROJECT</a></li>
      <li class="active"><a href="<?php echo base_url().'index.php/page/finished'?>">FINISHED PROJECT</a></li>
      <li class="active"><a href="<?php echo base_url().'index.php/page/rejected'?>">REJECTED PROJECT</a></li>
  <!--Akses Menu Untuk Pengawas-->
  <?php elseif($this->session->userdata('ses_roles')=='2'):?>
      <li class="active"><a href="<?php echo base_url().'index.php/page'?>">Home</a></li>
  <!--Akses Menu Untuk Teknik-->
  <?php elseif($this->session->userdata('ses_roles')=='3'):?>
      <li class="active"><a href="<?php echo base_url().'index.php/page'?>">Home</a></li>
  <!--Akses Menu Untuk Approval-->
  <?php elseif($this->session->userdata('ses_roles')=='4'):?>
      <li class="active"><a href="<?php echo base_url().'index.php/page'?>">Home</a></li>
      <li><a href="<?php echo base_url().'index.php/page/data_pegawai'?>">Data Pegawai</a></li>
  <!--Akses Menu Untuk Admin-->
  <?php elseif($this->session->userdata('ses_roles')=='99'):?>
      <li class="active"><a href="<?php echo base_url().'index.php/page'?>">Home</a></li>
      <li><a href="<?php echo base_url().'index.php/page/data_pegawai'?>">Data Pegawai</a></li>
  <?php endif; ?>
  </ul>

  <ul class="nav navbar-nav navbar-right">
    <li><a href="<?php echo base_url().'index.php/login/logout'?>">Sign Out</a></li>
  </ul>
</div><!-- /.navbar-collapse -->
</div><!-- /.container-fluid -->
</nav>
