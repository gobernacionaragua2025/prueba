<?php
session_start();
error_reporting(0);
include('includes/config.php');
if ($_SESSION['alogin'] != '') {
    $_SESSION['alogin'] = '';
}
if (isset($_POST['login'])) {
    $uname = $_POST['username'];
    $password = md5($_POST['password']);
    $sql = "SELECT UserName,Password FROM admin WHERE UserName=:uname and Password=:password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':uname', $uname, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    if ($query->rowCount() > 0) {
        $_SESSION['alogin'] = $_POST['username'];
        echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
    } else {
        echo "<script>alert('Datos Invalidos');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inicio de Sesión</title>
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="assets/css/animate-css/animate.min.css" media="screen">
    <link rel="stylesheet" href="assets/css/prism/prism.css" media="screen">
    <link rel="stylesheet" href="assets/css/main.css" media="screen">
    <script src="assets/js/modernizr/modernizr.min.js"></script>
    <style>
        body {
            background-color:rgb(255, 255, 255));
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .login-box {
            background: rgba(47, 151, 192, 0.86);
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 0 20px rgba(169, 172, 189, 0.43);
            width: 100%;
            max-width: 400px; /* Ajusta el ancho máximo */
            color: #fff;
        }
        .login-box h5 {
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 4px;
        }
        .login-btn {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="main-wrapper">
        <div class="login-box">
            <div class="panel-heading text-center">
                <a href="#">
                    <img style="height: 200px" src="assets/images/avatar10.png" alt="Logo">
                </a>
                <h5><strong>SISTEMA DE CORRESPONDENCIAfdfhd</strong></h5>
            </div>
            <div class="panel-body">
                <form class="admin-login" method="post">
                    <div class="form-group">
                        <label for="inputEmail3" class="control-label">Ingresa usuario</label>
                        <input type="text" name="username" class="form-control" id="inputEmail3" placeholder="Indicador" required>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="control-label">Clave</label>
                        <input type="password" name="password" class="form-control" id="inputPassword3" placeholder="Clave" required>
                    </div>
                    <div class="form-group mt-20">
                        <button type="submit" name="login" class="btn btn-primary login-btn">Ingresar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- ========== COMMON JS FILES ========== -->
    <script src="assets/js/jquery/jquery-2.2.4.min.js"></script>
    <script src="assets/js/jquery-ui/jquery-ui.min.js"></script>
    <script src="assets/js/bootstrap/bootstrap.min.js"></script>
    <script src="assets/js/pace/pace.min.js"></script>
    <script src="assets/js/lobipanel/lobipanel.min.js"></script>
    <script src="assets/js/iscroll/iscroll.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
