<!DOCTYPE html>
<html>
<head>
    <title>The Way is coming...</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <script type="text/javascript" src="js/jquery-1.11.1.js"></script>

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
<body>
<header id="header">
    <div id="logo-group">
        <span id="logo"> <img src="img/logo-TheWay.png" alt="SmartAdmin"> </span>
    </div>

    <div id="logout" class="bottom-right">
        <span> <a href="index.php" title="logout"><i class="btn btn-info">Salir</i></a> </span>
    </div>

</header>

<div>

    <!-- MAIN CONTENT -->

    <div id="content" class="container">
        <div class="row">
            <div class="col-xs-9 col-sm-9 col-md-3 col-lg-3">
                <h1 class="txt-color-red login-header-big"></h1>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                <div class="well no-padding">

                    <form  id="smart-form-register" action="return false" onsubmit="return false" class="smart-form client-form" method="post">
                        <header>
                            Registro rápido!!
                        </header>
                        <div id="resultado"></div>
                        <fieldset>
                            <section>
                                <label class="input"> <i class="icon-append fa fa-user"></i>
                                    <input type="text" id="alias" name="alias" placeholder="Alias" required="required">
                                    <b class="tooltip tooltip-bottom-right">Tu nombre de usuario para acceder</b> </label>
                            </section>

                            <section>
                                <label class="input"> <i class="icon-append fa fa-envelope"></i>
                                    <input type="email" id="mail" name="mail" placeholder="Direccion de Email" required="required">
                                    <b class="tooltip tooltip-bottom-right">Lo necesitamos para estar en contacto</b> </label>
                            </section>

                            <section>
                                <label class="input"> <i class="icon-append fa fa-lock"></i>
                                    <input type="password" id="password" name="password" placeholder="Password"  required="required">
                                    <b class="tooltip tooltip-bottom-right">Tu password para acceder</b> </label>
                            </section>

                            <section>
                                <label class="input"> <i class="icon-append fa fa-lock"></i>
                                    <input type="password" id="confirmar" name="confirmar" placeholder="Confirmar password" required="required">
                                    <b class="tooltip tooltip-bottom-right">No olvides tu password</b> </label>
                            </section>
                        </fieldset>

                        <fieldset>
                            <section>
                                <label class="checkbox">
                                    <input type="checkbox" name="terms" id="terms">
                                    <i></i>I agree with the <a href="#" data-toggle="modal" data-target="#myModal"> Terms and Conditions </a></label>
                            </section>
                        </fieldset>
                        <footer>
                            <button class="btn btn-primary" onclick="registrar(document.getElementById('alias').value,
                            document.getElementById('mail').value,
                            document.getElementById('password').value,
                            document.getElementById('confirmar').value);">
                                Register
                            </button>
                        </footer>

                    </form>
                    <script>
                        function registrar(alias, mail, password, confirmar)
                        {
                            if(password==confirmar){
                                var parametros={
                                    "alias": alias,
                                    "mail": mail,
                                    "password":password,
                                    "confirmar": confirmar
                                };
                                $.ajax({
                                    data: parametros,
                                    url: "register.php",
                                    type: "POST",
                                    success: function(resp){
                                        $('#resultado').html(resp)
                                    }
                                });
                            }
                            else{
                                alert('Los passwords no coinciden, vuelve a probar');
                            }

                        }
                    </script>
                </div>
            </div>
        </div>
    </div>

</div>

</body>
</html>