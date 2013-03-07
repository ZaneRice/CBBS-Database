<!DOCTYPE html>
<html>
<body>

<?php
$database = mysqli_connect("","","","");

// Check connection
if (mysqli_connect_errno($database))
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$mentorTableName = "\"Mentor\"\n";
$mentorColumns = "";
$mentorValues = "";

/* TODO Query database for Mentor data */
$mentorQuery = mysqli_query($database,"SELECT * FROM Mentor");
$mentorValuesArray = array();
$mentorValues = "";

$row = mysqli_fetch_array($mentorQuery);
$mentorKeys = array_keys($row);

//Get the column names
for($i = 1; $i < count($row); $i=$i + 2)
{
    $column = $mentorKeys[$i];

    if ($mentorColumns === "")
    {
	$mentorColumns = "\"$column\""; 
    }
    else
    {
	$mentorColumns = $mentorColumns . ",\"$column\""; 
    }
}
$mentorColumns = $mentorColumns . "\n";

//Extract the row information
do
{
    for($i = 0; $i < count($row)/2; $i = $i + 1)
    {
	if($i === 0)
	{
	    $mentorValues = $mentorValues . "\"" . $row[$i] . "\"";
	}
	else
	{
	    $mentorValues = $mentorValues . ",\"" . $row[$i] . "\"";
	}
    }
    $mentorValues = $mentorValues . "\n";
}while($row = mysqli_fetch_array($mentorQuery));

$menteeTableName = "\"Mentee\"\n";
$menteeColumns = "\"Email\", \"University\", \"Graduation Year\", \"Major\", \"First\", \"Middle\", \"Last\", \"Mobile Phone\", \"Street Address\", \"City\", \"State\", \"Country\", \"Zip\", \"IsMentor\", \"IsMentee\", \"IsAdmin\", \"ShowInMatchResult\", \"Background\"\n";

/* TODO Query database for Mentee data */
$menteeData = "\n";

mysqli_close($database);

/* Write the output to a Excel readable file */
$output = fopen("output","w+");
$toWrite = $mentorTableName . $mentorColumns . $mentorValues;// . $menteeTableName . $menteeColumns . $menteeData;
fwrite($output,$toWrite);
fclose($output);
?>

</body>
</html>
