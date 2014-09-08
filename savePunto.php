<?php
/**
 * Created by PhpStorm.
 * User: Borregana
 * Date: 21/07/14
 * Time: 18.52
 */

error_reporting(0);
session_start();
include 'connect.php';

@mysqli_query($con, "SET NAMES 'utf8'");

$nombre = mysqli_real_escape_string($con, $_POST['nombre']);
$texto= mysqli_real_escape_string($con, $_POST['texto']);
$punto = mysqli_real_escape_string($con, $_POST['punto']);
$usuario_id = mysqli_real_escape_string($con, $_SESSION['usuario_id']);
$ruta_id = mysqli_real_escape_string($con, $_POST['ruta_id']);
$idpunto= mysqli_real_escape_string($con, $_POST['idpunto']);
$pos=$_POST['posicion'];

$comprobar="SELECT * FROM Puntos WHERE id='$idpunto'";
$res_comp=mysqli_query($con,$comprobar);

if(mysqli_num_rows($res_comp)==0){
    $insert = mysqli_query($con, "INSERT INTO Puntos (ruta_id,usuario_id,nombre,punto_exacto,texto)
                                  VALUES ('$ruta_id','$usuario_id','$nombre','$punto', '$texto')");

    if ($insert)
    {
        $consulta="SELECT * FROM Puntos WHERE id='$idpunto'";
        $result=mysqli_query($con,$consulta);

        if(mysqli_num_rows($result)>0){
            $sol=mysqli_fetch_array($result)['id'];

            ?>
            <script> arrayMarkerId[<?= $pos ?>] = <?= $sol ?>;</script>
        <?php
        }
        $_SESSION['img_ruta']=$ruta_id;
        echo'<span class="txt-color-green login-header-big">El punto ha sido guardado con exito</span>';
        echo '<script>location.href = "display.php";</script>';
    }
    else
    {
        echo '<span class="txt-color-redLight login-header-big">El punto no ha sido guardado correctamente.</span>';
    }
}
else{
    $update = mysqli_query($con, "UPDATE Puntos SET nombre='$nombre', texto='$texto'
                                    WHERE id='$idpunto'");
    if($update){
        echo'<span class="txt-color-green login-header-big">El punto ha sido modificado con exito</span>';
    }
    else{
        echo'<span class="txt-color-green login-header-big">El punto no ha podido ser modificado con exito</span>';
    }
}
    mysqli_close($con);
?>