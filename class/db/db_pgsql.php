<?php
	/**
	 * db_pgsql: ORM to postgresql DBMS
	 * 
	 * @author Giacomo "KloWn" Bella
	 * @todo function for queries and sql generator
	 * @todo create table function
	 * @todo minimal data validation functions
	 * @todo join support
	 * @note some functions adapted

 	 */

/**
 * Database Opening
 *
 * \return Connection to the database if opens, error if error in opening, false otherwise
 */
function db_open(){
	$connection = pg_connect("host=".DB_SERVER." user=".DB_USERNAME." password=".DB_PASSWORD." dbname=".DB_NAME);
	if (PGSQL_CONNECTION_OK===pg_connection_status($connection)){
		return $connection;
	}else if (PG_CONNECTION_BAD===pg_connection_status($connection)){
		return pg_last_error();
	}else{
		return false;
	}
}

/**
 * Database connection close
 *
 * \return true
 */

function db_close(){
	if (false != $connection){
		pg_close($connection);
		unset($connection);
	}
	return true;
}

/**
 * Close open recordset 
 *
 * \param $result resource recordset
 *
 * \return 0
 */

function db_close_recordset($result){
	if (false != $result){
		pg_free_result($result);
	}
	return 0
}

/**
 * Delete rows from given table
 * 
 * \param $table Table from which delete rows
 * \param $filters Filter that applies to deletion
 * \retun resource if correctly deleted rows, false otherwise
 */

function db_delete_records($table, $filters){
	$filters = trim($filters);
	$table = trim($table);
	if (""==$filters){
		$sql = "delete from ".$table;
	}else{
		$sql = "delete from ".$table." where ".$filters;
	}
	return pg_query($connection, $sql);
}

/**
 * Drop table function
 *
 * \param $table Table to drop
 */

function db_drop_table($table){
	return pg_query("drop table ".trim($table));
}

/**
 * Open a recordset
 *
 * \param $fields Fields to return
 * \param $tables Tables the query applies
 * \param $groupby field for which group resulting rows
 * \param $filters Filters to apply
 * \param $sortfields Field for sorting results
 * \param $limit "start, offset" form limit for the query
 * \param $having 
 *
 * \return resource
 */

function db_open_recordset($fields, $tables, $groupby, $filters="", $sortfields=array(), $limit="", $having="") {
	$query = db_get_sql($fields, $tables, $groupby, $filters, $sortfields, $limit, $having);
	return pg_query($query);
}

/**
 * Return sql string for queries
 *
 * \param $fields Fields to return
 * \param $tables Tables the query applies
 * \param $groupby field for which group resulting rows
 * \param $filters Filters to apply
 * \param $sortfields Field for sorting results
 * \param $limit "start, offset" form limit for the query
 * \param $having 
 *
 * \return string of the query
 */

function db_get_sql($fields, $tables, $groupby, $filters, $sortfields, $limit, $having){

	$sql = "select";

	if (count($fields>0) && count($tables>0)){
		$i=1;
		
		// inserting fields in query
		foreach ($fields as $field){
			if ("*"==trim($field)){
				$sql .= " *";
				break;
			}else{
				if (""!=trim($field)){
					$sql .= " ".$field;
				}
			}

			if ($i++<count($fields)){
				$sql.=",";
			}
		}

		$sql.=" from";
		$i=1;

		//inserting tables in query
		foreach ($tables as $table){
			if ("" != trim($table)){
				$sql.=" ".trim($table);
			}
			if ($i++ < count($tables)){
				$sql.=",";
			}
		}
			
		//filters 
		if ("" != trim($filters)){
			$sql.=" where ".$filters;
		}
		
		//groupby
		if (count($groupby)>0){
			$sql.=" groupby";
			$i=1;
			foreach ($groupby as $group){
				if ("*"!=$group){
					$sql.= " ".trim($group);
				}
				if ($i++<count($group)){
					$sql.=",";
				}
			}
		}
		
		//sortfields
		if (count($sortfield)>0){
			$sql.= " order by";
			$i=1;
			foreach ($sortfields as $srtfield){
				$sql.=" ".trim($srtfield);
				if ($i++<count($sortfields)){
					$sql.=" ,";
				}
			}
		}

		if (""!=trim($limit)){
			$sql.=" limit ".$limit;
		}
		return $sql;
	}else{
		return false;
	}
}


/**
 * Walk through the rows
 *
 * \param $dbresult resource, database query
 * \param $get_assoc boolean, false returns array with row, true returns dictionary
 *
 * \retrun array or associative array with current row
 */

function db_get_next_record($dbresult, $get_assoc){
	if (false =! $dbresult){
		if ($get_assoc){
			return pg_fetch_assoc($result);
		}else{
			return pg_fetch_row($result);
		}
	}else{
		return false;
	}
}


