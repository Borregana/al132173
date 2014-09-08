<?php
/**
 * Created by PhpStorm.
 * User: Borregana
 * Date: 07/08/14
 * Time: 11.26
 */

if(isset($_POST)){
    include 'connect.php';

    $id=mysqli_real_escape_string($con,$_POST['idruta']);

    $update="UPDATE Rutas SET publica=1 WHERE id='$id'";
    $resultado=mysqli_query($con,$update);

    if($resultado){
        echo '<script>location.href="misrutas.php";</script>';
    }
    mysql_close($con);
}