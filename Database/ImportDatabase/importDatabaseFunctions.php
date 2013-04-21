<?php
function importDatabase($database,$file)
{
    $fp = fopen("$file",'r');

    if(!$fp)
    {
	echo "Could not open file!";
	exit;
    }

    while(!feof($fp))
    {
	$tablename = readTableName($fp);
	$columns = readColumns($fp);
	$data = readData($fp);
	updateDatabase($database, $tablename, $columns, $data);

	// ignore "\n"
	//if( fgets($fp,1000) == "<br>") {
	//    echo "<br>Blank is here<br>";
	//}
    }

    fclose($fp);
}

function readTableName($file)
{
}

function readColumns($file)
{
}

function readData($file)
{
    $arr = array();
    
    while($data = fgets($file))
    {
	if($data == "" || $data == "\n")
	    break;
	
	$data = trim($data);
	$row = explode(',',$data);
	$newRow = Array();

	/* 
	 * Start at $i=1 because the first element of row is an
	 * empty string artifact of exploding along double-quotes
	 * which needs to be ignored.
	 *
	 * Stop at count($row-1) because last element is the newline
	 * character which also needs to be ignored.
	 */
	for($i=0; $i < count($row); $i++)
	{
	    /*
	     * This is obscure, but what it does is combine
	     * cell elements that have commas within them
	     * but are surrounded by double quotes when you
	     * save the .csv file from the spreadsheet
	     * interface.
	     *
	     * Things like "email1,email2" get seperated
	     * into ["email1,email2"], so we need to 
	     * combine them into one element
	     * (single-quotes used here, but not actually
	     * present in strings) ['"email1,email2"']
	     */
	    $j = $i;
	    if(strlen($row[$i]) > 0 && $row[$i][0] == '"')
	    {
		while($row[$i][strlen($row[$i])-1] != '"')
		{
		    $j++;
		    $row[$i] = $row[$i] . "," . $row[$j];
		}
		$row[$i] = substr($row[$i],1,strlen($row[$i])-2);
	    }

	    $newRow[] = "'" . $row[$i] . "'";
	    $i = $j;
	}
	
	$arr[] = $newRow;
    }
    
    return $arr;
}

function updateDatabase($database,$tablename,$columns,$data)
{
    $i = 0;

    for($i = 0; $i < count($data); $i++)
    {
	$query = generateUpdateQuery($tablename,$columns,$data[$i]);
	print "\n" . $query . "\n";
	mysqli_query($database,$query);
    }
}

function importOldDatabase($database,$convert,$oldDatabase)
{
    /* Run the python script to generate parsable files for the new database */
    exec("$convert < $oldDatabase");

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
