<?php
/*
 * A couple definitions to help keep confusion down:
 *
 * entry -- the columns and data in those columns which form a table
 * label -- a string which describes an entry
 */


/*
 * This function will execute $query on $database and provide
 * an Excel readable entry with label $label.
 */
function toExcel($database,$query,$label)
{
  return combineLabelAndEntry($label,createExcelEntry($database, $query));
}

/*
 * This function will write all of the data in the tables listed in the
 * $tableNames array to a file named $filename using the database
 * connected to by $database 
 *
 * Mostly this function exists for convenience when all data from 
 * multiple tables needs to be written to a spreadsheet.
 *
 * For other puposes use toExcel()
 */
function exportTablesToExcel($database, $tableNames,$filename)
{
    $toWrite = "";

    for($i = 0; $i < count($tableNames); $i++)
    {
	$tableName = $tableNames[$i];
	$query = "SELECT * FROM $tableName"; 
	$toWrite = $toWrite . toExcel($database,$query,$tableName) . "\n";
    }
    
    /* Write the output to an Excel readable file */
    $outputFile = fopen($filename,"w+");
    fwrite($outputFile,$toWrite);
    fclose($outputFile);
}

/*
 * Returns a string which can be read in by Excel and represents
 * the result of running $query on $database.
 */
function createExcelEntry($database, $query)
{
    $columns = queryColumnsExcelString($database, $query);
    $rows = queryRowsExcelString($database, $query);

    return combineColumnsAndRows($columns,$rows);
}

/*
 * Places $label above $entry. Typically the $label will
 * be the name of the table represented by $entry.
 */
function combineLabelAndEntry($label,$entry)
{
    return "Table," . $label . "\n" . $entry;
}

/*
 * Combines the strings $columns and $rows in a way
 * which is formatted to look neat in Excel
 */
function combineColumnsAndRows($columns,$rows)
{
    return $columns . "\n" . $rows . "\n";
}

/*
 * Returns an Excel readable string of the columns returned
 * by executing $query on $database
 */
function queryColumnsExcelString($database, $query)
{
    $tableQuery = mysqli_query($database, $query);
    $queryColumns = mysqli_fetch_fields($tableQuery);

    /*
    $row = mysqli_fetch_array($tableQuery);

    if($row == NULL)
    {
	return "";
    }

    $keys = array_keys($row);

    //The columns of the table as an Excel readable string
    $columns = "";

    //Get the column names
    for($i = 1; $i < count($row); $i=$i + 2)
    {
	$column = $keys[$i];

	if ($columns === "")
	{
	    $columns = "$column"; 
	}
	else
	{
	    $columns = $columns . ",$column"; 
	}
    }
     */

    $columns = "";

    for($i = 1; $i < count($queryColumns); $i++)
    {
	$column = $queryColumns[$i]->name;

	if ($columns === "")
	{
	    $columns = "$column"; 
	}
	else
	{
	    $columns = $columns . ",$column"; 
	}
    }

    return $columns;
}

/*
 * Returns an Excel readable string of the rows returned
 * by executing $query on $database
 */
function queryRowsExcelString($database, $query)
{
    $tableQuery = mysqli_query($database,$query);
    $row = mysqli_fetch_array($tableQuery);

    if($row == NULL)
    {
	return "";
    }
    
    /*
    * Used here to spot boolean values 
    * To output 'YES' and 'NO' instead of
    * 'TRUE' and 'FALSE' per client request
    */
    $keys = array_keys($row);

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
	    //Check if this is a boolean value and adjust accordingly
	    $column = $keys[$i*2+1];
	    if($column[0] == "I" && $column[1] == "s")
	    {
		if($row[$i] == "0")
		{
		    $row[$i] = "NO";
		}
		else
		{
		    $row[$i] = "YES";
		}
	    }

	    if($i === 0) //Don't add a comma
	    {
		if(strpos($row[$i],","))
		{
		    $values = $values . "\"" . $row[$i] . "\"";
		}
		else
		{
		    $values = $values . $row[$i];
		}
	    }
	    else //Add a comma
	    {
		if(strpos($row[$i],","))
		{
		    $values = $values . ",\"" . $row[$i] . "\"";
		}
		else
		{
		    $values = $values . "," . $row[$i];
		}
	    }
	}
    }while($row = mysqli_fetch_array($tableQuery));

    return $values;
}
?>
