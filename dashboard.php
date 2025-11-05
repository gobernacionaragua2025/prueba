<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Configurar la zona horaria a Venezuela (GMT-4)
date_default_timezone_set('America/Caracas');

if(strlen($_SESSION['alogin']) == "") {   
    header("Location: index.php"); 
    exit();
}
?>

<!-- ========== TOP NAVBAR ========== -->
<?php include('includes/entrada.php');?> 

<!-- ========== WRAPPER FOR MAIN CONTENT ========== -->
<div class="content-wrapper">
    <div class="content-container">
        <div class="main-page">
            <div class="container-fluid">
                <section class="section">
                    <div class="container-fluid">
                        <div class="row text-center">
                            <div class="col-md-6">
                                <div class="card" style="margin: 20px; padding: 60px; cursor: pointer; background-color: rgb(11, 175, 19); color: white; border-radius: 10px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);" onclick="window.location.href='create-entrada.php';">
                                    <h2 style="font-size: 24px;">Registrar Entrada</h2>
                                    <p style="font-size: 18px;">Haz clic aquí para registrar una nueva entrada.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card" style="margin: 20px; padding: 60px; cursor: pointer; background-color: rgba(216, 26, 26, 0.9); color: white; border-radius: 10px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);" onclick="window.location.href='create-salida.php';">
                                    <h2 style="font-size: 24px;">Registrar Salida</h2>
                                    <p style="font-size: 18px;">Haz clic aquí para registrar una nueva salida.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
