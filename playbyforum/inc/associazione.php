<?php
switch ($_GET["sez"]){
	case "statuto":
			require_once("statuto.php");
			break;
	case "iscrizione":
			require_once("iscrizione_associazione.php");
			break;
	default:
		?>
			<div style="letter-spacing:1pt;text-indent:10px;padding:5px;line-height:170%;">
			<b><p align="center" class="cTestoNorm">Lux In Tenebra - Associazione culturale</p></b>
			
			<p>E’ nata l’Associazione Culturale Lux in Tenebra!</p>

			<p align=justify>Si tratta di un’associazione senza scopo di lucro che ha tra i suoi scopi principali 
			la diffusione di una corretta informazione sul gioco e sul gioco di ruolo in particolare 
			e la promozione di attività culturali legate sia al mondo ludico che a quello della 
			narrativa, per creare un legame tra il gioco e la letteratura.</p>

			<p align=justify>L’Associazione nasce in seguito alle nostre esperienze di un giocatori/scrittori 
			della comunità virtuale Lux in Tenebra. Ci siamo frequentati per diversi anni 
			giocando sul sito, ampliando e arricchendo la nostra esperienza di giocatori di 
			ruolo ma anche di appassionati di narrativa e letteratura fantasy.</p>

			<p align=justify>Via via che maturava e cresceva la nostra passione di scrittori, superando i 
			confini del gioco di ruolo tradizionale, abbiamo sentito l’esigenza di fondare 
			un’Associazione per rendere le nostre attività amatoriali più organizzate e 
			costruttive, e per coinvolgere anche altre persone che potessero avere i nostri 
			stessi interessi.</p>

			<p align=justify>Associarsi è facile! Potete prendere visione dello Statuto direttamente dal sito 
			e poi se vi riconoscete negli intenti dell’Associazione potete compilare il 
			modulo associativo, anch’esso disponibile online, e inviarlo con un semplice click.
			La quota associativa annuale è di 20 euro, il doppio se volete diventare “soci sostenitori”.</p>

			<p align=justify>Per versare la quota ci sono due soluzioni: bonifico bancario sul nostro conto 
			....................... ......................, oppure tramite bollettino postale,
			 indicando .........................................</p>

			<p align=justify>A tutti i soci neo-iscritti, oltre alla tessera associativa, verrà inviato un “welcome kit” 
			che comprende: 
			- un segnalibro magnetico Lux in Tenebra;
			- un andesivo Lux in Tenebra;
			- un CD-Rom contenente tutti i manuali di ambientazione necessari per giocare nel mondo di LiT, e cioè:<br>
					<ul style="text-align:justify;list-style-type : decimal;line-height:180%;">
					<li>"Il mondo di Lux in tenebra. Le terre del Pentacolo", modulo base.</li>
					<li>"Le terre oscure dell’Oltre Barriera". Ambientazione e regole aggiuntive per i territori esterni al Pentacolo;</li>
					<li>"Il Pantheon di Lux in Tenebra". Divinità maggiori e minori del Pentacolo e dell’Oltre Barriera;</li>
					<li>"I corpi armati di Lux in Tenebra". Ordini paladinici, ordini cavallereschi e consorterie militari del Pentacolo;</li>
					<li>"Miti e leggende di Lux in Tenebra". Antologia di racconti mitici ambientati nel mondo del Pentacolo.</li>
					<li>un miniposter con la mappa dei territori dove si svolgono le avventure di Lux in Tenebra;</li>
					</ul>
			</p>
			<p align=justify>Abbiamo tanti progetti per gli appassionati di GdR, letteratura fantasy e per chi ama scrivere! 
			Non mancate di visitare il nostro sito con regolarità! Troverete tutte le informazioni!</p>

						
						</div>
					
					<?php			
}
?>