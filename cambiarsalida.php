<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Configurar la zona horaria a Venezuela (GMT-4)
date_default_timezone_set('America/Caracas');

if(strlen($_SESSION['alogin']) == "") {   
    header("Location: index.php"); 
    exit();
} else {
    $msg = "";
    $error = "";
    $remitente = "";
    $cargo = "";
    $departamento = ""; 
    $descripcion = ""; 
    $asunto = ""; 
    $tipo = "salida"; // Cambiar tipo a salida
    $respuesta = ""; 
    $fechaSalida = date('Y-m-d H:i:s'); // Fecha y hora actual por defecto
    $filePath = ""; 

    // Registro de correspondencia
    if(isset($_POST['submit'])) {
        $remitente = $_POST['remitente'];
        $cargo = $_POST['cargo'];
        $departamento = $_POST['departamento']; 
        $descripcion = $_POST['descripcion']; 
        $asunto = $_POST['asunto']; 
        $tipo = $_POST['tipo']; 
        $respuesta = $_POST['respuesta']; 
        $fechaSalida = $_POST['fecha_salida']; // Obtener la fecha de salida del formulario

        // Manejo de archivo
        if(isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
            $filePath = 'uploads/' . basename($_FILES['archivo']['name']);
            move_uploaded_file($_FILES['archivo']['tmp_name'], $filePath);
        }

        // Insertar nuevo registro en la tabla de salidas
        $sql = "INSERT INTO registro_correspondencia_salidas(nombre, apellido, departamento, descripcion, asunto, tipo, respuesta, fecha_salida, archivo) 
                VALUES(:remitente, :cargo, :departamento, :descripcion, :asunto, :tipo, :respuesta, :fecha_salida, :archivo)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':remitente', $remitente, PDO::PARAM_STR);
        $query->bindParam(':cargo', $cargo, PDO::PARAM_STR);
        $query->bindParam(':departamento', $departamento, PDO::PARAM_STR); 
        $query->bindParam(':descripcion', $descripcion, PDO::PARAM_STR); 
        $query->bindParam(':asunto', $asunto, PDO::PARAM_STR); 
        $query->bindParam(':tipo', $tipo, PDO::PARAM_STR); 
        $query->bindParam(':respuesta', $respuesta, PDO::PARAM_STR); 
        $query->bindParam(':fecha_salida', $fechaSalida, PDO::PARAM_STR); // Bind de la fecha y hora
        $query->bindParam(':archivo', $filePath, PDO::PARAM_STR); 
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();

        if($lastInsertId) {
            $msg = "Su registro ha sido exitoso: " . htmlentities($remitente);
            // Vaciar los campos del formulario
            $remitente = "";
            $cargo = "";
            $departamento = "";
            $descripcion = "";
            $asunto = "";
            $respuesta = "";
            $fechaSalida = date('Y-m-d H:i:s'); // Resetear fecha a ahora
        } else {
            $error = "Ocurrió un error. Intente nuevamente.";
        }
    }

    // Obtener todos los registros
    $sql = "SELECT * FROM registro_correspondencia_salidas ORDER BY fecha_salida DESC";
    $query = $dbh->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
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
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel">
                                    <div class="panel-heading">
                                        <div class="panel-title">
                                            <h2 class="text-center">Registro Correspondencia Salida</h2>
                                        </div>
                                    </div>

                                    <div class="panel-body">
                                        <?php if($msg) { ?>
                                            <div class="alert alert-success left-icon-alert" role="alert" id="success-message">
                                                <strong>EXITOSO!</strong> <?php echo htmlentities($msg); ?>
                                            </div>
                                            <script>
                                                setTimeout(function() {
                                                    document.getElementById('success-message').style.display = 'none';
                                                }, 5000);
                                            </script>
                                        <?php } else if($error) { ?>
                                            <div class="alert alert-danger left-icon-alert" role="alert">
                                                <strong>ERROR!</strong> <?php echo htmlentities($error); ?>
                                            </div>
                                        <?php } ?>
                                        <form method="post" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="remitente" class="control-label">Remitente</label>
                                                        <input type="text" name="remitente" class="form-control" value="<?php echo htmlentities($remitente); ?>" required="required">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="cargo" class="control-label">Cargo</label>
                                                        <input type="text" name="cargo" class="form-control" value="<?php echo htmlentities($cargo); ?>" required="required">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="asunto" class="control-label">Asunto</label>
                                                        <input type="text" name="asunto" class="form-control" value="<?php echo htmlentities($asunto); ?>" required="required">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="departamento" class="control-label">Departamento</label>
                                                        <select name="departamento" class="form-control" required>
                                                            <option value="" disabled <?php echo ($departamento == '') ? 'selected' : ''; ?>>Seleccione...</option>
                                                            <option value="DIRECCIÓN GENERAL DE ADMINISTRACIÓN">DIRECCIÓN GENERAL DE ADMINISTRACIÓN</option>
                                                            <option value="DIRECCIÓN GENERAL DE CONTABILIDAD">DIRECCIÓN GENERAL DE CONTABILIDAD</option>
                                                            <option value="DIRECCIÓN GENERAL DE INFRAESTRUCTURA INTERNA Y TRANSPORTE">DIRECCIÓN GENERAL DE INFRAESTRUCTURA INTERNA Y TRANSPORTE</option>
                                                            <option value="DIRECCIÓN GENERAL DE INSPECCIÓN Y CONTROL DE PROYECTOS">DIRECCIÓN GENERAL DE INSPECCIÓN Y CONTROL DE PROYECTOS</option>
                                                            <option value="DIRECCIÓN GENERAL DE PLANIFICACIÓN, PRESUPUESTO Y OPTIMIZACIÓN ORGANIZACIONAL">DIRECCIÓN GENERAL DE PLANIFICACIÓN, PRESUPUESTO Y OPTIMIZACIÓN ORGANIZACIONAL</option>
                                                            <option value="DIRECCIÓN GENERAL DE SEGURIDAD INTEGRAL">DIRECCIÓN GENERAL DE SEGURIDAD INTEGRAL</option>
                                                            <option value="DIRECCIÓN GENERAL DE TALENTO HUMANO">DIRECCIÓN GENERAL DE TALENTO HUMANO</option>
                                                            <option value="DIRECCIÓN GENERAL DE TECNOLOGÍA Y SISTEMATIZACIÓN DE PROCESOS">DIRECCIÓN GENERAL DE TECNOLOGÍA Y SISTEMATIZACIÓN DE PROCESOS</option>
                                                            <option value="DIRECCIÓN GENERAL DE TESORERÍA Y FINANZAS">DIRECCIÓN GENERAL DE TESORERÍA Y FINANZAS</option>
                                                            <option value="OFICINA DE ATENCION AL CIUDADANO">OFICINA DE ATENCION AL CIUDADANO</option>
                                                            <option value="SEC. SECT. DEL P.P. EL AMBIENTE, ORDENAMIENTO TERRITORIAL Y MINAS">SEC. SECT. DEL P.P. EL AMBIENTE, ORDENAMIENTO TERRITORIAL Y MINAS</option>
                                                            <option value="SEC. SECT. DEL P.P. EL DESARROLLO AGROALIMENTARIO">SEC. SECT. DEL P.P. EL DESARROLLO AGROALIMENTARIO</option>
                                                            <option value="SEC. SECT. DEL P.P. EL TRANSPORTE">SEC. SECT. DEL P.P. EL TRANSPORTE</option>
                                                            <option value="SEC. SECT. DEL P.P. LA INFRAESTRUCTURA, OBRAS PÚBLICAS Y PROYECTOS">SEC. SECT. DEL P.P. LA INFRAESTRUCTURA, OBRAS PÚBLICAS Y PROYECTOS</option>
                                                            <option value="SEC. SECT. DEL P.P. LA OPTIMIZACIÓN DE LOS SERVICIOS PÚBLICOS">SEC. SECT. DEL P.P. LA OPTIMIZACIÓN DE LOS SERVICIOS PÚBLICOS</option>
                                                            <option value="SEC. SECT. DEL P.P. DE POLÍTICAS SOCIALES">SEC. SECT. DEL P.P. DE POLÍTICAS SOCIALES</option>
                                                            <option value="SEC. SECT. DEL P.P. EL DESARROLLO ECONÓMICO">SEC. SECT. DEL P.P. EL DESARROLLO ECONÓMICO</option>
                                                            <option value="SEC. SECT. DEL P.P. PARA LA EDUCACIÓN">SEC. SECT. DEL P.P. PARA LA EDUCACIÓN</option>
                                                            <option value="SEC. SECT. DEL P.P. PARA LA PREVENCION Y SEGURIDAD CIUDADANA">SEC. SECT. DEL P.P. PARA LA PREVENCION Y SEGURIDAD CIUDADANA</option>
                                                            <option value="SECRETARÍA DEL DESPACHO">SECRETARÍA DEL DESPACHO</option>
                                                            <option value="SECRETARÍA GENERAL DE GOBIERNO">SECRETARÍA GENERAL DE GOBIERNO</option>
                                                            <option value="SUPRARAGUA">SUPRARAGUA</option>
                                                            <option value="UNIDAD DE AUDITORÍA INTERNA">UNIDAD DE AUDITORÍA INTERNA</option>
                                                            <option value="1x10 DEL BUEN GOBIERNO">1x10 DEL BUEN GOBIERNO</option>
                                                            <option value="RESTAURANTE">RESTAURANTE</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="descripcion" class="control-label">Descripción</label>
                                                        <input type="text" name="descripcion" class="form-control" value="<?php echo htmlentities($descripcion); ?>" required="required">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="archivo" class="control-label">Cargar Archivo (PDF, Excel, Imagen, Word o PowerPoint)</label>
                                                        <input type="file" name="archivo" class="form-control" accept=".pdf,.xls,.xlsx,.jpg,.jpeg,.png,.doc,.docx,.ppt,.pptx" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="respuesta" class="control-label">Respuesta</label>
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
                                                            <option value="Urgente" style="background-color:rgb(229, 255, 0);">Urgente</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="tipo" class="control-label">Tipo</label>
                                                        <select name="tipo" class="form-control" required>
                                                            <option value="salida" <?php echo ($tipo == 'salida') ? 'selected' : ''; ?>>Salida</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="fecha_salida" class="control-label">Fecha de Salida</label>
                                                        <input type="datetime-local" name="fecha_salida" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" name="submit" class="btn btn-primary">Guardar</button>
                                                <button type="reset" name="reset" class="btn btn-danger">Limpiar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Tabla de Correspondencia Salidas -->
                <section class="section">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel">
                                    <div class="panel-heading">
                                        <div class="panel-title">
                                            <h2 class="text-center">Historial de Correspondencia Salida</h2>
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
                                                    <th>correlativo</th>
                                                    <th>Archivo</th>
                                                    <th>Respuesta</th>
                                                    <th>Cambiar Respuesta</th>
                                                    <th>Fecha de Salida</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
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
                                                    "Urgente" => "rgb(229, 255, 0)"
                                                ];

                                                if(isset($results) && count($results) > 0) {
                                                    $cnt = 1;
                                                    foreach($results as $result) { 
                                                        $fechaSalida = ($result->fecha_salida) ? date("d-m-Y H:i:s", strtotime($result->fecha_salida)) : ''; 
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
                                                            </td>
                                                            <td>
                                                                <center>
                                                                    <a href="cambiar_respuesta.php?id=<?php echo $result->id; ?>" class="btn btn-info btn-sm">Cambiar Respuesta</a>
                                                                </center>
                                                            </td>
                                                            <td style="background-color:rgb(51, 143, 54); color: white; padding: 10px;">
                                                            <center><?php echo htmlentities(date("d-m-Y", strtotime($fechaSalida))); ?></center> 
                                                            <!-- Solo mostrar la fecha -->
</td>

                                                        </tr>
                                                    <?php 
                                                        $cnt++; 
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='10'>No se encontraron registros.</td></tr>"; 
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

<script>
$(document).ready(function() {
    // Destruir la tabla si ya está inicializada
    if ($.fn.DataTable.isDataTable('#example')) {
        $('#example').DataTable().destroy();
    }

    // Inicializar DataTable
    $('#example').DataTable({
        language: {
            "lengthMenu": "Mostrar _MENU_ entradas",
            "zeroRecords": "No se encontraron registros",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
            "infoEmpty": "No hay entradas disponibles",
            "infoFiltered": "(filtrado de _MAX_ entradas totales)",
            "search": "Buscar:",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        }
    });
});
</script>

<?php include('includes/footer.php'); ?>

