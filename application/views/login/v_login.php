<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->


<html>
<head>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="<?php echo base_url().'assets/css/login.css'?>" rel="stylesheet">
    <link href="<?php echo base_url().'assets/css/style.css'?>" rel="stylesheet">
    <link href="<?php echo base_url().'assets/repair/css/linearicons.css'?>" rel="stylesheet">
    <link href="<?php echo base_url().'assets/repair/css/font-awesome.min.css'?>" rel="stylesheet">
    <link href="<?php echo base_url().'assets/repair/css/bootstrap.css'?>" rel="stylesheet">
    <link href="<?php echo base_url().'assets/repair/css/magnific-popup.css'?>" rel="stylesheet">
    <link href="<?php echo base_url().'assets/repair/css/nice-select.css'?>" rel="stylesheet">
    <link href="<?php echo base_url().'assets/repair/css/animate.min.css'?>" rel="stylesheet">
    <link href="<?php echo base_url().'assets/repair/css/owl.carousel.css'?>" rel="stylesheet">
    <link href="<?php echo base_url().'assets/repair/css/main.css'?>" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mada:300,400,600,700,800">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->
</head>
<body id="LoginForm">
<div class="container">
    <div class="login-form">
        <div class="main-div">
            <div class="panel mt-20">
                <h2>Login</h2>
                <p>Masukkan username and password</p>
            </div>
            <form id="Login" action="<?php echo base_url().'index.php/login/auth'?>" method="post">
                <?php echo $this->session->flashdata('msg');?>
                <div class="form-group">
                    <input type="text" id="username" name="username" class="form-control" placeholder="Username" required autofocus>
                </div>
                <div class="form-group">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <button type="submit" class="genric-btn danger radius">Login</button>
            </form>
        </div>
    </div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="<?php echo base_url().'assets/js/jquery.js'?>"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?php echo base_url().'assets/js/bootstrap.min.js'?>"></script>

</body>
</html>