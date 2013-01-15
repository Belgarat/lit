<?php
/*
 * Created on 28/set/07
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
if(!isset($_SESSION["ID"])){
	if($_POST["Submit"]=="Submit"){
		require_once($_SERVER["DOCUMENT_ROOT"] . "/src/newuser.php");
	}else{
		?>
		<TABLE width="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
			<?php
			switch($_GET["privacy"]){
				case "no":
					echo "<TR>";
					echo "<TD align='center'><br><br>";
					echo "Impossibile continuare con la registrazione.<br><br>";
					echo "<a href='../index.php'>Torna al portale Lux in tenebra.</a>";
					echo "</TD>";
					echo "</TR>";
					break;
				case "yes":
				?>
				
			<TR VALIGN="top" ALIGN="left">
				<TD>
					<p ALIGN="left">
						<form NAME="form1" METHOD="post" ACTION="./index.php?IdP=
						<?php
						echo $_GET["IdP"];
						?>
						">
							<table WIDTH="100%" BORDER="0">
								<tr>
									<td VALIGN="top"><b><font FACE="Arial, Helvetica, sans-serif" COLOR="#ffffff">Nome:</font><font color="red">*</font></b></td>
									<td><input id="Nome" OnBlur="javascript:onblur_bg(this.id);" OnFocus="javascript:onfocus_bg(this.id);" type="text" name="txtNome"></td>
								</tr>
								<tr>
									<td VALIGN="top"><b><font FACE="Arial, Helvetica, sans-serif" COLOR="#ffffff">Cognome:</font><font color="red">*</font></b></td>
									<td><input id="Cognome" OnBlur="javascript:onblur_bg(this.id);" OnFocus="javascript:onfocus_bg(this.id);" type="text" name="txtCognome"></td>
								</tr>
								<tr>
									<td WIDTH="22%">
										<b><font FACE="Arial, Helvetica, sans-serif" COLOR="#ffffff">Nickname:</font><font color="red">*</font></b>
									</td>
									<td WIDTH="78%">
											<input id="Nickname" OnBlur="javascript:onblur_bg(this.id);" OnFocus="javascript:onfocus_bg(this.id);" TYPE="text" NAME="txtNickname" MAXLENGTH="100" ID="Text1">
										<font COLOR="#ffffff" SIZE="2">Note:</font> <font SIZE="2" COLOR="#ffffff">Questo 
											nome verr&agrave utilizzato da voi per loggarvi all'interno del portale. <STRONG>Non 
												utilizzare caratteri spazio. Minimo 5 caratteri</STRONG></font>
									</td>
									</tr>
								<tr>
									<td WIDTH="22%"><b><font FACE="Arial, Helvetica, sans-serif" COLOR="#ffffff">Password:</font><font color="red">*</font></b></td>
									<td WIDTH="78%">
										<input id="Password" OnBlur="javascript:onblur_bg(this.id);" OnFocus="javascript:onfocus_bg(this.id);" TYPE="password" NAME="txtPassword" MAXLENGTH="50"> <FONT color="#ffffff" size="2">
											Note:</FONT> <FONT color="#ffffff" size="2">Consigliato almeno 8 caratteri.</FONT>
									</td>
								</tr>
								<tr>
									<td WIDTH="22%"><b><font FACE="Arial, Helvetica, sans-serif" COLOR="#ffffff">Conferma password:</font><font color="red">*</font></b></td>
									<td WIDTH="78%">
										<input id="ConfermaPassword" OnBlur="javascript:onblur_bg(this.id);" OnFocus="javascript:onfocus_bg(this.id);" TYPE="password" NAME="txtConfermaPassword" MAXLENGTH="50">
									</td>
								</tr>
								<tr>
									<td WIDTH="22%"><b><font FACE="Arial, Helvetica, sans-serif" COLOR="#ffffff">Email:</font><font color="red">*</font></b></td>
									<td WIDTH="78%">
										<input id="Email" OnBlur="javascript:onblur_bg(this.id);" OnFocus="javascript:onfocus_bg(this.id);" TYPE="text" NAME="txtEmail" MAXLENGTH="150"> <FONT color="#ffffff" size="2">Note:</FONT>
										<FONT size="2">sar&agrave utilizzata per mandarvi i dati dell'iscrizione e le News</FONT>
									</td>
								</tr>
								<tr>
									<td VALIGN="top"><b><font FACE="Arial, Helvetica, sans-serif" COLOR="#ffffff">Data di nascita:</font><font color="red">*</font></b></td>
									<td><input id="Data" OnBlur="javascript:onblur_bg(this.id);" OnFocus="javascript:onfocus_bg(this.id);" type="text" name="txtDataNascita"><FONT color="#ffffff" size="2">
											Note:</FONT> <FONT color="#ffffff" size="2">Registrazione vietata ai minori di 14 anni</FONT></td>
								</tr>
								<tr>
									<td VALIGN="top"><b><font FACE="Arial, Helvetica, sans-serif" COLOR="#ffffff">Luogo di nascita:</font></b></td>
									<td><input id="Luogo" OnBlur="javascript:onblur_bg(this.id);" OnFocus="javascript:onfocus_bg(this.id);" type="text" name="txtLuogoNascita"></td>
								</tr>
								<tr>
									<td VALIGN="top"><b><font FACE="Arial, Helvetica, sans-serif" COLOR="#ffffff">Professione:</font></b></td>
									<td><input id="Professione" OnBlur="javascript:onblur_bg(this.id);" OnFocus="javascript:onfocus_bg(this.id);" type="text" name="txtProfessione"></td>
								</tr>						
								<tr>
									<td VALIGN="top"><b><font FACE="Arial, Helvetica, sans-serif" COLOR="#ffffff">Residenza:</font></b></td>
									<td><input id="Residenza" OnBlur="javascript:onblur_bg(this.id);" OnFocus="javascript:onfocus_bg(this.id);" type="text" name="txtResidenza"></td>
								</tr>
								<tr>
									<td VALIGN="top"><b><font FACE="Arial, Helvetica, sans-serif" COLOR="#ffffff">Cellulare:</font></b></td>
									<td><input id="Cellulare" OnBlur="javascript:onblur_bg(this.id);" OnFocus="javascript:onfocus_bg(this.id);" type="text" name="txtCellulare"></td>
								</tr>
								<tr>
									<td VALIGN="top"><b><font FACE="Arial, Helvetica, sans-serif" COLOR="#ffffff">Come ho conosciuto Lux in Tenebra:</font><font color="red">*</font></b></td>
									<td><input id="Lux" OnBlur="javascript:onblur_bg(this.id);" OnFocus="javascript:onfocus_bg(this.id);" type="text" name="txtCome"></td>
								</tr>
								<!--<tr>
									<td VALIGN="top"><b><font FACE="Arial, Helvetica, sans-serif" COLOR="#ffffff">ICQ:</font></b></td>
									<td><input id="ICQ" OnBlur="javascript:onblur_bg(this.id);" OnFocus="javascript:onfocus_bg(this.id);" type="text" name="txtICQ"></td>
								</tr>
								<tr>
									<td VALIGN="top"><b><font FACE="Arial, Helvetica, sans-serif" COLOR="#ffffff">MSN o 
												Messenger:</font></b></td>
									<td><input id="MSN" OnBlur="javascript:onblur_bg(this.id);" OnFocus="javascript:onfocus_bg(this.id);" type="text" name="txtMSN"></td>
								</tr>
								<tr>
									<td WIDTH="22%" VALIGN="top"><b><font FACE="Arial, Helvetica, sans-serif" COLOR="#ffffff">Firma:</font></b></td>
									<td WIDTH="78%">
										<textarea  id="Firma" OnBlur="javascript:onblur_bg(this.id);" OnFocus="javascript:onfocus_bg(this.id);" NAME="txtSignature" COLS="40" ROWS="5"></textarea>
									</td>
								</tr>-->
								<tr>
									<td WIDTH="22%"><b><font FACE="Arial, Helvetica, sans-serif" COLOR="#ffffff">Immagine:</font></b></td>
									<td WIDTH="78%">
										<input id="Imm" OnBlur="javascript:onblur_bg(this.id);" OnFocus="javascript:onfocus_bg(this.id);" value="http://www.luxintenebra.net/forum/images/avatar.jpg" TYPE="text" NAME="txtPhoto" MAXLENGTH="50" ID="TextP">
										<font COLOR="#ffffff" SIZE="2">Note: questa &egrave l'immagine che identificher&agrave il 
											vostro utente non il vostro PG.</font></td>
								</tr>
								<tr>
									<td>
										<br>
									</td>
									<td>
										<br>
									</td>
								</tr>
								<tr>
									<td>
										<b><font COLOR="#ffff00" SIZE="2" face="Arial, Helvetica, sans-serif">Parte riguardante 
												il PG</font></b>
									</td>
									<td>
										<hr>
									</td>
								</tr>
								<tr>
									<td WIDTH="22%"><b><font FACE="Arial, Helvetica, sans-serif" COLOR="#ffffff">Nome 
												personaggio :</font><font color="red">*</font></b></td>
									<td WIDTH="78%">
										<input id="Pg" OnBlur="javascript:onblur_bg(this.id);" OnFocus="javascript:onfocus_bg(this.id);" TYPE="text" NAME="txtUsername" MAXLENGTH="50"> <font COLOR="#ffffff" SIZE="2">
											Note: questo sar&agrave il nome del vostro primo personaggio, diverso dal Nickname.</font></td>
								</tr>
								<tr>
									<td></td>
									<td WIDTH="78%"><input id="Reg" type="submit" name="Submit" value="Submit"> <input id="Res" type="reset" name="Reset" value="Reset">
									</td>
								</tr>
							</table>
						</form>
					</p>
				</TD>
			</TR>
			<?php
			break;
			default:
			?>
			<tr>
				<td align="center"><br><br>
					<table class="cParTab" width="90%">
						<tr>
							<td colspan="2">
								<font size="3">I dati personali volontariamente forniti dagli utenti all'atto della registrazione sul sito di Lux in Tenebra sono soggetti al trattamento previsto dal D.L. 30 giugno 2003, n. 196, "Codice in materia di protezione dei dati personali", ed in particolare a quanto prescritto dall'art. 7 ("Diritto di accesso ai dati personali ed altri diritti").<br><br>
								Per ogni comunicazione e/o richiesta in relazione al trattamento dei dati personali gli utenti sono pregati di scrivere a webmaster@luxintenebra.net.<br><br>
								I dati personali vengono registrati esclusivamente per scopi relativi alla gestione del database della comunit&agrave, ed utilizzati solo per finalit&agrave direttamente connesse e strumentali all'erogazione dei servizi del sito, e non verranno ceduti o comunicati a terze parti.</font>								
							</td>
						</tr>
						<tr>
							<td><br><br>
								<form action="./index.php?IdP=<?php
								echo $_GET["IdP"];
								?>&privacy=yes" method="post" name="frmYes">
									<input type="submit" name="Accetta" value="Accetta">
								</form>
							</td>
							<td align="right"><br><br>
								<form action="./index.php?IdP=<?php
								echo $_GET["IdP"];
								?>&privacy=no" method="post" name="frmNo">
									<input type="submit" name="Rifiuta" value="Rifiuta">
								</form>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<?php
			}
			?>
		</TABLE>
	<?php
	}
}else{
	echo "Sei gi&agrave registrato.";
}
?>
