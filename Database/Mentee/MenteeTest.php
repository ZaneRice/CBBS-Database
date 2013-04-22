<?php

/* removeMentor test
 *
 * First, INSERT a temporary Mentor and Mentee
 * 	--Make sure that the temp Mentee is MatchedWith the
 * 	  temporary Mentor
 * Then, run removeMentor on the temporary Mentor
 * End result should be that the Mentor's row will be deleted
 * and the Mentee's row should be updated.
 */
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

function testRemoveMentee()
{
    $database = connect();

    $mentor = new Mentor;
    $mentee = new Mentee;

    //Remove Mentee just in case it is already there
    $query = $mentee->removeMentee($database,"TempMentee@nothing.com");

    //Insert Mentee
    $query = $mentee->addMentee($database,
	Array("Email","MatchedWith"),
	Array("TempMentee@nothing.com",
	"AnotherMentor2@nothing.com,TempMentor@nothing.com,AnotherMentor@nothing.com"));
    
    //Remove Mentee just in case it is already there
    $query = $mentee->removeMentee($database,"TempMentee2@nothing.com");

    //Insert Mentee
    $query = $mentee->addMentee($database,
	Array("Email","MatchedWith"),
	Array("TempMentee2@nothing.com",
	"TempMentor@nothing.com,AnotherMentor@nothing.com"));

    //Remove Mentor in case it is already there
    $mentor->removeMentor($database, "TempMentor@nothing.com");
    
    //Insert Mentor
    $query = $mentor->addMentor($database,
	Array("Email","MatchedWith"),
	Array("TempMentor@nothing.com","TempMentee@nothing.com"));
    
    //Remove Mentor in case it is already there
    $mentor->removeMentor($database, "TempMentor2@nothing.com");
    
    //Insert Mentor
    $query = $mentor->addMentor($database,
	Array("Email","MatchedWith"),
	Array("TempMentor2@nothing.com","TempMentee2@nothing.com,TempMentee@nothing.com"));

    //Call Mentee->removeMentee()
    $mentee = new Mentee;
    $mentee->removeMentee($database, "TempMentee@nothing.com");

    mysqli_close($database);
}

function testAddMentee()
{
    $database = connect();
    $mentee = new Mentee;
    $mentee->addMentee($database,
	Array("Email","MatchedWith"),
	Array("TempMentee@nothing.com","TempMentor@nothing.com"));
    mysqli_close($database);
}

function testGetMatchedWith()
{
    //testAddMentee();
    $database = connect();
    $mentee   = new Mentee;
    print "\nShould be 'TempMentor@nothing.com'\n";
    print $mentee->getMatchedWith($database,"TempMentee@nothing.com");
    print "\n";
    mysqli_close($database);
}

function testSetMatchedWith()
{
    //testAddMentee();
    $database = connect();
    $mentee   = new Mentee;
    $mentee->setMatchedWith($database,
	"TempMentee@nothing.com",
	"nope2@nothing.com");
    print "\nShould be 'nope2@nothing.com'\n";
    print $mentee->getMatchedWith($database, 
	"TempMentee@nothing.com");
    print "\n";
    mysqli_close($database);
}

/* 
 * All the test are here, just comment out the ones that
 * do not need to be run
 * Please do NOT test clear(), I feel like some of the other
 * groups may have data in the tables that they are using for
 * their own debugging right now. So don't alter it.
 */
testRemoveMentee(); 
//testAddMentee();
//testGetMatchedWith();
//testSetMatchedWith();
//testRemoveMentee(); 
?>
