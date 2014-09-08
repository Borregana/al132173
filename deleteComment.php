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


    $idcom=mysqli_real_escape_string($con,$_POST['idcom']);

    $comentario=mysqli_query($con,"DELETE FROM Comentarios WHERE id='$idcom' ");

    if($comentario){

        echo '<span>El comentario ha sido borrado con exito</span>';
        echo '<script>location.href="vistaPublica.php"</script>';
    }
    else{
        echo '<span>El comentario no ha podido ser eliminado</span>';
    }
    mysql_close($con);
}