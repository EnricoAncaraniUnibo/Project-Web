<?php
session_start();

require_once __DIR__ . "/database.php";
require_once __DIR__ . "/functions.php";
//require_once("database.php");
//require_once("functions.php");
$dbh = new DataBaseHelper("localhost","root", "", "gestionale_eventi", "3307");
?>