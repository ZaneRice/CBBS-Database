<?php
require '../includes.php';

function clear($database)
{
    $mentor = new Mentor;
    $mentee = new Mentee;

    //First clear Mentor and Mentee tables in the database
    $mentor->clear($database);
    $mentee->clear($database);
}

function export($database)
{
    exportTablesToExcel($database, 
	Array("Mentor","Mentee"),
	"databaseTable.csv");
}

function import($database)
{
    importDatabase($database,"originalDatabaseTables.csv");
}

$database = mysqli_connect("oss-ci.cs.clemson.edu",
    "cpsc472","myDB4dmin","cpsc472");

clear($database);
export($database);
//import($database);
?>
