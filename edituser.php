<?php
/**
 * Created by PhpStorm.
 * User: Borregana
 * Date: 22/07/14
 * Time: 18.37
 */
session_start();
if(isset($_SESSION['alias'])){

    include 'connect.php';


    $usuario=mysqli_real_escape_string($con,$_SESSION['usuario_id']);
    $result= mysqli_query($con, "SELECT * FROM Usuarios WHERE id='$usuario'");

    $row = mysqli_fetch_array($result);

    $list_amigos=array(
        'nombre'=>"",
        'iduser'=>""
    );
    $busca_amigos=mysqli_query($con,"SELECT * FROM Grupos WHERE usuario_id='$usuario'");
    $i=0;
    while($col=mysqli_fetch_array($busca_amigos)){
        $list_amigos[$i]['nombre']=$col['nombre_amigo'];
        $list_amigos[$i]['iduser']=$col['amigo_id'];
        $i++;
    }

    mysqli_close($con);

    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>The Way is coming...</title>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <script type="text/javascript" src="js/jquery-1.11.1.js"></script>
        <script type="text/javascript" src="js/jquery.form.js"></script>

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

    <div id="main">
        <!-- HEADER -->
        <header id="header">
            <div id="logo-group" class="col-md-2">
                <span id="logo"> <img src="img/logo-TheWay.png" alt="TheWay"> </span>
            </div>
            <div class="col-md-8">
                <div class="btn-group">
                    <a href="display.php" title="Creador"><i class="btn btn-primary">Creador</i></a>
                    <a href="misrutas.php" title="Private"><i class="btn btn-success">Mis Rutas</i></a>
                    <a href="Buscador.php" title="Buscador"><i class="btn btn-info">Buscador</i></a>
                    <a href="logout.php" title="logout"><i class="btn btn-danger">Desconectar</i></a>
                </div>
            </div>
        </header>

        <div id="content" class="container">
            <div class="row">
                <div class="col-md-5">
                    <div class="well no-padding">

                        <form  id="smart-form-register" action="edit.php"  class="smart-form client-form" method="post" enctype="multipart/form-data">
                            <header>
                                Información de usuario
                            </header>

                            <fieldset>
                                <section>
                                    <label class="input"> <i class="icon-append fa fa-user-md"></i>
                                        <input type="text" id="nombre" name="nombre" placeholder="Nombre" value=<?= $row['nombre']?>>
                                        <b class="tooltip tooltip-bottom-right">Tu nombre real</b> </label>
                                </section>

                                <section>
                                    <label class="input"> <i class="icon-append fa fa-user-md"></i>
                                        <input type="text" id="apellidos" name="apellidos" placeholder="Apellidos" value=<?= $row['apellidos']?>>
                                        <b class="tooltip tooltip-bottom-right">Tus apellidos reales</b> </label>
                                </section>

                                <section>
                                    <label class="input"> <i class="icon-append fa fa-user"></i>
                                        <input type="text" id="alias" name="alias" placeholder="Alias" value=<?= $row['alias']?>>
                                        <b class="tooltip tooltip-bottom-right">Tu nombre de usuario para acceder</b> </label>
                                </section>

                                <section>
                                    <label class="input"> <i class="icon-append fa fa-envelope"></i>
                                        <input type="email" id="mail" name="mail" placeholder="Direccion de Email" value=<?= $row['mail']?>>
                                        <b class="tooltip tooltip-bottom-right">Lo necesitamos para estar en contacto</b> </label>
                                </section>


                                <section>
                                    <label class="input"> <i class="icon-append fa fa-home"></i>
                                        <input type="text" id="direccion" name="direccion" placeholder="Dirección" value=<?= $row['direccion']?>>
                                        <b class="tooltip tooltip-bottom-right">La dirección donde vives</b> </label>
                                </section>
                                <section>
                                    <label class="input"> <i class="icon-append fa fa-picture-o"></i>
                                        <input type="file" id="imagen" name="imagen" placeholder="Tu avatar">
                                        <br>
                                        <?php
                                        if($row['imagen']!=null){
                                            ?>
                                            <img width="100" height="100" src="<?= $row['imagen']?>">
                                        <?php } ?>
                                </section>
                            </fieldset>
                            <footer>
                                <button class="btn btn-primary">
                                    Guardar cambios
                                </button>
                            </footer>
                        </form>
                        <div id="resp"></div>
                        <form class="smart-form" action="return false" onsubmit="return false" method="post">
                            <header>
                                Darse de baja.
                            </header>
                            <fieldset>
                                <section>
                                    <input type="hidden" id="iduser" name="iduser" value="<?= $_SESSION['usuario_id']?>">
                                    <label class="input"> <i class="icon-append fa fa-lock"></i>
                                        <input type="password" id="pass" name="pass" placeholder="Password">
                                        <b class="tooltip tooltip-bottom-right">Tu password</b> </label>
                                </section>
                            </fieldset>
                            <footer>
                                <button class="btn btn-danger" onclick="borrarCuenta(document.getElementById('iduser').value,document.getElementById('pass').value);">Borrar cuenta</button>
                            </footer>
                        </form>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="well no-padding">
                        <section>
                            <div id="resultado"></div>
                            <form class="smart-form" action="return false" onsubmit="return false" method="post">
                                <input  size="32px" id="amigo" name="amigo" placeholder="introduce el nombre de usuario">
                                <footer>
                                    <button class="btn btn-success" onclick="Agregar(document.getElementById('amigo').value);">Agregar</button>
                                </footer>
                            </form>
                        </section>
                        <section>
                            <div style="overflow: auto;height:700px;">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Mis amigos</th>
                                    </tr>
                                    </thead>
                                    <?php
                                    for($i=0;$i<count($list_amigos)-2;$i++){
                                        ?>
                                        <tr>
                                            <form id="datoamigo" action="return false" onsubmit="return false" method="post">
                                                <td>
                                                    <input type="hidden" id="<?='iduser'.$i ?>" name="iduser" value="<?= $list_amigos[$i]['iduser'];?>"
                                                </td>
                                                <button class="btn-link" onclick="datos(document.getElementById('<?='iduser'.$i ?>').value);">
                                                    <?= $list_amigos[$i]['nombre'];?>
                                                </button>
                                                <div class="pull-right">
                                                    <button class="btn btn-danger" onclick="eliminarAmigo(document.getElementById('<?='iduser'.$i ?>').value);">
                                                        <i class="icon-append glyphicon glyphicon-trash"></i>
                                                    </button>
                                                </div>
                                                <div class="pull-right">
                                                    <button class="btn btn-info" onclick="susRutas(document.getElementById('<?='iduser'.$i ?>').value);">
                                                        <i class="icon-append glyphicon glyphicon-road"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </tr>
                                    <?php } ?>
                                </table>

                            </div>
                        </section>
                    </div>
                </div>
                <div class="col-md-4">
                    <div id="datos" class="well no-padding"></div>
                </div>
            </div>
        </div>
    </div>
    </body>
    </html>
    <script>
        function Agregar(amigo)
        {
            $.ajax({
                url: "saveAmigo.php",
                type: "POST",
                data: "amigo="+amigo,
                success: function(resp){
                    $('#resultado').html(resp)
                }
            });
        }
        function datos(iduser)
        {
            $.ajax({
                url: "datosAmigo.php",
                type: "POST",
                data: "iduser="+iduser,
                success: function(resp){
                    $('#datos').html(resp)
                }
            });
        }
        function susRutas(iduser)
        {
            $.ajax({
                url: "rutasAmigo.php",
                type: "POST",
                data: "iduser="+iduser,
                success: function(resp){
                    $('#datos').html(resp);
                }
            });
        }
        function eliminarAmigo(iduser)
        {
            $.ajax({
                url: "deleteAmigo.php",
                type: "POST",
                data: "iduser="+iduser,
                success: function(resp){
                    $('#datos').html(resp);
                }
            });
        }
        function borrarCuenta(iduser, pass)
        {
            var parametros={
                "iduser": iduser,
                "pass": pass
            };
            $.ajax({
                url: "deleteUser.php",
                type: "POST",
                data: parametros,
                success: function(resp){
                    $('#resp').html(resp);
                }
            });
        }
    </script>
<?php
}
else
{
    echo '<script>location.href = "index.php";</script>';
}
