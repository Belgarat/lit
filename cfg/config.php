<?PHP
// these variables are needed to connect to the database
$server = "localhost"; // often localhost
$username = "root"; // your MySQL server username
$password = "123456"; // your MySQL server password
$database = "oldlit"; // if you fill in nothing database 'members' will be used. If it doesn't exist it will be created.

	//
	// defining database type. allowed types: mysql
	//
	define("DB_TYPE", "mysql");
	
	//
	// defining global database variables
	//
	define("DB_SERVER", "localhost");
	define("DB_NAME", "oldlit");
	define("DB_USERNAME", "root");
#	define("DB_PASSWORD", "123456");
	define("DB_PASSWORD", "sguluag99");

	define("HTTP_ROOT", "");
	define("HTTP_AJAX", "src/ajax");
	define("HTTP_IMG", "img");
	define("SRV_ROOT", $_SERVER["DOCUMENT_ROOT"] . "");
	
	//
	// prefix for portal table names
	//
	define("DB_PREFIX", "cp_");

?>
