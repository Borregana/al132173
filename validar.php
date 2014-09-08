<?php
/**
 * Created by PhpStorm.
 * User: Borregana
 * Date: 26/07/14
 * Time: 13.29
 */

error_reporting(0);
session_start();
include 'connect.php';

@mysqli_query($con, "SET NAMES 'utf8'");
if ($_POST['user'] == null || $_POST['pass'] == null)
{
    echo '<span>Por favor completa todos los campos.</span>';
}
else
{
    $user = mysqli_real_escape_string($con, $_POST['user']);
    $pass = md5(mysqli_real_escape_string($con, $_POST['pass']));
    $consulta = mysqli_query($con, "SELECT * FROM Usuarios WHERE alias = '$user' AND password = '$pass'");
    if (mysqli_num_rows($consulta) > 0)
    {
        $row=mysqli_fetch_array($consulta);
        $_SESSION["alias"] = $user;
        $_SESSION["usuario_id"]=$row['id'];
        $_SESSION["imagen"]=$row['imagen'];
        echo '<script>location.href = "display.php"</script>';
    }
    else
    {
        echo '<span class="txt-color-redLight ">El usuario y/o clave son incorrectas, vuelva a intentarlo.</span>';
    }
    mysqli_close($con);

}
?>

