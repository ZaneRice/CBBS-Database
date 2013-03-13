<?php
/*
 * Creates a file named testTables.csv which can be read
 * in by Excel.
 *
 * The file is formatted to be human-readable and will contain the
 * data from all tables listed in $tableNames
 */
require 'excelFunctions.php';

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
$filename = "testTables.csv";

// Check connection
if (mysqli_connect_errno($database))
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$toWrite = "";

for ($i = 0; $i < count($tableNames); $i++)
{
    $tableName = $tableNames[$i];
    $query = "SELECT * FROM $tableName WHERE IsMentor = 1";
    $label = $tableName;
    $toWrite = $toWrite . toExcel($database,$query,$label);
}

for ($i = 0; $i < count($tableNames); $i++)
{
    $tableName = $tableNames[$i];
    $query = "SELECT * FROM $tableName WHERE IsMentor = 0";
    $label = $tableName;
    $toWrite = $toWrite . toExcel($database,$query,$label);
}

mysqli_close($database);

/* Write the output to an Excel readable file */
$outputFile = fopen($filename,"w+");
fwrite($outputFile,$toWrite);
fclose($outputFile);
?>
