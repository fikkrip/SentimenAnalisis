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
            <h5 align="center" style="padding-top: 20px; padding-bottom: 20px;color: white">AMBIL DATA SMARTPHONE</h5>
        </div>
        <div class="container">
            <form id="formsearch" data-parsley-validate action="#" method="POST" class="form-horizontal">
            <div class="form-group col-md-12">
                <label for="keyword">MASUKKAN KEYWORD:</label>
                <input type="text" class="form-control" name="keyword" id="keyword" required>
            </div>
            <div class="form-group col-md-12">
                <button id="search" type="submit" class="genric-btn primary radius mb-30">AMBIL DATA</button>
            </div>
            </form>
            <div id="loading" style="margin-bottom: 20px"></div>
        </div>
    </div>
    <div class="container mt-10 mb-10" style="background-color: #ffffff; border: 1px solid transparent; border-color: #0277bd; padding-right: 0px;padding-left: 0px;">
        <div class="mb-10" style="background-color: #0277bd">
            <h5 align="center" style="padding-top: 20px; padding-bottom: 20px;color: white">TABEL DATA SMARTPHONE</h5>
        </div>
        <div class="container" style="margin-bottom: 20px">
            <h4 style="margin-top: 30px; margin-bottom: 20px">Data Smartphone</h4>
            <table id="tabelsmartphone" class="table table-striped responsive-utilities jambo_table">
                <thead>
                <tr>
                    <th width="50px">No</th>
                    <th width="100px">Nama Produk </th>
                    <th>Harga </th>
                    <th>Spesifikasi </th>
                    <th>Kategorisasi </th>
                    <th>Sentiment </th>
                    <th>Action </th>
                </tr>
                </thead>

                <tbody>
                </tbody>

            </table>
        </div>
    </div>
</section>
<!-- End banner Area -->
<?php $this->load->view('footer');?>
</body>
</html>
<script src="<?php echo base_url().'assets/DataTables/datatables.min.js'?>"></script>
<script src="<?php echo base_url().'assets/js/bootstrapPager.js'?>"></script>
<script type="text/javascript">
    $(document).ready(function () {
        // $('.table').DataTable({
        //     'language':{
        //         'url': '//cdn.datatables.net/plug-ins/1.10.19/i18n/Indonesian.json'
        //     }
        // });
    });

    var table = $('#tabelsmartphone').DataTable({
        language:{
            'url': '//cdn.datatables.net/plug-ins/1.10.19/i18n/Indonesian.json'
        },
        "columns": [
            {"orderable":false },
            {"orderable":false },
            {"orderable":false },
            {"orderable":false },
            {"orderable":false },
            {"orderable":false },
            {"orderable":false }
        ],
        // pagingType: "bootstrapPager",
        // "sDom": "Rfrtlip",
        // pagerSettings: {
        //     searchOnEnter: true,
        //     language: "Halaman ~ Dari ~"
        // },
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?php echo base_url(); ?>smartphone/dataSmartphone",
            type: "POST",
            data: function (d) {

            }
        }
        // paginate: true
    });

    table.on('xhr.dt', function (e, settings, json) {
        setTimeout(function () {
            //initEvent();
        }, 500);
    });

    $("#formsearch").submit(function(event) {
        event.preventDefault();
        $("#search").text('Loading....');
        $("#search").attr('disabled',true);
        $("#loading").html('<center><img src="<?php echo base_url(); ?>assets/images/loading.gif" /></center>');
        var data = $("#formsearch").serialize();
        $.ajax({
            url: 'smartphone/ambilSmartphone',
            type: 'POST',
            dataType: 'json',
            data: data
        })
            .done(function(resp) {
                NotifikasiToast({
                    type : resp.tipe,
                    msg : resp.msg,
                    title: 'Informasi'
                });
                table.ajax.reload();
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
                $("#loading").html('');
                $("#search").text('Ambil Data');
                $("#search").attr('disabled',false);
            });
        return false;
    });

    function kategorisasi(id) {
        // alert(id);
        event.preventDefault();
        $("#search").text('Loading....');
        $("#search").attr('disabled',true);
        $("#loading").html('<center><img src="<?php echo base_url(); ?>assets/images/loading.gif" /></center>');
        $.ajax({
            url: 'kategorisasi/kategorisasi_proses',
            type: 'POST',
            data: {data: id}
        })
            .done(function(resp) {
                NotifikasiToast({
                    type : resp.tipe,
                    msg : resp.msg,
                    title: 'Informasi'
                });
                table.ajax.reload();
                // console.log(resp.msg);
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
                $("#loading").html('');
                $("#search").text('Ambil Data');
                $("#search").attr('disabled',false);
            });
        return false;
    }
</script>