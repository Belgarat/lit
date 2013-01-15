<?php
/*
 * Created on 20/lug/07
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * 
 * Classe per la gestione del contenuto figlia della classe Pagina
 */
require_once("cMenu.php");
require_once("cImmagini.php");
require_once("cTesti.php");
require_once("cAllegati.php");

class cContent extends cPage
{
 	
 	private $classname;
  	private $version;
 	
 	private $oMnu;
    private $bComment_enable = false;
    /**
     * this variable set type of content visualization: normal, blog, news(not avable)
     * */
    public $viewmode = "normal";
    
    /**
     * this public variable used for the blog view mode. set id parent menu for list article.
     **/
    public $blog_parent_menu = 0;

 	function __construct(){
	 	$this->classname = "cContent";
	 	$this->version = "Lux in tenebra <br> 2004-07 www.luxintenebra.net";

	 	//echo "ID: " . $this->oUt->id;
	 	//$this->aPermission = $this->oUt->fArrayPermission($this->id_sito,$this->classname);
	 	
	}

	/**
	 * Function for enable comments.
	 * */
	public function enable_comments(){
		$this->bComment_enable=true;
	}

 	/*Legge l'id del contenuto dato quello della pagina*/
 	function read_id_content($id){
		//echo "<br>id_Pag_cont: " . $this->id_sito;
		if (($id!=0) or ($id!="")){
			$sSql="select * from tbl_rel_pag_cont where id_sito='" . $this->id_sito . "' and idP=" . $id;
			
			$risultato = mysql_query($sSql);
			if (!$risultato){
				return false;
			}else{
				$row=mysql_fetch_assoc($risultato);
				return $row["idC"];
				mysql_free_result($risultato);
			}
		}
		else
		{
			$sSql="select * from tbl_rel_pag_cont where id_sito='" . $this->id_sito . "' and IdC<>0 order by idC desc limit 0,1;";
			
			$risultato = mysql_query($sSql);
			if (!$risultato){
				return 0;
			}else{
				$row=mysql_fetch_assoc($risultato);
				return "-" . $row["idC"];
				mysql_free_result($risultato);
			}
		}
 	}

 	/*Legge l'id della pagina contenente un contenuto passandogli l'id del contenuto testuale*/
 	function read_id_page_by_id_text($id){

		if (($id!=0) or ($id!="")){
			$sSql="select * from tbl_contenuto where idtipo=1 and id_sito='" . $this->id_sito . "' and idcont_tab=" . $id;
			
			$risultato = mysql_query($sSql);
			if (!$risultato){
				return false;
			}else{
				$row=mysql_fetch_assoc($risultato);
				$sSql="select * from tbl_rel_pag_cont where id_sito='" . $this->id_sito . "' and idC=" . $row["id"];
				$risultato = mysql_query($sSql);
				if (!$risultato){
					return false;
				}else{
					$row=mysql_fetch_assoc($risultato);
					return $row["idP"];
					mysql_free_result($risultato);
				}
			}
		}
		else
		{
			return 0;
		}
 	}


 	//funzione per la formattazione del testo da salvare, attualmente non utilizzata.
 	function FormatStr($stringa,$mod=0){
 		if($mod==0){
	 		//$stringa=str_replace(chr(13), "", $stringa);
	 		//$stringa=str_replace(chr(10) & chr(10), "[br][br]", $stringa);
	 		//$stringa=str_replace(chr(10), "[br]", $stringa);
 		}else{
	 		//$stringa=str_replace("[br][br]", chr(10) & chr(10), $stringa);
	 		//$stringa=str_replace("[br]", chr(10), $stringa);
 		}
	 	return $stringa;
 	}
 	
	function show($iIdP,$fAllow=1){
		//echo "ciao" . $this->id_sito;
		//echo "id_sito: " . $this->oUt->id;
 		$txt = new cText;
 		$img = new cImage;
 		$att = new cAttach;
 		$txt->set_id_sito($this->id_sito);
 		$img->set_id_sito($this->id_sito);
 		$att->set_id_sito($this->id_sito);
                
 		switch($this->viewmode){
 		
 		case "normal":
			/*ricava l'id del contenuto*/
			$iIdC = $this->read_id_content($iIdP);

			if (!$iIdC==0){
				
				/*incrocia le tabella tbl_rel_pag_cont e entrambe le tabelle tbl_contenuto e tbl_tipocontenuto per ottenere la pagina completa*/
				$sSql="select distinct idC,tbl_cont.id_sito,tbl_cont.idtipo,tbl_cont.idcont_tab,tbl_cont.nome_tabella from tbl_rel_pag_cont, ";
				$sSql.="(select tbl_contenuto.id_sito,tbl_contenuto.id, tbl_contenuto.idtipo,tbl_contenuto.idcont_tab, tbl_tipocontenuto.descrizione,nome_tabella from tbl_contenuto, tbl_tipocontenuto where tbl_contenuto.idtipo=tbl_tipocontenuto.id) as tbl_cont "; 
				$sSql.="where tbl_cont.id_sito='" . $this->id_sito . "' and tbl_cont.id=" . $iIdC . " and idC=" . $iIdC . " order by tbl_cont.idtipo, tbl_cont.idcont_tab;";
				
				$risultato = mysql_query($sSql);
				if(!$risultato){
					echo "Nessun contenuto";
				}else{
					$iC=0;

					while($row=mysql_fetch_assoc($risultato)){
						switch($row["idtipo"]){
							case 1:
								$txt->id=$row["idcont_tab"];
								$testo=$txt->read();
								if($this->bComment_enable){
									$aComments=$txt->read_comments();
								}
								break;
							case 2:
								$img->id=$row["idcont_tab"];
								$immagini=$img->read();
								$txt->body = $this->format_image($txt->body,$img->id,$img->title,$img->subtitle,$img->path);
								break;
							case 3:
								$att->id=$row["idcont_tab"];
								$allegati=$att->read();
								$txt->body = $this->format_attach($txt->body,$att->id,$att->title,$att->subtitle,$att->path);
								break;
							default:
								break;						
						}
					}
					$txt->body=$this->tag_text_filter($txt->body);
					$this->show_content($txt->title,$txt->subtitle,$txt->body,$txt->sign, $txt->id, $aComments);
					mysql_free_result($risultato);
				}
			}else{
						$aPagina=$this->read_page($iIdP);
						/*test per aggiungere o togliere barra dall'address del file*/
						if(substr($aPagina["page_address"],0,1)!="/"){
							$aPagina["page_address"]="/".$aPagina["page_address"];
						}

						if (($iIdP!=-1)){
                                                    if ($aPagina["classname"]=="") {
                                                        require_once(SRV_ROOT . $aPagina["page_address"]);
                                                    } else {
                                                        if($aPagina){
                                                            if($this->manage){
                                                                require_once(SRV_ROOT . $aPagina["page_address"]);
                                                                $objClass = new $aPagina["classname"];
                                                                $objClass->set_id_sito($this->let_id_sito());
                                                                $objClass->set_user($this->let_id_sito(),$this->oUt);
                                                                echo "<div id='contenuto_body'>";
                                                                $objClass->show_manage($this->opt);
                                                                echo "</div>";
                                                            }else{
                                                                require_once(SRV_ROOT . $aPagina["page_address"]);
                                                                $objClass = new $aPagina["classname"];
                                                                $objClass->set_id_sito($this->let_id_sito());
                                                                $objClass->set_user($this->let_id_sito(),$this->oUt);
                                                                echo "<div id='contenuto_body'>";
                                                                $objClass->show($this->opt);
                                                                echo "</div>";
                                                            }
                                                        }
                                                    }
						}
			}
			break;
		case "blog":
			/*return array with list content*/			
			$iIdP = $this->read_content_list($this->blog_parent_menu);

			foreach($iIdP as $value){
				
				$iIdC = $this->read_id_content($value);

				/*incrocia le tabella tbl_rel_pag_cont e entrambe le tabelle tbl_contenuto e tbl_tipocontenuto per ottenere la pagina completa*/
				$sSql="select distinct idC,tbl_cont.id_sito,tbl_cont.idtipo,tbl_cont.idcont_tab,tbl_cont.nome_tabella from tbl_rel_pag_cont, ";
				$sSql.="(select tbl_contenuto.id_sito,tbl_contenuto.id, tbl_contenuto.idtipo,tbl_contenuto.idcont_tab, tbl_tipocontenuto.descrizione,nome_tabella from tbl_contenuto, tbl_tipocontenuto where tbl_contenuto.idtipo=tbl_tipocontenuto.id) as tbl_cont "; 
				$sSql.="where tbl_cont.id_sito='" . $this->id_sito . "' and tbl_cont.id=" . $iIdC . " and idC=" . $iIdC . " order by tbl_cont.idtipo, tbl_cont.idcont_tab;";
				
				$risultato = mysql_query($sSql);
				if(!$risultato){
					echo "Nessun contenuto";
				}else{
					$iC=0;

					while($row=mysql_fetch_assoc($risultato)){
						switch($row["idtipo"]){
							case 1:
								$txt->id=$row["idcont_tab"];
								$testo=$txt->read();
								if($this->bComment_enable){
									$aComments=$txt->read_comments();
								}
								break;
							case 2:
								$img->id=$row["idcont_tab"];
								$immagini=$img->read();
								$txt->body = $this->format_image($txt->body,$img->id,$img->title,$img->subtitle,$img->path);
								break;
							case 3:
								$att->id=$row["idcont_tab"];
								$allegati=$att->read();
								$txt->body = $this->format_attach($txt->body,$att->id,$att->title,$att->subtitle,$att->path);
								break;
							default:
								break;						
						}
					}				
					$txt->body=$this->tag_text_filter($txt->body);
					$this->show_content($txt->title,$txt->subtitle,$txt->body,$txt->sign, $txt->id, $aComments);
					mysql_free_result($risultato);
				}
			}			
			break;
		}
 	}

	/**
	 * Return the list of id pages the selected parent menu voice.
	 **/
	private function read_content_list($parent_menu){
		$q = new Query();
		$q->tables = array("tbl_menu");
		$q->fields = array("id_pagina");
		$q->filters = "padre=".$parent_menu;
		$q->sortfields = array("id desc");
		
		if($q->Open()){
			while($row=$q->GetNextRecord()){
				$aId[]=$row[0];				
			}
			return $aId;
		}else{
			return false;
		}
	}

	private function show_content($title, $subtitle, $body, $sign, $id_txt, $comments=false){
        echo "<span style='color:green;' id='spanNotice'></span>";
        echo "<span style='color:yellow;' id='spanWorning'></span>";
        echo "<span style='color:red;' id='spanError'></span>";
       //Set form and load template comment
        if($this->bComment_enable){
            $form = new cForm();
		    $form_insert_comment = implode("", file(SRV_ROOT . '/class/cContenuto/comment.tpl.php'));
		    $form_insert_comment = eregi_replace("<!-- ACTION -->", "", $form_insert_comment);
            $form_insert_comment = eregi_replace("<!-- TITLE -->", $title, $form_insert_comment);
		    $form_insert_comment = eregi_replace("<!-- COMMENT -->", "", $form_insert_comment);
		    $form_insert_comment = eregi_replace("<!-- SIGN -->", $this->oUt->dati["Name"], $form_insert_comment);
		    $form_insert_comment = eregi_replace("<!-- IDPARENT -->", $id_txt, $form_insert_comment);
            $js_submit="new Ajax.Updater('spanNotice', '" . HTTP_AJAX . "/cContent_insert_comment.php', {method: 'post', parameters: Form.serialize($('DivComment'))});Effect.Fade('DivComment', { duration: 0.5 });";
		    $form_insert_comment = eregi_replace("<!-- JS_SUBMIT -->", $js_submit, $form_insert_comment);
		    echo $form_insert_comment;
        }
        echo "<div id='contenuto_head'>";
		echo "<H1 class='contenuto'>" . $title . "</H1>";
		echo "<H3 class='contenuto'>" . $subtitle . "</H3>";
		echo "</div>";
		//mostra il corpo del contenuto sostituendo i caratteri di newline con dei <br>
		echo "<div id='contenuto_body'>";
		echo "<p class='conteuto'>" . str_replace( "\n", "<br>", $body) . "</p>";
		echo "<H4 class='contenuto'><i class='contenuto'>" . $sign . "</i></H4>";
        if($this->bComment_enable){
            echo "<p><a id='buttonComment' href='javascript: void(0);' onclick=\"$('DivComment').style.top = (Event.pointerY(event)-166);Effect.Appear('DivComment', { duration: 1.5 });\">Insert comment</a></p>"; 
            if($comments){
                foreach($comments as $comment){
                    //var_dump($comment);
                    echo "<ul style='background-color:#290b35;border-width:1px;border-style:dotted;border-color:#b19bcf;list-style:none;'>";
                    echo "<li><H3 class='contenuto'>" . $comment["titolo"]  . "</H3></li>";
                    echo "<li>" . $comment["corpo"]  . "</li>";
                    echo "<li style='text-align:right;padding:0px 6px 0px 0px;'><i class='contenuto'>" . $comment["firma"]  . "</i></li>";
                    echo "</ul>";
                }
            }
        }
        echo "</div>";
	}

	private function format_image($str, $id, $title, $subtitle, $path){
		$tag="<img class='contenuto' title='".$title." - ".$subtitle."' src='" . HTTP_ROOT . "/" . $path."' border=0>";
		$str=str_replace("[" . $id . "]",$tag,$str);
		$tag="<img class='cont_center' title='".$title." - ".$subtitle."' src='" . HTTP_ROOT . "/" . $path."' border=0>";
		$str=str_replace("[center_" . $id . "]",$tag,$str);							
		$tag="<img class='contenuto_noborder' title='".$title." - ".$subtitle."' src='" . HTTP_ROOT . "/" . $path."' border=0>";
		$str=str_replace("[noborder_" . $id . "]",$tag,$str);							
		$tag="<img class='cont_left50' title='".$title." - ".$subtitle."' src='" . HTTP_ROOT . "/" . $path."' border=0>";
		$str=str_replace("[left50_" . $id . "]",$tag,$str);		
		$tag="<img class='cont_left' title='".$title." - ".$subtitle."' src='" . HTTP_ROOT . "/" . $path."' border=0>";
		$str=str_replace("[left_" . $id . "]",$tag,$str);
		$tag="<img class='cont_right' title='".$title." - ".$subtitle."' src='" . HTTP_ROOT . "/" . $path . "' border=0>";
		$str=str_replace("[right_" . $img->id . "]",$tag,$str);
		return $str;
	}

	private function format_attach($str, $id, $title, $subtitle, $path){
        $str.="<hr><b>Documenti allegati:</b><br><a target=\"_blank\"title='".$subtitle."'";
        if(HTTP_ROOT==""){
            $str.=" href='/" .$path."'>".$title."</a>";
        }else{
            $str.=" href='" . HTTP_ROOT . $path."'>".$title."</a>";
        }
        return $str;
    }

	private function tag_text_filter($str){
		$str=str_replace("[h1]","<H1 class='contenuto'>",$str);
		$str=str_replace("[/h1]","</H1>",$str);
		$str=str_replace("[h2]","<H2 class='contenuto'>",$str);
		$str=str_replace("[/h2]","</H2>",$str);
		$str=str_replace("[h3]","<H3 class='contenuto'>",$str);
		$str=str_replace("[/h3]","</H3>",$str);
		$str=str_replace("[ul]","<ul class='contenuto'>",$str);
		$str=str_replace("[/ul]","</ul>",$str);
		$str=str_replace("[li]","<li class='contenuto'>",$str);
		$str=str_replace("[/li]","</li>",$str);				
		$str=str_replace("[p]","<p class='contenuto'>",$str);
		$str=str_replace("[p_center]","<p align='center' class='contenuto'>",$str);
		$str=str_replace("[/p]","</p>",$str);
		$str=str_replace("[i]","<i class='contenuto'>",$str);
		$str=str_replace("[/i]","</i>",$str);
		$str=str_replace("[u]","<u class='contenuto'>",$str);
		$str=str_replace("[/u]","</u>",$str);
		$str=str_replace("[b]","<b class='contenuto'>",$str);
		$str=str_replace("[/b]","</b>",$str);
		$str=str_replace("[a","<a class='contenuto'",$str);
		$str=str_replace("[font","<font class='contenuto'",$str);
		$str=str_replace("[hr","<hr class='contenuto'",$str);
		$str=str_replace("[/a]","</a>",$str);
		$str=str_replace("[/font]","</font>",$str);
		$str=str_replace("[/hr]","</hr>",$str);
		$str=str_replace("[br]","<br>",$str);
		$str=str_replace("]",">",$str);
		return $str;
	}

	private function show_cont_modify_form($txt,$img,$att,$iIdC,$imgid,$attid){
		
		echo "<p id='alert'></p>";
		
		if (!empty($_POST['cmd_upload']) && $_POST['cmd_upload'] == 'Upload') {
		
			require_once(SRV_ROOT . "/src/upload_exec.php");
			
			echo "<a href='" . $_SERVER['REQUEST_URI'] . "'> .: INDIETRO :.</a>";

		}else{
		
			$form = new cForm();
			$form_upload_image = implode("", file(SRV_ROOT . '/class/cContenuto/template.tpl'));
			$form_upload_image = eregi_replace("<!-- ACTION -->", $_SERVER['REQUEST_URI'], $form_upload_image);
			$form_upload_image = eregi_replace("<!-- TITLE -->", $txt->title, $form_upload_image);
			$form_upload_image = eregi_replace("<!-- PERCORSO -->", $this->oUt->dati["Name"], $form_upload_image);
			$form_upload_image = eregi_replace("<!-- ID -->", $iIdC, $form_upload_image);

			echo $form_upload_image;

			
			//$aryImg[$img->id]=array($img->id,$img->path,$img->title);
			$aryImg=$imgid;
			$aryAtt=$attid;
			$form->formBr();
			$form->formOpenForm("Contenuto",$_SERVER["SCRIPT_NAME"] . "?action=save&IdP=".$_GET["IdP"]."","post","","");
			//Parte per l'inserimento delle immagini da visualizzare
			if ($aryImg!="") {
				$form->formOpenDiv("","cont_upload","Upload");
				$form->formBr();
				$form->formLabel("lLabelImmagini","Immagini associate a questo contenuto:","");
				$form->formOpenDiv("lDivImmagini","","Upload");
				echo "<table width='100%'>";
				echo "<tr align='center'><td colspan=\"4\"><b>Immagini associate:</b></td></tr>";
				echo "<tr><td><b>Id</b></td><td><b>Percorso</b></td><td><b>Titolo</b></td></tr>";
				foreach($aryImg as $key=>$val){
					$img->id=$val;
					$img->read();
					echo "<tr id='ImgRow" . $img->id . "'>";
					echo "<td>" . $img->id . "</td>";
					echo "<td>" . $img->path . "</td>";
					echo "<td>" . $img->title . "</td>";
					echo "<td>";
					$form->formInput("button","bCancella","Cancella","","OnClick=\"javascript:new Ajax.Updater( 'alert','" . HTTP_AJAX . "/cContent_Delete_Element.php?id=" . $img->id . "&id_type=2', { method: 'get', onSuccess: Element.remove('ImgRow" . $img->id . "') } );\" style='font-size:10px;color:white;background-color:grey;border:0px'");
					echo "</td>";
					echo "</tr>";
				}
				echo "</table>";
				$form->formCloseDiv();
				$form->formCloseDiv();
			}
			//parte per l'inserimento degli allegati
			if ($aryAtt!="") {
				$form->formOpenDiv("","cont_upload","Upload");
				$form->formBr();
				$form->formLabel("lLabelAllegati","Allegati associati a questo contenuto:","");
				$form->formOpenDiv("lDivAllegati","","Upload");
				echo "<table width='100%'>";
				echo "<tr align='center'><td colspan=\"4\"><b>Allegati associati:</b></td></tr>";
				echo "<tr><td><b>Id</b></td><td><b>Percorso</b></td><td><b>Titolo</b></td></tr>";
				foreach($aryAtt as $key=>$val){
					$att->id=$val;
					$att->read();
					echo "<tr id='AttRow" . $att->id . "'>";
					echo "<td>" . $att->id . "</td>";
					echo "<td>" . $att->path . "</td>";
					echo "<td>" . $att->title . "</td>";
					echo "<td>";
					$form->formInput("button","bCancella","Cancella","","OnClick=\"javascript:new Ajax.Updater( 'alert','" . HTTP_AJAX . "/cContent_Delete_Element.php?id=" . $att->id . "&id_type=3', { method: 'get', onSuccess: Element.remove('AttRow" . $att->id . "') } );\" style='font-size:10px;color:white;background-color:grey;border:0px'");
					echo "</td>";
					echo "</tr>";
				}
				echo "</table>";
				$form->formCloseDiv();
				$form->formCloseDiv();
			}
			echo "<a href=\"javascript:void(0);\" onclick=\"javascript: Effect.Appear('DivUpload', { duration: 0.5 });\"><p><img border=\"0\" height=\"20\" title=\"Upload file\" alt=\"Upload file\" src=\"" . HTTP_ROOT . SITE_IMG . "/ico/upload.png\"> Upload file</p></a>";
			echo "<br/>";
			//Parte per l'inserimento del testo
			$form->formInput("hidden","id",$txt->id,70,"");
			$form->formLabel("lTitolo","Titolo contenuto","Titolo contenuto:","");
			$form->formBr();
			$form->formInput("text","Titolo",utf8_decode($txt->title),70,"");
			echo " - <a OnMouseOver=\"Element.show('ihelp');$('ihelp').style.top=-50+Event.pointerY(event)/2+'px';$('ihelp').style.left=100+Event.pointerX(event)/2+'px';\" OnMouseOut=\"Element.hide('ihelp')\">Help</a>";
			$form->formBr();
			$form->formLabel("lSottotitolo","Sottotitolo contenuto","Sottotitolo contenuto:","");
			$form->formBr();
			$form->formTextarea("txtSottotitolo",utf8_decode(self::FormatStr($txt->subtitle,1)),3,80,"");
			$form->formBr();
			$form->formLabel("lCorpo","Corpo del test","Corpo:","");
			$form->formBr();
			$form->formTextarea("txtCorpo",utf8_decode(self::FormatStr($txt->body,1)),20,80,"");
			$form->formBr();
			$form->formLabel("lFirma","Firma dell'autore'","Firma:","");
			$form->formBr();
			$form->formInput("text","Firma",utf8_decode($txt->sign),35,"");
			$form->formBr(2);
			$form->formInput("submit","cmdModify","Modifica","","");
			$form->formBr();
			$form->formCloseForm();
			$form->formOpenDiv("ihelp","div_help","Aiuto in linea per i tag accettati.","style=\"display:none;\"");
			echo "<b>Inserire immagini caricate</b>";
			echo "<br>['num.immagine']";
			echo "<br><dt></dr>[noborder_'num.immagine']</dt><dd>immagine senza bordo</dd>";
			echo "<br><dt>[center_'num.immagine']:</dt><dd>immagine centrata e ridimensionata al 50% di larghezza</dd>";
			echo "<br>[left_'num.immagine']";
			echo "<br><dt>[left50_'num.immagine']:</dt><dd>immagine ridimensionata al 50% di larghezza</dd>";
			echo "<br>[right_'num.immagine']";
			echo "<br><i>Note:il numero dell'immagine non va fra apici.</i>";
			echo "<br><br><b>Tag html</b>";
			echo "<br>[i][/i]";
			echo "<br>[p][/p]";
			echo "<br>[p_center][/p]";
			echo "<br>[h1][/h1]";
			echo "<br>[h2][/h2]";
			echo "<br>[h3][/h3]";
			echo "<br>[b][/b]";
			echo "<br>[p][/p]";
			echo "<br>[font][/font]";
			echo "<br>[ul][/ul]";
			echo "<br>[a target='_blank' href=''][/a]";
			$form->formCloseDiv();	
		}
	}
 	
 	private function show_cont_insert_form(){
		//echo "id_pagna_barra: " . $_GET["IdP"];
		$oMnu = new cMenu;
		$oMnu->set_id_sito($this->let_id_sito());
		$oMnu->set_user($this->let_id_sito(),$this->oUt);
		//Parte per l'inserimento del testo
		$form = new cForm();
		$form->formLabel("lImportante","Le immagini e gli allegati si inseriscono una volta inserito il testo base.","Immagini e allegati dovranno essere inseriti rieditando il contenuto che state per creare.","style='color:red;'");
		$form->formBr(2);
		$form->formOpenForm("Contenuto",$_SERVER["SCRIPT_NAME"] . "?action=save&IdP=".$_GET["IdP"]."","post","","");
		$form->formLabel("lSelectMenu","Scegli il menu dove inserire il contenuto.","Scegli il menu dove inserire il contenuto:","");
		$form->formBr();
		$form->formSelect("","PagPadre",$oMnu->ShowList($_SERVER["SITO"],1),10,"","+");
		$form->formBr();
		//$form->formLabel("lSelectContenuto","Scegli tipo contenuto.","Scegli tipo contenuto:","");
		$form->formBr();
		$form->formInput("hidden","id","",70,"");
		$form->formLabel("lMenu","Nome voce nel menu","Nome voce menu:","");
		$form->formBr();
		$form->formInput("text","Menu","",70,"");
		$form->formBr();
		$form->formLabel("lTitolo","Titolo contenuto","Titolo contenuto:","");
		$form->formBr();
		$form->formInput("text","Titolo","",70,"");
		$form->formBr();
		$form->formLabel("lSottotitolo","Sottotitolo contenuto","Sottotitolo contenuto:","");
		$form->formBr();
		$form->formTextarea("txtSottotitolo","",3,80,"");
		$form->formBr();
		$form->formLabel("lCorpo","Corpo del test","Corpo:","");
		$form->formBr();
		$form->formTextarea("txtCorpo","",20,80,"");
		$form->formBr();
		$form->formLabel("lFirma","Firma dell'autore'","Firma:","");
		$form->formBr();
		$form->formInput("text","Firma","",35,"");
		$form->formBr(2);
		$form->formInput("submit","cmdModify","Crea","","");
		$form->formBr();
		$form->formCloseForm();	
 	}
 	
 	public function modify_cont($iIdP,$fAllow=1){
 		$txt = new cText;
 		$img = new cImage;
 		$att = new cAttach;
 		$txt->set_id_sito($this->id_sito);
 		$img->set_id_sito($this->id_sito);
 		$att->set_id_sito($this->id_sito);
 		
 		/*ricava l'id del contenuto*/
 		$iIdC = $this->read_id_content($iIdP);
		if (intval($iIdC)>=intval(1)){
			/*incrocia le tabella tbl_rel_pag_cont e entrambe le tabelle tbl_contenuto e tbl_tipocontenuto per ottenere la pagina completa*/
			$sSql="select distinct idC,tbl_cont.id_sito,tbl_cont.idtipo,tbl_cont.idcont_tab,tbl_cont.nome_tabella from tbl_rel_pag_cont, ";
			$sSql.="(select tbl_contenuto.id_sito,tbl_contenuto.id, tbl_contenuto.idtipo,tbl_contenuto.idcont_tab, tbl_tipocontenuto.descrizione,nome_tabella from tbl_contenuto, tbl_tipocontenuto where tbl_contenuto.idtipo=tbl_tipocontenuto.id) as tbl_cont "; 
			$sSql.="where tbl_cont.id_sito='" . $this->id_sito . "' and tbl_cont.id=" . $iIdC . " and idC=" . $iIdC . " order by tbl_cont.idtipo, tbl_cont.idcont_tab;";
			
			$risultato = mysql_query($sSql);
			if (!$risultato){
				echo "Nessun contenuto";
			}else{
				$iC=0;
				while($row=mysql_fetch_assoc($risultato)){
					switch($row["idtipo"]){
						case 1:
					 		$txt->id=$row["idcont_tab"];
					 		$testo=$txt->read(1);
					 		break;
						case 2:
							$img->id=$row["idcont_tab"];
							$immagini=$img->read();
							$imgid[]=$img->id;
							//$aryImg[$img->id]=array($img->id,$img->path,$img->title);
							break;
						case 3:
							$att->id=$row["idcont_tab"];
							$allegati=$att->read();
							$attid[]=$att->id;
							//$aryAtt[$att->id]=array($att->id,$att->path,$att->title);
							break;
						default:
							break;						
					}
				}
				$this->show_cont_modify_form($txt,$img,$att,$iIdC,$imgid,$attid);
				mysql_free_result($risultato);
			}
		}else{
			$this->show_cont_insert_form();
		}
 		
 	}
 	
	//il paramentro $sFields ha come delimitatori di stringhe la virgola
	private function list_type($sType="",$sFields="*"){
		switch($sType){
			case "delimiter":
				//restituisce i record delimitata da ";" con i campi  delimitati da "," indicati nella variabile $sFields
				//ordinata per descrizione. Se non sono specificati i campi li prende tutti.
				$sSql="select " . $sFields . " from tbl_tipocontenuto where primario=1 order by descrizione desc";
				$risultato=mysql_query($sSql);
				if($risultato){
					while($row=mysql_fetch_assoc($risultato)){
						$b=true;
						foreach($row as $value){
							if($b==true){
								$stringa.=$value . ",";
								$b=false;
							}else{
								$stringa.=$value . ";";
								$b=true;
							}
						}
					}					
					//ritorno la stringa eliminando l'ultimo punto e virgola o virgola che potrebbe dare problemi.
					return substr($stringa,0,(strlen($stringa)-1));
				}else{
					return false;
				}
				break;
			default:
				//restituisce un array
				break;
		}
	}
	
 	public function save_cont($iIdP=0,$IdUt=0){
 		$txt = new cText;
 		$img = new cImage;
 		$att = new cAttach;
 		$txt->set_id_sito($this->id_sito);
 		$img->set_id_sito($this->id_sito);
 		$att->set_id_sito($this->id_sito);
 		 		
 		/*ricava l'id del contenuto*/
		//echo "<br>Id_pagina_save_cont: " . $iIdP;
 		$iIdC = self::read_id_content($iIdP);
		//echo "<br>Id: " . $iIdC;
 		//echo "<br>test:" . $iIdC;
		if (intval($iIdC)>=intval(1)){
			$txt->id = $_POST["id"];
			$txt->id_sito=$this->id_sito;
			$txt->title = $_POST["Titolo"];
			$txt->subtitle = self::FormatStr($_POST["txtSottotitolo"]);
			$txt->sign = $_POST["Firma"];
			$txt->body = self::FormatStr($_POST["txtCorpo"]);
			$txt->paragraph = $_POST["paragrafo"];
			$txt->bComment = 0;
			$IdTxt=$txt->update();
			$IdCont=self::update_text_cont($iIdC,$IdTxt,$txt->type_cont());
		}else{
			$txt->id_sito=$this->id_sito;
			$txt->title = $_POST["Titolo"];
			$txt->subtitle = self::FormatStr($_POST["txtSottotitolo"]);
			$txt->sign = $_POST["Firma"];
			$txt->body = self::FormatStr($_POST["txtCorpo"]);
			$txt->paragraph = $_POST["paragrafo"];
			$txt->bComment = 0;
			$IdPg=parent::InsertPage($txt->title);
			//echo "IdPagina: " . $IdPg;
			$IdTxt=$txt->insert();
			$IdCont=self::insert_text_cont($IdTxt,$txt->type_cont(),$IdUt);
			parent::InsertPageCont($IdPg,$IdCont);
			parent::InsertPageMenu($IdPg,$IdCont,$_POST["PagPadre"],$_POST["Menu"]);
			return $IdPg;
		}
 		
 	}

	//data l'id di una pagina ne cancella il contenuto e quindi se stessa, compresi menu relativi.
	public function delete_cont($iIdP){
		if(parent::verify_child($iIdP)==0){
			$errore=false;
			$idC=self::read_id_content($iIdP);
			$sSql="select * from tbl_contenuto left join tbl_tipocontenuto on (tbl_contenuto.idtipo=tbl_tipocontenuto.id) where tbl_contenuto.id_sito='" . $this->id_sito . "' and tbl_contenuto.id='" . $idC . "'";
			//echo $sSql . "<br>";
			
			$risultato=mysql_query($sSql);
			if($risultato){
				while($row=mysql_fetch_assoc($risultato)){
					$sSql="delete from " . $row["nome_tabella"] . " where id='" . $row["idcont_tab"] . "'";
					//echo $sSql . "<br>";
					if(!mysql_query($sSql)){
						echo "Errore SQL (cContenuto.php,riga: 405): " . mysql_error();
						$errore=true;
					}
				}
				mysql_free_result($risultato);
			}
			if($errore==false){
				$sSql="delete from tbl_contenuto where id_sito='" . $this->id_sito . "' and id='" . $idC . "'";
				//echo $sSql . "<br>";
				if(!mysql_query($sSql)){
					echo "Errore SQL (cContenuto.php,riga: 413): " . mysql_error();
					$errore=true;
				}			
			}
			if($errore==false){
				parent::delete_page($iIdP);
			}
			if($errore==false){
				echo "La cancellazione &egrave avvenuta con successo. Cliccare <a href='" . $_SERVER["SCRIPT_NAME"] . "'>QUI</a> per aggiornare il menu.\r\n";
			}else{
				echo "Errore nella procedura di eliminazione, verificare.\r\n";
			}
		}else{
			echo "La cancellazione non &egrave possibile, perch&egrave ha dei sotto men&ugrave, cancellare le sottopagine e poi riprovare.<br><br> Cliccare <a href='" . $_SERVER["SCRIPT_NAME"] . "?IdP=" . $_GET["IdP"] . "'>QUI</a> per tornare alla pagina.\r\n";
		}
	}

 	private function insert_text_cont($IdTxt,$IdTipo,$id_owner=0){
 		$sSql="select id from tbl_contenuto where id_sito='" . $this->id_sito . "' order by id desc";
 		//echo $sSql . "<hr>";
 		$risultato=mysql_query($sSql);
			if (!$risultato){
				return false;
				exit;
			}else{
				$row=mysql_fetch_array($risultato);
				$IdCont=$row["id"]+1;
				mysql_free_result($risultato);
			}
 		$sSql="insert tbl_contenuto(id_sito,id,idtipo,idcont_tab,id_owner) values('" . $this->id_sito . "','". $IdCont ."','" . $IdTipo . "','" . $IdTxt . "','" . $id_owner . "');";
 		//echo $sSql . "<hr>";
 		mysql_query($sSql);
 		echo mysql_error();
 		return $IdCont;
 	}

 	public function insert_image_cont($IdCont,$IdImm,$IdTipo,$id_owner){
 		$sSql="insert tbl_contenuto(id_sito,id,idtipo,idcont_tab,id_owner) values('" . $this->id_sito . "','". $IdCont ."','" . $IdTipo . "','" . $IdImm . "','" . $id_owner . "');";
 		mysql_query($sSql);
 		echo mysql_error();
 		return $IdCont;
 	}

 	public function insert_attach_cont($IdCont,$IdAtt,$IdTipo,$id_owner){
 		$sSql="insert tbl_contenuto(id_sito,id,idtipo,idcont_tab,id_owner) values('" . $this->id_sito . "','". $IdCont ."','" . $IdTipo . "','" . $IdAtt . "','" . $id_owner . "');";
 		mysql_query($sSql);
 		echo mysql_error();
 		return $IdCont;
 	}

 	private function update_text_cont($IdCont,$IdTxt,$IdTipo){
 		//$sSql="update tbl_contenuto set idtipo='" . $IdTipo . "',idcont_tab='" . $IdTxt . "' where id='".$IdCont."';";
 		//mysql_query($sSql);
 		//echo mysql_error();
 		//return $IdCont;
 	}
	//funziona che disegna la barra di modifica dei contenuti
	//se il contenuto è una pagina statica crea i menù Create,Modify e Delete a seconda dei permessi passati
	//se invece è un modulo esterno (pagina autonoma di gestione) va a prendere il link di gestione specificato
	//specificato nella tabella tbl_pagine alla voce "page_address_gest"
	public function DesignBar($aAllow,$IdPagina){
		
		$IdC=self::read_id_content($IdPagina);
		//se l'id del contenuto è 0 vuol dire che è una pagina esterna e non un contenuto statico
		if($IdC==0){
			foreach($aAllow as $k => $value){
				if($k=="Modify"){
					if($value==1){
						$pagina=parent::read_gest_page($IdPagina);
						if((!is_null($pagina)) or (!$pagina=="")){
							echo "<span class='menu_action'><a target='_blank' href='" . HTTP_ROOT . $pagina . "'>Gestione</a></span>";
						}
					}
				}
			}
		}else{
			//cicla l'array dei permessi e crea di conseguenza le voci di menu abilitate.
			foreach($aAllow as $k => $value){
				if($value==1){
					//***E' necessario trovare un modo più elegante per distinguere la creazione pagina dal resto.***
					switch($k){
						case "Create":
							echo "<span class='menu_action'><a href='./index.php?action=edit&PagPadre=" . $IdPagina . "'>" . $k . "</a></span>";
							break;
						case "Modify":
							echo "<span class='menu_action'><a href='./index.php?action=edit&IdP=" . $IdPagina . "'>" . $k . "</a></span>";
							break;
						case "Delete":
							echo "<span class='menu_action'><a href='./index.php?action=delete&IdP=" . $IdPagina . "'>" . $k . "</a></span>";
							break;
					}
				}				
			}
			//echo "<hr style='height:1px;border-color:#3B1251;'>";
		}
	}
	public function show_board($id_sito=1, $id_tipo=1, $MaxNews=5){

       	$sSql = "SELECT id,titolo,sottotitolo FROM tbl_testi WHERE id >= (SELECT FLOOR( MAX(id) * RAND()) FROM tbl_testi ) and corpo!='' and sottotitolo!='' and id_sito='1' ORDER BY id LIMIT 1;";
		$ris = mysql_query($sSql);
		
		if (!$ris){
			$out="Query fallita!";
			exit;
		}
		//$out="<div style=\"position:absolute;margin-left: 2px;\">\r\n";
		while ($riga = mysql_fetch_assoc($ris)){
			$out.="<br><a href='index.php?IdP=" . $this->read_id_page_by_id_text($riga["id"]) . "'>" . $riga["titolo"] . "</a><br>" . $riga["sottotitolo"] . "\r\n";
		}
		//$out.="</div>\r\n";
		
		mysql_free_result($ris);

		return $out;
	}
		
 	//abstract function read();
 	//abstract function insert();
 	//abstract function update();
 	//abstract function delete();
}
?>
