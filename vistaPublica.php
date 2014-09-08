
<?php
/**
 * Created by PhpStorm.
 * User: Borregana
 * Date: 24/07/14
 * Time: 18.23
 */
session_start();
if(isset($_SESSION['alias']))
{
    ?>
    <script type="text/javascript"
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC9IemfslK-z4Wyuht0lka_Z2AqVbNfVXQ&sensor=false&libraries=drawing">
    </script>

    <script type="text/javascript" src="js/jquery-1.11.1.js"></script>
    <script>
        var centro = new google.maps.LatLng(39.8889285,-0.0847215,15);
        var geocoder= new google.maps.Geocoder();
    </script>
    <?php

    include 'connect.php';
    if(isset($_POST['idruta'])){
        $_SESSION['idruta']=mysqli_real_escape_string($con,$_POST['idruta']);
    }
    $idruta=$_SESSION['idruta'];

    $consulta="SELECT * FROM Rutas WHERE id='$idruta'";
    $resultado=mysqli_query($con,$consulta);

    if($resultado){

//Regcogemos la informacion de la ruta
        $infor=array(
            'nombre'=>"",
            'ciudad'=>"",
            'tiempo'=>"",
            'vehiculo'=>"",
            'puntuacion'=>"",
            'usuario'=>"",
            'fecha'=>"",
            'recorrido'=>"",
            'url_kml'=>""
        );
        while($col=mysqli_fetch_array($resultado)){
            $infor['nombre']=$col['nombre'];
            $infor['ciudad']=$col['ciudad'];
            $infor['tiempo']=$col['tiempo'];
            $infor['vehiculo']=$col['vehiculo'];
            $infor['puntuacion']=$col['puntuacion_media'];
            $userid=$col['usuario_id'];
            //Buscamos el alias del usuario creador de la ruta
            $username="SELECT alias FROM Usuarios WHERE id='$userid'";
            $resulname=mysqli_query($con,$username);
            $infor['usuario']=mysqli_fetch_array($resulname)['alias'];
            $infor['fecha']=$col['fecha_publicacion'];
            $infor['recorrido']=$col['recorrido'];
            $infor['url_kml']=$col['url_kml'];

        };
//Recogemos los datos del recorrido
        $line=explode("),",$infor['recorrido']);
        $tam=count($line);
        for($i=0;$i<$tam-1;$i++){
            $line[$i]=$line[$i].')';
        }

//Recogemos los datos de los marcadores
        $marcadores=array(array(
            'nombre'=>"",
            'texto'=>"",
            'punto_exacto'=>"",
            'imagen'=>"",
            'video'=>""
        ));
        $cons_puntos="SELECT * FROM Puntos WHERE ruta_id='$idruta'";
        $res_puntos=mysqli_query($con,$cons_puntos);

        if($res_puntos){
            $i=0;
            while($row=mysqli_fetch_array($res_puntos)){
                $marcadores[$i]['nombre']=$row['nombre'];
                $marcadores[$i]['texto']=$row['texto'];
                $marcadores[$i]['punto_exacto']=$row['punto_exacto'];
                $marcadores[$i]['imagen']=$row['imagen'];
                $marcadores[$i]['video']=$row['video'];
                $marcadores[$i]['youtube']=$row['youtube'];
                $i++;
            }
        }
        //Recogemos los comentarios
        $comentarios=array(array(
            'comentario'=>"",
            'puntuacion'=>"",
            'usuario'=>"",
            'id'=>""
        ));
        $nocomment=false;
        $cons_comentarios="SELECT * FROM Comentarios WHERE ruta_id='$idruta'";
        $res_coment=mysqli_query($con,$cons_comentarios);
        if(mysqli_num_rows($res_coment)>0){
            $cont=0;
            while($rcom=mysqli_fetch_array($res_coment)){
                $comentarios[$cont]['comentario']=$rcom['comentario'];
                $comentarios[$cont]['puntuacion']=$rcom['puntuacion'];
                //buscamos el nombre del usuario que ha escrito en comentario
                $alias_com=mysqli_real_escape_string($con,$rcom['usuario_id']);
                $alias_cons="SELECT alias FROM Usuarios WHERE id='$alias_com'";
                $res_alias_com=mysqli_query($con,$alias_cons);
                $comentarios[$cont]['usuario']=mysqli_fetch_array($res_alias_com)['alias'];
                $comentarios[$cont]['id']=$rcom['id'];;
                $cont++;
            }
        }
        else{
            $nocomment=true;
        }
        //centramos el mapa
        if($marcadores[0]['punto_exacto']!=""){
            $centro=$marcadores[0]['punto_exacto'];
            ?>
            <script> centro = new google.maps.LatLng(<?= $centro ?>);</script>
        <?php
        }
        elseif($infor['recorrido']!=""){
            $centro=$line[0];
            ?>
            <script> centro = new google.maps.LatLng<?= $centro ?>;</script>
        <?php
        }
        elseif($infor['ciudad']!=""){
            $centro=$infor['ciudad'];
            ?>
            <script>
                centro = "<?= $centro ?>";
                geocoder.geocode( { 'address': centro}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        centro=results[0].geometry.location;
                    }
                });
            </script>
        <?php
        }
        ?>

        <!DOCTYPE html>
        <html>
        <head>

            <title>The Way is here...</title>
            <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
            <meta charset="utf-8">
            <style>
                html, body, #map-canvas {
                    height: 90%;
                    margin: 0px;
                    padding: 0px
                }

            </style>

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
            <div id="logo-group" class="col-xs-4 col-sm-3 col-md-2">
                <span id="logo"> <img src="img/logo-TheWay.png" alt="TheWay"> </span>
            </div>
            <div class=" col-xs-12 col-sm-6 col-md-8">
                <div class="btn-group">
                    <a href="display.php" title="Private"><i class="btn btn-primary">Creador</i></a>
                    <a href="misrutas.php" title="Private"><i class="btn btn-success">Mis Rutas</i></a>
                    <a href="Buscador.php" title="Publica"><i class="btn btn-info">Buscador</i></a>
                    <a href="edituser.php" title="Perfil"><i class="btn btn-warning">Perfil</i></a>
                    <a href="logout.php" title="logout"><i class="btn btn-danger">Desconectar</i></a>
                </div>
            </div>
            <div class="pull-right">
                <span class="txt-color-teal login-header-big"><b><?= $_SESSION['alias'] ?></b></span>
                <?php
                if($_SESSION['imagen']!=null){
                    ?>
                    <img width="50" height="50" src="<?= $_SESSION['imagen']?>">
                <?php } ?>
            </div>
        </header>
        <div class=" col-xs-6 col-sm-4 col-md-3">
            <div>
                <!-- NEW WIDGET START -->
                <article class=" col-xs-12 col-sm-12 col-md-12">
                    <!-- Widget ID (each widget will need unique ID)-->
                    <div class="jarviswidget" id="wid-id-1" data-widget-fullscreenbutton="true">
                        <header>
                            <h2 class="txt-color-red login-header-big"><strong><?= $infor['nombre'] ?></strong></h2>
                        </header>
                        <!-- widget div-->
                        <div>
                            <!-- widget content -->
                            <div class="widget-body">
                                <ul>
                                    <li>Usuario: <b><i><?=$infor['usuario']?></i></b></li>
                                    <li>Ciudad: <b><i><?=$infor['ciudad']?></i></b></li>
                                    <li>Tiempo: <b><i><?=$infor['tiempo']?></i></b></li>
                                    <li>Vehiculo: <b><i><?=$infor['vehiculo']?></i></b></li>
                                    <li>Fecha: <b><i><?=$infor['fecha']?></i></b></li>
                                    <?php if($infor['url_kml']!=""){?>
                                        <li>Kml: <b><i>SI</i></b></li>
                                    <?php
                                    }
                                    else{ ?><li>Kml: <b><i>NO</i></b></li><?php } ?>

                                    <li>Puntuacion:
                                        <?for($i=0;$i<$infor['puntuacion'];$i++){?>
                                            <i class="icon-append fa fa-star"></i>
                                        <?php } ?></li>
                                </ul>
                                <div id="res"></div>
                                <div class="pull-right">
                                    <a  class="btn btn-success" onclick="copiar();">Copiar Ruta</a>
                                </div>
                                <script>
                                    function copiar()
                                    {
                                        var parametros={
                                            "idruta":<?= $idruta ?>
                                        };
                                        $.ajax({
                                            url: "copiaRoute.php",
                                            type: "POST",
                                            data: parametros,
                                            success: function(resp){
                                                $('#res').html(resp)
                                            }
                                        });
                                    }
                                </script>
                            </div>
                            <!-- end widget content -->
                        </div>
                        <!-- end widget div -->
                    </div>
                    <!-- end widget -->
                </article>
                <article class="col-xs-12 col-sm-12 col-md-12">
                    <div>
                        <!-- widget div-->
                        <div>

                            <!-- widget content -->
                            <div class="smart-form">
                                <header>
                                    <h3 class="txt-color-green header-big"><strong>COMENTARIOS</strong></h3>
                                </header>
                                <div style="overflow: auto;height:180px;">
                                    <?php
                                    for($com=0;$com<count($comentarios);$com++){
                                        if(!$nocomment){
                                            ?>
                                            <fieldset>
                                                <section class="widget-body">
                                                    <ul>
                                                        <li class="fa fa-user">
                                                            <?= $comentarios[$com]['usuario'];?>
                                                        </li>
                                                        <br>
                                                        <li class="fa fa-comment">
                                                            <?= $comentarios[$com]['comentario'];?>
                                                        </li>
                                                    </ul>
                                                </section>
                                                <section>
                                                    <div class="rating">
                                                        Puntuacion:
                                                        <?php
                                                        for($s=0;$s<$comentarios[$com]['puntuacion'];$s++){
                                                            ?>
                                                            <i class="fa fa-star"></i>
                                                        <? }
                                                        if($comentarios[$com]['usuario']==$_SESSION['alias']){
                                                            ?>
                                                            <section class="pull-right">
                                                                <form id="delete_comment" action="deleteComment.php" method="post">
                                                                    <input type="hidden" id="idcom" name="idcom" value="<?= $comentarios[$com]['id'];?>">
                                                                    <button class="btn btn-link">Borrar</button>
                                                                </form>
                                                            </section>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </section>
                                            </fieldset>

                                        <?php
                                        }
                                        else{?>
                                            <fieldset>
                                                <section class="col-xs-12 col-sm-12 col-md-12">
                                                    <h2>No hay comentarios...</h2>
                                                </section>
                                            </fieldset>
                                        <?php }?>
                                    <? } ?>
                                </div>
                                <form id="review-form" class="smart-form" action="saveComentario.php" method="post">
                                    <fieldset>
                                        <header>
                                            <H2>TU OPINION</H2>
                                            <section>
                                                <label class="label"></label>
                                                <label class="textarea"> <i class="icon-append fa fa-comment"></i>
                                                    <textarea rows="3" name="comentario" id="comentario" placeholder="Tu Comentario"></textarea>
                                                </label>
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
                                            <section>
                                                <input type="hidden" id="idruta" name="idruta" value="<?= $idruta?>">
                                            </section>
                                            <footer>
                                                <button class="btn btn-primary">
                                                    Guardar
                                                </button>
                                            </footer>
                                    </fieldset>
                                </form>
                                <script>
                                    function comentar(comentario,puntuacion)
                                    {
                                        var parametros={
                                            "idruta": <?= $idruta?>,
                                            "comentario": comentario,
                                            "puntuacion": puntuacion
                                        };
                                        $.ajax({
                                            url: "saveComentario.php",
                                            type: "POST",
                                            data: parametros,
                                            success: function(resp){
                                            }
                                        });
                                    }
                                </script>
                            </div>
                            <!-- end widget content -->

                        </div>
                        <!-- end widget div -->

                    </div>
                    <!-- end widget -->

                </article>
            </div>
        </div>
        <div class=" col-xs-12 col-sm-6 col-md-9" id="map-canvas"></div>

        </body>
        </html>
        <script>
            var route=[];
            <?php
            //Rellenamos route con las coordenadas de los punto que delimitan las lineas
            for( $j= 0;$j<=$tam;$j++){
            ?>
            route[<?=$j;?>]=new google.maps.LatLng<?= $line[$j] ?>;
            <?php }
        ?>
            function initialize() {

                var mapOptions = {
                    center: centro,
                    zoom: 16
                };

                var map = new google.maps.Map(document.getElementById('map-canvas'),
                    mapOptions);

                map.setCenter(centro);

                var ctaLayer= new google.maps.KmlLayer({
                    url:"<?= $infor['url_kml']?>"
                });
                ctaLayer.setMap(map);
                var polylineOptions= {
                    path: route,
                    strokeColor: "#8000FF"
                };

                var polyline= new google.maps.Polyline(polylineOptions);
                polyline.setMap(map);

                google.maps.event.addListenerOnce(map, 'idle', function() {
                    <?php
                    for($i=0;$i<=count($marcadores);$i++){
                    ?>
                    point= new google.maps.LatLng(<?= $marcadores[$i]['punto_exacto'] ?>);

                    var contentString =
                        '<div>'+
                            '<fieldset>'+
                            '<section>'+
                            '<div><?= $marcadores[$i]['nombre']?></div>'+
                            '</section>'+
                            '<section>' +
                            '<div><?= $marcadores[$i]['texto']?></div>'+
                            '</section>'+
                            <?php
                            if($marcadores[$i]['imagen']!=""){?>
                            '<section class="col-md-6">' +
                            '<img width="300" src="<?= $marcadores[$i]['imagen']?>">'+
                            '</section>'+
                            <?php } ?>
                            '<section class="col-md-6">'+
                            <?php
                            if($marcadores[$i]['video']!=""){?>
                            '<div>'+
                            '<video width="640" height="390" preload controls>'+
                            '<source src="<?= $marcadores[$i]['video'];?>" type="video/mp4">'+
                            '<source src="<?= $marcadores[$i]['video'];?>" type="video/webm">'+
                            '<source src="<?= $marcadores[$i]['video'];?>" type="video/ogg">'+
                            'Your browser does not support the video tag.'+
                            '</video>'+
                            '<div>'+
                            <?php }
                            if($marcadores[$i]['youtube']!=""){ ?>
                            '<div>'+
                            '<iframe id="ytplayer"  width="640" height="390"'+
                            'src="http://www.youtube.com/embed/<?= $marcadores[$i]['youtube'];?>"'+
                            'frameborder="0"/>'+
                            '</div>'+
                            <?php } ?>
                            '</section>'+
                            '</fieldset>'+
                            '</div>';


                    var infoWindow = new google.maps.InfoWindow({
                        maxwidth: "60px",
                        content: contentString
                    });

                    var marker= new google.maps.Marker({
                        position: point,
                        content: contentString
                    });
                    google.maps.event.addListener(marker, 'click', function() {
                        infoWindow.setContent(this.content);
                        infoWindow.open(map, this);
                    });
                    marker.setMap(map);
                    <?php
                         }
                        ?>
                })
            }
            google.maps.event.addDomListener(window, 'load', initialize);

        </script>
    <?php
    }
}
else{
    echo '<script>location.href = "index.php";</script>';

}