<?php
session_start();
if(isset($_POST)){
    if($_POST['nombre']!=""){

        include 'connect.php';

        $lines= mysqli_real_escape_string($con,$_POST['lines']);
        $markers= mysqli_real_escape_string($con,$_POST['puntos']);
        $city= mysqli_real_escape_string($con,$_POST['ciudad']);
        //El usuario introduce minutos, pero la BD lo recoge como segundos
        //antes de guardarlo convertiremos los segundos en minutos
        $time= mysqli_real_escape_string($con,$_POST['tiempo']);
        $real_time= $time;
        $vehicle= mysqli_real_escape_string($con,$_POST['vehiculo']);
        $usuario= mysqli_real_escape_string($con,$_SESSION['usuario_id']);
        $idruta=mysqli_real_escape_string($con,$_POST['ruta_id']);
        $publica=mysqli_real_escape_string($con,$_POST['publica']);
        $name= mysqli_real_escape_string($con,$_POST['nombre']);

        //nos guardamos la fecha actual
        $fecha_actual=date('c');
        $fecha=explode('T',$fecha_actual);
        $date=$fecha[0];

        // si la ruta no existe
        if($idruta==""){

            //Comprobamos que el nombre no este en uso
            $consul_nombre="SELECT nombre FROM Rutas WHERE nombre='$name'";
            $res_nombre=mysqli_query($con,$consul_nombre);
            if(mysqli_num_rows($res_nombre)>0){
                echo "<span class='txt-color-redLight'>El nombre ya esta en uso, intente con otro</span>";
            }

            else{
                $sql="INSERT INTO Rutas (nombre,ciudad,marcadores,recorrido,tiempo,vehiculo,usuario_id,publica,fecha_publicacion)
     VALUES ('$name','$city', '$markers','$lines','$real_time','$vehicle','$usuario','$publica','$date')";


                if(!mysqli_query($con, $sql)){
                    die('Error'. mysqli_error($con));
                }
                else{
                    $consulta=mysqli_query($con, "SELECT * FROM Rutas WHERE nombre='$name'");

                    if($consulta)
                    {
                        $ruta_id=mysqli_fetch_array($consulta)['id'];
                        $_SESSION['img_ruta']=$ruta_id;
                        ?>

                        <script> idRuta = <?= $ruta_id ?>;</script>

                        <?php
                        echo '<span class="txt-color-green login-header-big">La Ruta ha sido creada con exito</span>';
                    }
                }
            }
        }
        // Si al ruta existe
        else{
            $sql="UPDATE Rutas SET nombre='$name',ciudad='$city',marcadores='$markers',recorrido='$lines', tiempo='$real_time',vehiculo='$vehicle',publica='$publica'
              WHERE id='$idruta'";

            if(!mysqli_query($con, $sql)){
                die('Error'. mysqli_error($con));
            }
            else{
                $_SESSION['img_ruta']=$ruta_id;
                echo '<span class="txt-color-green login-header-big">La Ruta ha sido modificada con exito</span>';
            }
        }
        mysqli_close($con);

    }
}


