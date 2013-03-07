<?php
/*
 * This function will write all of the data in the tables listed in the
 * $tableNames array to a file named $filename using the database
 * connected to by $database 
 */
function exportTablesToExcel($database, $tableNames,$filename)
{
    $toWrite = "";

    for($i = 0; $i < count($tableNames); $i++)
    {
	$tableName = $tableNames[$i];
	$columns = queryColumnsExcelString($database, "SELECT * FROM $tableName");
	$rows = queryRowsExcelString($database, "SELECT * FROM $tableName");
	$toWrite = $toWrite . "\"" . $tableNames[$i] . "\"\n" . $columns . "\n" . $rows . "\n\n";
    }
    
    /* Write the output to a Excel readable file */
    $outputFile = fopen($filename,"w+");
    fwrite($outputFile,$toWrite);
    fclose($outputFile);
}

function queryColumnsExcelString($database, $query)
{
    $tableQuery = mysqli_query($database, $query);

    $row = mysqli_fetch_array($tableQuery);
    $keys = array_keys($row);

    //The columns of the table as an Excel readable string
    $columns = "";

    //Get the column names
    for($i = 1; $i < count($row); $i=$i + 2)
    {
	$column = $keys[$i];

	if ($columns === "")
	{
	    $columns = "\"$column\""; 
	}
	else
	{
	    $columns = $columns . ",\"$column\""; 
	}
    }

    return $columns;
}

function queryRowsExcelString($database, $query)
{
    $tableQuery = mysqli_query($database,$query);
    $row = mysqli_fetch_array($tableQuery);

    //The rows of the table as an Excel readable string
    $values = "";

    //Extract the row information
    do{
	if($values !== "")
	{
	    $values = $values . "\n";
	}

	for($i = 0; $i < count($row)/2; $i = $i + 1)
	{
	    if($i === 0)
	    {
		$values = $values . "\"" . $row[$i] . "\"";
	    }
	    else
	    {
		$values = $values . ",\"" . $row[$i] . "\"";
	    }
	}
    }while($row = mysqli_fetch_array($tableQuery));

    return $values;
}
?>
