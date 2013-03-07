<?php
/*
 * This script will write all of the data in the tables listed in the
 * $tableNames array to a file named $filename using the database
 * connected to by $database 
 */
require 'excelFunctions.php';

$database = mysqli_connect("","","","");

/* 
 * The names of the tables which need to be written in an Excel
 * readable format.
 */
$tableNames = array("Mentor", "Mentee");

/*
 * The name of the fiel which will contain the table data in an Excel
 * readable format
 */
$fileName = "databaseTables.csv";

// Check connection
if (mysqli_connect_errno($database))
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

exportTablesToExcel($database,$tableNames,$fileName);

mysqli_close($database);
?>
