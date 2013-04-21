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
    $database = mysqli_connect("oss-ci.cs.clemson.edu","cpsc472","myDB4dmin","cpsc472");
    //$database = mysqli_connect("","","","");

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

    //Insert Mentee
    $query = generateInsertQuery("Mentee", 
	Array("Email","MatchedWith"),
	Array("TempMentee@nothing.com","TempMentor@nothing.com"));
    mysqli_query($database,$query);

    //Insert Mentor
    $query = generateInsertQuery("Mentor",
	Array("Email","MatchedWith"),
	Array("TempMentor@nothing.com","TempMentee@nothing.com"));
    mysqli_query($database,$query);

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
		       Array("TempMentor@nothing.com","TempMentee@nothing.com"));
    mysqli_close($database);
}

function testGetMatchedWith()
{
    testAddMentor();
    $database = connect();
    $mentor   = new Mentor;
    print "\n" . $mentor->getMatchedWith($database,"TempMentor@nothing.com") . "\n";
    mysqli_close($database);
}

/* 
 * All the test are here, just comment out the ones that
 * do not need to be run
 * Please do NOT test clear(), I feel like some of the other
 * groups may have data in the tables that they are using for
 * their own debugging right now. So don't alter it.
 */
//testAddMentor();
//testRemoveMentor(); //Also tests addMentor and addMentee
testGetMatchedWith();

?>
