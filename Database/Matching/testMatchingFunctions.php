<?php
require '../includes.php';

function connect()
{
    //$database = mysqli_connect("","","","");
    $database = mysqli_connect("oss-ci.cs.clemson.edu","cpsc472","myDB4dmin","cpsc472");

    // Check connection
    if (mysqli_connect_errno($database))
    {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	exit();
    }
    else
	return $database;
}

function testAddMatch($database)
{
    $mentor = new Mentor;
    $mentee = new Mentee;

    //Remove Mentor in case it is already there
    $mentor->removeMentor($database, "TempMentor@nothing.com");
    
    //Remove Mentee just in case it is already there
    $query = $mentee->removeMentee($database,"TempMentee@nothing.com");
    
    //Remove Mentee just in case it is already there
    $query = $mentee->removeMentee($database,"TempMentee2@nothing.com");

    //Insert Mentee
    $query = $mentee->addMentee($database,
	Array("Email","MatchedWith"),
	Array("TempMentee@nothing.com",""));
    
    //Insert Mentee
    $query = $mentee->addMentee($database,
	Array("Email","MatchedWith"),
	Array("TempMentee2@nothing.com",""));
    
    //Insert Mentor
    $query = $mentor->addMentor($database,
	Array("Email","MatchedWith"),
	Array("TempMentor@nothing.com",""));



    addMatch($database,"TempMentor@nothing.com","TempMentee@nothing.com");
    addMatch($database,"TempMentor@nothing.com","TempMentee2@nothing.com");
}

$database = connect();
testAddMatch($database);
//Honestly, remove match is better tested when you
//call removeMentor() and removeMentee() in the
//Mentor and Mentee test scipts. So no real need
//to test it here.
?>
