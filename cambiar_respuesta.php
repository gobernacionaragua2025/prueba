<?php
session_start();
include('includes/config.php');

if(strlen($_SESSION['alogin']) == "") {   
    header("Location: index.php"); 
} else {
    $msg = "";
    $error = "";
    $id = $_GET['id']; // Obtener el ID del registro

    // Consultar el registro para editar
    $sql = "SELECT * FROM registro_correspondencia WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    // Actualizar la respuesta
    if(isset($_POST['update'])) {
        $respuesta = $_POST['respuesta'];

        // Actualizar en registro_correspondencia_entradas
        $sqlEntradas = "UPDATE registro_correspondencia_entradas SET respuesta = :respuesta WHERE id = :id";
        $queryEntradas = $dbh->prepare($sqlEntradas);
        $queryEntradas->bindParam(':respuesta', $respuesta, PDO::PARAM_STR);
        $queryEntradas->bindParam(':id', $id, PDO::PARAM_INT);
        $queryEntradas->execute();

        if($queryEntradas) {
            $msg = "Respuesta actualizada exitosamente.";
        } else {
            $error = "Error al actualizar la respuesta.";
        }
    }
}
?>

<?php include('includes/entrada.php');?>   
<div class="content-wrapper">
    <div class="main-page">
        <div class="container-fluid">
            <div class="row page-title-div">
                <div class="col-md-12 text-center">
                    <h2 class="title">Cambio de Respuesta</h2>
                </div>
            </div>
            <section class="section">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div class="panel">
                                <div class="panel-heading">
                                    <div class="panel-title">
                                        <h5>Cambiar Respuesta</h5>
                                    </div>
                                </div>
                                <?php if($msg) { ?>
                                    <div class="alert alert-success" role="alert">
                                        <strong>EXITOSO!</strong> <?php echo htmlentities($msg); ?>
                                    </div>
                                <?php } else if($error) { ?>
                                    <div class="alert alert-danger" role="alert">
                                        <strong>ERROR!</strong> <?php echo htmlentities($error); ?>
                                    </div>
                                <?php } ?>
                                <div class="panel-body">
                                    <form method="post">
                                        <div class="form-group">
                                            <label for="respuesta">Nueva Respuesta</label>
                                            <select name="respuesta" class="form-control" required>
                                                <option value="" disabled selected>Seleccione...</option>
                                                <option value="Analizar" style="background-color: lightblue;">Analizar</option>
                                                <option value="Archivar" style="background-color: lightgreen;">Archivar</option>
                                                <option value="Asistir" style="background-color: lightyellow;">Asistir</option>
                                                <option value="Coordinar" style="background-color: lightcoral;">Coordinar</option>
                                                <option value="Destruir" style="background-color: lightpink;">Destruir</option>
                                                <option value="Elaborar Memorándum" style="background-color: lightgray;">Elaborar Memorándum</option>
                                                <option value="Elaborar Oficio" style="background-color: lightcyan;">Elaborar Oficio</option>
                                                <option value="Elaborar Punto de Cuenta" style="background-color: lightgoldenrodyellow;">Elaborar Punto de Cuenta</option>
                                                <option value="Informar" style="background-color: lightseagreen;">Informar</option>
                                                <option value="Inspeccionar" style="background-color: lightsteelblue;">Inspeccionar</option>
                                                <option value="Nota Informativa" style="background-color: lavender;">Nota Informativa</option>
                                                <option value="Procesar" style="background-color: lemonchiffon;">Procesar</option>
                                                <option value="Tramitar" style="background-color: lightsalmon;">Tramitar</option>
                                                <option value="Urgente" style="background-color: rgb(229, 255, 0);">Urgente</option>
                                                <option value="revisar" style="background-color: rgba(97, 99, 206, 0.8);">Por Revisar</option>
                                                <option value="procesado" style="background-color: rgb(0, 255, 170);">Procesado</option>
                                            </select>
                                        </div>
                                        <button type="submit" name="update" class="btn btn-primary">Actualizar Respuesta</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Tabla de Correspondencia -->
            <section class="section">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel">
                                <div class="panel-heading">
                                    <div class="panel-title">
                                        <h5>Registros de Correspondencia</h5>
                                    </div>
                                </div>
                                <div class="panel-body p-20">
                                    <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>N °</th>
                                                <th>Remitente</th>
                                                <th>Cargo</th>
                                                <th>Departamento</th>
                                                <th>Descripción</th>
                                                <th>Asunto</th>
                                                <th>Archivo</th>
                                                <th>Respuesta</th>
                                                <th>Fecha&nbsp;Entrada</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            // Obtener todos los registros para la tabla de entradas
                                            $sql = "SELECT * FROM registro_correspondencia_entradas ORDER BY fecha_entrada DESC"; 
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);

                                            // Mapeo de colores para las respuestas
                                            $colorMap = [
                                                "Analizar" => "lightblue",
                                                "Archivar" => "lightgreen",
                                                "Asistir" => "lightyellow",
                                                "Coordinar" => "lightcoral",
                                                "Destruir" => "lightpink",
                                                "Elaborar Memorándum" => "lightgray",
                                                "Elaborar Oficio" => "lightcyan",
                                                "Elaborar Punto de Cuenta" => "lightgoldenrodyellow",
                                                "Informar" => "lightseagreen",
                                                "Inspeccionar" => "lightsteelblue",
                                                "Nota Informativa" => "lavender",
                                                "Procesar" => "lemonchiffon",
                                                "Tramitar" => "lightsalmon",
                                                "Urgente" => "rgb(229, 255, 0)",
                                                "revisar" => "rgba(97, 99, 206, 0.8)",
                                                "procesado" => "rgb(20, 255, 170)",
                                                    // Color para "revisar"
                                                
                                            ];

                                            if(isset($results) && count($results) > 0) {
                                                $cnt = 1;
                                                foreach($results as $result) { 
                                                    $fechaEntrada = ($result->fecha_entrada) ? date("d-m-Y H:i:s", strtotime($result->fecha_entrada)) : ''; 
                                                    $respuestaColor = isset($colorMap[$result->respuesta]) ? $colorMap[$result->respuesta] : 'white'; 
                                                    ?>
                                                    <tr>
                                                        <td><?php echo htmlentities($cnt);?></td>
                                                        <td><?php echo htmlentities($result->nombre);?></td>
                                                        <td><?php echo htmlentities($result->apellido);?></td>
                                                        <td><?php echo htmlentities($result->departamento);?></td>
                                                        <td><?php echo htmlentities($result->descripcion);?></td>
                                                        <td><?php echo htmlentities($result->asunto);?></td>
                                                        <td>
                                                            <?php if ($result->archivo) { 
                                                                $fileName = basename($result->archivo);
                                                                ?>
                                                                <a href="<?php echo htmlentities($result->archivo); ?>" download="<?php echo $fileName; ?>">
                                                                    <?php echo htmlentities($fileName); ?>
                                                                </a>
                                                            <?php } else { ?>
                                                                Sin archivo
                                                            <?php } ?>
                                                        </td>
                                                        <td style="background-color: <?php echo $respuestaColor; ?>;">
                                                            <center><?php echo htmlentities($result->respuesta); ?></center>
                                                            <center><a href="cambiar_respuesta.php?id=<?php echo $result->id; ?>" class="btn btn-info btn-sm">Cambiar Respuesta</a></center>
                                                            </td>
                                                                                                                        <td style="background-color:rgb(51, 143, 54); color: white; padding: 10px;">
                                                                                                                        <center><?php echo htmlentities(date("d-m-Y", strtotime($result->fecha_entrada))); ?></center>
                                                                                                                        <!-- Solo mostrar la fecha -->
                                                                                                                        </td>
                                                    <?php 
                                                    $cnt++; 
                                                }
                                            } else {
                                                echo "<tr><td colspan='9'>No se encontraron registros.</td></tr>"; 
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.section -->
        </div>
        <!-- /.main-page -->
    </div>
    <!-- /.content-container -->
</div>
<!-- /.content-wrapper -->

<?php include('includes/footer.php');?>
