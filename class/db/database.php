<?php
/*
	Database abstraction layer for ChaosPortal.

	Versions:
	20061019 - Denetor - Starting developing
	20070531 - Denetor - Porting to php5
	20080523 - Belgarat - Singleton implementation
	
	
	Dettagli:
	Per eseguire le query, raccoglie solo i dati dei campi e delle tabelle su cui lavorare. Poi passa tutto
	alle apposite funzioni dei moduli specifici per ogni tipo di database.
	
	Query di comando
	- comando (insert, drop, delete, ...)
	- campi destinazione
	- tabelle destinazione
	- filtri
	
	Query di selezione
	- campi da prelevare
	- tabelle di lavoro
	- filtri
	- ritorna un array bidimensionale con le righe e le colonne dedi risultati
*/

require_once('query.php');


//
// Include database-specific library
//
if (trim(strtolower(DB_TYPE)) == 'mysql') 
{
	require_once('db_mysql.php');
}

		
		
class Db
{
	// properties
	private static $instance;	// istance connection variable (for singleton implementation, 20080523 - Belgarat)
	protected $connection;		// database connection		
	
	
	//
	// Constructor
	//
	private function __construct()
	{
		$this->connection = false;		
	}
	
	
	//
	// getInstance
	// Singleton Implementation
	//
	public static function getInstance()
	{
	  if (!self::$instance)
	  {
	    $className = __CLASS__;
	    self::$instance = new $className();
	  }	  
	  return self::$instance;
	}


	//
	// Open database connection
	//
	public function Open()
	{
		// close connection if already opened
		if ($this->connection != false) {
			db_close($this->connection);
		}
		// open new connection
		$this->connection = db_open();
	}
	
	
	//
	// Close database connection
	//
	public function Close()
	{
		if ($this->connection != false) {
			db_close($this->connection);
			$this->connection = false;
		}
	}
	
	
	/*
		Delete rows from specified table
	*/
	public function DeleteRecords($table, $filters)
	{
		return db_delete_records($table, $filters);
	}


	//
	// Drops specified table
	//
	public function DropTable($table)
	{
		db_drop_table($table);
	}
	
	
	//
	// Quick query that returns just one field
	//
	public function GetValue($field, $tables, $filters="")
	{
		return db_get_value($field, $tables, $filters);
	}
	
	
	/*
		Set sql forming GetValue method
	*/
	public function GetValue_SQL($field, $tables, $filters="")
	{
		return db_get_value_sql($field, $tables, $filters);
	}
	
	
	/*
		Normalizes user input string to be used as value in queries.
		To be used to filter user input only, not entire queries.
	*/
	public function StrNormalize($s)
	{
		return db_normalize_string($s);
	}
	
}
?>