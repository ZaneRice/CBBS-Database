<?php
class Mentor
{
    function getMatchedWith($database, $email)
    {
	$result = "SELECT MatchedWith FROM Mentor WHERE Email='$email'";

	$queryResult = mysqli_query($database,$result);
	$row = mysqli_fetch_array($queryResult);

	$stringResult = (string)$row[0];

	return $stringResult;
    }

    function removeMentor($database,$mentorEmail) 	
    {
	$tableQuery = mysqli_query($database,"SELECT Email FROM Mentee");
	
	//Remove all matches to this Mentor from the Mentee table
	while($row = mysqli_fetch_array($tableQuery))
	{
	    //Remove this Mentor from the current Mentee's MathcedWith
	    removeMatch($database,$mentorEmail,$row[0]);
	}

	//Remove this mentor from the Mentor table
	$query = generateDeleteQuery("Mentor","Email",$mentorEmail);
	mysqli_query($database,$query);
    }

    function setMatchedWith($database,$email,$data) 
    {
	$columns = array("Email","MatchedWith");
	$rowData = array($email,$data);
	$query = generateUpdateQuery("Mentor",$columns,$rowData);
	mysqli_query($database,$query);
    }
    
    function addMentor($database,$columns,$values)
    {
	$query = generateInsertQuery("Mentor",$columns,$values);
	mysqli_query($database,$query);

	//Add all matches to this Mentor to the Mentee table
	$mentor = new Mentor;
	$mentorEmail = $values[array_search("Email",$columns)];
	$matches = $mentor->getMatchedWith($database,$mentorEmail);

	$matchesArray = explode(",",$matches);

	for($i = 0; $i < count($matchesArray); $i++)
	{
	    //Add this match. addMatch does not allow duplicate matching
	    //so just calling this does the right thing.
	    //Don't try to add a match to an empty string email, because
	    //you might end up with something like the following:
	    //	Mentee
	    //	Email	MatchedWith
	    //	email   email2,
	    //	Which is wrong because the comma is there for no reason.
	    if(strlen($matchesArray[$i]) > 0)
		addMatch($database,$mentorEmail,$matchesArray[$i]);
	}
    }

    /*
     * Removes all rows from the Mentor table.
     */
    function clear($database)
    {
	mysqli_query($database,"DELETE FROM Mentor");
    }
}
?>
