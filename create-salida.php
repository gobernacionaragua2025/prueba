<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Configurar la zona horaria a Venezuela (GMT-4)
date_default_timezone_set('America/Caracas');

if (strlen($_SESSION['alogin']) == "") {   
    header("Location: index.php"); 
    exit();
} else {
    $msg = "";
    $error = "";
    $departamento = ""; 
    $descripcion = ""; 
    $destinatario = ""; 
    $correlativo = ""; 
    $filePath = ""; 
    $fecha_salida = date('Y-m-d'); // Inicializar la variable de fecha con la fecha actual

    // Registro de correspondencia
    if (isset($_POST['submit'])) {
        $departamento = $_POST['departamento']; 
        $descripcion = $_POST['descripcion']; 
        $destinatario = $_POST['destinatario']; 
        $correlativo = $_POST['correlativo']; 
        $fecha_salida = $_POST['fecha_salida']; // Obtener la fecha del formulario

        // Manejo de archivo
        if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
            $filePath = 'uploads/' . basename($_FILES['archivo']['name']);
            move_uploaded_file($_FILES['archivo']['tmp_name'], $filePath);
        }

        // Insertar nuevo registro en la tabla de salidas
        $sql = "INSERT INTO registro_correspondencia_salidas(departamento, descripcion, destinatario, correlativo, fecha_salida, archivo) 
                VALUES(:departamento, :descripcion, :destinatario, :correlativo, :fecha_salida, :archivo)";
        
        $query = $dbh->prepare($sql);
        $query->bindParam(':departamento', $departamento, PDO::PARAM_STR); 
        $query->bindParam(':descripcion', $descripcion, PDO::PARAM_STR); 
        $query->bindParam(':destinatario', $destinatario, PDO::PARAM_STR); 
        $query->bindParam(':correlativo', $correlativo, PDO::PARAM_STR); 
        $query->bindParam(':fecha_salida', $fecha_salida, PDO::PARAM_STR); // Usar la fecha del formulario
        $query->bindParam(':archivo', $filePath, PDO::PARAM_STR); 

        try {
            $query->execute(); // Ejecutar la consulta
            $lastInsertId = $dbh->lastInsertId();

            if ($lastInsertId) {
                $msg = "Su registro ha sido exitoso.";
                // Vaciar los campos del formulario
                $departamento = "";
                $descripcion = "";
                $destinatario = ""; 
                $correlativo = ""; 
                $fecha_salida = date('Y-m-d'); // Resetear fecha a la actual
            }
        } catch (PDOException $e) {
            $error = "Error al insertar: " . $e->getMessage();
        }
    }

    // Filtrar registros por fecha si se seleccionan fechas
    $fechaDesde = isset($_GET['fechaDesde']) ? $_GET['fechaDesde'] : '';
    $fechaHasta = isset($_GET['fechaHasta']) ? $_GET['fechaHasta'] : '';

    if ($fechaDesde && $fechaHasta) {
        $sql = "SELECT * FROM registro_correspondencia_salidas WHERE fecha_salida BETWEEN :fechaDesde AND :fechaHasta ORDER BY fecha_salida DESC"; 
        $query = $dbh->prepare($sql);
        $query->bindParam(':fechaDesde', $fechaDesde);
        $query->bindParam(':fechaHasta', $fechaHasta);
    } else {
        // Obtener todos los registros para la tabla de salidas
        $sql = "SELECT * FROM registro_correspondencia_salidas ORDER BY fecha_salida DESC"; 
        $query = $dbh->prepare($sql);
    }
    
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
                                        <?php if ($msg) { ?>
                                            <div class="alert alert-success left-icon-alert" role="alert" id="success-message">
                                                <strong>EXITOSO!</strong> <?php echo htmlentities($msg); ?>
                                            </div>
                                            <script>
                                                setTimeout(function() {
                                                    document.getElementById('success-message').style.display = 'none';
                                                }, 5000);
                                            </script>
                                        <?php } else if ($error) { ?>
                                            <div class="alert alert-danger left-icon-alert" role="alert">
                                                <strong>ERROR!</strong> <?php echo htmlentities($error); ?>
                                            </div>
                                        <?php } ?>
                                        <form method="post" enctype="multipart/form-data">
                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    <label for="destinatario" class="control-label">Destinatario</label>
                                                    <input type="text" name="destinatario" class="form-control" value="<?php echo htmlentities($destinatario); ?>" required="required">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="correlativo" class="control-label">Correlativo</label>
                                                    <input type="text" name="correlativo" class="form-control" value="<?php echo htmlentities($correlativo); ?>" required="required">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    <label for="departamento" class="control-label">Departamento</label>
                                                    <select name="departamento" class="form-control" required>
                                                        <option value="" disabled <?php echo ($departamento == '') ? 'selected' : ''; ?>>Seleccione...</option>
                                                        <option value="DESPACHO DE LA GOBERNADORA">DESPACHO DE LA GOBERNADORA</option>
<option value="DIR. GRAL. DE ADMINISTRACIÓN">DIR. GRAL. DE ADMINISTRACIÓN</option>
<option value="DIR. GRAL. DE CONTABILIDAD">DIR. GRAL. DE CONTABILIDAD</option>
<option value="DIR. GRAL. DE INFRAESTRUCTURA INTERNA Y TRANSPORTE">DIR. GRAL. DE INFRAESTRUCTURA INTERNA Y TRANSPORTE</option>
<option value="DIR. GRAL. DE INSPECCIÓN Y CONTROL DE PROYECTOS">DIR. GRAL. DE INSPECCIÓN Y CONTROL DE PROYECTOS</option>
<option value="DIR. GRAL. DE PLANIFICACIÓN, PRESUPUESTO Y OPTIMIZACIÓN ORGANIZACIONAL">DIR. GRAL. DE PLANIFICACIÓN, PRESUPUESTO Y OPTIMIZACIÓN ORGANIZACIONAL</option>
<option value="DIR. GRAL. DE SEGURIDAD INTEGRAL">DIR. GRAL. DE SEGURIDAD INTEGRAL</option>
<option value="DIR. GRAL. DE TALENTO HUMANO">DIR. GRAL. DE TALENTO HUMANO</option>
<option value="DIR. GRAL. DE TECNOLOGÍA Y SISTEMATIZACIÓN DE PROCESOS">DIR. GRAL. DE TECNOLOGÍA Y SISTEMATIZACIÓN DE PROCESOS</option>
<option value="DIR. GRAL. DE TESORERÍA Y FINANZAS">DIR. GRAL. DE TESORERÍA Y FINANZAS</option>
<option value="SECRETARÍA DEL DESPACHO DE LA GOBERNADORA">SECRETARÍA DEL DESPACHO DE LA GOBERNADORA</option>
<option value="SECRETARÍA GENERAL DE GOBIERNO">SECRETARÍA GENERAL DE GOBIERNO</option>
<option value="SECRETARÍA PARA ASUNTOS DE LA MUJER E IGUALDAD DE GÉNERO">SECRETARÍA PARA ASUNTOS DE LA MUJER E IGUALDAD DE GÉNERO</option>
<option value="SECRETARÍA PARA EL AMBIENTE, ORDENAMIENTO TERRITORIAL Y MINAS">SECRETARÍA PARA EL AMBIENTE, ORDENAMIENTO TERRITORIAL Y MINAS</option>
<option value="SECRETARÍA PARA LA INFRAESTRUCTURA, OBRAS PÚBLICAS Y PROYECTOS">SECRETARÍA PARA LA INFRAESTRUCTURA, OBRAS PÚBLICAS Y PROYECTOS</option>
<option value="SECRETARÍA PARA LAS COMUNICACIONES, TECNOLOGÍA Y TELECOMUNICACIONES">SECRETARÍA PARA LAS COMUNICACIONES, TECNOLOGÍA Y TELECOMUNICACIONES</option>
<option value="SECRETARÍA PARA LA OPTIMIZACIÓN DE LOS SERVICIOS PÚBLICOS">SECRETARÍA PARA LA OPTIMIZACIÓN DE LOS SERVICIOS PÚBLICOS</option>
<option value="SECRETARÍA PARA LA PREVENCION Y SEGURIDAD CIUDADANA">SECRETARÍA PARA LA PREVENCION Y SEGURIDAD CIUDADANA</option>
<option value="SECRETARÍA PARA LA CULTURA">SECRETARÍA PARA LA CULTURA</option>
<option value="SECRETARÍA PARA LAS POLÍTICAS SOCIALES">SECRETARÍA PARA LAS POLÍTICAS SOCIALES</option>
<option value="SECRETARÍA PARA EL DESARROLLO AGROALIMENTARIO">SECRETARÍA PARA EL DESARROLLO AGROALIMENTARIO</option>
<option value="SECRETARÍA PARA EL DESARROLLO ECONÓMICO">SECRETARÍA PARA EL DESARROLLO ECONÓMICO</option>
<option value="SECRETARÍA PARA EL TRANSPORTE">SECRETARÍA PARA EL TRANSPORTE</option>
<option value="SECRETARÍA PARA EL TURISMO">SECRETARÍA PARA EL TURISMO</option>
<option value="SECRETARÍA PARA LA EDUCACIÓN">SECRETARÍA PARA LA EDUCACIÓN</option>
<option value="SECRETARÍA PARA LA JUVENTUD Y DEPORTE">SECRETARÍA PARA LA JUVENTUD Y DEPORTE</option>
<option value="4FHELADOS S.A.">4FHELADOS S.A.</option>
<option value="ALIMENTOS ARAGUA SOCIALISTA S.A. (ALAS, S.A.)">ALIMENTOS ARAGUA SOCIALISTA S.A. (ALAS, S.A.)</option>
<option value="ARAGUA GAS">ARAGUA GAS</option>
<option value="ARAMICA">ARAMICA</option>
<option value="ASODIAM">ASODIAM</option>
<option value="BIBLIOTECAS VIRTUALES">BIBLIOTECAS VIRTUALES</option>
<option value="BOMBEROS Y BOMBERAS DE ADMINISTRACION DE CARACTER CIVIL">BOMBEROS Y BOMBERAS DE ADMINISTRACION DE CARACTER CIVIL</option>
<option value="CENTRO DE CAPACITACION DEL CONSTRUCTOR POPULAR. CCCP">CENTRO DE CAPACITACION DEL CONSTRUCTOR POPULAR. CCCP</option>
<option value="CINCATESA, S.A.">CINCATESA, S.A.</option>
<option value="CONSTRUCIONES ARAGUA S.A. CONSTRUARAGUA, S.A">CONSTRUCIONES ARAGUA S.A. CONSTRUARAGUA, S.A</option>
<option value="CORPORACION DE SALUD DEL ESTADO ARAGUA CORPOSALUD">CORPORACION DE SALUD DEL ESTADO ARAGUA CORPOSALUD</option>
<option value="COSTARAGUA">COSTARAGUA</option>
<option value="FONDESA">FONDESA</option>
<option value="FONDO DE EFICIENCIA SOCIAL DEL ESTADO ARAGUA">FONDO DE EFICIENCIA SOCIAL DEL ESTADO ARAGUA</option>
<option value="FUNDACION FONDO EDITORIAL LETRAS DE ARAGUA">FUNDACION FONDO EDITORIAL LETRAS DE ARAGUA</option>
<option value="FUNDACION ORQUESTA SINFONICA DE ARAGUA (FOSA)">FUNDACION ORQUESTA SINFONICA DE ARAGUA (FOSA)</option>
<option value="FUNDACION ORQUESTAS SINFONICAS Y COROS JUVENILES E INFANTILES">FUNDACION ORQUESTAS SINFONICAS Y COROS JUVENILES E INFANTILES</option>
<option value="FUNDACION RECREACIONAL CARLOS RAUL VILLANUEVA">FUNDACION RECREACIONAL CARLOS RAUL VILLANUEVA</option>
<option value="FUNDACION REGIONAL EL NIÑO ARAGUA (FRNSA)">FUNDACION REGIONAL EL NIÑO ARAGUA (FRNSA)</option>
<option value="FUNDACION SISTEMA DE RADIO DIFUSION DE ARAGUA. ARAGUEÑA 96,5">FUNDACION SISTEMA DE RADIO DIFUSION DE ARAGUA. ARAGUEÑA 96,5</option>
<option value="FUNDACION ZOOLOGICO LAS DELICIAS">FUNDACION ZOOLOGICO LAS DELICIAS</option>
<option value="FUNDAPARQUES">FUNDAPARQUES</option>
<option value="FUNDARAGUA">FUNDARAGUA</option>
<option value="INPO">INPO</option>
<option value="INSAJUV">INSAJUV</option>
<option value="INSTITUTO DE DESARROLLO DEL SUR">INSTITUTO DE DESARROLLO DEL SUR</option>
<option value="INSTITUTO DE LA CULTURA ARAGUA (ICA)">INSTITUTO DE LA CULTURA ARAGUA (ICA)</option>
<option value="INSTITUTO DE LA MUJER DE ARAGUA (IMA)">INSTITUTO DE LA MUJER DE ARAGUA (IMA)</option>
<option value="IPPP LA VIVIENDA Y HÁBITAT DIGNO DEL ESTADO ARAGUA (VIDA)">IPPP LA VIVIENDA Y HÁBITAT DIGNO DEL ESTADO ARAGUA (VIDA)</option>
<option value="IRDA">IRDA</option>
<option value="LOTERIA DE ARAGUA-IOBPAS">LOTERIA DE ARAGUA-IOBPAS</option>
<option value="OFICINA DE ATENCIÓN AL CIUDADANO">OFICINA DE ATENCIÓN AL CIUDADANO</option>
<option value="PARQUE ACUATICO MARACAY">PARQUE ACUATICO MARACAY</option>
<option value="PROCURADURIA GENERAL DEL ESTADO BOLIVARIANO DE ARAGUA">PROCURADURIA GENERAL DEL ESTADO BOLIVARIANO DE ARAGUA</option>
<option value="PROTECCION CIVIL ARAGUA">PROTECCION CIVIL ARAGUA</option>
<option value="RECICLAJE Y PROTECCIÓN AMBIENTAL">RECICLAJE Y PROTECCIÓN AMBIENTAL</option>
<option value="SAGER">SAGER</option>
<option value="SAPANNA">SAPANNA</option>
<option value="SERVICIO AUTONOMO BOLIVARIANO AEROPUERTO TACARIGUA">SERVICIO AUTONOMO BOLIVARIANO AEROPUERTO TACARIGUA</option>
<option value="SERVICIO DE ATENCIÓN AEREA">SERVICIO DE ATENCIÓN AEREA</option>
<option value="SERVICIO DESCONCENTRADO TELECOMUNICACIONES ARAGUA">SERVICIO DESCONCENTRADO TELECOMUNICACIONES ARAGUA</option>
<option value="SERVICIO TRIBUTARIO DE ARAGUA (SETA)">SERVICIO TRIBUTARIO DE ARAGUA (SETA)</option>
<option value="ARAGUA F.C.SOCIEDAD CIVIL , CLUB DE FUTBOL Y FUTBOL SALA">ARAGUA F.C.SOCIEDAD CIVIL , CLUB DE FUTBOL Y FUTBOL SALA</option>
<option value="SUMINISTROS Y PROCURAS ARAGUA (SUPRARAGUA)">SUMINISTROS Y PROCURAS ARAGUA (SUPRARAGUA)</option>
<option value="TEATRO DE LA OPERA DE MARACAY (TOM)">TEATRO DE LA OPERA DE MARACAY (TOM)</option>
<option value="TELEARAGUA">TELEARAGUA</option>
<option value="UNIDAD DE AUDITORÍA INTERNA">UNIDAD DE AUDITORÍA INTERNA</option>
<option value="VÍAS DE ARAGUA, S.A.">VÍAS DE ARAGUA, S.A.</option>


                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="fecha_salida" class="control-label">Fecha de Salida</label>
                                                    <input type="date" name="fecha_salida" class="form-control" value="<?php echo htmlentities($fecha_salida); ?>" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    <label for="descripcion" class="control-label">Descripción</label>
                                                    <input type="text" name="descripcion" class="form-control" value="<?php echo htmlentities($descripcion); ?>" required="required">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="archivo" class="control-label">Cargar Archivo (PDF, Excel, Imagen, Word o PowerPoint)</label>
                                                    <input type="file" name="archivo" class="form-control" accept=".pdf,.xls,.xlsx,.jpg,.jpeg,.png,.doc,.docx,.ppt,.pptx" required>
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

                <!-- Tabla de Correspondencia Salida -->
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
                                        <form method="GET" action="" class="mb-3">
                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    <label for="fechaDesde">Fecha Desde:</label>
                                                    <input type="date" name="fechaDesde" id="fechaDesde" class="form-control" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="fechaHasta">Fecha Hasta:</label>
                                                    <input type="date" name="fechaHasta" id="fechaHasta" class="form-control" required>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Buscar</button>
                                            <a href="exportar_salida.php" class="btn btn-warning" style="margin-left: 10px;">Exportar a Excel</a>
                                        </form>

                                        <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>N °</th>
                                                    <th>Fecha</th>
                                                    <th>Correlativo</th>
                                                    <th>Destinatario</th>
                                                    <th>Departamento</th>
                                                    <th>Descripción</th>
                                                    <th>Archivo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                if (isset($results) && count($results) > 0) {
                                                    $cnt = 1;
                                                    foreach ($results as $result) { 
                                                        // Mostrar solo la fecha
                                                        $fechaSalida = ($result->fecha_salida) ? date("d-m-Y", strtotime($result->fecha_salida)) : ''; 
                                                        ?>
                                                        <tr>
                                                            <td><?php echo htmlentities($cnt);?></td>
                                                            <td><?php echo htmlentities($fechaSalida);?></td>
                                                            <td><?php echo htmlentities($result->correlativo);?></td>
                                                            <td><?php echo htmlentities($result->destinatario);?></td>
                                                            <td><?php echo htmlentities($result->departamento);?></td>
                                                            <td><?php echo htmlentities($result->descripcion);?></td>
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
                                                        </tr>
                                                    <?php 
                                                        $cnt++; 
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='7'>No se encontraron registros.</td></tr>"; 
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
            "lengthMenu": "Mostrar _MENU_ salidas",
            "zeroRecords": "No se encontraron registros",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ salidas",
            "infoEmpty": "No hay salidas disponibles",
            "infoFiltered": "(filtrado de _MAX_ salidas totales)",
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
