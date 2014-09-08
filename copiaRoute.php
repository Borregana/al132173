<?php
/**
 * Created by PhpStorm.
 * User: Borregana
 * Date: 05/08/14
 * Time: 13.06
 */

session_start();
if(isset($_SESSION['alias'])){
    include 'connect.php';
    $idruta=mysqli_real_escape_string($con,$_POST['idruta']);

    $consulta="SELECT * FROM Rutas WHERE id='$idruta'";
    $resultado=mysqli_query($con,$consulta);

    if(mysqli_num_rows($resultado) > 0){
        $row=mysqli_fetch_array($resultado);
        $inforuta0=mysqli_real_escape_string($con,$row['nombre']);
        $inforuta1=mysqli_real_escape_string($con,$row['ciudad']);
        $inforuta2=mysqli_real_escape_string($con,$row['marcadores']);
        $inforuta3=mysqli_real_escape_string($con,$row['recorrido']);
        $inforuta4=mysqli_real_escape_string($con,$row['tiempo']);
        $inforuta5=mysqli_real_escape_string($con,$row['vehiculo']);
        $inforuta6=mysqli_real_escape_string($con,$_SESSION['usuario_id']);
        $num_copias=mysqli_real_escape_string($con,$row['num_copias']);
        $url_kml=mysqli_real_escape_string($con,$row['url_kml']);

        //nos guardamos la fecha actual
        $fecha_actual=date('c');
        $fecha=explode('T',$fecha_actual);
        $date=$fecha[0];
        //Ampliamos el numero de copias en la BD
        $num_copias++;
        $suma_copia="UPDATE Rutas SET num_copias='$num_copias' WHERE id='$idruta'";
        $res_n_copias=mysqli_query($con,$suma_copia);
        //construimos el nombre de la copia
        $nombre_copia=$inforuta0.'_copia_'.$num_copias;

        $insert="INSERT INTO Rutas (nombre,ciudad,marcadores,recorrido,tiempo,vehiculo,usuario_id,fecha_publicacion,url_kml)
            VALUES ('$nombre_copia','$inforuta1','$inforuta2','$inforuta3','$inforuta4','$inforuta5','$inforuta6','$date','$url_kml')";

        $res_insert=mysqli_query($con,$insert);

        //Buscamos la nueva id de la ruta insertada
        $busca_nueva_id="SELECT * FROM Rutas WHERE nombre='$nombre_copia'";
        $res_id=mysqli_query($con,$busca_nueva_id);
        $idruta_copia=mysqli_fetch_array($res_id)['id'];

        $cons_puntos="SELECT * FROM Puntos WHERE ruta_id='$idruta'";
        $res_puntos=mysqli_query($con,$cons_puntos);
        $todo_ok=true;
        if(mysqli_num_rows($res_puntos)>0){
            while($row=mysqli_fetch_array($res_puntos)){
                $infopunto0=mysqli_real_escape_string($con,$idruta_copia);
                $infopunto1=mysqli_real_escape_string($con,$_SESSION['usuario_id']);
                $infopunto2=mysqli_real_escape_string($con,$row['nombre']);
                $infopunto3=mysqli_real_escape_string($con,$row['punto_exacto']);
                $infopunto4=mysqli_real_escape_string($con,$row['imagen']);
                $infopunto5=mysqli_real_escape_string($con,$row['texto']);
                $infopunto6=mysqli_real_escape_string($con,$row['video']);

                $insert_punto="INSERT INTO Puntos (ruta_id,usuario_id,nombre,punto_exacto,imagen,texto,video)
                        VALUES ('$infopunto0','$infopunto1','$infopunto2','$infopunto3','$infopunto4','$infopunto5','$infopunto6')";
                $res_insert_puntos=mysqli_query($con,$insert_punto);
                if(!$res_insert_puntos){
                    $todo_ok=false;
                }
            }
        }

        if($res_insert and $todo_ok){
            echo '<span class="txt-color-green login-header-big"><i><b>La ruta ha sido copiada satisfactoriamente</b></i></span>';
        }
        else{
            echo '<span class="txt-color-red login-header-big">La ruta no ha podido ser copiada</span>';

        }
    }
    else{
        echo '<span class="txt-color-red login-header-big">La ruta ha sido borrada y no se puede copiar</span>';
    }
}
