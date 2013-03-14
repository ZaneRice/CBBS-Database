<?php
/*
 * Creates a file named databaseTables.csv which can be read
 * in by Excel.
 *
 * The file is formatted to be human-readable and will contain the
 * data from all tables listed in $tableNames
 */
require '../excelFunctions.php';

$database = mysqli_connect("","","","");

/* 
 * The names of the tables which need to be written in an Excel
 * readable format.
 */
$tableNames = array("Mentor", "Mentee");

/*
 * The name of the file which will contain the table data in an Excel
 * readable format
 */
$fileName = "databaseTables.csv";

// Check connection
if (mysqli_connect_errno($database))
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

exportTablesToExcel($database,$tableNames,$fileName);

mysqli_close($database);
?>
