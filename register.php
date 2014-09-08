<?php
/**
 * Created by PhpStorm.
 * User: Borregana
 * Date: 22/07/14
 * Time: 17.40
 */

include 'connect.php';


//validar variables
$alias= mysqli_real_escape_string($con,$_POST['alias']);
$mail= mysqli_real_escape_string($con,$_POST['mail']);
$password= md5(mysqli_real_escape_string($con,$_POST['password']));
$confirmar= md5(mysqli_real_escape_string($con,$_POST['confirmar']));

if($alias=="" or $mail=="" or $password=="" or $confirmar=="" ){
    echo '<span class="txt-color-redLight login-header-big">Todos los campos son obligatorios</span>';

}
else{
    if($_POST['password'] == $_POST['confirmar']){
//nos guardamos la fecha actual
        $fecha_actual=date('c');
        $fecha=explode('T',$fecha_actual);
        $date=$fecha[0];

        $consulta=mysqli_query($con,"SELECT * FROM Usuarios WHERE alias='$alias'");

        if(mysqli_num_rows($consulta)>0){
            echo '<span class="txt-color-redLight login-header-big">El alias ya esta en uso</span>';
        }
        else{
            $sql="INSERT INTO Usuarios (alias, mail, password,fecha_alta) VALUES ('$alias','$mail','$password','$date')";

            if(!mysqli_query($con, $sql)){
                die('Error'. mysqli_error($con));
            }
            else{
                echo '<span class="txt-color-green login-header-big">Usuario registrado con exito</span>';
                echo '<script>location.href = "index.php";</script>';
            }
        }
    }
    else{
        print_r($_POST);
        echo '<span class="txt-color-redLight login-header-big">Los passwords no coinciden.</span>';

    }
}
mysqli_close($con);

