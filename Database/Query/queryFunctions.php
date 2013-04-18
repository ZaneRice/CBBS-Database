<?php
/* 
 * Generates a query to update a row in $tableName 
 * Assumes that $columns[0] and $rowData[0] correspond
 * to the primary key of the table and therefore will
 * not be changed by the update.
 */
function generateUpdateQuery($tableName, $columns, $rowData)
{
    //Genereate the SET part of the query
    $i = 0;
    $set = "";

    for($i = 0; $i < count($columns); $i=$i+1)
    {
	$set = $set . $columns[$i] . "=" . $rowData[$i];
	if ($i+1 < count($columns))
	    $set = $set . ",";
    }

    return "UPDATE " . $tableName . " SET " . $set . " WHERE " . $columns[0] . "=" . $rowData[0];
}

function generateInsertQuery($tableName,$columns,$values)
{
}

function generateDeleteQuery($tableName,$column,$value)
{
}
?>
