<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Correspondencia</title>
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="assets/css/animate-css/animate.min.css" media="screen">
    <link rel="stylesheet" href="assets/css/lobipanel/lobipanel.min.css" media="screen">
    <link rel="stylesheet" href="assets/css/toastr/toastr.min.css" media="screen">
    <link rel="stylesheet" href="assets/css/icheck/skins/line/blue.css">
    <link rel="stylesheet" href="assets/css/icheck/skins/line/red.css">
    <link rel="stylesheet" href="assets/css/icheck/skins/line/green.css">
    <link rel="stylesheet" href="assets/css/main.css" media="screen">
    <link rel="stylesheet" href="assets/css/prism/prism.css" media="screen">
    
    <script src="assets/js/modernizr/modernizr.min.js"></script>
    
    <style>
        /* Estilos personalizados */
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .navbar-nav > li {
            margin-right: 15px;
        }
        .navbar-nav > li > a {
            color: #555;
            font-weight: bold;
            transition: color 0.3s;
        }
        .navbar-nav > li > a:hover {
            color: #007bff;
        }
        .navbar {
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .marquee {
            font-size: 14px;
            color: red;
            font-weight: bold;
        }
        .navbar-header {
            display: flex; /* Usar flexbox para alinear elementos */
            align-items: center; /* Centrar verticalmente */
        }
        .navbar-brand {
            margin-right: 10px; /* Espacio entre el logo y el enlace "Inicio" */
        }
    </style>
</head>
<body class="top-navbar-fixed">
    <div id="page"></div>
    <div id="loading"></div>

    <div class="main-wrapper">
        <nav class="navbar top-navbar bg-white">
            <div class="container-fluid">
                <div class="row">
                    <div class="navbar-header no-padding">
                        <a class="navbar-brand" href="#">
                            <img src="assets/images/logo1.png" style="width: 60px">
                        </a>
                        <ul class="nav navbar-nav" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                            <li><a href="dashboard.php">Inicio</a></li>
                            <li><a href="create-entrada.php"><i class="fa fa-plus"></i> Registrar entrada</a></li>
                            <li><a href="create-salida.php"><i class="fa fa-plus"></i> Registrar salida</a></li>
                            <li><a href="change-password.php"><i class="fa fa-key"></i> Cambio de Clave</a></li>
                        </ul>
                    </div>

                    <div class="collapse navbar-collapse" id="navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-right" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                            <li><div id="google_translate_element"></div></li>
                            <li>
                                <a href="logout.php" class="color-danger text-center"><i class="fa fa-sign-out"></i> Salir</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <!-- Incluir jQuery y Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>
