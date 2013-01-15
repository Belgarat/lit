<?php

/****************
 * library with little silly string/array operations
 *
 * 20091106 - Klown - stringCapitalized(String $string) Capitalizes correctly strings
 * 20091109 - Klown - arrayUnique (Array $array, Boolean $caseInsensitive=false)
 *					  returns only the unique values in $array comparison both case sensitive
 *					  and case insensitive (bult-in function is only case sensitive)
 * 20091119 - Klown - Reimplemented arrayUnique
 *****************/

	//function optimized that returns capitalized strings
	function stringCapitalized($string){
		if ($string && ($string!="")){
			$firstStep = strtolower($string);
			return ucwords($firstStep);
		}
		return false;
	}
	
	//same as array_unique but with case insensitive support(suspended don't Work)
	function arrayUnique($array, $caseInsensitive=false){
		if (!$caseInsensitive){
			return array_unique($array);
		}
		$ret = array();
		foreach ($array as $item){
			$value = strtolower($item);
			if (!in_array($value, $ret)){
				$ret[] = $item;
			}
		}
		return $ret;
	}
?>
