<?php
/*
 * Created on 28/set/07
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

//class cRegister extends cPage
//{

//	public function show(opt=""){
		$sRispPriv="";
		$sRispDir="";
		if(!isset($_SESSION["ID"])){
			if($_POST["Submit"]=="Registrati"){
				require_once("newuser.php");
			}else{
				?>
				<form action="<?php
				echo $_SERVER["REQUEST_URI"];
				?>" method="post" name="frmYes">
				<TABLE width="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
					<?php
					$sRispPriv=$_POST["optPriv"];
					$sRispDir=$_POST["optDir"];
					$sRisp=$sRispPriv . $sRispDir;
					switch($sRisp){
						case "11":
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
														Note:</FONT> <FONT color="#ffffff" size="2">Data in formato gg/mm/aa usare solamente numeri e la barra (slash). Registrazione vietata ai minori di 14 anni</FONT></td>
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
												<td colspan=2><b>IMPORTANTE: per la creazione del primo personaggio &egrave; necessario contattare un
												master. Per scegliere che master contattare cliccare sul tasto "AVVENTURE" del forum e decidere a quale
												avventura di ingresso appartenere per iniziare.
												<br><br>
											</tr>
											<tr>
												<td></td>
												<td WIDTH="78%"><input id="Reg" type="submit" name="Submit" value="Registrati"> <input id="Res" type="reset" name="Reset" value="Reset">
												</td>
											</tr>
										</table>
									</form>
								</p>
							</TD>
						</TR>
						<?php
						break;
					case "10":
						echo "Non &egrave possibile procedere con la registrazione, manca approvazione della privicy!";
						break;
					case "01":	
						echo "Non &egrave possibile procedere con la registrazione, manca approvazione delle Common!";
						break;
					case "00":
						echo "Non &egrave possibile procedere con la registrazione, manca approvazione delle Common!<br>";
						echo "Non &egrave possibile procedere con la registrazione, manca approvazione della privicy!";
						break;
					default:
						?>
						<td align="center"><br><br>
							<table class="cParTab" width="90%"><tr><td colspan="2">
										<div style="padding-right:6px;font-color:white;height:300px;width:550px;overflow:auto;">
											<P LANG="it-IT" ALIGN=CENTER STYLE="margin-bottom: 0cm; line-height: 0.35cm">
											<FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><B>CONDIZIONI
											GENERALI </B></FONT></FONT></FONT>
											</P>
											<P ALIGN=LEFT STYLE="margin-bottom: 0cm; line-height: 0.35cm"><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT"><SPAN STYLE="font-weight: medium">Premess</SPAN></SPAN></FONT></FONT></FONT><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT"><SPAN STYLE="font-weight: medium">o
											che:</SPAN></SPAN></FONT></FONT></FONT></P>
											<P ALIGN=JUSTIFY STYLE="margin-bottom: 0cm; line-height: 0.35cm"><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT"><B>a)
											-</B></SPAN></FONT></FONT></FONT><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT">
											con la presente registrazione Lei accetta le seguenti condizioni
											generali che si applicano a tutte le attivit&agrave; svolte,
											attraverso il sito internet www.luxintenebra.it, dall'Associazione
											culturale senza scopo di lucro "Luxintenebra" (di seguito anche
											soltanto "LiT"), con sede legale in Via Cart 40, 32032 Feltre
											(BL);</SPAN></FONT></FONT></FONT></P>
											<P ALIGN=JUSTIFY STYLE="margin-bottom: 0cm; line-height: 0.35cm"><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT"><B>b)
											-</B></SPAN></FONT></FONT></FONT><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT">
											ai fini delle presenti condizioni generali si adottono le seguenti
											definizioni:</SPAN></FONT></FONT></FONT></P>
											<UL>
												<LI><P ALIGN=JUSTIFY STYLE="margin-bottom: 0cm; line-height: 0.35cm">
												<FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT"><B>Utente</B></SPAN></FONT></FONT></FONT><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT">:
												chiunque crei un nome identificativo relazionato ad una parola
												segreta e che, accedendo a mezzo di detta combinazione al sito
												internet www.luxintenebra.it, partecipi al forum di discussione ivi
												realizzato, nonch&eacute; a tutte le attivit&agrave; che, a mezzo
												dello stesso sito, vengano realizzate;</SPAN></FONT></FONT></FONT></P>
												<LI><P ALIGN=JUSTIFY STYLE="margin-bottom: 0cm; line-height: 0.35cm">
												<FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT"><B>Contenuti</B></SPAN></FONT></FONT></FONT><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT">:
												tutti gli scritti, le immagini, i suoni, i video e comunque ogni
												opera dell'ingegno che un Utente invii in una qualsiasi delle
												sezioni del sito internet predetto;</SPAN></FONT></FONT></FONT></P>
												<LI><P ALIGN=JUSTIFY STYLE="margin-bottom: 0cm; line-height: 0.35cm">
												<FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT"><B>Opera</B></SPAN></FONT></FONT></FONT><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT">:
												la seguenza dei Contenuti, inviati dagli Utenti nel corso di una o
												pi&ugrave; sessioni di gioco, che nel loro complesso formano uno,
												molteplici o parti di racconti.</SPAN></FONT></FONT></FONT></P>
											</UL>
											<P LANG="it-IT" ALIGN=JUSTIFY STYLE="margin-bottom: 0cm; line-height: 0.35cm">
											<FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3>Tanto
											premesso,</FONT></FONT></FONT></P>
											<P LANG="it-IT" ALIGN=JUSTIFY STYLE="margin-bottom: 0cm; line-height: 0.35cm">
											<FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><B>1)
											- Premesse.</B></FONT></FONT></FONT></P>
											<P LANG="it-IT" ALIGN=JUSTIFY STYLE="margin-bottom: 0cm; line-height: 0.35cm">
											<FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3>Le
											premesse formano parte integrante e sostanziale del presente atto e
											ne costituiscono patto.</FONT></FONT></FONT></P>
											<P ALIGN=JUSTIFY STYLE="margin-bottom: 0cm; line-height: 0.35cm"><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT"><B>2</B></SPAN></FONT></FONT></FONT><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT"><B>)
											- Descrizione del servizio.</B></SPAN></FONT></FONT></FONT></P>
											<P ALIGN=JUSTIFY STYLE="margin-bottom: 0cm; line-height: 0.35cm"><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT">LiT
											gestisce il sito internet www.luxintenebra.it a mezzo del quale si
											svolgono le sessioni di gioco di ruolo, consistente nella narrazione
											di avventure realizzate attraverso l'interazione tra gli Utenti ed i
											Contenuti dagli stessi inviati al forum del sito medesimo (c.d. </SPAN></FONT></FONT></FONT><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT"><I>play
											by forum</I></SPAN></FONT></FONT></FONT><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT">,
											indicato di seguito anche solo "PbF"). </SPAN></FONT></FONT></FONT>
											</P>
											<P LANG="it-IT" ALIGN=JUSTIFY STYLE="margin-bottom: 0cm; line-height: 0.35cm">
											<FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3>Il
											gioco, ha delle regole proprie, indicate in specifiche sezioni del
											sito internet, a loro volta integrate con quelle previste nei manuali
											del gioco di ruolo "Dungeons &amp; Dragons". Detto complesso di
											regole si ha per conosciuto e si considerano come un unico complesso.</FONT></FONT></FONT></P>
											<P LANG="it-IT" ALIGN=JUSTIFY STYLE="margin-bottom: 0cm; line-height: 0.35cm">
											<FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3>LiT
											realizza progetti di varia natura  relativi al PdF e, pi&ugrave; in
											generale, al gioco di ruolo, alla comunit&agrave; degli Utenti ed
											alla narrativa. A titolo esemplificativo e non esaustivo, LiT si
											adopera: all'eventuale pubblicazione delle Opere attraverso la
											realizzazione di fumetti, romanzi, racconti ed ogni altra forma di
											diffusione ed espressione narrativa; alla possibile organizzazione di
											laboratori letterari dedicati all'approfondimento delle tecniche
											narrative e alla produzione letteraria; all'organizzazione di raduni
											ed appuntamenti di gioco, anche a tema storico medievale.</FONT></FONT></FONT></P>
											<P ALIGN=JUSTIFY STYLE="margin-bottom: 0cm; line-height: 0.35cm"><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT"><B>3</B></SPAN></FONT></FONT></FONT><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT"><B>)
											-  Disciplina del diritto d'autore.</B></SPAN></FONT></FONT></FONT></P>
											<P ALIGN=JUSTIFY STYLE="margin-bottom: 0cm; line-height: 0.35cm"><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT">Lit
											adotta, salvo diversamente previsto, una Licenza </SPAN></FONT></FONT></FONT><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="en">Creative
											Commons</SPAN></FONT></FONT></FONT><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT">
											Attribuzione-Non opere derivate 2.5. (LINK)</SPAN></FONT></FONT></FONT></P>
											<P LANG="it-IT" ALIGN=JUSTIFY STYLE="margin-bottom: 0cm; line-height: 0.35cm">
											<FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3>L'Utente
											acconsente che i Contenuti inviati sul sito internet e le Opere siano
											assoggettati alla medesima licenza.</FONT></FONT></FONT></P>
											<P ALIGN=JUSTIFY STYLE="margin-bottom: 0cm; line-height: 0.35cm"><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT">LiT
											non rivendica la propriet&agrave; dei Contenuti forniti dall'Utente
											il quale mantiene la propriet&agrave; degli stessi; l'Utente
											garantisce di detenere sui Contenuti i diritti necessari allo
											svolgimento delle attivit&agrave; realizzate da Lit, anche</SPAN></FONT></FONT></FONT><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT">
											ceduti dall'autore in via non esclusiva.</SPAN></FONT></FONT></FONT></P>
											<P ALIGN=JUSTIFY STYLE="line-height: 0.35cm"><STRONG><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT">4</SPAN></FONT></FONT></FONT></STRONG><STRONG><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT"><B>)
											-</B></SPAN></FONT></FONT></FONT></STRONG><STRONG><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT">
											Concessione della Licenza.</SPAN></FONT></FONT></FONT></STRONG><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT">
											</SPAN></FONT></FONT></FONT>
											</P>
											<P LANG="it-IT" ALIGN=JUSTIFY STYLE="line-height: 0.35cm"><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3>Nel
											rispetto dei termini e delle condizioni predette, l'Utente concede a
											LiT una licenza per tutto il mondo, gratuita, non esclusiva e
											perpetua (per la durata del diritto d’autore applicabile); e, nel
											trasferire a LiT i diritti di utilizzazione economica, autorizza la
											stessa alla:</FONT></FONT></FONT></P>
											<UL>
												<LI><P LANG="it-IT" ALIGN=JUSTIFY STYLE="line-height: 0.35cm"><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3>riproduzione
												dell'Opera, incorporazione dell'Opera in una o pi&ugrave;
												Collezioni di Opere e riproduzione dell'Opera come incorporata
												nelle Collezioni di Opere;</FONT></FONT></FONT></P>
												<LI><P LANG="it-IT" ALIGN=JUSTIFY STYLE="line-height: 0.35cm"><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3>distribuzione
												di copie dell'Opera, comunicazione al pubblico, rappresentazione,
												esecuzione, recitazione o esposizione in pubblico, ivi inclusa la
												trasmissione audio digitale dell'Opera, e ci&ograve; anche quando
												l'Opera sia incorporata in Collezioni di Opere; </FONT></FONT></FONT>
												</P>
											</UL>
											<P LANG="it-IT" ALIGN=JUSTIFY STYLE="line-height: 0.35cm"><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3>I
											diritti sopra descritti potranno essere esercitati con ogni mezzo di
											comunicazione e in tutti i formati. Tra i diritti di cui sopra si
											intende compreso il diritto di apportare all'Opera le modifiche che
											si rendessero tecnicamente necessarie per l'esercizio di detti
											diritti tramite altri mezzi di comunicazione o su altri formati.
											Tutti i diritti non espressamente concessi dall'Utente rimangono
											riservati.</FONT></FONT></FONT></P>
											<P ALIGN=JUSTIFY STYLE="line-height: 0.35cm"><STRONG><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT">5</SPAN></FONT></FONT></FONT></STRONG><STRONG><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT"><B>)
											-</B></SPAN></FONT></FONT></FONT></STRONG><STRONG><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT">
											Restrizioni.</SPAN></FONT></FONT></FONT></STRONG><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT">
											La Licenza concessa in conformit&agrave; al precedente punto 4 &egrave;
											espressamente assoggettata a, e limitata da, le seguenti restrizioni:</SPAN></FONT></FONT></FONT></P>
											<UL>
												<LI><P ALIGN=JUSTIFY STYLE="line-height: 0.35cm"><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT">LiT
												pu&ograve; distribuire, comunicare al pubblico, rappresentare,
												eseguire, recitare o esporre in pubblico l'Opera, anche in forma
												digitale, solo assicurando che i termini di cui alla licenza di cui
												al punto 3 siano rispettati e, insieme ad ogni copia dell'Opera
												che distribuisca, comunichi al pubblico o rappresenti, esegua,
												reciti od esponga in pubblico, anche in forma digitale, debba
												indicare la licenza medesima</SPAN></FONT></FONT></FONT><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3><SPAN LANG="it-IT">;</SPAN></FONT></FONT></FONT></P>
												<LI><P LANG="it-IT" ALIGN=JUSTIFY STYLE="line-height: 0.35cm"><FONT ><FONT FACE="Century Gothic, sans-serif"><FONT SIZE=3>Qualora
												LiT distribuisca, comunichi al pubblico, rappresenti, esegua, reciti
												o esponga in pubblico, anche in forma digitale, l'Opera, deve
												mantenere intatte tutte le informative sul diritto d’autore
												sull'Opera. Deve riconoscere all'Utente una menzione adeguata
												rispetto al mezzo di comunicazione o supporto che utilizzi citando
												il nome (o lo pseudonimo, se del caso) dell'Utente, ove fornito.</FONT></FONT></FONT></P>
											</UL>
										</div>
									</td>
								</tr>
							</table>
							<br>
							<Select name="optDir">
								<option value="0">Non accetto</option>
								<option value="1">Accetto</option>
							</Select>
						</td>
					</tr>
					<tr>
					<td  align="center"><br><br>
						<table class="cParTab" width="90%">
							<tr>
								<td colspan="2">
									<font size="3">I dati personali volontariamente forniti dagli utenti all'atto della registrazione sul sito di Lux in Tenebra sono soggetti al trattamento previsto dal D.L. 30 giugno 2003, n. 196, "Codice in materia di protezione dei dati personali", ed in particolare a quanto prescritto dall'art. 7 ("Diritto di accesso ai dati personali ed altri diritti").<br><br>
									Per ogni comunicazione e/o richiesta in relazione al trattamento dei dati personali gli utenti sono pregati di scrivere a webmaster@luxintenebra.net.<br><br>
									I dati personali vengono registrati esclusivamente per scopi relativi alla gestione del database della comunit&agrave, ed utilizzati solo per finalit&agrave direttamente connesse e strumentali all'erogazione dei servizi del sito, e non verranno ceduti o comunicati a terze parti.</font>								
								</td>
							</tr>
						</table>
						<br>
						<Select name="optPriv">
							<option value="0">Non accetto</option>
							<option value="1">Accetto</option>
						</Select>
					</td>
					</tr>
					<tr>
					<td  align="center"><br><br>
						<input type="submit" name="Continua" value="Continua">
					</td>
					</tr>
					<?php
					}
					?>
				</TABLE>
				</form>
			<?php
			}
		}else{
			echo "Sei gi&agrave registrato.";
		}
//	}
//}
?>
