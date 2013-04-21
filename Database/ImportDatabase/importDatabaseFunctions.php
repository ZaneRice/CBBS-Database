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
	$tablename = readTableName();
	$columns = readColumns();
	$data = readData();
	updateDatabase($database, $tablename, $columns, $data);

	// ignore "\n"
	//if( fgets($fp,1000) == "<br>") {
	//    echo "<br>Blank is here<br>";
	//}
    }

    fclose($fp);
}

function readData($file)
{
    $arr = array();
    
    while($data = fgets($file))
    {
	if($data == "" || $data == "\n")
	    break;
	
	$row = explode(",",$data);

	for($i=0; $i < count($row); $i++)
	{
	    if(strlen($row[$i]) > 2)
		$row[$i] = "'" . substr(1,strlen($row[$i])-2) . "'";
	    else
		$row[$i] = "''";
	}

	$arr[] = $data;
    }
    
    return $arr;
}

//$file = fopen("../ExportDatabase/databaseTables.csv","r");
//fgets($file);
//fgets($file);
//var_export(readData($file));

function updateDatabase($database,$tablename,$columns,$data)
{
    $i = 0;

    for($i = 0; $i < count($data); $i++)
    {
	$element = $data[$i];
	$query = generateUpdateQuery($tablename,$columns,$element);
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
