<?php
class Mentor
{
    function getMatchedWith($database, $email)
    {
    }
    
    function removeMentor($database,$mentorEmail) 	
    {
	$tableQuery = mysqli_query($database,"SELECT Email FROM Mentee");
	
	//Remove all matches to this Mentor from the Mentee table
	while($row = mysqli_fetch_array($tableQuery))
	{
	    //Remove this Mentor from the current Mentee's MathcedWith
	    //removeMatch($database,$mentorEmail,$row[0]);
	    print "Mentee Emails:\n";
	    print $row[0] . "\n";
	}

	$query = generateDeleteQuery($tableName,"Email",$mentorEmail);
	print "Delete Query:\n" . $query . "\n";
	//mysqli_query($database,$query);
    }

    function setMatchedWith($database,$email,$data) 
    {
    }
    
    function addMentor($database,$columns,$values)
    {
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
