<?php
/**
 * Created by PhpStorm.
 * User: Borregana
 * Date: 16/08/14
 * Time: 12.47
 */
session_start();

include 'connect.php';


$usuario= mysqli_real_escape_string($con,$_SESSION['usuario_id']);
$amigo_id= mysqli_real_escape_string($con,$_POST['iduser']);

$eliminar=mysqli_query($con,"DELETE FROM Grupos WHERE usuario_id='$usuario' and amigo_id='$amigo_id'");

if($eliminar){
    echo '<span class="txt-color-green">El usuario se ha eliminado de tu lista de amigos </span> ';
    echo '<script>location.href="edituser.php"</script>';
}
else{
   echo '<span class="txt-color-red">El usuario no se ha podido eliminar de tu lista de amigos </span> ';
}

mysql_close($con);
