<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <?php $this->load->view('head');?>
</head>
<body style="background-color: #f5f5f5">
<?php $this->load->view('header');?>
<!-- start banner Area -->
<section id="home">
    <div class="container mt-30 mb-30" style="background-color: #ffffff;border: 1px solid transparent;border-color: #0277bd;padding-right: 0px;padding-left: 0px;">
        <div class="mb-50" style="background-color: #0277bd">
            <h5 align="center" style="padding-top: 20px; padding-bottom: 20px;color: white">UJI COBA SENTIMENT</h5>
        </div>
        <div class="container">
            <form id="formsearch" data-parsley-validate action="#" method="POST" class="form-horizontal">
            <div class="form-group col-md-12">
                <label for="kata">MASUKKAN KALIMAT:</label>
                <textarea type="text" class="form-control" name="kata" id="kata" rows="6" required></textarea>
            </div>
            <div class="form-group col-md-12">
                <button id="search" type="submit" class="genric-btn primary radius mb-30">CEK</button>
            </div>
            </form>
            <div id="loading" style="margin-bottom: 20px"></div>
        </div>
    </div>

    <div class="container mt-30 mb-30" style="background-color: #ffffff;border: 1px solid transparent;border-color: #0277bd;padding-right: 0px;padding-left: 0px;">
        <div class="mb-50" style="background-color: #0277bd">
            <h5 align="center" style="padding-top: 20px; padding-bottom: 20px;color: white">HASIL SENTIMENT</h5>
        </div>
        <div class="x_content" id="contentsummary" style="padding: 10px">

        </div>
    </div>
</section>
<!-- End banner Area -->
<?php $this->load->view('footer');?>
</body>
</html>
<script type="text/javascript">
    $(document).ready(function () {

        $("#formsearch").submit(function(event) {
            event.preventDefault();
            $("#search").attr('disabled', true);
            $("#search").text('Loading...');
            $("#loading").html('<center><img src="<?php echo base_url(); ?>assets/images/loading.gif" /></center>');
            var data = $("#formsearch").serialize();
            $.ajax({
                url: 'SentimentAnalysis/cek_kata_single',
                type: 'POST',
                dataType: 'json',
                data: data
            })
                .done(function(resp) {
                    if (resp) {
                        console.info(resp);
                        var html = '<table border="2" class="table table-striped responsive-utilities jambo_table"><tr><td colspan="3">Metode Nai\'ve Bayes</td></tr><tr><td>Kata</td><td>Index</td><td>Setiment</td></tr>';
                        for(i=0;i<resp.naivebayes.data.length;i++){
                            html+= '<tr><td>'+resp.naivebayes.data[i].kata+'</td><td>'+resp.naivebayes.data[i].index+'</td><td>'+resp.naivebayes.data[i].sentiment+'</td></tr>';
                        }
                        html+= '<tr><td colspan="2">Kesimpulan</td><td>'+resp.naivebayes.kesimpulan+'</td></tr>';
                        html+= '</table>';
                        $("#contentsummary").html(html);
                    }
                    console.log("success");
                })
                .fail(function() {
                    console.log("error");
                })
                .always(function() {
                    $("#loading").html('');
                    $("#search").attr('disabled', false);
                    $("#search").text('Cek');
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