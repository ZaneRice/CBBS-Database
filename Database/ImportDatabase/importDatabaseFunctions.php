<?php
function importDatabase($database,$file)
{
    $fp = fopen("$file",'r');

    if(!$fp)
    {
	echo "Could not open file!";
	exit;
    }

    $tablename = readTableName($fp);

    while(!feof($fp))
    {
	$columns = readColumns($fp);
	//$result will be an array with the data in index 0 and
	//the name of the next table in index 1
	$result = readData($fp);
	updateDatabase($database, $tablename, $columns, $result[0]);
	$tablename = $result[1];
    }

    fclose($fp);
}

function readTableName($file)
{
    $row = Array();

    do{
    $row = explode(",",trim(fgets($file)));
    }while(!feof($file) && $row[0] != "Table");
    
    return $row[1];
}

function readColumns($file)
{
    $columns = Array();

    do{
	$columns = explode(",",trim(fgets($file)));
    }while(!feof($file) && $columns[0] == "");

    return $columns;
}

function readData($file)
{
    $arr = array();
    $nextTable = "";
    
    while($data = fgets($file))
    {
	
	$data = trim($data);
	$row = explode(',',$data);
	
	//"Table" tag means stop
	if($row[0] == "Table")
	{
	    $nextTable = $row[1];
	    break;
	}

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

	    //Convert back to boolean from "yes", "no" output format
	    if(    strtolower($row[$i]) == "yes" 
		|| strtolower($row[$i]) == "true")
	    {
		$row[$i] = "1";
	    }
	    else if (    strtolower($row[$i]) == "no" 
		      || strtolower($row[$i]) == "false")
	    {
		$row[$i] = "0";
	    }


	    $newRow[] = "'" . $row[$i] . "'";
	    $i = $j;
	}
	
	$arr[] = $newRow;
    }
    
    return Array($arr, $nextTable);
}

function updateDatabase($database,$tablename,$columns,$data)
{
    $i = 0;

    for($i = 0; $i < count($data); $i++)
    {
	$value = $data[$i][0];
	$len = strlen($value);
	if( $len <= 1 || $value[0] != "'" || $value[$len-1] != "'" )
	    $query = "SELECT * FROM $tablename WHERE $columns[0]='$value'";
	else
	    $query = "SELECT * FROM $tablename WHERE $columns[0]=$value";
	$queryResult = mysqli_fetch_array(mysqli_query($database,$query));
	    
	//$queryResult = mysqli_query($database,$query);

	if($queryResult != NULL) //If row already exists update it
	{
	    //DO NOT UPDATE A ROW TO HAVE A BLANK EMAIL
	    if($data[$i][0] != "''" && $data[$i][0] != "")
		$query = generateUpdateQuery($tablename,$columns,$data[$i]);
	    else
		$query = "";
	}
	else //If row does not already exist create it
	{
	    //DO NOT ADD A ROW WITH A BLANK EMAIL
	    if($data[$i][0] != "''" && $data[$i][0] != "")
		$query = generateInsertQuery($tablename,$columns,$data[$i]);
	    else
		$query = "";
	}

	//Only run the query if it actually was built
	if(strlen($query) > 0)	
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
