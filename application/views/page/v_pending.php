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
        <h2 style="margin-top: 30px; margin-bottom: 20px">Pending Project</h2>
      <div style="color: red; margin-bottom: 10px"><?php echo $this->session->flashdata('msg');?></div>
        <table class="table table-hover">
          <thead>
            <tr style="color: white" bgcolor="#0277bd">
                <th>NO</th>
                <th>NO.LAPORAN</th>
                <th>NAMA PROYEK</th>
                <th>STATUS</th>
                <?php if($this->session->userdata('ses_roles')!='1'):?>
                <th>ACTION</th>
                <?php endif; ?>
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
                    <td>
                        <?php
                        if($row->status=='2'){
                            echo 'Work Order sudah dibuat';
                        }elseif ($row->status=='3'){
                            echo 'Work Order sudah disetujui pengawas';
                        }elseif ($row->status=='4'){
                            echo 'Work Order sudah disetujui pengawas dan bagian teknik';
                        }
                        ?>
                    </td>
                    <?php if($this->session->userdata('ses_roles')=='2'):?>
                        <?php if($row->status == 2 && $row->bagian == $this->session->userdata('ses_bagian')):?>
                            <td>
                                <input type="button" class="btn btn-sm btn-success" value="Approve" onclick="ConfirmApprove(<?= $row->id ?>)">
                                <input type="button" class="btn btn-sm btn-danger" id="btn_reject" value="Reject" data-toggle="modal" data-id="<?php echo 'id='.$row->id.'&no_laporan='.$row->no_laporan.'&rejector='.$this->session->userdata('ses_id'); ?>" data-target="#modalForm">
                            </td>
                        <?php else: ?>
                            <td></td>
                        <?php endif; ?>
                    <?php elseif($this->session->userdata('ses_roles')=='3'):?>
                        <?php if($row->status == 3):?>
                            <td>
                                <input type="button" class="btn btn-sm btn-success" id="btn_approve" value="Approve" data-toggle="modal" data-id="<?php echo 'id='.$row->id; ?>" data-target="#modalForm2">
                                <input type="button" class="btn btn-sm btn-danger" id="btn_reject" value="Reject" data-toggle="modal" data-id="<?php echo 'id='.$row->id.'&no_laporan='.$row->no_laporan.'&rejector='.$this->session->userdata('ses_id'); ?>" data-target="#modalForm">
                            </td>
                        <?php else: ?>
                            <td></td>
                        <?php endif; ?>
                    <?php elseif($this->session->userdata('ses_roles')=='4'):?>
                        <?php if($row->status == 4):?>
                            <td>
                                <input type="button" class="btn btn-sm btn-success" value="Approve" onclick="ConfirmApprove(<?= $row->id ?>)">
                                <input type="button" class="btn btn-sm btn-danger" id="btn_reject" value="Reject" data-toggle="modal" data-id="<?php echo 'id='.$row->id.'&no_laporan='.$row->no_laporan.'&rejector='.$this->session->userdata('ses_id'); ?>" data-target="#modalForm">
                            </td>
                        <?php else: ?>
                            <td></td>
                        <?php endif; ?>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalForm" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="labelModalKu">Rejected Form</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <p class="statusMsg"></p>
                    <form role="form">
                        <div class="form-group">
                            <label for="alasan">Alasan</label>
                            <textarea class="form-control" id="alasan" placeholder="Masukkan alasan Anda"></textarea>
                        </div>
                        <input class="form-control" type="hidden" id="tanggal" value="<?= date('Y-m-d') ?>">
                    </form>
                </div>
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="genric-btn primary radius submitBtn" onclick="submitReject()">KIRIM</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalForm2" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="labelModalKu2">Approval Teknik</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <p class="statusMsg2"></p>
                    <form role="form">
                        <div class="form-group">
                            <label for="note">Catatan</label>
                            <textarea class="form-control" id="catatan" placeholder="Masukkan catatan tindak lanjut/perbaikan yang akan dilakukan"></textarea>
                        </div>
                    </form>
                </div>
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="genric-btn primary radius submitBtn2" onclick="submitTeknik()">KIRIM</button>
                </div>
            </div>
        </div>
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
<script type="text/javascript">
    function ConfirmApprove(id) {
        if (confirm("Approving this project?"))
            location.href='approve_proyek/'+id;
    }
</script>
<script>
    function submitReject(){
        var alasan = $('#alasan').val();
        var tanggal = $('#tanggal').val();
        var rowid = $('#btn_reject').data('id') + '&alasan=' + alasan + '&tanggal=' + tanggal;

        console.log(rowid);

        if(alasan.trim() == '' ){
            alert('Masukkan alasan Anda');
            $('#alasan').focus();
            return false;
        }else{
            $.ajax({
                type:'POST',
                url:'reject_proyek',
                data:rowid,
                beforeSend: function () {
                    $('.submitBtn').attr("disabled","disabled");
                    $('.modal-body').css('opacity', '.5');
                },
                success:function(msg){
                    if(msg == 'ok'){
                        $('#modalForm').modal('hide');
                        location.href='rejected';
                    }else{
                        $('.statusMsg').html('<span style="color:red;">Ada sedikit masalah, silakan coba lagi.</span>');
                    }
                    $('.submitBtn').removeAttr("disabled");
                    $('.modal-body').css('opacity', '');
                }
            });
        }
    }

    function submitTeknik(){
        var catatan = $('#catatan').val();
        var rowid = $('#btn_approve').data('id') + '&catatan=' + catatan;

        console.log(rowid);

        if(catatan.trim() == '' ){
            alert('Masukkan catatan Anda');
            $('#catatan').focus();
            return false;
        }else{
            $.ajax({
                type:'POST',
                url:'approve_teknik',
                data:rowid,
                beforeSend: function () {
                    $('.submitBtn2').attr("disabled","disabled");
                    $('.modal-body').css('opacity', '.5');
                },
                success:function(msg){
                    if(msg == 'ok'){
                        $('#modalForm2').modal('hide');
                        location.href='pending';
                    }else{
                        $('.statusMsg2').html('<span style="color:red;">Ada sedikit masalah, silakan coba lagi.</span>');
                    }
                    $('.submitBtn2').removeAttr("disabled");
                    $('.modal-body').css('opacity', '');
                }
            });
        }
    }
</script>