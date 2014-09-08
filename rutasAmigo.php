<?php
/**
 * Created by PhpStorm.
 * User: Borregana
 * Date: 16/08/14
 * Time: 19.51
 */
session_start();

include 'connect.php';


$amigo= mysqli_real_escape_string($con,$_POST['iduser']);

$consulta=mysqli_query($con,"SELECT * FROM Rutas WHERE publica='1' and usuario_id='$amigo' ORDER BY fecha_publicacion DESC ");

if(mysqli_num_rows($consulta)>0){
    echo'<table class="table table-hover">';
    echo'<thead>
        <tr>
        <th>Nombre</th>
        <th>Ciudad</th>
        <th>Fecha</th>
        </tr>
        </thead>';
    while($row=mysqli_fetch_array($consulta)){
        echo '<tr>';
        echo '<td>';
        echo $row['nombre'];
        echo '</td>';
        echo '<td>';
        echo $row['ciudad'];
        echo '</td>';
        echo '<td>';
        echo $row['fecha_publicacion'];
        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';
}
else{
    echo '<span class="txt-color-red"> El usuario no tiene rutas</span>';
}
