<?php
session_start();
require_once("database.php");
require_once("functions.php");
$dbh = new DataBaseHelper("localhost","root", "", "gestionale_eventi", "3307");
var_dump($dbh);
?>