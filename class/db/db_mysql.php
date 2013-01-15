<?php
/*
		Database functions library fot MySql database.
	
		Versions:
		20061019 - Denetor - Starting developing
		20061024 - Denetor - Basic functions done
		20080801 - Denetor - Having statement included
		20081205 - Klown - Added db_get_fetch_assoc function (returns an array key=>value) Deprecated
		20090718 - Klown - Added mysql_get_assoc as a case of db_get_next_record
*/


//
// Open database connection
//
function db_open()
{
	// try to connect database
	$connection = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
	if ($connection != false) {
		// try to select database
		if (!mysql_select_db(DB_NAME, $connection)) {
			mysql_close($connection);
			$connection = false;
		}
	}	
	mysql_set_charset('utf8',$connection);
	return $connection;
}



//
// Close database connection
//
function db_close($connection)
{
	if ($connection != false) {
		mysql_close($connection);
		$connection = false;
	}
	return $connection;
}


//
// Delete records from table
//
function db_delete_records($table, $filters)
{
	if (trim($filters) == "") {
		$sql = "delete from " . trim($table);
	} else {	
		$sql = "delete from " . trim($table) . 
			" where ( " . trim($filters) . " )";
	}
	return mysql_query($sql);
}



//
// Perform a drop table query
//
function db_drop_table($table)
{
	return mysql_query("drop table " . trim($table));
}


//
// Get value from 1st result row
// Quick query to get a single value from tables
//
function db_get_value($field, $tables, $filters)
{
	$ret = false;
	$sql  = "select " . trim($field) . " ";
	$sql .= "from " . trim($tables) . " ";
	if (trim($filters) != "") {
		$sql .= "where ( " . trim($filters) . " ) ";
	}
	$sql .= "limit 0,1";
	$result = mysql_query($sql);
	if ($result != false) {
		$row = mysql_fetch_array($result, MYSQL_NUM);
		if (count($row) > 0) {
			$ret = $row[0];
		}
	}
	return $ret;
}


/*
	opens a new recordset and returns it
	<ASINELLO> gestire meglio sortfields
*/
function db_open_recordset($fields, $tables, $groupby, $filters = "", 
							$sortfields = array(), $limit="", $having="")
{
	$sql = "select ";
	
	// query creation
	if ((count($fields) > 0) && (count($tables) > 0))
	{
		// fields
		$i=1;
		foreach ($fields as &$item) {
			if (trim($item) != "*") {
				$sql .= trim($item);
			} else {
				$sql .= "*";
			}
			if ($i < count($fields)) {
				$sql .= ", ";
				$i++;
			}
		}
		// tables
		$sql .= " from ";
		$i=1;
		foreach ($tables as &$item) {
			$sql .= trim($item);
			if ($i < count($tables)) {
				$sql .= ", ";
				$i++;
			}
		}		
		// filters
		if (trim($filters) != "") {
			$sql .= " where ( " . trim($filters) . " ) ";
		}
		// group by array (similar as fields array)
		if (count($groupby) > 0) {
			$sql .= " group by ";
			$i=1;
			foreach ($groupby as &$item) {
				if (trim($item) != "*") {
					$sql .= trim($item);
				}
				if ($i < count($groupby)) {
					$sql .= ", ";
					$i++;
				}
			}
		}
		// having
		if (trim($having) != "") {
			$sql .= " having ( " . trim($having) . " ) ";
		}
		// sortfields
		if (count($sortfields) > 0) {
			$sql .= " order by ";
			$i=1;
			foreach ($sortfields as &$item) {
				$sql .= trim($item);
				if ($i < count($sortfields)) {
					$sql .= ", ";
					$i++;
				}
			}
		}
		// limit
		if ($limit != "") {
			$sql .= " limit " . $limit;
		}
		//echo "<br>=== " . $sql . " ===<br>";
		// open query
		return mysql_query($sql);		
	} else {
		return false;
	}
}


//
// close specified recordset
//
function db_close_recordset($result)
{
	if ($result != false) {
		mysql_free_result($result);
	}
	return 0;
}


//
// return array with next recordset row.
// returns false if no rows available
//
function db_get_next_record($dbresult, $get_assoc)
{
	if ($dbresult != false) {
		if($get_assoc){
			return mysql_fetch_assoc($dbresult);
		} else {
			return mysql_fetch_row($dbresult);
		}
	} else {
		return false;
	}
}


/*
	Insert record in a table.
	Takes just first value of tables array
	Returns record ID or false on errors
*/
function db_insert_record($fields, $tables, $values)
{
	$sql = "";
	if ((count($fields) > 0) && (count($tables) > 0) && (count($values) > 0)) {
		$sql = "insert into `" . $tables[0] . "` (";
		$i=1;
		foreach ($fields as &$item) {
			$sql .= "`" . trim($item) . "`";
			if ($i < count($fields)) {
				$sql .= ", ";
				$i++;
			}
		}
		$sql .= ") values (";
		$i=1;
		foreach ($values as &$item) {
			$sql .= "'" . trim($item) . "'";
			if ($i < count($values)) {
				$sql .= ", ";
				$i++;
			}
		}
		$sql .= ");";
	} else {
		return false;
	}
	//echo $sql;
	if (mysql_query($sql) == false) {
		echo mysql_error();
		return false;
	} else {
		return mysql_insert_id();		
	}
}


/*
	Update selected fields of specified table.
	Fields affected are the specified by filters
*/
function db_update_records($fields, $tables, $values, $filters)
{
	$sql = "";	
	if ((count($fields) > 0) && (count($tables) == 1) && 		
		(count($values) == count($fields))) {		
		$sql = "update `" . $tables[0] . "` set ";
		$i=0;
		while ($i < count($fields)) {
			$sql .= "`" . trim($fields[$i]) . "` = '" . trim($values[$i]) . "'";
			$i++;
			if ($i < count($fields)) {
				$sql .= ", ";
			}
		}
		/*$i=1;
		foreach ($fields as &$field) {
			$sql .= "`" . trim($field) . "` = ";		// VALORE!!!
			if ($i < count($fields)) {
				$sql .= ", ";
				$i++;
			}
		}*/
		// filters
		if (trim($filters) != "") {
			$sql .= " where ( " . trim($filters) . " ) ";
		}
	} else {
		return false;
	}
	//echo "<br>=== " . $sql . " ===<br>";
	// execute query
	return mysql_query($sql);
}
/*
---- copia di backup ----
function db_update_records($fields, $tables, $values, $filters)
{
	$sql = "";
	if ((count($fields) > 0) && (count($tables) == 1) && 
		(count($values) == count($fields))) {
		$sql = "update `" . $tables[0] . "` set ";
		$i=1;
		foreach ($fields as &$field) {
			$sql .= "`" . trim($field) . "` = ";		// VALORE!!!
			if ($i < count($fields)) {
				$sql .= ", ";
				$i++;
			}
		}
		// filters
		if (trim($filters) != "") {
			$sql .= " where ( " . trim($filters) . " ) ";
		}
		// sortfields
		if (count($sortfields) > 0) {
			$sql .= " order by ";
			$i=1;
			foreach ($sortfields as &$item) {
				$sql .= "" . trim($item) . "";
				if ($i < count($sortfields)) {
					$sql .= ", ";
					$i++;
				}
			}
		}
	} else {
		return false;
	}
	// execute query
	return mysql_query($sql);
}
*/



/*
	returns SQL code for SELECT query
	this is copy of db_open_recordset function. userd for debugging purposes only
	<ASINELLO> gestire meglio sortfields
*/
//function db_get_select_sql($fields, $tables, $filters = "", $sortfields = array())
function db_get_select_sql($fields, $tables, $groupby, $filters = "", $sortfields = array(), $limit="")						   
{
	$sql = "select ";
	
	// query creation
	if ((count($fields) > 0) && (count($tables) > 0))
	{
		// fields
		$i=1;
		foreach ($fields as &$item) {
			if (trim($item) != "*") {
				$sql .= "`" . trim($item) . "`";
			} else {
				$sql .= "*";
			}
			if ($i < count($fields)) {
				$sql .= ", ";
				$i++;
			}
		}
		// tables
		$sql .= " from ";
		$i=1;
		foreach ($tables as &$item) {
			$sql .= trim($item);
			if ($i < count($tables)) {
				$sql .= ", ";
				$i++;
			}
		}		
		// filters
		if (trim($filters) != "") {
			$sql .= " where ( " . trim($filters) . " ) ";
		}
		// group by array (similar as fields array)
		if (count($groupby) > 0) {
			$sql .= " group by ";
			$i=1;
			foreach ($groupby as &$item) {
				if (trim($item) != "*") {
					$sql .= trim($item);
				}
				if ($i < count($groupby)) {
					$sql .= ", ";
					$i++;
				}
			}
		}
		// having
		if (trim($having) != "") {
			$sql .= " having ( " . trim($having) . " ) ";
		}
		// sortfields
		if (count($sortfields) > 0) {
			$sql .= " order by ";
			$i=1;
			foreach ($sortfields as &$item) {
				$sql .= "" . trim($item) . "";
				if ($i < count($sortfields)) {
					$sql .= ", ";
					$i++;
				}
			}
		}
		$sql .= $limit;
		//echo "<br>SQL: " . $sql . "<br>";
		// open query
		return $sql;
	} else {
		return "";
	}
}



/*
	Get SQL query for db_get_value function.
	For debugging purposes only.
*/
function db_get_value_SQL($field, $tables, $filters)
{
	$sql  = "select " . trim($field) . " ";
	$sql .= "from " . trim($tables) . " ";
	if (trim($filters) != "") {
		$sql .= "where ( " . trim($filters) . " ) ";
	}
	$sql .= "limit 0,1";
	return $sql;
}


/*
	Normalizes user input string to be used as value in queries.
	To be used to filter user input only, not entire queries.
*/
function db_normalize_string($s)
{
	$ret = $s;
	if (get_magic_quotes_gpc()) {
		$ret = stripslashes($ret);
	}
	return mysql_real_escape_string($ret);
}

/*
 	Returns an associative array (only for first row)
	
	!!Deprecated use db_get_next_record instead!!
 */
function db_get_fetch_assoc($fields, $tables, $groupby, $filters = "", $sortfields = array(), $limit="")
{
	$res = db_open_recordset($fields, $tables, $groupby, $filters, $sortfields, $limit);
	if (!$res){
		return false;
	}else{
		return mysql_fetch_assoc($res);
	}
}

/*
	returns an array with value of the enums
*/

function db_enum_value($table, $field)
{
	$enum = array();
	$sql = "describe ".$table." ".$field;
	if ($result = mysql_query($sql)){
		$values = mysql_fetch_assoc($result);
		$search = $values["Type"];
		$regexp = "/'(.*?)'/";
		preg_match_all($regexp, $search, $enum);
		$enum = $enum[1];
		mysql_free_result($result);
		return $enum;
	}
	return false;
}
?>
