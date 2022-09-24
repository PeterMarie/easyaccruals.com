<?php
    require_once('../inc/functions.php');
    require_once('../inc/db.php');

    start_session('acclzsess');

    if(isset($_SESSION['logged_in'])){
        unset($_SESSION['logged_in']);
    }
    if(isset($_SESSION['admin'])){
        unset($_SESSION['admin']);
    }

    header("location: index.php");
?>