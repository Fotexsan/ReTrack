<?php
//Session auflösen und zur homepage leiten
session_start();
session_unset();
session_destroy();
header("Location:../../homepage.php");
die();
?>