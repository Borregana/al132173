<?php
/**
 * Created by PhpStorm.
 * User: Borregana
 * Date: 10/08/14
 * Time: 17.02
 */
session_start();
if(isset($_POST)){
    include 'connect.php';


    $idruta=mysqli_real_escape_string($con,$_POST['idruta']);

    $update=mysqli_query($con,"UPDATE Rutas SET url_kml='' WHERE id='$idruta'");

    if($update){
        echo'<span class="txt-color-green">La capa ha sido eliminada con exito</span>';
    }
    else{
        echo'<span class="txt-color-redLight">La capa no ha podido ser eliminada</span>';
    }
    mysql_close($con);
}