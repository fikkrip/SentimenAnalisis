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
        <h2 style="margin-top: 30px; margin-bottom: 20px">Finished Project</h2>
        <table class="table table-hover">
            <thead>
            <tr style="color: white" bgcolor="#0277bd">
              <th>NO</th>
              <th>NO.LAPORAN</th>
              <th>NAMA PROYEK</th>
              <th>STATUS</th>
            </tr>
          </thead>
            <tbody style="color: black" bgcolor="#ffffff">
          <?php
          $i=0;
          foreach($query as $row): ?>
              <tr>
                  <td><?php echo $i+1; $i++;?></td>
                  <td><a href="<?= "print_proyek2/".$row->id ?>"><?= $row->no_laporan ?></a></td>
                  <td><a href="<?= "detail/".$row->id ?>" target="_blank" rel="noopener noreferrer"><?php echo $row->nama_proyek; ?></a></td>
                  <td>Finished</td>
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
        });
    });
</script>