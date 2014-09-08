<?php
/**
 * Created by PhpStorm.
 * User: Borregana
 * Date: 22/07/14
 * Time: 11.42
 */
session_start();
if (isset($_SESSION['alias']))
{
    echo '<script>location.href = "display.php";</script>';
}
else
{
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>The Way is coming...</title>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
        <meta charset="utf-8">

        <script type="text/javascript" src="js/jquery-1.11.1.js"></script>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <!-- Basic Styles -->
        <link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="css/font-awesome.min.css">

        <!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
        <link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-production.css">
        <link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-skins.css">


        <!-- FAVICONS -->
        <link rel="shortcut icon" href="img/favicon/favicon.ico" type="image/x-icon">
        <link rel="icon" href="img/favicon/favicon.ico" type="image/x-icon">

        <!-- GOOGLE FONT -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

    </head>
    <body id="login" class="animated fadeInDown">

    <div id="main" role="main">
        <header id="header">
            <div id="logo-group">
                <span id="logo"> <img src="img/logo-enBinari.png" alt="TheWay"> </span>
            </div>
            <div id="register" class="pull-right">
                <span> No tienes cuenta? <a href="vistaRegister.php" title="Registro"><i class="btn btn-danger">Registrarse</i></a> </span>
            </div>
        </header>

        <div id="content">
            <div class="row">

                <div class="col-md-3">
                    <div class="well no-padding">
                        <form id="login-form" action="return false" onsubmit="return false" class="smart-form" novalidate="novalidate" method="post">
                            <header>
                                Login
                            </header>
                            <div id="resultado"></div>
                                <fieldset>
                                    <section>
                                        <label class="label">Alias</label>
                                        <label class="input"> <i class="icon-append fa fa-user"></i>
                                            <input  name="user" id="user">
                                            <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Introduce tu alias</b></label>
                                    </section>
                                    <section>
                                        <label class="label">Password</label>
                                        <label class="input"> <i class="icon-append fa fa-lock"></i>
                                            <input type="password" name="pass" id="pass">
                                            <b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> Introduce tu password</b> </label>
                                    </section>
                                    <section>
                                        <label class="checkbox">
                                            <input type="checkbox" name="remember" checked="">
                                            <i></i>Manten mi cuenta</label>
                                    </section>
                                </fieldset>
                                <footer>
                                    <button class="btn btn-primary" onclick="Validar(document.getElementById('user').value, document.getElementById('pass').value);">
                                        Entrar
                                    </button>
                                </footer>
                            </form>
                        <script>
                            function Validar(user, pass)
                            {
                                $.ajax({
                                    url: "validar.php",
                                    type: "POST",
                                    data: "user="+user+"&pass="+pass,
                                    success: function(resp){
                                        $('#resultado').html(resp)
                                    }
                                });
                            }
                        </script>
                    </div>
                </div>
                <div class="col-md-9">
                    <img src="img/img-bienvenida-01.png" alt="TheWay" width="1000px">
                </div>
            </div>
        </div>

    </div>

    </body>
    </html>
<?php
}
?>
