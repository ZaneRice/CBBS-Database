<?php
/*
 * This script will parse a CSV file of the format
 * created by exportDatabase() and modify the data
 * in the database to match the CSV file.
 */
require 'importDatabaseFunctions.php';

/* Parse command line arguments */
if(count($argv) != 7)
{
    print("Usage: php $argv[0] hostName userName password databaseName path/to/file.csv\n");
    exit();
}
$hostName = $argv[1];
$userName = $argv[2];
$password = $argv[3];
$databaseName = $argv[4];
$convert = $argv[5];
$oldDatabase = $argv[6];

$database = mysqli_connect($hostName,$userName,$password,$databaseName);

// Check connection
if (mysqli_connect_errno($database))
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

importDatabase($database,$file);
?>
