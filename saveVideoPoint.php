<?php
/**
 * Created by PhpStorm.
 * User: Borregana
 * Date: 20/08/14
 * Time: 16.36
 */

session_start();
include 'connect.php';


//primero guardamos el video
if(isset($_POST['idpuntovid']))
{
    $Destination = 'video/video_marker';

    if(!isset($_FILES['video_punto']) || !is_uploaded_file($_FILES['video_punto']['tmp_name']))
    {
        if($_POST['vidold']!=""){
            $video=mysqli_real_escape_string($con,$_POST['vidold']);
        }
        else{
            $video="";
        }
    }
    else{
        $RandomNum   = rand(0, 9999999999);

        $VideoName      = str_replace(' ','-',strtolower($_FILES['video_punto']['name']));
        $VideoType      = $_FILES['video_punto']['type'];

        $VideoExt = substr($VideoName, strrpos($VideoName, '.'));
        $VideoExt = str_replace('.','',$VideoExt);
        if($VideoExt=='mp4' or $VideoExt=='ogg' or $VideoExt=='webm'){

            $VideoName      = preg_replace("/\.[^.\s]{3,4}$/", "", $VideoName);

            $NewVideoName = $VideoName.'-'.$RandomNum.'.'.$VideoExt;

            move_uploaded_file($_FILES['video_punto']['tmp_name'], "$Destination/$NewVideoName");

            $video=$Destination.'/'.$NewVideoName;
            chmod($img, 0644);
        }
        else{
            $video=mysqli_real_escape_string($con,$_POST['vidold']);
        }
    }
    $id=mysqli_real_escape_string($con,$_POST['idpuntovid']);
    $idruta=mysqli_real_escape_string($con,$_POST['idrut']);
    $vid=mysqli_real_escape_string($con,$video);

    //guardamos el video subido desde DD
    $result= mysqli_query($con, "UPDATE Puntos SET video='$vid' WHERE id='$id'");

    //Guardamos el ID del video de youtube
    $yflag=true;
    if($_POST['youtube']!=""){
        $youtube=mysqli_real_escape_string($con,$_POST['youtube']);
        $result_y= mysqli_query($con, "UPDATE Puntos SET youtube='$youtube' WHERE id='$id'");
        if(!$result_y){
            $yflag=false;
        }
    }


    if($result and $yflag)
    {
        $_SESSION['img_ruta']=$idruta;
        echo '<script>location.href = "display.php";</script>';
    }
    else{
        die('No ha podio ser');
    }



}
mysqli_close($con);


