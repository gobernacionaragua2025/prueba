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
    $remitente = "";
    $cargo = "";
    $departamento = ""; 
    $descripcion = ""; 
    $asunto = ""; 
    $respuesta = ""; 
    $fechaEntrada = date('Y-m-d H:i:s'); // Fecha y hora actual por defecto
    $filePath = ""; 

    // Registro de correspondencia
    if (isset($_POST['submit'])) {
        $remitente = $_POST['remitente'];
        $cargo = $_POST['cargo'];
        $departamento = $_POST['departamento']; 
        $descripcion = $_POST['descripcion']; 
        $asunto = $_POST['asunto']; 
        $respuesta = $_POST['respuesta']; 
        $fechaEntrada = $_POST['fecha_entrada'] . ' ' . date('H:i:s'); // Obtener fecha y hora desde el formulario

        // Manejo de archivo
        if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
            $filePath = 'uploads/' . basename($_FILES['archivo']['name']);
            move_uploaded_file($_FILES['archivo']['tmp_name'], $filePath);
        }

        // Insertar nuevo registro en la tabla de entradas
        $sql = "INSERT INTO registro_correspondencia_entradas(nombre, apellido, departamento, descripcion, asunto, respuesta, fecha_entrada, archivo) 
                VALUES(:remitente, :cargo, :departamento, :descripcion, :asunto, :respuesta, :fecha_entrada, :archivo)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':remitente', $remitente, PDO::PARAM_STR);
        $query->bindParam(':cargo', $cargo, PDO::PARAM_STR);
        $query->bindParam(':departamento', $departamento, PDO::PARAM_STR); 
        $query->bindParam(':descripcion', $descripcion, PDO::PARAM_STR); 
        $query->bindParam(':asunto', $asunto, PDO::PARAM_STR); 
        $query->bindParam(':respuesta', $respuesta, PDO::PARAM_STR); 
        $query->bindParam(':fecha_entrada', $fechaEntrada, PDO::PARAM_STR); // Bind de la fecha y hora
        $query->bindParam(':archivo', $filePath, PDO::PARAM_STR); 
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();

        if ($lastInsertId) {
            $msg = "Su registro ha sido exitoso: " . htmlentities($remitente);
            // Vaciar los campos del formulario
            $remitente = "";
            $cargo = "";
            $departamento = "";
            $descripcion = "";
            $asunto = "";
            $respuesta = "";
            $fechaEntrada = date('Y-m-d H:i:s'); // Resetear fecha a ahora
        } else {
            $error = "Ocurrió un error. Intente nuevamente.";
        }
    }

    // Obtener todos los registros
    $sql = "SELECT * FROM registro_correspondencia_entradas ORDER BY fecha_entrada DESC";
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
                                            <h2 class="text-center">Registro Correspondencia entrada</h2>
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
                                            <!-- Formulario para el registro de correspondencia -->
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
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="fecha_entrada" class="control-label">Fecha de Entrada</label>
                                                        <input type="date" name="fecha_entrada" class="form-control" value="<?php echo htmlentities(date('Y-m-d')); ?>" required="required">
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

                <!-- Tabla de Correspondencia -->
                <section class="section">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel">
                                    <div class="panel-heading">
                                        <div class="panel-title">
                                            <h2 class="text-center">Historial de Registro</h2>
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
                                            <button type="button" class="btn btn-danger" id="exportMonthButton">Exportar Mes</button>
                                            <button type="button" class="btn btn-warning" id="exportAllButton">Exportar Todo</button>
                                        </form>

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
                                                    <th>Actualizar</th>
                                                    <th>Fecha&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
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
                                                    "Urgente" => "rgb(229, 255, 0)",
                                                    "revisar" => "rgba(97, 99, 206, 0.8)",
                                                    "procesado" => "rgb(0, 255, 170)",
                                                    // Color para "revisar"
                                                ];

                                                if (isset($results) && count($results) > 0) {
                                                    $cnt = 1;
                                                    foreach ($results as $result) { 
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
                                                                                                                        </td>
                                                                                                                        <td>
                                                                                                                            <center>
                                                                                                                                <a href="cambiar_respuesta.php?id=<?php echo $result->id; ?>" class="btn btn-info btn-sm">Actualizar</a>
                                                                                                                            </center>
                                                                                                                        </td>
                                                                                                                        <td style="background-color:rgb(51, 143, 54); color: white; padding: 10px;">
                                                                                                                        <center><?php echo htmlentities(date("d-m-Y", strtotime($result->fecha_entrada))); ?></center>
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
                                                            
                                                            // Funcionalidad para los botones de exportación
                                                            document.getElementById('exportMonthButton').addEventListener('click', function() {
                                                                const today = new Date();
                                                                const month = String(today.getMonth() + 1).padStart(2, '0'); // Enero es 0!
                                                                const year = today.getFullYear();
                                                                const fechaDesde = `${year}-${month}-01`;
                                                                const fechaHasta = `${year}-${month}-${new Date(year, month, 0).getDate()}`; // Último día del mes
                                                            
                                                                window.location.href = `exportar.php?fechaDesde=${fechaDesde}&fechaHasta=${fechaHasta}`;
                                                            });
                                                            
                                                            document.getElementById('exportAllButton').addEventListener('click', function() {
                                                                window.location.href = `exportar.php`;
                                                            });
                                                            </script>
                                                            
                                                            <?php include('includes/footer.php'); ?>
                                                            

