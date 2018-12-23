<!DOCTYPE html>
<html xmlns:http="http://www.w3.org/1999/xhtml">
<head>
    <title>Sub Project</title>
    <head>
        <?php $this->load->view('head');?>
        <link href="<?php echo base_url().'assets/DataTables/datatables.min.css'?>" rel="stylesheet">
    </head>
<body style="background-color: #f5f5f5">
<?php $this->load->view('header');?>
<!-- start banner Area -->
<section id="home">
    <div class="container mt-30 mb-30" style="background-color: #ffffff;border: 1px solid transparent;border-color: #0277bd;padding-right: 0px;padding-left: 0px;">
        <div class="mb-50" style="background-color: #0277bd">
            <h3 align="center" style="padding-top: 20px; color: white">LAPORAN KERUSAKAN / PERBAIKAN SARANA & FASILITAS</h3>
            <h6 align="center" style="padding-bottom: 20px;color: white;">LOKASI : TERMINAL BBM SURABAYA GROUP</h6>
        </div>
    <div class="container">
        <?php echo form_open("page/update_proyek/".$query->id, array('enctype'=>'multipart/form-data', 'class'=>'form-horizontal')); $str_kosong= ($this->session->userdata('ses_roles')=='3') ? '' :'Data belum diisi oleh bagian teknis' ?>
            <div style="color: red;"><?php echo $this->session->flashdata('msg');?></div>
            <div class="form-row col-md-12">
                <div class="form-group col-md-6">
                    <label class="control-label" for="no_laporan">NO LAPORAN:</label>
                    <input type="text" class="form-control" name="no_laporan" id="no_laporan" readonly value="<?= $query->no_laporan ?>">
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label" for="nama_proyek">NAMA PROYEK:</label>
                    <input type="text" class="form-control" name="nama_proyek" id="nama_proyek" readonly value="<?= $query->nama_proyek ?>">
                </div>
            </div>
            <div class="form-row col-md-12">
                <div class="form-group col-md-12">
                    <label class="control-label" for="vendor">VENDOR:</label>
                    <input type="text" class="form-control" name="vendor" id="vendor" <?= ($this->session->userdata('ses_roles')=='3') ? '' : 'readonly' ?> required value="<?= $query->vendor ? $query->vendor : $str_kosong ?>">
                </div>
            </div>
            <div class="form-row col-md-12">
                <div class="form-group col-md-6">
                    <label class="control-label" for="estimasi_selesai">ESTIMASI SELESAI:</label>
                    <input type="date" class="form-control" name="estimasi_selesai" id="estimasi_selesai" <?= ($this->session->userdata('ses_roles')=='3') ? '' : 'hidden' ?> required value="<?= $query->estimasi_selesai ?>">
                    <input type="text" class="form-control" id="estimasi_selesai_nonteknis" <?= ($this->session->userdata('ses_roles')=='3') ? 'hidden' : 'readonly' ?> value="<?= $query->estimasi_selesai ? $query->estimasi_selesai : $str_kosong ?>">
                </div>
                <div class="form-group col-md-6" style="display: <?= ($this->session->userdata('ses_roles')=='3') ? '' : 'none' ?>">
                    <label class="control-label" for="file_timeline">UPLOAD TIMELINE:</label>
                    <input type="file" class="form-control" name="file_timeline" id="file_timeline" required>
                </div>
            </div>
            <div class="form-row col-md-12">
                <div class="form-group col-md-6">
                    <label class="control-label" for="estimasi_biaya">ESTIMASI BIAYA:</label>
                    <input type="text" class="form-control" name="estimasi_biaya" id="estimasi_biaya" <?= ($this->session->userdata('ses_roles')=='3') ? '' : 'readonly' ?> required value="<?= $query->estimasi_biaya ? $query->estimasi_biaya : $str_kosong ?>">
                </div>
                <div class="form-group col-md-6" style="display: <?= ($this->session->userdata('ses_roles')=='3') ? '' : 'none' ?>">
                    <label class="control-label" for="file_oe">UPLOAD OE:</label>
                    <input type="file" class="form-control" name="file_oe" id="file_oe" required>
                </div>
            </div>
            <div class="form-row col-md-12" style="display: <?= ($this->session->userdata('ses_roles')=='3') ? '' : 'none' ?>">
                <button type="submit" class="btn btn-success">SUBMIT</button>
            </div>
        <?= form_close() ?>
        <hr width="100%">
        <table class="table table-hover">
            <thead>
            <tr style="color: white" bgcolor="#0277bd">
                <th>NO</th>
                <th>TANGGAL</th>
                <th>DETAIL PEKERJAAN</th>
                <th>JUMLAH PEKERJA</th>
                <th>DURASI (JAM)</th>
                <th>LAMPIRAN</th>
            </tr>
            </thead>
            <tbody style="color: black" bgcolor="#ffffff">
            <?php
            $i=0;
            foreach($query->sub_proyek as $row): ?>
                <tr>
                    <td><?php echo $i+1; $i++;?></td>
                    <td><?php
                        setlocale(LC_ALL, 'id_ID');
                        echo strftime("%e %B %Y", strtotime($row->tanggal));
                        ?></td>
                    <td><?= $row->detail_pekerjaan ?></td>
                    <td><?= $row->jumlah_pekerja ?></td>
                    <td><?= $row->durasi ?></td>
                    <td>
                        <?php if($row->attachment): /*rel="noopener noreferrer" => https://medium.com/@jitbit/96e328301f4c*/ ?>
                            <a href="<?= base_url().'images/'.$row->attachment ?>" target="_blank" rel="noopener noreferrer">Buka file</a>
                        <?php else: ?>
                            <?= '-' ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <br>

        <?php if($this->session->userdata('ses_roles')=='3'):?>
        <div class="btn-group d-flex justify-content-center" style="display: <?= ($this->session->userdata('ses_roles')=='3') ? '' : 'none' ?>">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#journalModal">Update Journal</button>
<!--            <button type="button" class="btn btn-success" data-toggle="" data-target="">Print</button>-->
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#finishModal">Pekerjaan Selesai</button>
        </div>
        <?php endif; ?>
    </div>

        <!-- Modal Update Journal -->
        <div class="modal fade" id="journalModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Journal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?= form_open_multipart("page/experimental/".$query->id, array('class' => 'form-horizontal', 'id' => 'journalForm'), array('no_laporan' => $query->no_laporan)) ?>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="control-label" for="tanggal">TANGGAL:</label>
                                <input type="date" class="form-control" name="tanggal" id="tanggal" required value="<?= date('Y-m-d') ?>">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="detail_pekerjaan">DETAIL PEKERJAAN:</label>
                                <input type="text" class="form-control" name="detail_pekerjaan" id="detail_pekerjaan" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="jumlah_pekerja">JUMLAH PEKERJA:</label>
                                <input type="text" class="form-control" name="jumlah_pekerja" id="jumlah_pekerja" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="durasi">DURASI (JAM):</label>
                                <input type="text" class="form-control" name="durasi" id="durasi" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="attachment">ATTACHMENT:</label>
                                <input type="file" class="form-control" name="attachment" id="attachment">
                            </div>
                            <!-- <button type="submit" class="btn btn-success">SUBMIT</button> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
        
        <!-- Modal Pekerjaan Selesai -->
        <div class="modal fade" id="finishModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Pekerjaan Selesai</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?= form_open('page/finish_proyek/'.$query->id, array('class' => 'form-horizontal', 'id' => 'finishForm')) ?>
                        <div class="modal-body">
                                <div class="form-group">
                                    <label class="control-label" for="tanggal_selesai">TANGGAL SELESAI:</label>
                                    <input type="date" class="form-control" name="tanggal_selesai" id="tanggal_selesai" required value="<?= $query->estimasi_selesai ?>">
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="realisasi_biaya">BIAYA:</label>
                                    <input type="text" class="form-control" name="realisasi_biaya" id="realisasi_biaya" required value="<?= $query->estimasi_biaya ?>">
                                </div>
                                <!-- <button type="submit" class="btn btn-success">SUBMIT</button> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="genric-btn primary radius" data-dismiss="modal">Close</button>
                            <button type="submit" class="genric-btn info radius">Save changes</button>
                        </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
        
        <hr>
    </div>
</section>
    <?php $this->load->view('footer');?>
</body>
<script src="<?php echo base_url().'assets/DataTables/datatables.min.js'?>"></script>
<script>
    // HTML document is loaded. DOM is ready.
    $(function() {
        var table = $('.table').DataTable({
            // "searching":false,
            // "paging":false,
            // "info":false
            'language':{
                'url': '//cdn.datatables.net/plug-ins/1.10.19/i18n/Indonesian.json'
            }
        });
        
        // https://www.mkyong.com/jquery/jquery-ajax-submit-a-multipart-form/
        $('#journalForm').on('submit', function(e){
            e.preventDefault();
            var formData = new FormData($('#journalForm')[0]);
            $.ajax({
                url: '<?= site_url()."/page/insert_subproyek/".$query->id ?>',
                type: "POST",
                enctype: 'multipart/form-data',
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                success: function(result){
                    console.log(result);
                    var newData = JSON.parse(result);

                    var arr = table.rows().data().toArray();
                    var i = 0;
                    if(arr.length !== 0){
                        i = arr[arr.length - 1][0];
                    }

                    if (newData.attachment) {
                        var attachment = "<a href=\"<?= base_url().'images/' ?>" + newData.attachment + "\" target=\"_blank\" rel=\"noopener noreferrer\">Buka file</a>";
                    } else var attachment = "-";

                    moment.locale('id');
                    var dataTanggal = moment(newData.tanggal).format('DD MMMM YYYY');

                    table.row.add([
                        ++i,
                        dataTanggal,
                        newData.detail_pekerjaan,
                        newData.jumlah_pekerja,
                        newData.durasi,
                        attachment
                    ]).draw();
                    table.page('last').draw('page');
                    $('#journalModal').modal('toggle');
                },
                error: function(er){
                    // console.log(er);
                    alert('Terjadi error: ' + er.statusText);
                }
            });
        });

        // $('#finishForm').on('submit', function(e){
        //     e.preventDefault();
        //     $.ajax({
        //         url: '<?= site_url()."/page/finish_proyek/".$query->id ?>',
        //         type: "POST",
        //         data: $('#finishForm').serialize(),
        //         success: function(result){
        //             console.log(result);
        //             $('#finishModal').modal('toggle');
        //         },
        //         error: function(er){
        //             console.log(er);
        //             alert('Terjadi error: ' + er.statusText);
        //         }
        //     });      
        // });
    });
</script>
</html>