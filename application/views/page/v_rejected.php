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
    <div class="container">
        <h2 style="margin-top: 30px; margin-bottom: 20px">Rejected Project</h2>
      <table class="table table-striped">
          <thead>
          <tr style="color: white" bgcolor="#0277bd">
              <th>NO</th>
              <th>NO.LAPORAN</th>
              <th>NAMA PROYEK</th>
              <th>TANGGAL REJECTED</th>
              <th>ALASAN</th>
              <th>REJECTOR</th>
          </tr>
          </thead>
          <tbody style="color: black" bgcolor="#ffffff">
          <?php
          $i=0;
          foreach($query as $row): ?>
              <tr>
                  <td><?php echo $i+1; $i++;?></td>
                  <td><a href="<?= "detail/".$row->id ?>"><?= $row->no_laporan ?></a></td>
                  <td><?php echo $row->nama_proyek; ?></td>
                  <td><?php
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
                          $split = explode('-', $row->tanggal_reject);
                          echo $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
                      ?>
                  </td>
                  <td><?php echo $row->alasan; ?></td>
                  <td><?php echo $row->jabatan; ?></td>
              </tr>
          <?php endforeach; ?>
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
    // HTML document is loaded. DOM is ready.
    $(function() {
        $('.table').DataTable({
            'language':{
                'url': '//cdn.datatables.net/plug-ins/1.10.19/i18n/Indonesian.json'
            }
        });    });
</script>