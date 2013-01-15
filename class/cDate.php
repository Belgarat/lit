<?php
class cDate{

	public static function ConvertDataISO($DataISO){
		
		$day="00";
		$mon="00";
		$year="0000";
		
		$year = substr($DataISO,0,4);
		$mon = substr($DataISO,5,2);
		$year = substr($DataISO,7,2);
		
		return date("d/m/Y",mktime(0,0,0,$mon,$day,$year));
		
	}

}
?>