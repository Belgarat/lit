<?php
/*
	Query module for chaosportal

	Versions:
	20061023 - Denetor - Starting developing
	20061024 - Denetor - Basic funztions done
	20061102 - Denetor - Added insert queries
	20070530 - Denetor - Porting to php5
	20071208 - Denetor - Added GetOpenSql function
	20080801 - Denetor - Having clause added
	20081204 - Klown - Added GetAssoc function (Deprecated)
	20090718 - klown - Modified GetNextRecord() in GetNextRecord(bool $get_assoc=FALSE)
	20091116 - Klown - Added GetColumnType($table, $column) function 
	
	Usage example:
		$q = new Query();
		$q->fields = array("codice", "nome", "cognome", "anno");
		$q->tables = array("tab_provami");
		$q->filters = "cognome like 'r%'";
		$q->groupby = array("codice", "nome", "cognome", "anno");		
		$q->having = "anno=1976";
		$q->sortfields = array("anno");
		$q->limit = "0,1";
		if ($q->Open() ) {
			echo "<br>Query riuscita<br>";
			while ($row = $q->GetNextRecord()) {
				echo "<br>Row: ";
				foreach ($row as &$item) {
					echo $item . " - ";
				}
			}
		} else {
			echo "<br>Query fallita<br>";
		}
		$q->Close();
		
		
	DoInsert sample:
		$q = new Query();
		$q->fields = array("codice", "nome", "cognome", "anno");
		$q->tables = array("tab_provami");
		$q->values = array("01", "ciccio", "panza", "2006");
		$q->DoInsert();
*/


class Query
{
	//
	// properties
	//
	public $fields;			// field names array
	public $tables;			// table names array
	public $filters;		// filters string
	public $groupby;		// fields groupby array
	public $having;			// having clause
	public $sortfields;		// sort fields array
	public $limit;			// limit string
	public $values;			// values array	
	protected $dbresult;	// database result resource
	protected $error;		// last operation's error code (0 = ok)
	
	
	//
	// constructor
	//
	function __construct()
	{
		$error = 0;	
		$dbresult = false;
		$this->fields = array();
		$this->tables = array();
		$this->filters = "";
		$this->groupby = array();		
		$this->having = "";
		$this->sortfields = array();
		$this->limit = "";
		$this->values = array();		
	}
	
	
	//
	// destructor
	//
	function __destruct()
	{
		$this->Close();
	}
	
	
	//
	// Open a recordset
	//
	public function Open()
	{
		$this->error = 0;
		
		//$dbresult = db_open_recordset(array("nome", "cognome"), array("tab_provami"), "", array());
		$this->dbresult = db_open_recordset($this->fields, $this->tables, 
								$this->groupby, $this->filters, $this->sortfields, 
								$this->limit, $this->having);
		if ($this->dbresult == false) {
			$this->error = -1;
			return false;
		} else {
			return true;
		}
	}
	
	
	//
	// get Open SQL without executing the query
	//
	public function GetOpenSql()
	{
		return db_get_select_sql($this->fields, $this->tables, $this->filters, $this->sortfields, $this->limit);
	}


	//
	// Return an array with next row
	//
	public function GetNextRecord($get_assoc=false)
	{
		if ($this->dbresult != false) {
			return db_get_next_record($this->dbresult, $get_assoc);
		} else {
			$this->error = -1;
			return false;
		}
	}


	//
	// Closes recordset
	//
	public function Close()
	{
		$ret = db_close_recordset($this->dbresult);
		$this->dbresult = false;
		return $ret;
	}
	
	
	/*
		Insert query
	*/
	public function DoInsert()
	{
		return db_insert_record($this->fields, $this->tables, $this->values);
	}
	
	
	/*
		Show SQL code of current SELECT query
		This has debugging purpose only
	*/
	public function GetSQL()
	{
		return db_get_select_sql($this->fields, $this->tables, $this->groupby, $this->filters, $this->sortfields, $this->limit);
	}
	
	/*
	 	returns an associative array
	 	Alternative to Open() that return only the values

		!!Deprecated use GetNextRecord(true) instead!!
	*/
	
	public function GetAssoc()
	{
		return db_get_fetch_assoc($this->fields, $this->tables, $this->groupby, $this->filters, $this->sortfields, $this->limit);
	}
	
	/*
		Update query
		Put only one item in tables array
	*/
	public function DoUpdate()
	{

		return db_update_records($this->fields, $this->tables, $this->values, $this->filters);
	}
	
	/*
		retrun values admitted for enum fields
	*/
	public function GetEnumValue(){
		return db_enum_value($this->tables[0], $this->fields[0]);
	}
}
?>
