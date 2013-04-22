<?php
require '../includes.php';

//$database = mysqli_connect("","","","");
$database = mysqli_connect("oss-ci.cs.clemson.edu","cpsc472","myDB4dmin","cpsc472");
/*
$mentor = new Mentor;
$mentee = new Mentee;
$mentor->clear($database);
$mentee->clear($database);
 */
importDatabase($database,"../ExportDatabase/databaseTables.csv");
?>
