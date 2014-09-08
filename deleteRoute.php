<?php
/**
 * Created by PhpStorm.
 * User: Borregana
 * Date: 06/08/14
 * Time: 16.28
 */
    session_start();
if(isset($_POST)){
    include 'connect.php';


    $idruta=mysqli_real_escape_string($con,$_POST['idruta']);

    $puntos=mysqli_query($con,"DELETE FROM Puntos WHERE ruta_id='$idruta' ");
    $comentarios=mysqli_query($con,"DELETE FROM Comentarios WHERE ruta_id='$idruta' ");
    $ruta=mysqli_query($con,"DELETE FROM Rutas WHERE id='$idruta' ");

    if($ruta){

        echo '<span>La ruta ha sido borrada con exito</span>';
        echo '<script>location.href="misrutas.php";</script>';
    }
    else{
        echo '<span>La ruta no ha podido ser eliminada</span>';
    }
    mysql_close($con);
}