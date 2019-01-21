<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <?php $this->load->view('head');?>
    <link href="<?php echo base_url().'assets/DataTables/datatables.min.css'?>" rel="stylesheet">
    <link href="<?php echo base_url().'assets/css/select/select2.min.css'?>" rel="stylesheet">
    <link href="<?php echo base_url().'assets/css/custom.css'?>" rel="stylesheet">
</head>
<body style="background-color: #f5f5f5">
<?php $this->load->view('header');?>
<!-- start banner Area -->
<section id="home">
    <div class="container mt-30 mb-30" style="background-color: #ffffff;border: 1px solid transparent;border-color: #0277bd;padding-right: 0px;padding-left: 0px;">
        <div class="mb-50" style="background-color: #0277bd">
            <h5 align="center" style="padding-top: 20px; padding-bottom: 20px;color: white">FORM REKOMENDASI PRODUK</h5>
        </div>
        <div class="container">
            <form id="formsearch" data-parsley-validate action="#" method="POST" class="form-horizontal">
                <div class="form-row col-md-12">
                    <div class="form-group col-md-6">
                        <label class="control-label" for="harga">KISARAN HARGA:</label>
                        <input type="number" name="harga" id="harga" required class="form-control" placeholder="Masukkan kisaran harga...">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label" for="ukuran_layar">UKURAN LAYAR:</label>
                        <select class="select2_group form-control" id="ukuran_layar" required name="ukuran_layar">
                            <option value="">5.6</option>
                            <option value="">5.8</option>
                            <option value="">6.1</option>
                            <option value="">6.2</option>
                            <option value="">6.3</option>
                        </select>
                    </div>
                </div>
                <div class="form-row col-md-12">
                    <div class="form-group col-md-6">
                        <label class="control-label" for="ram">RAM:</label>
                        <select class="select2_group form-control" id="ram" required name="ram">
                            <option value="">2</option>
                            <option value="">3</option>
                            <option value="">4</option>
                            <option value="">6</option>
                            <option value="">8</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label" for="baterai">BATERAI:</label>
                        <select class="select2_group form-control" id="baterai" required name="baterai">
                            <option value="">3200</option>
                            <option value="">3500</option>
                            <option value="">3800</option>
                            <option value="">4230</option>
                            <option value="">4400</option>
                        </select>
                    </div>
                </div>
                <div class="form-row col-md-12">
                    <div class="form-group col-md-6">
                        <label class="control-label" for="internal">MEMORI INTERNAL:</label>
                        <select class="select2_group form-control" id="internal" required name="internal">
                            <option value="">16</option>
                            <option value="">32</option>
                            <option value="">64</option>
                            <option value="">128</option>
                            <option value="">256</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label" for="kamera">KAMERA UTAMA:</label>
                        <select class="select2_group form-control" id="kamera" required name="kamera">
                            <option value="">12</option>
                            <option value="">13</option>
                            <option value="">16</option>
                            <option value="">24</option>
                            <option value="">48</option>
                        </select>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <button id="search" type="submit" class="genric-btn primary radius mb-30">Hitung Sentimen</button>
                </div>
            </form>
            <div id="loading" style="margin-bottom: 20px"></div>
        </div>
    </div>
    <div class="container mt-10 mb-10" style="background-color: #ffffff; border: 1px solid transparent; border-color: #0277bd; padding-right: 0px;padding-left: 0px;">
        <div class="mb-10" style="background-color: #0277bd">
            <h5 align="center" style="padding-top: 20px; padding-bottom: 20px;color: white">HASIL PERHITUNGAN SENTIMENT</h5>
        </div>
        <div class="container" style="margin-bottom: 20px">
            <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Positif <span class="required">:</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <span id="total0">0 Komentar</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Negatif <span class="required">:</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <span id="total1">0 Komentar</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Netral <span class="required">:</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <span id="total2">0 Komentar</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Kesimpulan <span class="required">:</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <span id="total3">Belum ada kesimpulan</span>
                    </div>
                </div>
                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-8">
                        <a class="btn btn-info" id="linksumarry" href="">Detail Summary</a>
                        <!-- <button type="submit" class="btn btn-info">Detail Summary</button> -->
                    </div>
                </div>

            </form>
        </div>
    </div>
</section>
<!-- End banner Area -->
<?php $this->load->view('footer');?>
</body>
</html>
<script src="<?php echo base_url().'assets/DataTables/datatables.min.js'?>"></script>
<script src="<?php echo base_url().'assets/js/bootstrapPager.js'?>"></script>
<script src="<?php echo base_url().'assets/js/select/select2.full.js'?>"></script>
<script src="<?php echo base_url().'assets/js/moment.min.js'?>"></script>
<script src="<?php echo base_url().'assets/js/datepicker/daterangepicker.js'?>"></script>
<script type="text/javascript">
    $(document).ready(function () {
        // $(".select2_group").select2({
        //     // placeholder: "Pilih Smartphone",
        //     // allowClear: true
        // });
        // $('#tanggal').daterangepicker({
        //     singleDatePicker: true,
        //     calender_style: "picker_2",
        //     format: 'YYYY-MM-DD'
        // }, function (start, end, label) {
        //     console.log(start.toISOString(), end.toISOString(), label);
        // });

        $("#search").click(function(event) {
            event.preventDefault();
            $("#formsearch").submit();
        });

        $("#formsearch").submit(function(event) {
            event.preventDefault();

            $(".btnsearch").attr('disabled', true);
            $("#search").text('Loading...');
            $("#loading").html('<center><img src="<?php echo base_url(); ?>assets/images/loading.gif" /></center>');
            var data = $("#formsearch").serialize();
            $.ajax({
                url: 'sentimentproduk/hitungsentiment',
                type: 'POST',
                dataType: 'json',
                data: data
            })
                .done(function(resp) {
                    if (resp) {
                        $("#loading").html('<center><img src="<?php echo base_url(); ?>assets/images/loading.gif" /></center>');
                        $.post('<?php echo base_url(); ?>sentimentproduk/hitungdata_bayes/'+resp.idproduk, {}, function(data, textStatus, xhr) {
                            console.info(data);
                            $("#total0").text(data.data_pos+" Komentar");
                            $("#total1").text(data.data_neg+" Komentar");
                            $("#total2").text(data.data_net+" Komentar");
                            $("#total3").text(data.data_sentiment);
                            $("#loading").html('');
                            $(".btnsearch").attr('disabled', false);
                            $("#search").text('Hitung Sentimen');
                            $("#linksumarry").attr('href', '<?php echo base_url(); ?>sentimentproduk/detailhasil/'+resp.idproduk);
                        });
                    }
                    console.log("success");
                })
                .fail(function() {
                    console.log("error");
                })
                .always(function() {
                    console.log("complete");
                });

            return false;
        });

        // NotifikasiToast({
        //   type : 'success',
        //   msg : 'coba toast',
        //   title: 'Title'
        // });
    });
</script>