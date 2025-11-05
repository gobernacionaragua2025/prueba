<?php
session_start();
include('includes/config.php');

// Verificar si el usuario está autenticado
if(strlen($_SESSION['alogin']) == "") {   
    header("Location: index.php"); 
    exit();
}

// Consultar todos los registros
$sql = "SELECT * FROM registro_correspondencia_salidas ORDER BY fecha_salida DESC"; 
$query = $dbh->prepare($sql);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);

// Configurar encabezados para la descarga de archivo Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="registro_correspondencia_salidas.xls"');
header('Pragma: no-cache');
header('Expires: 0');

// Generar el contenido del archivo Excel
echo "<table border='1'>";
echo "<tr>
        <th>N.</th>
        <th>Fecha y Hora de Salida</th>
        <th>Correlativo</th>
        <th>Destinatario</th>
        <th>Departamento</th>
        <th>Descripcion</th>
        <th>Archivo</th>
    </tr>";

if(isset($results) && count($results) > 0) {
    $cnt = 1;
    foreach($results as $result) { 
        $fechaSalida = ($result->fecha_salida) ? date("d-m-Y H:i:s", strtotime($result->fecha_salida)) : ''; 
        $fileLink = $result->archivo ? basename($result->archivo) : 'Sin archivo';
        echo "<tr>
                <td>{$cnt}</td>
                <td>{$fechaSalida}</td>
                <td>{$result->correlativo}</td>
                <td>{$result->destinatario}</td>
                <td>{$result->departamento}</td>
                <td>{$result->descripcion}</td>
                <td>{$fileLink}</td>
            </tr>";
        $cnt++;
    }
} else {
    echo "<tr><td colspan='7'>No se encontraron registros.</td></tr>";
}

echo "</table>";
exit();
?>
