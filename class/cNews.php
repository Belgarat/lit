<?php
/*
	News object
	
	Trace the user in the portal.

    Versions:
    20071028 - Belgarat - Starting developing
		
	Table cp_events_log fields
  	 id
	 id_sito
	 id_news
	 IdUt
	 Autore
	 title
	 News
	 Date
	 Time

*/
require_once("cPage.php");

Class cNews extends cPage
	{
	
    private $classname;
	public $id_news;
	public $id_utente;
	public $id_tipo;
	public $sStile;
	public $MaxNews;
	public $title;
	public $body;
	public $author;
	//protected $oUt;
	//protected $id_sito;	
	//private $aPermission;
	
	function __construct(){
        	$this->classname="cNews";
		if($this->sStile==""){
			$this->sStile="default";
		}
		if($this->id_tipo==""){
			if ($this->opt="") {
				$this->id_tipo=0;
			} else {
				$this->id_tipo=$this->opt;
			}
		}
		if($this->id_sito==""){
			$this->set_id_sito(0);
		}
		if(($this->MaxNews=="") or ($this->MaxNews=0) or (is_null($this->MaxNews="")) or (isset($this->MaxNews))){
			$this->MaxNews=3;
		}
	}
	
	public function include_css(){
		if($this->let_id_sito()==1){
			echo "<link rel='stylesheet' type='text/css' href='".THEMES."/css/pbf_news.css'>";
		}else{
			echo "<link rel='stylesheet' type='text/css' href='".HTTP_ROOT."/css/associazione_news.css'>";
		}
	}
	public function include_js(){
		echo "<script type=\"text/javascript\" src=\""  .HTTP_ROOT."/js/cNews.js\"></script>";
	}

	public function show($opt=""){
		
		$aAllow["Show"]=$this->oUt->fControlPermission($_SERVER["SITO"],"cNews","show");
		$aAllow["Create"]=$this->oUt->fControlPermission($_SERVER["SITO"],"cNews","create");
		$aAllow["Modify"]=$this->oUt->fControlPermission($_SERVER["SITO"],"cNews","modify");
		$aAllow["Delete"]=$this->oUt->fControlPermission($_SERVER["SITO"],"cNews","delete");

		//$this->let_id_sito();
		//$this->id_tipo = $opt;
		$this->MaxNews = 5;
	
		if((!$_GET["IdN"]=="")){
			?>
			<p align=center><span style="font-size:14pt; color:#800080; font-weight:bold;"><?php echo $this->page_title; ?></span></br>
			<table border="0" bordercolor="#4E1D69" cellSpacing=2 cellpadding="10" width="100%" border=0 valign="top">
			<tr>
			<td vAlign=top width="100%">
			<?php
			$this->set_title_page($this->let_id_page());
			$this->id_news=(int) $_GET["IdN"];
			$this->show_single_news($aAllow,0);
			//ASINELLO: Correggere passaggio variabile IdP
			?>
			<p align=center><a href="./index.php">Indietro</a></br>
			<?php
		}else{	
			?>
			<br>
			<p align=center><span style="font-size:14pt; color:#800080; font-weight:bold;"><?php echo $this->page_title; ?></span></br>
			<span style="font-size:10pt; color:#800080; font-style:italic;">(Ultimi : <?php echo $this->MaxNews ?>)</span></p>
			<table border="0" bordercolor="#4E1D69" cellSpacing=2 cellpadding="10" width="100%" border=0 valign="top">
			<tr>
			<td vAlign=top width="100%">
			<?php
			$this->show_news($aAllow,0);
		}
		?>
		</td>
		</tr>
		</table>
		<?php
	}


	private function verify_property(){
		if(get_magic_quotes_gpc()==0){
	        $this->author=addslashes($this->author);
	        $this->title=addslashes($this->title);
	        $this->body=addslashes($this->body);
		}
	}

	public function read(){
		
	        $sSql = "SELECT id,IdUt,id_tipo,time,datetime,title,Autore,News FROM tbl_news where id_sito='" . $this->id_sito . "' and id='" . $this->id_news . "' and id_tipo='" . $this->id_tipo . "' order by Date desc,Time desc LIMIT 0," . $this->MaxNews . ";";
			$ris = mysql_query($sSql);		
			
			if (!$ris){
				echo "Query fallita!";
				exit;
			}
			
	        $riga = mysql_fetch_assoc($ris);
	        
	        $this->id_news=$riga["id"];
	        $this->id_utente=$riga["IdUt"];
	        $this->author=$riga["Autore"];
	        $this->title=$riga["title"];
	        $this->body=$riga["News"];
	        $this->datetime=$riga["datetime"];
	        
	        mysql_free_result($ris);
	        
	}

	public function insert_news($aAllow){
		$this->verify_property();
		foreach($aAllow as $k => $value){
			if($k=="Create"){
				//***E' necessario trovare un modo più elegante per distinguere la creazione pagina dal resto.***
				if($value==1){
					$sSql="insert into tbl_news(id_sito,id_tipo,IdUt,Autore,title,News,datetime) " .
							"values('" . $this->id_sito . "', '" . $this->id_tipo . "', '" . $this->id_utente. "', " .
									"'" . $this->author . "', '" . $this->title . "', '" . $this->body . "', '" . time() . "');";
					mysql_query($sSql);
					return "Inserimento avvenuto con successo.";
				}else{
					return "Non autorizzato all'inserimento.";
				}
			}
		}
		
	}

	public function modify_news($aAllow){
		$this->verify_property();
		foreach($aAllow as $k => $value){
			if($k=="Modify"){
				//***E' necessario trovare un modo più elegante per distinguere la creazione pagina dal resto.***
				if($value==1){
					$sSql="update tbl_news " .
							"set id_sito='" . $this->id_sito . "', id_tipo='" . $this->id_tipo . "', IdUt='" . $this->id_utente. "', " .
									"title='" . $this->title . "', News='" . $this->body . "' where id='" . $this->id_news . "';";
					mysql_query($sSql);
					//echo $sSql;
					echo mysql_error();
					return "Modifica avvenuta con successo.";
				}else{
					return "Non autorizzato alla modifica.";
				}
			}
		}
		
	}

	public function delete_news($aAllow){
		foreach($aAllow as $k => $value){
			if($k=="Delete"){
				//***E' necessario trovare un modo più elegante per distinguere la creazione pagina dal resto.***
				if($value==1){
					$sSql="delete from tbl_news where id='" . $this->id_news . "';";
					mysql_query($sSql);
					return "Cancellazione avvenuto con successo.";
				}else{
					return "Non autorizzato alla cancellazione.";
				}
			}
		}
		
	}

	private function read_style(){
		$sSql="select stile from tbl_news_tipo where id='" . $this->id_tipo . "'";
		$risultato=mysql_query($sSql);
		if($risultato){
			$row=mysql_fetch_assoc($risultato);
			return $row["stile"];
		}else{
			return "default";
		}
		mysql_free_result($risultato);
	}

	public function show_news($aAllow=0,$manage=0){
		$this->sStile=$this->read_style();
		//echo "Stile: " . $this->sStile;
		if($aAllow["Show"]==1){
	        $sSql = "SELECT id,time,datetime,title,Autore,News FROM tbl_news where id_sito='" . $this->id_sito . "' and id_tipo='" . $this->id_tipo . "' order by datetime desc LIMIT 0," . $this->MaxNews . ";";
			$ris = mysql_query($sSql);
			
			if (!$ris){
				echo "Query fallita!";
				exit;
			}
			
	        while ($riga = mysql_fetch_assoc($ris)){
	                echo "<ul class='News_" . $this->sStile . "'>";
	                if(($aAllow["Modify"]==1) and ($manage==1)){	                	
	                echo "<br><span class='News_" . $this->sStile . "_author'><a href='#' onclick='select_modify(\"" . $riga["id"] . "\",\"" . $this->id_sito . "\",\"" . $this->id_tipo . "\")'>Edit</a></span>";
	                }
	                echo "<br><li class='News_" . $this->sStile . "_title'>" . $riga["title"] . "</li>";
	                echo "<br><li class='News_" . $this->sStile . "_author'>di " . $riga["Autore"] . "</li>";
	                echo " - " . "<li class='News_" . $this->sStile . "_data'>" . date("d F Y - H.i.s",$riga["datetime"]) . "</li>";
	                echo "<br><li class='News_" . $this->sStile . "_body'>" .$this->fElaboraNews($riga["News"]) . "</li>";
	                echo "</ul>";
	        }
	        mysql_free_result($ris);
		}else{
			echo "Permessi non sufficienti per vedere le news!\r<br>";
		}
	}

	public function show_single_news($aAllow=0,$manage=0){		
		
		if($aAllow["Show"]==1){
	        $sSql = "SELECT id,id_tipo,time,datetime,title,Autore,News FROM tbl_news where id='" . $this->id_news . "';";
			$ris = mysql_query($sSql);
			if (!$ris){
				echo "Query fallita!";
				exit;
			}else{
                $riga = mysql_fetch_assoc($ris);
                $this->id_tipo=$riga["id_tipo"];
                $this->sStile=$this->read_style();
                echo "<div class='News_" . $this->sStile . "'>";
                if(($aAllow["Modify"]==1) and ($manage==1)){	                	
                	echo "<br><span class='News_" . $this->sStile . "_author'><a href='#' onclick='select_modify(\"" . $riga["id"] . "\",\"" . $this->id_sito . "\",\"" . $this->id_tipo . "\")'>Edit</a></span>";	                	
                }
                echo "<br><span class='News_" . $this->sStile . "_title'>" . $riga["title"] . "</span>";
                echo "<br><span class='News_" . $this->sStile . "_author'>di " . $riga["Autore"] . "</span>";
                echo " - " . "<span class='News_" . $this->sStile . "_data'>" . date("d F Y - H.i.s",$riga["datetime"]) . "</span>";
                echo "<br><span class='News_" . $this->sStile . "_body'>" .$this->fElaboraNews($riga["News"]) . "</span>";
                echo "</div>";
	        }
	        mysql_free_result($ris);
		}else{
			echo "Permessi non sufficienti per vedere le news!\r<br>";
		}
	}

	public function show_list_news($aAllow=0,$manage=0){
		$this->sStile=$this->read_style();
		//echo "Stile: " . $this->sStile;		
		if($aAllow["Show"]==1){
	        $sSql = "SELECT id,time,datetime,title,Autore,News FROM tbl_news where id_sito='" . $this->let_id_sito() . "' and id_tipo='" . $this->id_tipo . "' order by datetime desc LIMIT 0," . $this->MaxNews . ";";
			$ris = mysql_query($sSql);		
			
			if (!$ris){
				echo "Query fallita!";
				exit;
			}
			echo "<div class='News_list'><b>Elenco:</b><br>";
			while ($riga = mysql_fetch_assoc($ris)){
				echo "<div style='display:block;'>";
				if(($aAllow["Modify"]==1) and ($manage==1)){	                	
					echo "<span class='News_list_cont'><a href='#' onclick='select_modify(\"" . $riga["id"] . "\",\"" . $this->let_id_sito() . "\",\"" . $this->id_tipo . "\")'>Edit</a></span>";	                	
				}
				echo "<span class='News_list_cont'><a href='?IdP=" . $this->let_id_page() . "&IdN=" . $riga["id"] . "'>" . substr($riga["title"],0,35) . "</a></span>";
				echo "<br>" . "<span class='News_" . $this->sStile . "_data'>" . date("d F Y - H.i.s",$riga["datetime"]) . "</span><br>";
				echo "</div>";
			}
			mysql_free_result($ris);
			echo "</div>";
		}else{
			echo "Permessi non sufficienti per vedere le news!\r<br>";
		}
	}


	//Da usare solo in caso di conversione date, cioè mai più.
	public function UpdateDatetime(){
	        $sSql = "SELECT id,time,Autore,date_format(Date,'%Y') as Anno,date_format(Date,'%m') as Mese,date_format(Date,'%d') as Giorno,News FROM tbl_news;";
			$ris = mysql_query($sSql);		
			
			if (!$ris){
				echo "Query fallita!";
				exit;
			}
			
	        while ($riga = mysql_fetch_assoc($ris)){
	                $h=floor($riga["time"] / (3600));
	                $m=floor(($riga["time"]-($h*3600))/60);
	                $s=floor(($riga["time"]-($m*60)-($h*3600)));
	                //echo mktime($h,$m,$s,$riga["Mese"],$riga["Giorno"],$riga["Anno"]);
	                $sSql="update tbl_news set datetime='" . mktime($h,$m,$s,$riga["Mese"],$riga["Giorno"],$riga["Anno"]) . "' where id='" . $riga["id"] . "'";
	                mysql_query($sSql);
	        }
	        mysql_free_result($ris);
		
	}
	
	function fElaboraNews($sN){
	
		$sEl=$sN;
		
		$sEl=str_replace("[","<",$sEl);
		$sEl=str_replace("]",">",$sEl);
		
		return $sEl;
	}	

	public function DesignBar($aAllow,$id_news){
		
		//cicla l'array dei permessi e crea di conseguenza le voci di menu abilitate.
		foreach($aAllow as $k => $value){
			if($value==1){
				//***E' necessario trovare un modo più elegante per distinguere la creazione pagina dal resto.***
				if($k=="Modify"){
					echo "<span style='padding-right:2px;padding-left:2px;margin:0px;border-width:0px 0px 1px 0px;border-color:#3B1251;border-style:solid;'><a href='' onclick='news_select('" . $id_news . "')'>" . $k . "</a></span>";
				}
			}				
		}
	}

	public function show_board($id_sito=1, $id_tipo=1, $MaxNews=5){
		//$this->sStile=$this->read_style();
		$aPermission = $this->oUt->fArrayPermission($id_sito,$this->classname);
		if($aPermission["Show"]==1){
	        $sSql = "SELECT id,time,datetime,title,Autore,News FROM tbl_news where id_sito='" . $id_sito . "' and id_tipo='" . $id_tipo . "' order by datetime desc LIMIT 0," . $MaxNews . ";";
			$ris = mysql_query($sSql);
			
			if (!$ris){
				$out="Query fallita!";
				exit;
			}
			$out="<div class='noticeboard'>\r\n";
			while ($riga = mysql_fetch_assoc($ris)){
				$out.="<a target=\"_blank\" href='http://" . $this->let_subdomain($id_sito) . "/" . $this->let_home($id_sito) ."/index.php?IdN=" . $riga["id"] . "'>" . substr($riga["title"],0,100) . "</a>\r\n";
				$out.="<br>" . "" . date("d F Y - H.i.s",$riga["datetime"]) . "<br>\r\n";
			}
			$out.="</div>\r\n";
			
			mysql_free_result($ris);
		}else{
			$out="Permessi non sufficienti per vedere le news!\r<br>";
		}
		return $out;
	}

}
?>
