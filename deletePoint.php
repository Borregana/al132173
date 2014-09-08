<?php
/**
 * Created by PhpStorm.
 * User: Borregana
 * Date: 30/07/14
 * Time: 18.35
 */

error_reporting(0);
session_start();
include 'connect.php';


$id=mysqli_real_escape_string($con,$_POST['id']);

$delete="DELETE FROM Puntos WHERE id='$id'";

if(mysqli_query($con,$delete)){
    echo 1;
}
else{
    echo 0;
}