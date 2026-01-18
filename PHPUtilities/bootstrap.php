<?php
require_once("database.php");
$dbh = new DataBaseHelper("localhost","root", "", "gestionale_eventi", "3307");
var_dump($dbh);
?>