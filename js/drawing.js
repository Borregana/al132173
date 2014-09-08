var markersArray = [];
var routeArray = [];
var idRuta="";
var marcador="";
var posicion=0;
var arrayMarkerId=[];

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

function submitPoint(nombre_punto,texto,posicion)
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
    }
}

function removeMarker(marker){
    var longitud=marker.content.length-1;
    var bien="";

    if(arrayMarkerId[marker.content[longitud]]!=""){
        $.ajax({
            url: "deletePoint.php",
            type: "POST",
            data: "id="+arrayMarkerId[marker.content[longitud]],
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


function initialize() {

    var mapOptions = {
        center: new google.maps.LatLng(39.8867882,-0.0867385,15),
        zoom: 17
    };

    var map = new google.maps.Map(document.getElementById('map-canvas'),
        mapOptions);

    function contentwindow() {


        var contentString = '<div>'+
            '<form  id="punto" action="return false" onsubmit="return false" class="smart-form client-form" method="post">'+
            '<header>'+
            'Punto de Interes'+
            '</header>'+
            '<div id="resultado"></div>'+
            '<fieldset>'+
            '<section>'+
            '<label class="input"> <i class="icon-append fa fa-picture-o"></i>'+
            '<input type="text" id="nombre_punto" name="nombre_punto" placeholder="Nombre" required="required">'+
            '<b class="tooltip tooltip-bottom-right">Nombre del punto</b> </label>'+
            '</section>'+
            '<section>' +
            '<label class="label"></label>'+
            '<label class="textarea"><i class="icon-append fa fa-comment-o"></i>'+
            '<textarea id="texto" name="texto" rows="2" placeholder="Cuentanos..."></textarea> '+
            '<b class="tooltip tooltip-bottom-right">Algo que decir?</b> </label>'+
            '</section>'+
            '<section>' +
            '<input id="posicion" type="hidden" value='+posicion+'>'+
            '</section>'+
            '</fieldset>'+
            '<footer>'+
            '<button class="btn btn-primary" onclick=submitPoint(document.getElementById("nombre_punto").value,document.getElementById("texto").value,document.getElementById("posicion").value);>'+
            'Guardar'+
            '</form>'+
            '</div>';

        result= contentString+posicion;
        posicion++;
        return result;
    }
    var infoWindow = new google.maps.InfoWindow({
        content: contentwindow()
    });

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
            draggable: true,
        }
    });

    drawingManager.setMap(map);

    google.maps.event.addListenerOnce(map, 'idle', function() {
        // do something only the first time the map is loaded
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

        //$("#btnBar").append('<div style="float: left; line-height: 0;"><div id="btnRoute" style="direction: ltr; overflow: hidden; text-align: left; position: relative; color: rgb(51, 51, 51); font-family: Arial,sans-serif; -moz-user-select: none; font-size: 13px; background: none repeat scroll 0% 0% rgb(255, 255, 255); padding: 4px; border-width: 1px 1px 1px 0px; border-style: solid solid solid none; border-color: rgb(113, 123, 135) rgb(113, 123, 135) rgb(113, 123, 135) -moz-use-text-color; -moz-border-top-colors: none; -moz-border-right-colors: none; -moz-border-bottom-colors: none; -moz-border-left-colors: none; border-image: none; box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.4); font-weight: normal;" title="Delete"><span style="display: inline-block;"><div style="width: 16px; height: 16px; overflow: hidden; position: relative;"><img style="position: absolute; left: 0px; top: -195px; -moz-user-select: none; border: 0px none; padding: 0px; margin: 0px; width: 16px; height: 350px;" src="img/goma.png" draggable="false"></div></span></div></div>');

        //$("#btnBar").append('<div style="float: left; line-height: 0;"><div id="btnSave" style="direction: ltr; overflow: hidden; text-align: left; position: relative; color: rgb(51, 51, 51); font-family: Arial,sans-serif; -moz-user-select: none; font-size: 13px; background: none repeat scroll 0% 0% rgb(255, 255, 255); padding: 4px; border-width: 1px 1px 1px 0px; border-style: solid solid solid none; border-color: rgb(113, 123, 135) rgb(113, 123, 135) rgb(113, 123, 135) -moz-use-text-color; -moz-border-top-colors: none; -moz-border-right-colors: none; -moz-border-bottom-colors: none; -moz-border-left-colors: none; border-image: none; box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.4); font-weight: normal;" title="Save Route"><span style="display: inline-block;"><div style="width: 16px; height: 16px; overflow: hidden; position: relative;"><img style="position: absolute; left: 0px; top: -195px; -moz-user-select: none; border: 0px none; padding: 0px; margin: 0px; width: 16px; height: 350px;" src="img/save.png" draggable="false"></div></span></div></div>');

        //google.maps.event.addDomListener(document.getElementById('btnSave'), 'click', submitRoute);
        //google.maps.event.addDomListener(document.getElementById('btnRoute'), 'click', submitRoute);

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

