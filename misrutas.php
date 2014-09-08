<?php
/**
 * Created by PhpStorm.
 * User: Borregana
 * Date: 28/07/14
 * Time: 16.11
 */
session_start();
if (isset($_SESSION['alias']))
{
    ?>
    <!DOCTYPE html>
    <html>

    <head>

        <title>The Way is coming...</title>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
        <meta charset="utf-8">
        <style>
            html, body, #map-canvas {
                height: 100%;
                margin: 0px;
                padding: 0px
            }

        </style>
        <script type="text/javascript"
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC9IemfslK-z4Wyuht0lka_Z2AqVbNfVXQ&sensor=false&libraries=drawing">
        </script>

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
    <body>
    <!-- HEADER -->
    <header id="header">
        <div id="logo-group" class="col-md-2">
            <span id="logo"> <img src="img/logo-TheWay.png" alt="TheWay"> </span>
        </div>
        <div class="col-md-8">
            <div class="btn-group">
                <a href="display.php" title="Private"><i class="btn btn-primary">Creador</i></a>
                <a href="Buscador.php" title="Publica"><i class="btn btn-info">Buscador</i></a>
                <a href="edituser.php" title="Perfil"><i class="btn btn-warning">Perfil</i></a>
                <a href="logout.php" title="logout"><i class="btn btn-danger">Desconectar</i></a>
            </div>
        </div>
        <div class="pull-right">
            <span class="txt-color-teal login-header-big"><b><?= $_SESSION['alias'] ?></b></span>
            <?php
            if($_SESSION['imagen']!=""){
                ?>
                <img width="50" height="50" src="<?= $_SESSION['imagen']?>">
            <?php } ?>
        </div>
    </header>
    <?php
    if(isset($_SESSION['usuario_id']))
    {
        include 'connect.php';

        $usuario=mysqli_real_escape_string($con, $_SESSION['usuario_id']);

        $consulta=mysqli_query($con,"SELECT * FROM Rutas WHERE usuario_id='$usuario'");

        if(mysqli_num_rows($consulta) > 0){
            ?>
            <div>
                <div id="content">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="well no-padding">
                                <header>
                                    <h1 class="txt-color-red login-header-big">MIS RUTAS</h1>
                                </header>
                                <div style="overflow: auto;height:600px;">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Ciudad</th>
                                            <th>tiempo</th>
                                            <th>Vehículo</th>
                                            <th>Puntuación</th>
                                            <th>Fecha de publicación</th>
                                            <th>Copias</th>
                                            <th>Pública</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <?php
                                        while($row=mysqli_fetch_array($consulta)){
                                            ?>
                                            <tr>
                                                <td>
                                                    <?= $row['nombre']; ?>
                                                </td>
                                                <td>
                                                    <?= $row['ciudad']; ?>
                                                </td>
                                                <td>
                                                    <?= $row['tiempo']; ?>
                                                </td>
                                                <td>
                                                    <?= $row['vehiculo']; ?>
                                                </td>
                                                <td>
                                                    <?for($i=0;$i<$row['puntuacion_media'];$i++){?>
                                                        <i class="icon-append fa fa-star"></i>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <?= $row['fecha_publicacion']; ?>
                                                </td>
                                                <td>
                                                    <?= $row['num_copias']; ?>
                                                </td>
                                                <td>
                                                    <?php if($row['publica']==0){
                                                        echo 'NO';
                                                    }
                                                    else{
                                                        echo 'SI';
                                                    }?>
                                                </td>
                                                <form id="miruta_vista" method="post" action="vistaPublica.php">
                                                    <td>
                                                        <input type="hidden" id="idruta" name="idruta" value="<?= $row['id']; ?>"
                                                    </td>
                                                    <button class="btn btn-success"><i class="icon-append fa fa-desktop"></i></button>
                                                </form>
                                                <?php if($row['publica']==0){ ?>
                                                    <form id="miruta_publicar" method="post" action="publicar.php">
                                                        <td>
                                                            <input type="hidden" id="idruta" name="idruta" value="<?= $row['id']; ?>"
                                                        </td>
                                                        <button class="btn btn-info"><i class="icon-append glyphicon glyphicon-eye-open"></i></button>
                                                    </form>
                                                <?php }
                                                else { ?>
                                                    <form id="miruta_despublicar" method="post" action="despublicar.php">
                                                        <td>
                                                            <input type="hidden" id="idruta" name="idruta" value="<?= $row['id']; ?>"
                                                        </td>
                                                        <button class="btn btn-danger"><i class="icon-append glyphicon glyphicon-eye-close"></i></button>
                                                    </form>
                                                <?php } ?>
                                                <?php if($row['solo_amigos']==0){ ?>
                                                    <form id="miruta_amigos" method="post" action="soloAmigos.php">
                                                        <td>
                                                            <input type="hidden" id="idruta" name="idruta" value="<?= $row['id']; ?>"
                                                        </td>
                                                        <button class="btn btn-info"><i class="icon-append fa fa-group"></i></button>
                                                    </form>
                                                <?php }
                                                else { ?>
                                                    <form id="miruta_todos" method="post" action="paraTodos.php">
                                                        <td>
                                                            <input type="hidden" id="idruta" name="idruta" value="<?= $row['id']; ?>"
                                                        </td>
                                                        <button class="btn btn-success"><i class="icon-append glyphicon glyphicon-globe"></i></button>
                                                    </form>
                                                <?php } ?>
                                                <form id="miruta_editar" method="post" action="display.php">
                                                    <td>
                                                        <input type="hidden" id="idruta" name="idruta" value="<?= $row['id']; ?>"
                                                    </td>
                                                    <button class="btn btn-link" ><i class="icon-append glyphicon glyphicon-pencil"></i></button>
                                                </form>
                                                <form id="miruta_eliminar" method="post" action="deleteRoute.php">
                                                    <td>
                                                        <input type="hidden" id="idruta" name="idruta" value="<?= $row['id']; ?>"
                                                    </td>
                                                    <button class="btn btn-danger"><i class="icon-append glyphicon glyphicon-trash"></i></button>
                                                </form>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="well no-padding">
                                <header>
                                    <h1 class="txt-color-red login-header-big">Herramientas</h1>
                                </header>
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Botón</th>
                                        <th>Acción</th>
                                    </tr>
                                    </thead>
                                    <tr>
                                        <td>
                                            <button class="btn btn-success"><i class="icon-append fa fa-desktop"></i></button>
                                        </td>
                                        <td>
                                            <font size="3"> Ir a la vista pública de la ruta.</font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <button class="btn btn-info"><i class="icon-append glyphicon glyphicon-eye-open"></i></button>
                                            <button class="btn btn-danger"><i class="icon-append glyphicon glyphicon-eye-close"></i></button>
                                        </td>
                                        <td>
                                            <font size="3"> Publicar / Despublicar la ruta.</font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <button class="btn btn-info"><i class="icon-append fa fa-group"></i></button>
                                        </td>
                                        <td>
                                            <font size="3"> Al pulsar solo verán la ruta los que te hayan agregado como amigo.</font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <button class="btn btn-success"><i class="icon-append glyphicon glyphicon-globe"></i></button>
                                        </td>
                                        <td>
                                            <font size="3"> Al pulsar todos los usuarios podrán ver tu ruta.</font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <button class="btn btn-link"><i class="icon-append glyphicon glyphicon-pencil"></i></button>
                                        </td>
                                        <td>
                                            <font size="3"> Editar la ruta.</font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <button class="btn btn-danger"><i class="icon-append glyphicon glyphicon-trash"></i></button>
                                        </td>
                                        <td>
                                            <font size="3"> Eliminar la ruta.</font>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        <?php
        }
        else
        {
            ?>
            <div id="content" class="container">
                <div class="row">
                    <div class="col-xs-9 col-sm-9 col-md-3 col-lg-3">
                        <h1 class="txt-color-red login-header-big"></h1>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                        <div class="well no-padding">
                            <header>
                                <h1 class="txt-color-red login-header-big">MIS RUTAS</h1>
                            </header>
                            Todavia no has creado ninguna ruta...
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
        mysqli_close($con);

    }
    else
    {
        echo '<script>location.href = "index.php";</script>';
    }

    ?>
    </body>
    </html>
<?php
}
else
{
    echo '<script>location.href = "index.php";</script>';
}
?>
