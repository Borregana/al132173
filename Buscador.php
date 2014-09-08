<?php
/**
 * Created by PhpStorm.
 * User: Borregana
 * Date: 24/07/14
 * Time: 18.22
 */
session_start();
if (isset($_SESSION['alias']))
{
    ?>

    <!DOCTYPE html>
    <html>
    <head>

        <title>The way is here...</title>
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
    <body>
    <header id="header">
        <div id="logo-group" class="col-md-2">
            <span id="logo"> <img src="img/logo-TheWay.png" alt="TheWay"> </span>
        </div>
        <div class="col-md-8">
            <div class="btn-group">
                <a href="display.php" title="Creador"><i class="btn btn-primary">Creador</i></a>
                <a href="misrutas.php" title="Private"><i class="btn btn-success">Mis Rutas</i></a>
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
    <div>
    <!-- NEW WIDGET START -->
    <article class="col-md-4">

        <!-- Widget ID (each widget will need unique ID)-->
        <div class="jarviswidget" id="wid-id-0" data-widget-fullscreenbutton="true">

            <header>
                <h2><strong>Buscador</strong></h2>
            </header>

            <!-- widget div-->
            <div>
                <!-- widget content -->
                <div class="widget-body">

                    <form  id="smart-form-search" action="Buscador.php" class="smart-form client-form" method="post">
                        <fieldset>
                            <div class="col-md-12">
                                <section>
                                    <label class="input"> <i class="icon-append fa fa-user-md"></i>
                                        <input type="text" name="usuario" placeholder="Usuario" value="<?= $_POST['usuario']?>">
                                        <b class="tooltip tooltip-bottom-right">Que usuario creo la ruta?</b> </label>
                                </section>

                                <section>
                                    <label class="input"> <i class="icon-append fa fa-suitcase"></i>
                                        <input type="text" name="nombre" placeholder="Nombre" value="<?= $_POST['nombre']?>">
                                        <b class="tooltip tooltip-bottom-right">Nombre de la ruta</b> </label>
                                </section>

                                <section>
                                    <label class="input"> <i class="icon-append fa fa-globe"></i>
                                        <input type="text" name="ciudad" placeholder="Ciudad" value="<?= $_POST['ciudad']?>">
                                        <b class="tooltip tooltip-bottom-right">Ciudad buscada</b> </label>
                                </section>
                            </div>
                            <div class="col-md-12">
                                <section>
                                    <label class="input"> <i class="icon-append fa fa-clock-o"></i>
                                        <input type="time" name="tiempo" placeholder="Tiempo de recorrido" value="<?= $_POST['tiempo']?>">
                                        <b class="tooltip tooltip-bottom-right">De cuanto tiempo dispones?</b> </label>
                                </section>

                                <section>
                                    <label class="input"> <i class="icon-append fa fa-truck"></i>
                                        <input type="text" name="vehiculo" placeholder="Vehiculo" value="<?= $_POST['vehiculo']?>">
                                        <b class="tooltip tooltip-bottom-right">De que modo quieres moverte por la ciudad?</b> </label>
                                </section>
                                <section>
                                    <label class="input"> <i class="icon-append fa fa-calendar"></i>
                                        <input type="text" name="fecha_publicacion" placeholder="Fecha de publicación 'yyyy-mm-dd'" value="<?= $_POST['fecha_publicacion']?>">
                                        <b class="tooltip tooltip-bottom-right">Que dia publicaron la ruta?</b> </label>
                                </section>
                                <section>
                                    <div>
                                        Puntuación:
                                        <label class="bigboxnumber">
                                            <input type="number" id="puntuacion" name="puntuacion" placeholder="1-5" min="1" max="5">
                                            <i class="fa fa-star"></i>
                                        </label>
                                    </div>
                                </section>
                            </div>
                        </fieldset>

                        <footer>
                            <button type="submit" class="btn btn-primary" >
                                Buscar
                            </button>
                        </footer>
                    </form>

                </div>
                <!-- end widget content -->

            </div>
            <!-- end widget div -->

        </div>
        <!-- end widget -->
    </article>

    <!-- NEW WIDGET START -->
    <article class="col-md-4 col-lg-8">

        <!-- Widget ID (each widget will need unique ID)-->
        <div class="jarviswidget" id="wid-id-1" data-widget-fullscreenbutton="true">

            <header>
                <h2><strong>Rutas</strong></h2>
            </header>

            <!-- widget div-->
            <div style="overflow: auto">
                <!-- widget content -->
                <div class="widget-body">
                    <?php
                    if(isset($_POST['nombre'])){

                    include 'connect.php';

                    $usuario= mysqli_real_escape_string($con,$_POST['usuario']);
                    $name= mysqli_real_escape_string($con,$_POST['nombre']);
                    $city= mysqli_real_escape_string($con,$_POST['ciudad']);
                    $time= mysqli_real_escape_string($con,$_POST['tiempo']);
                    $vehicle= mysqli_real_escape_string($con,$_POST['vehiculo']);
                    $date= mysqli_real_escape_string($con,$_POST['fecha_publicacion']);
                    $puntuacion=mysqli_real_escape_string($con,$_POST['puntuacion']);

                    $options=array();
                    if($usuario!=""){
                        //buscamos el id del usuario
                        $consuser="SELECT id FROM Usuarios WHERE alias='$usuario'";
                        $resultuser=mysqli_query($con,$consuser);
                        $user=mysqli_fetch_array($resultuser)['id'];
                        //lo anyadimos a la array
                        $options[]='usuario_id="'.$user.'"';}
                    if($name!=""){ $options[]='nombre="'.$name.'"';}
                    if($city!=""){ $options[]='ciudad="'.$city.'"';}
                    if($time!=""){ $options[]='tiempo="'.$time.'"';}
                    if($vehicle!=""){ $options[]='vehiculo="'.$vehicle.'"';}
                    if($date!=""){ $options[]='fecha_publicacion="'.$date.'"';}
                    if($puntuacion!=""){ $options[]='puntuacion_media="'.$puntuacion.'"';}

                    $consulta='SELECT * FROM Rutas WHERE publica="1" and '.implode(' and ', $options);
                    $resultado=mysqli_query($con,$consulta);

                    if(mysqli_num_rows($resultado)>0){
                    ?>
                    <div id="content" class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="well no-padding">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>Vista</th>
                                            <th>Usuario</th>
                                            <th>Nombre</th>
                                            <th>Ciudad</th>
                                            <th>tiempo</th>
                                            <th>Vehiculo</th>
                                            <th>Puntuación</th>
                                            <th>Fecha de publicación</th>
                                        </tr>
                                        </thead>
                                        <?php
                                        while($row=mysqli_fetch_array($resultado)){
                                            if($row['solo_amigos']==1){
                                                $yo=mysqli_real_escape_string($con,$_SESSION['usuario_id']);
                                                $amigo=$row['usuario_id'];
                                                $es_amigo=mysqli_query($con,"SELECT * FROM Grupos WHERE usuario_id='$yo' and amigo_id='$amigo'");
                                                if(mysqli_num_rows($es_amigo)>0){
                                                    ?>
                                                    <tr>
                                                        <form id="miruta" method="post" action="vistaPublica.php">
                                                            <td>
                                                                <input type="hidden" id="idruta" name="idruta" value="<?= $row['id']; ?>"
                                                            </td>
                                                            <button class="btn btn-success"><i class="icon-append fa fa-desktop"></i></button>
                                                        </form>
                                                        <td>
                                                            <?
                                                            $ami_id=$row['usuario_id'];
                                                            $con_ami=mysqli_query($con,"SELECT * FROM Usuarios WHERE id='$ami_id'");
                                                            $ami_nom=mysqli_fetch_array($con_ami)['alias'];
                                                            echo $ami_nom;
                                                            ?>
                                                        </td>
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
                                                    </tr>
                                                <?php }
                                            }
                                            else
                                            {
                                                ?>
                                                <tr>
                                                    <form id="miruta" method="post" action="vistaPublica.php">
                                                        <td>
                                                            <input type="hidden" id="idruta" name="idruta" value="<?= $row['id']; ?>"
                                                        </td>
                                                        <button class="btn btn-success"><i class="icon-append fa fa-desktop"></i></button>
                                                    </form>
                                                    <td>
                                                        <?
                                                        $ami_id=$row['usuario_id'];
                                                        $con_ami=mysqli_query($con,"SELECT * FROM Usuarios WHERE id='$ami_id'");
                                                        $ami_nom=mysqli_fetch_array($con_ami)['alias'];
                                                        echo $ami_nom;
                                                        ?>
                                                    </td>
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
                                                </tr>
                                            <?php }
                                        }?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <?php
                }
                else{?>
                    <div class="col-md-12">
                        <h1 class="txt-color-red login-header-big">No se ha encontrado ninguna ruta con estas caracteristicas</h1>
                    </div>
                <?php
                }
                }
                else
                {
                    ?>
                    <div class="col-md-12">
                        <h2>Encuentra tu </h2><h1 class="txt-color-red">camino...</h1>
                    </div>
                <?php
                }?>

            </div>
            <!-- end widget content -->

        </div>
        <!-- end widget div -->

    </article>
    </div>

    </body>
    </html>
<?php
}
else
{
    echo '<script>location.href = "index.php";</script>';
}
?>