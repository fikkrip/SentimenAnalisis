<?php
/**
 * Created by PhpStorm.
 * User: Halimyr8
 * Date: 8/13/2018
 * Time: 11:30 AM
 */
?>
<header id="home">
    <div style="background: #ffffff; padding-bottom: 26px">
    <div class="container main-menu">
        <div class="row align-items-center justify-content-between d-flex">
            <div id="logo">
                <h2>Final Project</h2>
            </div>
            <nav id="nav-menu-container">
                <ul class="nav-menu">
                    <li><a href="<?php echo base_url().'page'?>">DASHBOARD</a></li>
                    <?php if($this->session->userdata('masuk') == TRUE):?>
                        <li class="active"><a href="<?php echo base_url().'sentimentproduk'?>">SENTIMENT SMARTPHONE</a></li>
                        <li class="menu-has-children">
                            <a>MASTER</a>
                            <ul>
                                <li class="active"><a href="<?php echo base_url().'smartphone'?>">DATA SMARTPHONE</a></li>
                            </ul>
                        </li>
                        <li class="menu-has-children">
                            <a>UJI COBA</a>
                            <ul>
                                <li class="active"><a href="<?php echo base_url().'textmining'?>">TEXT MINING</a></li>
                                <li class="active"><a href="<?php echo base_url().'sentimentanalysis'?>">SENTIMENT ANALYSIS</a></li>
                            </ul>
                        </li>
                        <li class="active"><a href="<?php echo base_url().'login/logout'?>">Sign Out<b> [
                                    <?php
                                        $kalimat = explode(" ", $this->session->userdata('ses_nama'));
                                        if(sizeof($kalimat) > 1){
                                            echo $kalimat[0].' '.$kalimat[1];
                                        }else{
                                            echo $kalimat[0];
                                        }
                                    ?> ] </b> </a></li>
                    <?php else:?>
                        <li class="active"><a href="<?php echo base_url().'login'?>">LOGIN ADMIN</a></li>
                    <?php endif; ?>
                </ul>
            </nav><!-- #nav-menu-container -->
        </div>
    </div>
    </div>
</header><!-- #header -->
