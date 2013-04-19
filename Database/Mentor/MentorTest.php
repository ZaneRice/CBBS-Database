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

$database = mysqli_connect("","","","");

// Check connection
if (mysqli_connect_errno($database))
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

$query = generateInsertQuery("Mentee",["Email","MatchedWith"],["TempMentee@nothing.com","TempMentor@nothing.com"]);
mysqli_query($database,$query);
$query = generateInsertQuery("Mentor",["Email","MatchedWith"],["TempMentor@nothing.com","TempMentee@nothing.com"]);

mentor = new Mentor;
mentor->removeMentor($database, "TempMentor@nothing.com");

mysqli_close($database);
?>
