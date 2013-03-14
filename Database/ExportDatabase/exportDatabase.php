<?php
/*
 * Creates a file named databaseTables.csv which can be read
 * in by Excel.
 *
 * The file is formatted to be human-readable and will contain the
 * data from all tables listed in $tableNames
 */
require '../ExcelFunctions/excelFunctions.php';

if(count($argv) < 7)
{
    print("Usage: php $argv[0] hostName userName password databaseName outputFileName [ListOfTablesToExport]\n");
    exit();
}
$hostName = $argv[1];
$userName = $argv[2];
$password = $argv[3];
$databaseName = $argv[4];
/*
 * The name of the file which will contain the table data in an Excel
 * readable format
 */
$fileName = $argv[5];

/* 
 * The names of the tables which need to be written in an Excel
 * readable format.
 */
$tableNames = array();
for($i = 6; $i < count($argv); $i++)
{
    $tableNames[$i-6] = $argv[$i];
}

var_export($tableNames);
print("\n");

$database = mysqli_connect($hostName,$userName,$password,$databaseName);
// Check connection
if (mysqli_connect_errno($database))
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

exportTablesToExcel($database,$tableNames,$fileName);

mysqli_close($database);
?>
