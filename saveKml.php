<?php
/**
 * Created by PhpStorm.
 * User: Borregana
 * Date: 09/08/14
 * Time: 13.13
 */

session_start();
if(isset($_POST)){
    include 'connect.php';


    $url=mysqli_real_escape_string($con,$_POST['url']);
    $idruta=mysqli_real_escape_string($con,$_POST['idruta']);

    $update=mysqli_query($con,"UPDATE Rutas SET url_kml='$url' WHERE id='$idruta'");

    if($update){
        echo '<span class="txt-color-green">El archivo ha sido cargado con exito </span>';
    }
    else{
        echo '<span class="txt-color-redLight">El archivo no ha podido ser cargado</span>';
    }

}