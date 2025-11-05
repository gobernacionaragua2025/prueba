<?php
session_start();
error_reporting(0);
include('includes/config.php');
{
?>

            <!-- ========== TOP NAVBAR ========== -->
            <?php include('includes/topbar.php');?>   
       
            <div class="content-wrapper">
                <div class="content-container">


<?php include('includes/leftbar.php');?>                   


                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Información del Desarrollador</h2>
                                </div>
                                
                            </div>
                      
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
            							<li><a href="dashboard.php"><i class="fa fa-home"></i> Inicio</a></li>
            							<li>Información del Autor</li>
            							
            						</ul>
                                </div>
                               
                            </div>
                      
                        </div>
               

                        <section class="section" style="background-color: #ffffff; text-align: justify;">
                            <div class="container-fluid">

                             

                              

                                <div class="row">


                                    <div class="col-md-8 col-md-offset-2">


                                        <div class="panel" style="background-color: #f7f7f7;">
                                            <div class="panel-heading">
<br><div class="container">
        <div class="panel-title">
                                                
                             <span style="background-color: #0b2a97;
    padding: 2px 10px;    
    color: #fff;">Sistema Automatizado de Activos de Tecnologia</span><br><br>

FUNCIONES DEL SISTEMA<br><br>
GESTIÓN DE USUARIOS <br>
GESTIÓN DE ACTIVOS <br>
GESTIÓN DE ASIGNACIONES<br>
CAMBIO DE CLAVE




          <br><br><br><h5><strong></h5>DESARROLLADO POR:</strong></h5><br>
      <h5><strong></h5>WINYER HANDES 27.687.907 </strong></h5><br><br>
                                            
    <A HREF="#" TARGET="BLANK"> <IMG SRC="assets/images/PDF_file_icon.svg.PNG" WIDTH="23">DESCARGAR MANUAL DE USUARIO</A>
                                            <div class="panel-body">

                                                
<br>
                                                       

                                                    
                                               

                                              
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col-md-8 col-md-offset-2 -->
                                </div>
                                <!-- /.row -->

                               
                               

                            </div>
                            <!-- /.container-fluid -->
                        </section>
                        <!-- /.section -->

                    </div>
                    <!-- /.main-page -->

                </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->
    <?php include('includes/footer.php');?>

<!--  Author Name: Author winyer handes. 
 for any PHP, Codeignitor, Laravel OR Python work contact me at winyerhandes10@gmail.com 
  -->  

<?php  } ?>
