<!DOCTYPE html>
<html>
<body>

<?php
$output = fopen("output","w+");

$mentorTableName = "\"Mentor\"\n";
$mentorColumns = "\"Email\" ,\"First\",\"Middle\",\"Last\" ,\"Work Phone\",\"Work Phone Ext.\" ,\"Mobile Phone\",\"Street Address\",\"City\" ,\"State\",\"Country\",\"Zip\",\"IsMentor\",\"IsMentee\",\"IsAdmin\" ,\"IsSponsor\" ,\"Years In Current Position\" ,\"Years In Company\" ,\"Company Name\" ,\"Position\" ,\"ShowInMatchResult\" ,\"Title\" ,\"Background\"\n";

/* TODO Query database for Mentor data */
$mentorData = "\n";

$menteeTableName = "\"Mentee\"\n";
$menteeColumns = "\"Email\", \"University\", \"Graduation Year\", \"Major\", \"First\", \"Middle\", \"Last\", \"Mobile Phone\", \"Street Address\", \"City\", \"State\", \"Country\", \"Zip\", \"IsMentor\", \"IsMentee\", \"IsAdmin\", \"ShowInMatchResult\", \"Background\"\n";

/* TODO Query database for Mentee data */
$menteeData = "\n";

$toWrite = $mentorTableName . $mentorColumns . $mentorData . $menteeTableName . $menteeColumns . $menteeData;
fwrite($output,$toWrite);

fclose($output);
?>

</body>
</html>
