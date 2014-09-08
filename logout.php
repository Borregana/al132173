<?php
/**
 * Created by PhpStorm.
 * User: Borregana
 * Date: 22/07/14
 * Time: 13.22
 */
    session_start();
    session_destroy();

    header('location: index.php');
?>