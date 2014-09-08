<?php
/**
 * Created by PhpStorm.
 * User: Borregana
 * Date: 27/08/14
 * Time: 17.31
 */
session_start();
    $con=mysqli_connect("localhost","al132173","Pablo1987.UJI","al132173");
    if(mysqli_connect_errno()){
        echo "No se pudo conectar con la base de datos".mysqli_connect_error();
    }
