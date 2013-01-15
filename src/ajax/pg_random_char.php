<?php
/*
* pagina per la creazione del PG
* richiama le funzioni della classe Opg
* per dare la possibilità di creare i PG
* in maniera "standard"
* 
*/
	session_start();
	require_once ("required_pg.php");
	
	$oDb = Db::getInstance();
	$oDb->Open();
?>

<h3>Passaggio 3 - Caratteristiche</h3>
	<form method="POST" action="chars.php">
		<p>Caratteristiche:
		<table cellpadding="2" border="1" >
			<thead>
				<tr><th>Caratteristica</th><th>Valore</th></tr>
			</thead>
			<?php 
			$temporary=Opg::randomAbility();
			foreach ($temporary as $char=>$value){
				echo '<tr><td>'.$char.'</td><td>'.$value.'</td></tr>';
			}
			?>
		</table></p>
		<input type="submit" value="Accetta" />
		<input type="button" value="Altri Valori" onclick='javascript: var uli = new Ajax.Updater("char","../src/ajax/pg_random_char.php","get")' />
	</form>
