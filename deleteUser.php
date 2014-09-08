<?php
/**
 * Created by PhpStorm.
 * User: Borregana
 * Date: 27/08/14
 * Time: 16.56
 */
session_start();
if(isset($_POST['iduser'])){
    include 'connect.php';


    $id=mysqli_real_escape_string($con,$_POST['iduser']);
    $pass = md5(mysqli_real_escape_string($con, $_POST['pass']));
    $consulta = mysqli_query($con, "SELECT * FROM Usuarios WHERE id = '$id' AND password = '$pass'");
    if (mysqli_num_rows($consulta) > 0)
    {
        $delete=mysqli_query($con, "DELETE FROM Usuarios WHERE id='$id'");
        if($delete){
            echo 'La cuenta ha sido eliminada';
            echo '<script>location.href = "logout.php";</script>';
        }
        else{
            echo 'La cuenta no ha podido se eliminada';
        }
    }
    else{
        echo'<span class="txt-color-redLight">El password es incorrecto.</span>';
    }
    mysqli_close($con);
}