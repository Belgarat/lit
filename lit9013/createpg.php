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

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>Lux in Tenebra - Gestione del PG</title>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<script type="text/javascript" src=<?php echo HTTP_ROOT."/js/scriptaculous182/lib/prototype.js"?>></script>
		<script type="text/javascript" src=<?php echo HTTP_ROOT."/js/scriptaculous182/src/scriptaculous.js"?>></script>
		<link href=<?php echo $SRV_ROOT."/css/pg.css"?> rel="stylesheet" type="text/css">
		<link href=<?php echo $SRV_ROOT."/css/pbf_style.css"?> rel="stylesheet" type="text/css">
		<link href=<?php echo $SRV_ROOT."/css/pbf_amb.css"?> rel="stylesheet" type="text/css">
		<link href=<?php echo $SRV_ROOT."/css/pbf_site.css"?> rel="stylesheet" type="text/css">
	</head>
	
	<body>
	<h2>Creazione nuova scheda personaggio</h2>
	<!--creazione del personaggio, ajax -->
	<div class="create_pg">
		<ul>
			<li id="basic_data">
				<h3>Passaggio Numero 1 - Dati Base</h3>
				<form method="POST" action="base.php">
					<p>Nome: <input type="text" name="pg_name" maxlength="30" /></p>
					<p>Et&agrave;: <input type="text" name="eta" maxlength="3" /></p>
					<p>Sesso:
					<ul>
						<li><input type="radio" name="sex" value="m">Maschio</li>
						<li><input type="radio" name="sex" value="f">Femmina</li>
					</ul>
					</p>
					<p>Razza:
					<ul id="sel_race">
					<?php 
						$temporary = Opg::SelectRace();
						foreach($temporary as $array){
							echo '<li><input type="radio" name="race" value="'.$array[0].'">'.$array[1].'</li>'."\n"; 
						}
					?></ul></p>
					<input type="submit" value="Prossima Pagina" />
					<input type="reset" value="Cancella Dati" />
				</form>
			</li>
			<li id="class_align">
				<h3>Passaggio 2 - Classe e Allineamento</h3>
				<form method="POST" action="class_align.php">
					<p>Classe:
					<ul id="class">
					<?php
						$temporary = Opg::selectClass();
						foreach ($temporary as $array){
							echo '<li><input type="radio" name="class" value="'.$array[0].'">'.$array[1].'</li>';
						}
					?></ul></p>
				<p>Allineamento:
					<ul id="align">
					<?php
						$align = Opg::selectAlign();
						foreach ($align as $array){
							echo '<li><input type="radio" name="alignment" value="'.$array[0].'">'.$array[1].'</li>';
						}
					?></ul></p>
					<input type="submit" value="Prossima Pagina" />
					<input type="reset" value="Cancella Dati" />
				</form>
			</li>
			<li id="char">
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
			</li>
		</ul>
	</div>
	</body>
