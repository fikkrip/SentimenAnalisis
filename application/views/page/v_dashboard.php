<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <?php $this->load->view('head');?>
    <style type="text/css">
        p {
            text-indent: 20px;
            line-height: 200%;
            font-family: Georgia;
        }
    </style>
</head>
<body style="background-color: #f5f5f5">
<?php $this->load->view('header');?>
<!-- start banner Area -->
<section id="home">
    <div class="container mt-30 mb-30" style="background-color: #ffffff;border: 1px solid transparent;border-color: #0277bd;padding-right: 0px;padding-left: 0px;">
        <div class="mb-50" style="background-color: #0277bd">
            <h5 align="center" style="padding-top: 20px; padding-bottom: 20px;color: white">LATAR BELAKANG</h5>
        </div>
        <div class="container">
            <p style="text-align:justify; margin-bottom: 50px">
                Smartphone menjadi salah satu produk yang terus meningkat penjualannya dalam bisnis e-commerce. Berdasarkan informasi dari kominfo, pada tahun 2018 diperkirakan jumlah pengguna aktif smartphone di Indonesia mencapai lebih dari 100 juta orang [1]. Banyaknya merek dan tipe smartphone dengan berbagai macam spesifikasi dan harga dapat menyulitkan konsumen dalam memilih smartphone yang akan dibeli. Menurut survey yang dilakukan oleh DailySocial pada tahun 2018, klasifikasi smartphone di Indonesia dapat di bagi menjadi tiga, yaitu low-end, mid-range, dan flagship [2]. Selain aspek harga dan spesifikasi, terdapat beberapa aspek yang dapat mempengaruhi minat beli konsumen terhadap produk smartphone, seperti kepercayaan, pengalaman, kualitas produk, serta orientasi merek [3]. Konsumen biasanya melakukan pencarian terhadap rekomendasi atau review suatu produk melalui komunitas, blog, maupun forum untuk mempelajari kualitas produk yang akan dibeli [4]. Sehingga, kepercayaan konsumen terhadap produk yang akan dibeli akan bertambah. Kepercayaan diyakini memiliki pengaruh paling besar dalam meningkatkan minat beli konsumen. Untuk meningkatkan kepercayaannya, banyak calon pembeli yang memanfaatkan website forum diskusi produk yang memiliki fitur review untuk mengetahui seberapa baik kualitas dari produk tersebut. Fitur review ini terbukti memberikan pengaruh terhadap minat beli konsumen [5]. Selain bermanfaat untuk konsumen, review produk juga membantu produsen atau desainer produk agar lebih memahami kebutuhan dan keinginan dari konsumen [6].
            </p>
        </div>
    </div>

    <div class="container mt-30 mb-30" style="background-color: #ffffff;border: 1px solid transparent;border-color: #0277bd;padding-right: 0px;padding-left: 0px;">
        <div class="mb-50" style="background-color: #0277bd">
            <h5 align="center" style="padding-top: 20px; padding-bottom: 20px;color: white">TUJUAN</h5>
        </div>
        <div class="container">
            <p style="text-align:justify; margin-bottom: 50px">
                Pada penelitian ini, dibuat sistem rekomendasi berbasis web untuk memberikan rekomendasi pilihan produk untuk masyarakat dengan cara opinion mining berbasis sentiment analysis yang akan di intergrasikan dengan decision support systems.
                Sistem rekomendasi ini memanfaatkan review yang ada pada website dan kemudian melakukan mining pada review tersebut dengan menganalisa sentimen pada review produk tersebut, yang selanjutnya nilai sentimen tersebut dijadikan sebagai salah satu atribut/kriteria dalam perhitungan SPK. Sehingga menghasilkan perankingan rekomendasi produk yang berguna bagi calon pembeli dan penjual produk smartphone.
                Aplikasi ini ditujukan untuk masyarakat sebagai calon pembeli agar dapat dengan mudah mengetahui smartphone apa saja yang sesuai dengan budget dan keinginan mereka.
            </p>
        </div>
    </div>
</section>
<!-- End banner Area -->
<?php $this->load->view('footer');?>
</body>
</html>