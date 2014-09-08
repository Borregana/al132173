<?php
/**
 * Created by PhpStorm.
 * User: Borregana
 * Date: 06/08/14
 * Time: 17.48
 */


session_start();
include 'connect.php';


//primero guardamos la imagen
if(isset($_POST['idpuntoimg']))
{

    $Destination = 'img/img_markers';

    if(!isset($_FILES['img_punto']) || !is_uploaded_file($_FILES['img_punto']['tmp_name']))
    {
        $img=mysqli_real_escape_string($con,$_POST['imgold']);
    }
    else{
        $RandomNum   = rand(0, 9999999999);

        $ImageName      = str_replace(' ','-',strtolower($_FILES['img_punto']['name']));
        $ImageType      = $_FILES['img_punto']['type']; //"image/png", image/jpeg etc.

        $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
        $ImageExt = str_replace('.','',$ImageExt);

        $ImageName      = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);

        //Create new image name (with random number added).
        $NewImageName = $ImageName.'-'.$RandomNum.'.'.$ImageExt;

        move_uploaded_file($_FILES['img_punto']['tmp_name'], "$Destination/$NewImageName");

        $img=$Destination.'/'.$NewImageName;
        chmod($img, 0644);

    }
        $id=mysqli_real_escape_string($con,$_POST['idpuntoimg']);
        $idruta=mysqli_real_escape_string($con,$_POST['idrut']);
        $imagen=mysqli_real_escape_string($con,$img);

        $result= mysqli_query($con, "UPDATE Puntos SET imagen='$imagen' WHERE id='$id'");

        if($result)
        {
            $_SESSION['img_ruta']=$idruta;
            echo '<script>location.href = "display.php";</script>';
        }
        else{
            die('No ha podio ser');
        }


}
mysqli_close($con);


