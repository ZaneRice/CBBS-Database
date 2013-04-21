<?php
class Mentee
{
    function getMatchedWith($database, $email)
    {
	$result = SELECT MatchedWith FROM Mentee WHERE Email=$email;

	//where MatchedWith is the name of a column in the database

	//Then we convert the query result to a string like this:
	$stringResult = (string)$result;

	return $stringResult;
    }
    
    function setMatchedWith($database,$email,$data) 
    {
    }
    
    function addMentee($database,$columns,$values)
    {
	$query = generateInsertQuery($tableName,$columns,$values);
	mysqli_query($database,$query);
    }
    
    function removeMentee($database,$menteeEmail)	
    {
	$tableQuery = mysqli_query($database,"SELECT Email FROM Mentor");
	
	//Remove all matches to this Mentee from the Mentor table
	while($row = mysqli_fetch_array($tableQuery))
	{
	    //Remove this Mentee from the current Mentor's MathcedWith
	    removeMatch($database,$row[0],$menteeEmail);
	}

	//Remove this mentee from the Mentee table
	$query = generateDeleteQuery("Mentee","Email",$menteeEmail);
	mysqli_query($database,$query);
    }

    function clear($database)
    {
	mysqli_query($database,"DELETE FROM Mentee");
    }
}
?>
