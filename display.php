<?php
/**
 * Created by PhpStorm.
 * User: Borregana
 * Date: 26/07/14
 * Time: 13.32
 */

session_start();
if (isset($_SESSION['alias']))
{
    ?>
    <script>
        var map;
        var route=[];
        var markersArray = [];
        var routeArray = [];
        var idRuta="";
        var marcador="";
        var posicion=0;
        var arrayMarkerId=[];
    </script>
    <!DOCTYPE html>
    <html>
    <head>
        <title>The Way is coming...</title>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
        <meta charset="utf-8">
        <style>
            html, body, #map-canvas {
                height: 90%;
                margin: 0px;
                padding: 0px
            }

        </style>
        <script type="text/javascript"
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC9IemfslK-z4Wyuht0lka_Z2AqVbNfVXQ&sensor=false&libraries=drawing">
        </script>

        <script type="text/javascript" src="js/jquery-1.11.1.js"></script>
        <script src="js/smartwidgets/jarvis.widget.min.js"></script>
        <script src="js/bootstrap/bootstrap.min.js"></script>


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
    <script>
        var centro = new google.maps.LatLng(39.8889285,-0.0847215,15);
        var geocoder= new google.maps.Geocoder();
    </script>
    <?php
    if($_SESSION['img_ruta']!=""){
        $_POST['idruta']=$_SESSION['img_ruta'];
        $_SESSION['img_ruta']="";
    }
    if(isset($_POST['idruta'])){?>
        <script> idRuta = <?= $_POST['idruta'] ?>;</script>
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
            'idpunto'=>"",
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
                $marcadores[$i]['idpunto']=$row['id'];
                $img=explode('/',$row['imagen']);
                $marcadores[$i]['imagen']=$img[2];
                $vid=explode('/',$row['video']);
                $marcadores[$i]['video']=$vid[2];
                $marcadores[$i]['youtube']=$row['youtube'];
                $i++;
            }
        }
    }
    if($marcadores[0]['punto_exacto']!=""){
    $centro=$marcadores[0]['punto_exacto'];
    ?>
        <script> centro = new google.maps.LatLng(<?= $centro ?>);</script>
    <?php
    }
    elseif($infor['recorrido']!=""){
    $centro=$line[0].$line[1];
    ?>
        <script> centro = new google.maps.LatLng(<?= $centro ?>);</script>
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
    }
    ?>
    <body>

    <!-- HEADER -->
    <header id="header">
        <div id="logo-group" class="col-xs-1 col-sm-3 col-md-2 col-lg-12">
            <span id="logo"> <img src="img/logo-TheWay.png" alt="TheWay"> </span>
        </div>
        <!-- projects dropdown -->
        <div id="instrucciones-context">

            <span class="label"></span>
            <span id="intrucciones" class="popover-trigger-element dropdown-toggle" data-toggle="dropdown"><font size="3">Ayuda</font> <i class="fa fa-info-circle"></i></span>

            <!-- Suggestion: populate this list with fetch and push technique -->
            <ul class="dropdown-menu">
                <li>
                    <a class="txt-color-red" ><font size="2"> Herramientas de dibujo en el mapa</font></a>
                </li>
                <li>
                    <a>Mano: permite mover el mapa y dejar de dibujar.</a>
                </li>
                <li>
                    <a>Marcador: puedes colocar marcadores en el mapa.</a>
                </li>
                <li>
                    <a>Línea: puedes dibujar líneas rectas haciendo click en el mapa.</a>
                </li>
                <li>
                    <a>Eliminar: para eliminar un marcador o una línea haz click con el botón secundario sobre ello.</a>
                </li>
                <li class="divider"></li>
                <li>
                    <a class="txt-color-red" ><font size="2"> Herramientas de dibujo fuera del mapa</font></a>
                </li>
                <li>
                    <a>Redireccionar: Coloca el mapa sobre la ciudad o dirección escrita en el campo superior.</a>
                </li>
                <li>
                    <a>Añadir marcador: Puedes colocar un marcador directamente escribiendo la dirección que desees.</a>
                </li>
                <li>
                    <a>Cargar ruta kml: Escribiendo la url donde se encuentra el archivo kml o kmz lo carga sobre el mapa.</a>
                </li>
                <li class="divider"></li>
                <li>
                    <a class="txt-color-red" ><font size="2"> Crear una ruta </font></a>
                </li>
                <li>
                    <a>En primer lugar debes guardar la ruta, el unico campo obligatorio es el nombre.
                        Se guarda la información y la ruta, pero no los marcadores.</a>
                </li>
                <li>
                    <a>Para guardar un marcador haz click sobre el y guardalo, posteriormente puedes subir fotos y videos.</a>
                </li>
                <li>
                    <a>Si deseas subir un video de youtube necesitaras el ID, lo puedes encontrar al final de la url del video en la web de youtube</a>
                </li>
                <li>
                    <a>Recuerda guardar la ruta despues de realizar cambios.</a>
                </li>
            </ul>
            <!-- end dropdown-menu-->

        </div>
        <!-- end projects dropdown -->
        <div class="col-xs-12 col-sm-6 col-md-8">
            <div class="btn-group">
                <?php
                if($idruta!=""){?>
                    <a href="display.php" title="Private"><i class="btn btn-primary">Creador</i></a>
                <?php } ?>
                <a href="misrutas.php" title="Private"><i class="btn btn-success">Mis Rutas</i></a>
                <a href="Buscador.php" title="Publica"><i class="btn btn-info">Buscador</i></a>
                <a href="edituser.php" title="Perfil"><i class="btn btn-warning">Perfil</i></a>
                <a href="logout.php" title="logout"><i class="btn btn-danger">Desconectar</i></a>
            </div>
        </div>

        <div class="pull-right">
            <span class="txt-color-teal login-header-big"><font size="4"><b><?= $_SESSION['alias'] ?></b></font></span>
            <?php
            if($_SESSION['imagen']!=""){
                ?>
                <img width="50" height="50" src="<?= $_SESSION['imagen']?>">
            <?php } ?>
        </div>


    </header>

    <div class="col-xs-2 col-sm-6 col-md-9" id="map-canvas"></div>

    <!-- NEW WIDGET START -->
    <article class="col-xs-6 col-sm-4 col-md-3">

        <!-- Widget ID (each widget will need unique ID)-->
        <div class="jarviswidget" id="wid-id-0" data-widget-fullscreenbutton="true">
            <header>
                <h2><strong>Información </strong> <i>Ruta</i></h2>
            </header>
            <!-- widget div-->
            <div>
                <!-- widget content -->
                <div class="widget-body">
                    <form  id="registro-ruta"  action="return false" onsubmit="return false" class="smart-form client-form" method="post">
                        <header>
                        </header>
                        <div id="result"></div>
                        <fieldset>
                            <section>
                                <label class="input"> <i class="icon-append fa fa-suitcase"></i>
                                    <input type="text" id="nombre" name="nombre" placeholder="Nombre" required="required" value="<?= $infor['nombre'];?>">
                                    <b class="tooltip tooltip-bottom-right">Nombre de la ruta</b> </label>
                            </section>

                            <section>
                                <label class="input"> <i class="icon-append fa fa-home"></i>
                                    <input type="text" id="ciudad" name="ciudad" placeholder="Ciudad, dirección..." value="<?= $infor['ciudad'];?>">
                                    <b class="tooltip tooltip-bottom-right">Ciudad recorrida</b> </label>
                                <footer>
                                    <font size="2" class="txt-color-orange">Coloca el mapa donde prefieras</font>
                                    <button class="btn btn-info" onclick="codeAddress()">Redireccionar</button>
                                </footer>
                            </section>

                            <section>
                                <label class="input"> <i class="icon-append fa fa-clock-o"></i>
                                    <input type="time" id="tiempo" name="tiempo" placeholder="Tiempo de recorrido 'hh:mm'" value="<?= $infor['tiempo'];?>">
                                    <b class="tooltip tooltip-bottom-right">Cuanto tiempo tardaste?</b> </label>
                            </section>

                            <section>
                                <label class="input"> <i class="icon-append fa fa-truck"></i>
                                    <input type="text" id="vehiculo" name="vehiculo" placeholder="Vehiculo" value="<?= $infor['vehiculo'];?>">
                                    <b class="tooltip tooltip-bottom-right">De que modo te moviste por la ciudad?</b> </label>
                            </section>

                        </fieldset>
                        <footer>
                            <button class="btn btn-primary" onclick="submitRoute(
                        document.getElementById('nombre').value,
                        document.getElementById('ciudad').value,
                        document.getElementById('tiempo').value,
                        document.getElementById('vehiculo').value);">

                                Guardar
                            </button>
                        </footer>
                    </form>
                </div>
                <!-- end widget content -->
            </div>
            <!-- end widget div -->
        </div>
        <div class="jarviswidget">
            <header>
                <h2><strong class="txt-color-blue login-header-big">Marcar dirección </strong></h2>
            </header>
            <div class="widget-body">
                <div class="pull-left">
                    <strong class="txt-color-blue">Añade un marcador escribiendo aquí la dirección</strong>
                </div>
                <form class="form-actions" id="markerform" name="markerform" action="return false" onsubmit="return false">
                    <input type="text" class="pull-left" id="marker_dir" placeholder="Direccion">
                    <button class="btn btn-warning" onclick="markerAddress();">
                        Marcar
                    </button>
                </form>
            </div>
        </div>
        <!-- end widget -->
        <div class="jarviswidget">
            <header>
                <h2 class="pull-left"><strong class="txt-color-orangeDark login-header-big">Subir ruta </strong><i><b>Kml</b></i></h2><br>
            </header>
            <div class="widget-body">
                <div id="resultado"></div>
                <b class="txt-color-orange">Introducir url del archivo.</b>
                <form class="form-actions" id="kmlform" name="kmlform" action="return false" onsubmit="return false" method="post">
                    <input type="text" class="pull-left" id="urlKml" name="urlKml">
                    <button class="btn btn-success" onclick="loadKml(document.getElementById('urlKml').value);">
                        Cargar Kml
                    </button>
                </form>
                <?php
                if($infor['url_kml']!=""){?>
                    <div id='deletekml'>
                        <?='<span><b class="txt-color-blue">Capa: </b>'.$infor['url_kml'].'</span><br><br>'?>
                        <button  class="btn btn-danger" onclick="deleteKml();">Eliminar capa existente</button>
                    </div>
                <?php }
                ?>
            </div>
    </article>

    </body>

    </html>
    <script>
    <?php
    //Rellenamos route con las coordenadas de los punto que delimitan las lineas
    for( $j= 0;$j<=$tam;$j++){
    ?>
    route[<?=$j;?>]=new google.maps.LatLng<?= $line[$j] ?>;
    <?php }
?>
    function contentwindow(nombre,texto,idpunto,imagen,video,youtube) {
        var contentString="";
        if(nombre== undefined){
            nombre="";
        }
        if(texto== undefined){
            texto="";
        }
        if (idpunto == undefined){
            contentString = '<div>'+
                '<div class="col-md-7">'+
                '<form  id="punto" action="return false" onsubmit="return false" class="smart-form client-form" method="post">'+
                '<header class="txt-color-blueDark">'+
                'Punto de Interés'+
                '</header>'+
                '<div id="resultado"></div>'+
                '<fieldset>'+
                '<section>'+
                '<label class="input"> <i class="icon-append fa fa-picture-o"></i>'+
                '<input type="text" id="nombre_punto" name="nombre_punto" placeholder="Nombre" maxlength="100" value='+nombre+'>'+
                '<b class="tooltip tooltip-bottom-right">Nombre del punto</b> </label>'+
                '</section>'+
                '<section>' +
                '<label class="textarea"><i class="icon-append fa fa-comment-o"></i>'+
                '<textarea id="texto" name="texto" rows="2" placeholder="Cuentanos...">'+texto+'</textarea> '+
                '<b class="tooltip tooltip-bottom-right">Algo que decir?</b> </label>'+
                '</section>'+
                '<section>' +
                '<input id="posicion" type="hidden" value='+posicion+'>'+
                '<input type="hidden" id="punto" name="punto" value='+idpunto+'>'+
                '</section>'+
                '</fieldset>'+
                '<footer>'+
                '<button class="btn btn-primary" onclick=submitPoint(document.getElementById("nombre_punto").value,document.getElementById("texto").value,document.getElementById("posicion").value,document.getElementById("punto").value);>'+
                'Guardar'+
                '</form>'+
                '</div>'+
                '<div class="col-md-5">'+
                '<div id="imagen" class="image">'+
                '<header class="txt-color-orangeDark">'+
                'Imagen'+
                '</header>'+
                '<form id="imgp" action="saveImgPoint.php" method="post" class="smart-form client-form" enctype="multipart/form-data">'+
                '<input type="hidden" id="imgold" name="imgold" value='+imagen+'>'+
                '<input type="hidden" id="idrut" name="idrut" value='+idRuta+'>'+
                '<label class="input"><input type="file" id="img_punto" name="img_punto" >'+
                '<footer>'+
                '<button id="img_btn" name="img_btn" class="btn btn-success" style="display:none;">'+
                'Subir Imagen'+
                '</button>'+
                '</footer>'+
                '</form>'+
                '</div>'+
                '<div id="video" class="video">'+
                '<header class="txt-color-orangeDark">'+
                'Video'+
                '</header>'+
                '<form id="vidp" action="saveVideoPoint.php" method="post" class="smart-form client-form" enctype="multipart/form-data">'+
                '<input type="hidden" id="vidold" name="vidold" value='+video+'>'+
                '<input type="hidden" id="idrut" name="idrut" value='+idRuta+'>'+
                '<label class="input"><input type="file" id="vid_punto" name="vid_punto" >'+
                '<footer>'+
                '<button id="vid_btn" name="vid_btn" class="btn btn-success" style="display:none;">'+
                'Subir Video'+
                '</button>'+
                '</footer>'+
                '</form>'+
                '</div>'+
                '<div><span class="txt-color-blue">Debes guardar primero el punto y luego si lo deseas subir algun archivo</span></div>'+
                '</div>'+
                '</div>';
        }
        else{
            contentString = '<div>'+
                '<div class="col-md-7">'+
                '<form  id="punto" action="return false" onsubmit="return false" class="smart-form client-form" method="post">'+
                '<header class="txt-color-blueDark">'+
                'Punto de Interés'+
                '</header>'+
                '<div id="resultado"></div>'+
                '<fieldset>'+
                '<section>'+
                '<label class="input"> <i class="icon-append fa fa-picture-o"></i>'+
                '<input type="text" id="nombre_punto" name="nombre_punto" placeholder="Nombre" maxlength="100" value='+nombre+' required="required">'+
                '<b class="tooltip tooltip-bottom-right">Nombre del punto</b> </label>'+
                '</section>'+
                '<section>' +
                '<label class="textarea"><i class="icon-append fa fa-comment-o"></i>'+
                '<textarea id="texto" name="texto" rows="2" placeholder="Cuentanos...">'+texto+'</textarea> '+
                '<b class="tooltip tooltip-bottom-right">Algo que decir?</b> </label>'+
                '</section>'+
                '<section>' +
                '<input id="posicion" type="hidden" value='+posicion+'>'+
                '<input type="hidden" id="idpunto" name="idpunto" value='+idpunto+'>'+
                '</section>'+
                '</fieldset>'+
                '<footer>'+
                '<button class="btn btn-primary" onclick=submitPoint(document.getElementById("nombre_punto").value,document.getElementById("texto").value,document.getElementById("posicion").value,document.getElementById("idpunto").value);>'+
                'Guardar'+
                '</form>'+
                '</div>'+
                '<div class="col-md-5">'+
                '<div id="imagen" class="image">'+
                '<header class="txt-color-orangeDark">'+
                'Imagen'+
                '</header>'+
                '<form id="imgp" action="saveImgPoint.php" method="post" class="smart-form client-form" enctype="multipart/form-data">'+
                '<input type="hidden" id="idpuntoimg" name="idpuntoimg" value='+idpunto+'>'+
                '<input type="hidden" id="imgold" name="imgold" value='+imagen+'>'+
                '<input type="hidden" id="idrut" name="idrut" value='+idRuta+'>'+
                '<label class="input"><input type="file" accept="image" id="img_punto" name="img_punto" >'+
                '<input type=text readonly="readonly" placeholder="No hay imagen" value='+imagen+'>'+
                '<footer>'+
                '<button id="img_btn" name="img_btn" class="btn btn-success"">'+
                'Subir Imagen'+
                '</button>'+
                '</footer>'+
                '</form>'+
                '</div>'+
                '<div id="video" class="video">'+
                '<header class="txt-color-orangeDark">'+
                'Video'+
                '</header>'+
                '<form id="vidp" action="saveVideoPoint.php" method="post" class="smart-form client-form" enctype="multipart/form-data">'+
                '<input type="hidden" id="idpuntovid" name="idpuntovid" value='+idpunto+'>'+
                '<input type="hidden" id="vidold"  name="vidold" value='+video+'>'+
                '<input type="hidden" id="idrut" name="idrut" value='+idRuta+'>'+
                '<input type="file" id="video_punto" name="video_punto" >'+
                '<input type=text readonly="readonly" placeholder="No hay video." value='+video+'>'+
                '<br><span class="txt-color-green">Si deseas colocar un video de youtube, introduce aquí su ID.</span><br>'+
                '<input type="text" id="youtube" name="youtube" placeholder="No hay ID." value="'+youtube+'">'+
                '<footer>'+
                '<button id="vid_btn" name="vid_btn" class="btn btn-success"">'+
                'Subir Video'+
                '</button>'+
                '</footer>'+
                '</form>'+
                '</div>'+
                '</div>'+
                '</div>';
        }
        result= contentString+posicion;
        posicion++;
        return result;
    }
    var infoWindow = new google.maps.InfoWindow({
        maxwidth: "60px",
        content: contentwindow()
    });
    function loadKml(url){
        if(idRuta==""){
            alert('Debes crear la ruta primero')
        }
        else{
            var ctaLayer= new google.maps.KmlLayer({
                url:url
            });
            var parametros={
                "url":url,
                "idruta":idRuta
            };
            $.ajax({
                data:parametros,
                url: "saveKml.php",
                type: "post",
                success: function(resp){
                    $("#resultado").html(resp)
                }
            });
            ctaLayer.setMap(map);
            $("#deleteKml").show();
        }
    }
    function deleteKml(){
        var parametros={
            "idruta":idRuta
        };
        $.ajax({
            data:parametros,
            url: "deleteKml.php",
            type: "post",
            success: function(resp){
                $("#resultado").html(resp)
            }
        });
        ctaLayer.setMap(null);
        $("#deleteKml").hide();
    }


    function submitRoute(nombre,ciudad,tiempo,vehiculo,publica) {
        var p = "";
        for (var i=0; i<routeArray.length; i++) {
            p += routeArray[i].getPath().getArray().toString() + "\n";
            }
        var s = "";
        for (var j=0; j<markersArray.length; j++) {
            s += markersArray[j].getPosition().toString();
        }
        var parametros = {
            "ruta_id":idRuta,
            "lines" : p,
            "puntos": s,
            "nombre": nombre,
            "ciudad": ciudad,
            "tiempo": tiempo,
            "vehiculo": vehiculo,
            "publica": publica,
            "nocache" : Math.random() // no cache
        };
        $.ajax({
            url:   'saveRoute.php',
            type:  'post',
            data:  parametros,
            success:  function (response) {
                $('#result').html(response);
            }
        });
    }

    function submitPoint(nombre_punto,texto,posicion,idpunto)
    {
        if(idRuta==""){
            alert("Debes crear la ruta primero");
        }
        else
        {
            var parametros = {
                "nombre": nombre_punto,
                "texto": texto,
                "posicion": posicion,
                "idpunto": idpunto,
                "punto": marcador,
                "ruta_id": idRuta
            };
            $.ajax({
                url: "savePunto.php",
                type: "POST",
                data: parametros,
                success: function(resp){
                    $('#resultado').html(resp);
                }
            });
            document.getElementById('img_btn').style.display = 'block';
        }
    }

    function removeMarker(marker){
        var longitud=marker.content.length-1;
        var bien="";
        var numpos=marker.content[longitud];
        var i=1;
        while(marker.content[longitud-i]!='>'){
            numpos=numpos+marker.content[longitud-i];
            i++;
        }
        numpos++;
        if(arrayMarkerId[marker.content[longitud]]!=""){
            $.ajax({
                url: "deletePoint.php",
                type: "POST",
                data: "id="+arrayMarkerId[numpos],
                success: function(responce){
                    bien=responce;
                }
            });
            if(bien=1){
                marker.setMap(null);
            }
            else{
                alert('El punto no ha podido ser eliminado');
            }
        }
        else{
            marker.setMap(null);
        }
    }

    function removePolyline(polyline){
        polyline.setMap(null);
        routeArray=[];
    }

    function codeAddress() {
        var address = document.getElementById("ciudad").value;
        geocoder.geocode( { 'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);

            } else {
                alert("Geocode was not successful for the following reason: " + status);
            }
        });
    }

    function markerAddress() {
        var address = document.getElementById("marker_dir").value;
        geocoder.geocode( { 'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
                var marker = new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location,
                    content: contentwindow()
                });
                marker.content = contentwindow();
                google.maps.event.addListener(marker, 'click', function() {
                    marcador=marker.getPosition().toUrlValue();
                    infoWindow.setContent(this.content);
                    infoWindow.open(map, this);
                });
                google.maps.event.addDomListener(marker, "rightclick", function() {
                    removeMarker(marker);
                });
                markersArray.push(marker);
            }
            else {
                alert("Geocode was not successful for the following reason: " + status);
            }
        });
    }
    function initialize() {
        geocoder = new google.maps.Geocoder();

        var mapOptions = {
            center: centro,
            zoom: 16
        };

        map = new google.maps.Map(document.getElementById('map-canvas'),
            mapOptions);

        var ctaLayer= new google.maps.KmlLayer({
            url:"<?= $infor['url_kml']?>"
        });
        ctaLayer.setMap(map);

        var polylineOptions= {
            path: route
        };

        google.maps.event.addListenerOnce(map, 'idle', function() {

            var polyline= new google.maps.Polyline(polylineOptions);
            if(idRuta!=""){
                routeArray.push(polyline);
            }

            google.maps.event.addDomListener(polyline, "rightclick", function() {
                removePolyline(polyline);
            });
            polyline.setMap(map);

            <?php
            for($i=0;$i<=count($marcadores);$i++){
            ?>
            point= new google.maps.LatLng(<?= $marcadores[$i]['punto_exacto'] ?>);

            infoWindow = new google.maps.InfoWindow({
                maxwidth: "60px",
                content: contentwindow('<?=$marcadores[$i]['nombre']?>','<?=$marcadores[$i]['texto']?>','<?=$marcadores[$i]['idpunto']?>','<?=$marcadores[$i]['imagen']?>','<?=$marcadores[$i]['video']?>','<?=$marcadores[$i]['youtube']?>')
            });

            var marker= new google.maps.Marker({
                position: point,
                content: contentwindow('<?=$marcadores[$i]['nombre']?>','<?=$marcadores[$i]['texto']?>','<?=$marcadores[$i]['idpunto']?>','<?=$marcadores[$i]['imagen']?>','<?=$marcadores[$i]['video']?>','<?=$marcadores[$i]['youtube']?>')
            });

            google.maps.event.addListener(marker, 'click', function() {
                infoWindow.setContent(this.content);
                infoWindow.open(map, this);
            });
            google.maps.event.addDomListener(marker, "rightclick", function() {
                removeMarker(this);
            });
            marker.setMap(map);
            arrayMarkerId[posicion] = "<?=$marcadores[$i]['idpunto']?>";
            <?php
                 }
                ?>

            var drawingManager = new google.maps.drawing.DrawingManager({
                drawingControl: true,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: [
                        google.maps.drawing.OverlayType.MARKER,
                        google.maps.drawing.OverlayType.POLYLINE
                    ]
                },
                markerOptions: {
                    editable: true,
                    draggable: true
                },

                polylineOptions: {
                    editable: true,
                    draggable: true
                }
            });

            drawingManager.setMap(map);

            $(".gmnoprint").each(function() {
                var newObj = $(this).find("[title='Stop drawing']");
                newObj.attr('id', 'btnStop');

                // ID the toolbar
                newObj.parent().parent().attr("id", "btnBar");

                // ID the Marker button
                newObj = $(this).find("[title='Add a marker']");
                newObj.attr('id', 'btnMarker');

                // ID the line button
                newObj = $(this).find("[title='Draw a line']");
                newObj.attr('id', 'btnLine');
            });
            google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) {
                if(event.type == google.maps.drawing.OverlayType.POLYLINE) {
                    google.maps.event.addListener(drawingManager, 'polylinecomplete', function(polyline) {
                        google.maps.event.addDomListener(polyline, "rightclick", function() {
                            removePolyline(polyline);
                        });
                        routeArray.push(polyline);
                    });
                }
                else if(event.type == google.maps.drawing.OverlayType.MARKER) {
                    google.maps.event.addListener(drawingManager, 'markercomplete', function(marker) {
                        marker.content = contentwindow();
                        google.maps.event.addListener(marker, 'click', function() {
                            marcador=marker.getPosition().toUrlValue();
                            infoWindow.setContent(this.content);
                            infoWindow.open(map, this);
                        });
                        google.maps.event.addDomListener(marker, "rightclick", function() {
                            removeMarker(marker);
                        });
                        markersArray.push(marker);
                    });
                }
            });
        });
    }

    google.maps.event.addDomListener(window, 'load', initialize);

    </script>
<?php
}
else
{
    echo '<script>location.href = "index.php";</script>';
}
?>