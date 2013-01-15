<?php    
	function redirect($url,$tempo = FALSE ){
	 if(!headers_sent() && $tempo == FALSE ){
	  header('Location:' . $url);
	 }elseif(!headers_sent() && $tempo != FALSE ){
	  header('Refresh:' . $tempo . ';' . $url);
	 }else{
	  if($tempo == FALSE ){
	    $tempo = 0;
	  }
	  echo "<meta http-equiv=\"refresh\" content=\"" . $tempo . ";url=" . $url . "\">";
	  }
	}
    if ( eregi ( "luxintenebra.net", $_SERVER['HTTP_REFERER'] ) ){
        if($_SESSION["ID"]==""){
            redirect("http://www.luxintenebra.net");
        }
    }else{
        redirect("http://www.luxintenebra.net");
    }
?>
