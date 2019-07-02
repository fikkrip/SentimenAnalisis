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
            <h5 align="center" style="padding-top: 20px; padding-bottom: 20px;color: white">SISTEM REKOMENDASI</h5>
        </div>
        <div class="container">
            <form id="formsearch" name="rekomendasiForm" data-parsley-validate action="rekomendasi/tampilHasilRekomendasi" onsubmit="return validateForm()" method="POST" class="form-horizontal">
                <div class="form-group col-md-12">
                    <label for="harga_min">MASUKKAN HARGA MINIMAL:</label>
                    <input type="number" class="form-control" min="0" value="0" name="harga_min" id="harga_min" required>
                </div>
                <div class="form-group col-md-12">
                    <label for="harga_max">MASUKKAN HARGA MAKSIMAL:</label>
                    <input type="number" class="form-control" min="0" value="5000000" name="harga_max" id="harga_max" required>
                </div>
                <div class="form-group col-md-12" style="margin-top: 50px; margin-bottom: 50px">
                    <h5 align="center">MASUKKAN PROSENTASE PRIORITAS</h5>
                    <h6 align="center" style="color: red">*jumlah nilai prosentase harus 100%</h6>
                    <hr style="border: 0; border-top: 3px double #8c8c8c; ">
                </div>
                <div class="form-row col-md-12">
                    <div class="form-group col-md-6">
                        <label class="control-label" for="p_harga">HARGA:</label>
                        <input type="number" class="form-control" min="1" max="94" value="15" name="p_harga" id="p_harga" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label" for="p_layar">LAYAR:</label>
                        <input type="number" class="form-control" min="1" max="94" value="10" name="p_layar" id="p_layar" required>
                    </div>
                </div>
                <div class="form-row col-md-12">
                    <div class="form-group col-md-6">
                        <label class="control-label" for="p_ram">RAM:</label>
                        <input type="number" class="form-control" min="1" max="94" value="15" name="p_ram" id="p_ram" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label" for="p_internal">MEMORI INTERNAL:</label>
                        <input type="number" class="form-control" min="1" max="94" value="15" name="p_internal" id="p_internal" required>
                    </div>
                </div>
                <div class="form-row col-md-12">
                    <div class="form-group col-md-6">
                        <label class="control-label" for="p_baterai">BATERAI:</label>
                        <input type="number" class="form-control" min="1" max="94" value="15" name="p_baterai" id="p_baterai" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label" for="p_kamera">KAMERA:</label>
                        <input type="number" class="form-control" min="1" max="94" value="15" name="p_kamera" id="p_kamera" required>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label class="control-label" for="p_sentimen">NILAI SENTIMEN:</label>
                    <input type="number" class="form-control" min="1" max="94" value="15" name="p_sentimen" id="p_sentimen" required>
                </div>
                <div class="form-group col-md-12">
                    <button id="search" type="submit" class="genric-btn primary radius mb-30">CARI REKOMENDASI</button>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- End banner Area -->
<?php $this->load->view('footer');?>
</body>
<script type="text/javascript">
    function validateForm() {
        var harga = parseInt(document.forms["rekomendasiForm"]["p_harga"].value);
        var layar = parseInt(document.forms["rekomendasiForm"]["p_layar"].value);
        var memori = parseInt(document.forms["rekomendasiForm"]["p_internal"].value);
        var ram = parseInt(document.forms["rekomendasiForm"]["p_ram"].value);
        var baterai = parseInt(document.forms["rekomendasiForm"]["p_baterai"].value);
        var kamera = parseInt(document.forms["rekomendasiForm"]["p_kamera"].value);
        var sentimen = parseInt(document.forms["rekomendasiForm"]["p_sentimen"].value);
        var harga_min = parseInt(document.forms["rekomendasiForm"]["harga_min"].value);
        var harga_max = parseInt(document.forms["rekomendasiForm"]["harga_max"].value);
        var total = harga+layar+memori+ram+baterai+kamera+sentimen;
        if (harga_max < harga_min) {
            alert("Jumlah harga maksimal harus lebih besar dari harga minimal");
            return false;
        }else if(total !== 100){
            alert("Jumlah prosentase harus 100%");
            return false;
        }
    }
</script>
</html>