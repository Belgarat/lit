
<style>
ul.file{
	list-style: none;
	padding: 0px;
	margin: 0px;
}
ul.file > li {
	color: white;
	padding-bottom: 1em;
}
ul.file > li > span {
	font-weight: bold;
	color: grey;
}
ul.file .success{
	font-size: 1.2em;
	font-weight: bold;
	color: green;
}
ul.file .failed{
	font-size: 1.2em;
	font-weight: bold;
	color: red;
}
</style>
<?php

if (!empty($_POST['cmd_upload']) && $_POST['cmd_upload'] == 'Upload') {

	$upload = new cUpload;


	switch ($_POST["type"]){
		case "2":
			$oOgg = new cImage();
			$oOgg->set_id_sito($_SERVER["SITO"]);
			break;
		case "3":
			$oOgg = new cAttach();
			$oOgg->set_id_sito($_SERVER["SITO"]);
			break;
	}

	switch ($_POST["type"]){
		case "2":
			$upl_dir="/home/" . $_POST["txtPercorso"] . "/images/";
			break;
		case "3":
			$upl_dir="/home/" . $_POST["txtPercorso"] . "/attach/";
			break;
	}

	if($upload->uploadFile($upl_dir, 'latin', 36)){
		?>
		<script>
			//setTimeout('document.location="scambio_file.php"',1000);
		</script>
		<?php
		echo "<ul class='file'>";
		echo "<li class='success'>";
		$msgOk="File caricato con successo!";
		echo $msgOk;
		echo "</li>";
		echo "<li>";
		$oOgg->subtitle=$_POST["sottotitolo"];
		$oOgg->path=substr($upl_dir,1) . $upload->_files["file"];
		echo "<span>Immagine caricata: </span>" . $oOgg->path . $upload->_files["file"] . "<br>";
		echo "</li>";
		echo "<li>";
		$oOgg->title=$_POST["txtTitolo"];
		echo "<span>Titolo: </span>" . $oOgg->title . "<br>";
		echo "</li>";
		echo "<li>";
		$oOgg->size=$upload->_size;
		echo "<span>Dimensione file: </span>" . $upload->_size . "<br>";
		echo "</li>";
		echo "<li>";
		$oOgg->format = $upload->_type;
		echo "<span>Tipo file: </span>" . $upload->_type . "<br>";
		echo "</li>";
		echo "</ul>";
		$oOgg->iIdUt=$_SESSION["ID"];
		
		switch ($_POST["type"]){
			case "2":
				$LastId=$oOgg->insert();
				if($LastId!=0){
					$oOgg->insert_image_cont($_POST["txtId"],$LastId,2,$_SESSION["ID"]);
				}else{
					echo "Inserimento dati nel database fallito. Errore: " . mysql_error();
				}
				break;
			case "3";
				$LastId=$oOgg->insert();
				if($LastId!=0){
					$oOgg->insert_attach_cont($_POST["txtId"],$LastId,3,$_SESSION["ID"]);
				}else{
					echo "Inserimento dati nel database fallito. Errore: " . mysql_error();
				}
				break;
		}
	}else{
		//echo "Upload failed!" . E_USER_WARNING;
		$errMsg=$upload->error;
		
		echo "<ul class='file'>";
		echo "<li class='failed'>";
		$msgOk="File caricato con successo!";
		echo $msgOk;
		echo "</li>";
		echo "</ul>";
		
	}
}
?>
