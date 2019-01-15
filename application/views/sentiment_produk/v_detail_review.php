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
        <table width="100%" style="margin-top: 20px; margin-bottom: 20px">
            <tr>
                <td><h2>Tabel Detail Review</h2></td>
                <td align="right"><h6>Nama Smartphone   : <?= $namaproduk; ?></h6></td>
            </tr>
            <tr>
                <td></td>
                <td align="right"><h6>Tanggal Perhitungan Sentiment : <?= $tglsentiment; ?></h6></td>
            </tr>
        </table>
        <table class="table table-hover table-striped">
            <thead>
            <tr style="color: white" bgcolor="#0277bd">
                <th>NO</th>
                <th>REVIEW</th>
                <th>POS</th>
                <th>NEG</th>
                <th>NET</th>
                <th>KESIMPULAN</th>
            </tr>
            </thead>
            <tbody style="color: black" bgcolor="#ffffff">
            <?php
            $i=0;
//            var_dump($datareview->m_review_text);
            foreach($datareview as $row): ?>
                <tr>
                    <td><?php echo $i+1; $i++;?></td>
                    <td><?php echo $row->m_review_text; ?></td>
                    <td><?php echo $row->m_review_index_pos; ?></td>
                    <td><?php echo $row->m_review_index_neg; ?></td>
                    <td><?php echo $row->m_review_index_net; ?></td>
                    <td><?php echo $row->m_review_sentiment; ?></td>
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