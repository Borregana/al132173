<?php

session_start();
include 'connect.php';

//primero guardamos la imagen
if(isset($_POST))
{
    $Destination = 'img/img_users';

    if(!isset($_FILES['imagen']) || !is_uploaded_file($_FILES['imagen']['tmp_name']))
    {
        $img=$_SESSION['imagen'];
    }
    else{
        $RandomNum   = rand(0, 9999999999);

        $ImageName      = str_replace(' ','-',strtolower($_FILES['imagen']['name']));
        $ImageType      = $_FILES['imagen']['type']; //"image/png", image/jpeg etc.

        $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
        $ImageExt = str_replace('.','',$ImageExt);

        $ImageName      = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);

        //Create new image name (with random number added).
        $NewImageName = $ImageName.'-'.$RandomNum.'.'.$ImageExt;

        move_uploaded_file($_FILES['imagen']['tmp_name'], "$Destination/$NewImageName");
        $img=$Destination.'/'.$NewImageName;
        chmod($img, 0644);
    }

    $nombre= mysqli_real_escape_string($con,$_POST['nombre']);
    $apellidos= mysqli_real_escape_string($con,$_POST['apellidos']);
    $alias= mysqli_real_escape_string($con,$_POST['alias']);
    $mail= mysqli_real_escape_string($con,$_POST['mail']);
    $direccion= mysqli_real_escape_string($con,$_POST['direccion']);
    $imagen=mysqli_real_escape_string($con,$img);
    $usuario= mysqli_real_escape_string($con,$_SESSION['usuario_id']);


    $result= mysqli_query($con, "UPDATE Usuarios SET nombre='$nombre', apellidos='$apellidos',
        alias='$alias', mail='$mail', direccion='$direccion' , imagen='$imagen' WHERE id='$usuario'");

    if($result)
    {
        $_SESSION['alias']=$alias;
        $_SESSION['imagen']=$img;
        echo '<script>location.href = "edituser.php";</script>';
    }
}

mysqli_close($con);

