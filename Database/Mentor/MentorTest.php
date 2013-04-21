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

function testRemoveMentor()
{
    $database = connect();

    $mentor = new Mentor;
    $mentee = new Mentee;

    //Insert Mentee
    $query = $mentee->addMentee($database,
	Array("Email","MatchedWith"),
	Array("TempMentee@nothing.com","TempMentor@nothing.com"));

    //Insert Mentor
    $query = $mentor->addMentor($database,
	Array("Email","MatchedWith"),
	Array("TempMentor@nothing.com","TempMentee@nothing.com"));

    //Call Mentor->removeMentor()
    $mentor = new Mentor;
    $mentor->removeMentor($database, "TempMentor@nothing.com");

    mysqli_close($database);
}

function testAddMentor()
{
    $database = connect();
    $mentor = new Mentor;
    $mentor->addMentor($database,
	Array("Email","MatchedWith"),
	Array("TempMentor@nothing.com","TempMentee@nothing.com,TempMentee2@nothing.com"));
    mysqli_close($database);
}

function testGetMatchedWith()
{
    testAddMentor();
    $database = connect();
    $mentor   = new Mentor;
    print "\nShould be 'TempMentee@nothing.com'\n";
    print $mentor->getMatchedWith($database,"TempMentor@nothing.com");
    print "\n";
    mysqli_close($database);
}

function testSetMatchedWith()
{
    testAddMentor();
    $database = connect();
    $mentor   = new Mentor;
    $mentor->setMatchedWith($database,
	"TempMentor@nothing.com",
	"nope@nothing.com,nope2@nothing.com");
    print "\nShould be 'nope@nothing.com,nope2@nothing.com'\n";
    print $mentor->getMatchedWith($database, "TempMentor@nothing.com");
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
testRemoveMentor(); 
testAddMentor();
testGetMatchedWith();
testSetMatchedWith();
testRemoveMentor(); 
testAddMentor();
?>
