<?php
function addMatch($database,$mentorEmail,$menteeEmail)
{
    $tor = new Mentor;
    $match = $tor->getMatchedWith($database,$mentorEmail);

    $matchArray = explode(",",$match);

    //Disallow duplicate matches
    if(!in_array($menteeEmail,$matchArray)
    {
	if ( $match == "") 
	    $match = $menteeEmail;
	else
	    $match = $match . "," . $menteeEmail;
    
	//Update Mentor table (well, just the row containing $mentorEmail)
	$query = generateUpdateQuery("Mentor",
	    array("Email","MatchedWith","Status"),
	    array($mentorEmail,$match,"matched"));
	mysqli_query($database,$query);
    }

    $tee = new Mentee;
    $match = $tee->getMatchedWith($database,$mentorEmail);
    $today = date("Y-m-d");

    $matchArray = explode(",",$match);

    //Disallow duplicate matches
    if(!in_array($mentorEmail,$matchArray))
    {
	if ( $match == "" ) 
	    $match = $mentorEmail;
	else
	    $match = $match . "," . $mentorEmail;

	//Update Mentee table (well, just the row containing $menteeEmail)
	$query = generateUpdateQuery("Mentee",
	    array("Email","MatchDate","Status","MatchedWith"),
	    array($menteeEmail,$today,"matched",$match));
	mysqli_query($database,$query);		
    }
} 

function removeMatch($database,$mentorEmail,$menteeEmail)
{
    $newMenteeMatches = "";
    $newMentorMatches = "";

    $mentor = new Mentor;
    $mentee = new Mentee;

    $menteeMatches = $mentee->getMatchedWith($database,$menteeEmail);
    $mentorMatches = $mentor->getMatchedWith($database,$mentorEmail);

    $menteeMatches = explode(",",$menteeMatches);
    //var_export($menteeMatches);
    for($i = 0; $i < count($menteeMatches); $i++)
    {
	if($menteeMatches[$i] != $mentorEmail)
	{
	    if($newMenteeMatches == "")
		$newMenteeMatches = $menteeMatches[$i];
	    else
		$newMenteeMatches = $newMenteeMatches . "," . $menteeMatches[$i];
	}
    }
    
    $mentorMatches = explode(",",$mentorMatches);
    for($i = 0; $i < count($mentorMatches); $i++)
    {
	if($mentorMatches[$i] != $menteeEmail)
	{
	    if($newMentorMatches == "")
		$newMentorMatches = $mentorMatches[$i];
	    else
		$newMentorMatches = $newMentorMatches . "," . $mentorMatches[$i];
	}
    }


    $status = "";
    $today = date("Y-m-d");

    if($newMenteeMatches == "")
	$status = "unmatched";
    else
	$status = "matched";

    //Update Mentee table (well, just the row containing $menteeEmail)
    $query = generateUpdateQuery("Mentee",
	array("Email","MatchDate","Status","MatchedWith"),
	array($menteeEmail,$today,$status,$newMenteeMatches));
    mysqli_query($database,$query);	

    
    if($newMentorMatches == "")
	$status = "unmatched";
    else
	$status = "matched";

    //Update Mentor table (well, just the row containing $mentorEmail)
    $query = generateUpdateQuery("Mentor",
	array("Email","Status","MatchedWith"),
	array($mentorEmail,$status,$newMentorMatches));
    mysqli_query($database,$query);	
}
?>
