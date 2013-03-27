<?php
function importDatabase($hostName,$userName,$password,$databaseName,$convert,$oldDatabase)
{
    $database = mysqli_connect($hostName,$userName,$password,$databaseName);

    /* Run the python script to generate parsable files for the new database */
    exec("$convert < $oldDatabase");

    // Check connection
    if (mysqli_connect_errno($database))
    {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	exit();
    }

    /* 
     * The names of the tables for which data will be added.
     * These are used to create the file names for fopen()
     *
     * The names are parsed from the TableNames file created
     * by convert.py
     */
    $tableNames = array();

    $toParse = fopen("TableNames","r");

    if(!$toParse)
    {
	printf("Failed to open $fileName. Exiting.\n");
	exit();
    }

    while(!feof($toParse))
    {
	//Have to trim() off the '\n'
	$tableName = trim(fgets($toParse));
	if($tableName != "")
	{
	    $tableNames[] = $tableName;
	}
    }

    for($i = 0; $i < count($tableNames); $i++)
    {
	$tableName = $tableNames[$i];

	/*
	 * The current file being parsed.
	 */
	$fileName = $tableName . ".csv";

	$toParse = fopen($fileName, "r");

	if(!$toParse)
	{
	    printf("Failed to open $fileName. Exiting.\n");
	    mysqli_close($database);
	    exit();
	}

	while(!feof($toParse))
	{
	    $row = fgets($toParse);

	    /* Build insertion query */
	    $row = trim($row);
	    if($row != NULL && $row != "")
	    {
		$valuesArray = explode(",",$row);
		$valuesString = "";

		for($j = 0; $j < count($valuesArray); $j++)
		{
		    if($j === 0)
		    {
			$valuesString = $valuesString . "'" . $valuesArray[$j] . "'";
		    }
		    else
		    {
			$valuesString = $valuesString . ",'" . $valuesArray[$j] . "'";
		    }
		}

		$query = "INSERT INTO $tableName VALUES($valuesString)";

		/* Add the row to the database */
		mysqli_query($database,$query);
	    }
	}

	fclose($toParse);
    }

    mysqli_close($database);
}
?>
