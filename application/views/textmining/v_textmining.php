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
            <h5 align="center" style="padding-top: 20px; padding-bottom: 20px;color: white">UJI COBA TEXT MINING</h5>
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
            <h5 align="center" style="padding-top: 20px; padding-bottom: 20px;color: white">HASIL TEXT MINING</h5>
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
                url: 'textmining/text_processing_proses',
                type: 'POST',
                dataType: 'json',
                data: data
            })
                .done(function(resp) {
                    if (resp) {
                        console.info(resp);
                        var html='';
                        var bataskolom = 4;
                        var kolomjalan = 0;

                        //buat tabel tokenizing
                        html += '<hr><b>Tokenizing</b><hr>';
                        var tokenizing = '<table class="table table-striped responsive-utilities jambo_table">';
                        for(i=0;i<resp.tokenizing.length;i++){
                            if (kolomjalan == 0) {tokenizing+='<tr>'}
                            tokenizing += '<td>'+resp.tokenizing[i]+'</td>';
                            if (kolomjalan == 4) {tokenizing+='</tr>';kolomjalan = 0;}else{
                                kolomjalan++;}
                        }
                        tokenizing+= '</table>';
                        html+=tokenizing;
                        //end tabel tokenizing

                        //buat tabel stopword
                        kolomjalan = 0;
                        html += '<hr><b>Stopword</b><hr>';
                        var stopword = '<table class="table table-striped responsive-utilities jambo_table">';
                        for(i=0;i<resp.stopword.length;i++){
                            if (kolomjalan == 0) {stopword+='<tr>'}
                            stopword += '<td>'+resp.stopword[i]+'</td>';
                            if (kolomjalan == 4) {stopword+='</tr>';kolomjalan = 0;}else{
                                kolomjalan++;}
                        }
                        stopword+= '</table>';
                        html+=stopword;
                        //end tabel stopword

                        //buat tabel stemming
                        html += '<hr><b>Stemming</b><hr>';
                        var stemming = '<table class="table table-striped responsive-utilities jambo_table"><tr><td>Kata Awal</td><td>Kata Akhir</td></tr>';
                        for(i=0;i<resp.stemming.length;i++){
                            stemming += '<tr><td>'+resp.stemming[i].kataawal+'</td><td>'+resp.stemming[i].kataakhir+'</td></tr>';
                        }
                        stemming+= '</table>';
                        html+=stemming;
                        //end tabel stemming

                        //buat table unknow
                        kolomjalan = 0;
                        html += '<hr><b>Unknown Word</b><hr>';
                        var unknownword = '<table class="table table-striped responsive-utilities jambo_table">';
                        for(i=0;i<resp.unknown.length;i++){
                            if (kolomjalan == 0) {unknownword+='<tr>'}
                            unknownword += '<td>'+resp.unknown[i]+'</td>';
                            if (kolomjalan == 4) {unknownword+='</tr>';kolomjalan = 0;}else{
                                kolomjalan++;}
                        }
                        unknownword+= '</table>';
                        html+=unknownword;
                        //end tabel unknown

                        //buat table kata akhir
                        kolomjalan = 0;
                        html += '<hr><b>Final Word</b><hr>';
                        var finalword = '<table class="table table-striped responsive-utilities jambo_table">';
                        for(i=0;i<resp.lastword.length;i++){
                            if (kolomjalan == 0) {finalword+='<tr>'}
                            finalword += '<td>'+resp.lastword[i]+'</td>';
                            if (kolomjalan == 4) {finalword+='</tr>';kolomjalan = 0;}else{
                                kolomjalan++;}
                        }
                        finalword+= '</table>';
                        html+=finalword;
                        //end tabel kata akhir
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