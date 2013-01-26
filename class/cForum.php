<?php
/**
 *@DATE Created on 09/feb/08
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
/**
 * TODO: Inserire possibilità di modifica e cancellazione all'owner del post
 *       Inserire personalizzazione dell'IdP del Forum a seconda del sito
 * 
 * VISTA MESSAGGI PER ARGOMENTO
 * SELECT arg.id_argument,arg.image,arg.description,fdb.name,mdb.subject FROM `tbl_forum_argument` as arg left join (forumdb as fdb, messagedb as mdb) on (arg.id_argument=fdb.argument and fdb.tipo=mdb.cat) where fdb.id is not null order by mdb.id desc;
 */
class cForum
{
	
	private $url_script; //indica l'URL base senza parametri es: http://www.luxintenebra.net/index.php
	private $id_thread;  //contiene l'id del thread
	private $id_topic;   //contiene l'id del topic
	private $id_post;    //contiene l'id del post
	private $id_pg;      //contiene l'id del pg

	private $TitleThread;
	private $DescThread;
	private $ModThread;
	private $TypeThread;
	private $ArgumentThread;
	private $ImgThread;
	private $OrdThread;
	private $StatusThread;

	private $NamePoster;
	private $ObjPost;
	private $BodyPost;
	private $SignPost;
	private $aPermission;
	private $oUt;
	private $form;
	private $id_sito;

	public $classname;
	public $version;
	public $RecPag;
	
	function __construct(){
	 	$this->classname = "cForum";
	 	$this->version = "Lux in tenebra <br> 2008-09 www.luxintenebra.net";
		//$this->form = new cForm();
	 	$this->url_script = $_SERVER["SCRIPT_NAME"] . "?IdP=" . @$_GET["IdP"];
                $this->RecPag=(int)10;
	}
	/**
	 * todo: Da implementare controlli
	 **/
	function _conv_data_iso($DtISO,$Timer=0,$Separatore=":"){
		
		//if($DtISO=="" or $DtISO==0){
		//}
		//if($Timer=="" or $Timer==0){
		//}		
		
		return substr($DtISO,6,2)."/".substr($DtISO,4,2)."/".substr($DtISO,0,4)." ".sprintf("%02d",(int)($Timer / 3600)).$Separatore.sprintf("%02d",(int)(($Timer-(3600*(int)($Timer / 3600)))/60));
	}
	
	public function set_user($id_sito,$obj){
		if (is_object($obj)) {
			$this->oUt = $obj;
		 	//$this->aPermission = $this->oUt->fArrayPermission($this->let_id_sito(),$this->classname);
                        $this->aPermission = $this->oUt->fArrayPermission($this->let_id_sito(),'cForum','',$this->oUt->id, 'messagedb', 'SiteID', 'ID', 'IdUt');
		 } else {
		 	echo "Non e' un oggetto.";
		 	return false;
		 }
	}

	public function set_id_sito($id_sito){
		if (is_numeric($id_sito)) {
			$this->id_sito = $id_sito;
		 } else {
		 	echo "Id sito non valido. (non numerico!)";
		 	return false;
		 }
	}

	public function let_id_sito(){
		return $this->id_sito;
	}

        private function findArgumentThread($id_arg){
            $sql="SELECT arg.id_argument,arg.name as arg_name,fdb.name as thread_name FROM `tbl_forum_argument` as arg left join forumdb as fdb on arg.id_argument=fdb.argument where arg.id_argument=".$id_arg.";";
            $oDb = Db::getInstance();
            $oDb->Open();
            $result=mysql_query($sql);
            while ($row = mysql_fetch_assoc($result)) {
                $rows[]=$row;
            }
            return $rows;
        }

        private function findArgumentFromThread($id_thread){
            $sql="SELECT arg.id_argument,arg.name as arg_name,fdb.name as thread_name FROM `tbl_forum_argument` as arg left join forumdb as fdb on arg.id_argument=fdb.argument where fdb.id=".$id_thread.";";
            $oDb = Db::getInstance();
            $oDb->Open();
            $result=mysql_query($sql);
            while ($row = mysql_fetch_assoc($result)) {
                $rows[]=$row;
            }
            return $rows;
        }
        
        private function findIdArgumentThread($id){
            $sql="SELECT fdb.id FROM `tbl_forum_argument` as arg left join forumdb as fdb on arg.id_argument=fdb.argument where id_argument=".$id." and fdb.id is not null;";
            $oDb = Db::getInstance();
            $oDb->Open();
            $result=mysql_query($sql);
            while ($row = mysql_fetch_assoc($result)) {
                $rows[]=$row["id"];
            }
            return $rows;
        }
  

  // **************************************************************************
  // SCOPO: elabora e mostra il contenuto della pagina
  // INPUT: $opt = ??
  // STORIA:
  // **************************************************************************

	public function show ($opt = "") {
		$this->set_ArgumentThread(@$_GET["IdArg"]);
		$this->set_id_thread(@$_GET["IdTh"]);
		$this->set_id_topic(@$_GET["IdPs"]);
	
	//$aPermission=$this->oUt->fArrayPermission($_SERVER["SITO"],"cForum_thread");

		switch ($_POST["bSalva"]) {
		case "Reply":
			$this->set_id_post ($_POST["txtIdPost"]);
			$this->set_NamePoster ($_POST["txtNome"]);
			$this->set_ObjPost ($_POST["txtOggetto"]);
			$this->set_BodyPost ($_POST["txtMessaggio"]);
			$this->set_SignPost ($_POST["txtFirma"]);
			
			if ($this->insert_post()) {
				echo "<p>Reply <i>" . $this->let_ObjPost() . "</i> completato!</p>\r\n";
			} else {
				echo "Reply <i>" . $this->let_ObjPost()
				    .'</i> <span style="color: red">fallito!</span>'
				    ."<br>Contattare gli admin del sito!<br><br>\r\n";
			}

		case "EditPost":
			$this->set_id_post ($_POST["txtEditIdPost"]);
			$this->set_NamePoster ($_POST["txtEditNome"]);
			$this->set_ObjPost ($_POST["txtEditOggetto"]);
			$this->set_BodyPost ($_POST["txtEditMessaggio"]);
			$this->set_SignPost ($_POST["txtEditFirma"]);		

			if ($this->update_post ()) {
				echo "<p>Aggiornamento <i>" . $this->let_ObjPost ()
				    ."</i> effettuato!</p>\r\n";
			} else {
				echo "Aggiornamento <i>" . $this->let_ObjPost()
				    .'</i> <span style="color: red">fallito!</span>'
				    ."<br>Contattare gli admin del sito!<br><br>\r\n";
			}

		default:
			?>
			<div id="id_forum" style="position:relative;">
			<?php
			if ($this->let_ArgumentThread () !=0 || $this->let_id_thread () != 0) {
				//mostra la barra di navigazione forum 
				$this->show_bar ();
				$this->div_smile ();
				?>
				<div class="box_reply" id="id_boxavvisi" style="display:block;">
				</div>
				<?php

				//if nidificati per la scelta della visualizzazione. 
				if ($this->let_ArgumentThread () != 0) {
					?>
					<div class="box_reply" id="formInsertThread" style="display:none;">
					<?php $this->show_thread_form_insert (); ?>
					</div>
					<div class="box_reply" id="formEditThread" style="display:none;">
					<?php $this->show_thread_form_edit (); ?>
					</div>
					<div class="box_reply" id="formConfirmDelete" style="display:none;">
					</div>
					<?php
					$this->show_threads($this->aPermission, $this->let_ArgumentThread ());
				} else {
					if ($this->let_id_topic () == 0) {
						$this->show_form_reply($this->oUt->dati["Name"]);
						?>					
						<div class="box_reply" id="formEditPost" style="display:none;">
						<?php $this->show_form_edit ($this->oUt->dati["Name"]); ?>
						</div>
						<div class="box_reply" id="formConfirmDelete" style="display:none;">
						</div>
						<?php
						$this->show_thread($this->aPermission);	
					} else {
						$this->page_bar(@$_GET["Offset"], $_SERVER["SCRIPT_NAME"]
						                                 ."?IdP=" . @$_GET["IdP"]
						                                 ."&IdTh=" . @$_GET["IdTh"]
						                                 ."&IdPs=" . @$_GET["IdPs"]);

						$this->show_form_reply ($this->oUt->dati["Name"]);
						?>
						<div class="box_reply" id="formEditPost" style="display:none;">
						<?php $this->show_form_Edit ($this->oUt->dati["Name"]); ?>
						</div>
						<div class="box_reply" id="formConfirmDelete" style="display:none;">
						</div>
						<?php
						$this->show_topic ($this->aPermission);
						$this->page_bar (@$_GET["Offset"], $_SERVER["SCRIPT_NAME"]
						                                  ."?IdP=" . @$_GET["IdP"]
						                                  ."&IdTh=" . @$_GET["IdTh"]
						                                  ."&IdPs=" . @$_GET["IdPs"]);
					}  // else di if $this->let_id_topic
				}  // else di if $this->let_ArgumentThread

				?>
				</div>
				<?php
			} else { // appartenente ad if let_ArgumentThread || let_id_thread
				$tpl = implode("", file(SRV_ROOT . '/class/' . $this->classname . '/tpl/argument.phtml'));
				$ary_arguments=$this->findAll("tbl_forum_argument","id_sito=".$_SERVER["SITO"]);

				if ($ary_arguments) {
					foreach ($ary_arguments as $value) {
						$link = "<a href='".$this->url_script."&IdArg="
						       .$value["id_argument"]."'>".$value["name"]."</a>";

						$image = "<img style='float:left;margin:4px;' src='"
						        .THEMES.$value["image"]."'>";

						$link_image = "<a href='".$this->url_script."&IdArg="
						             .$value["id_argument"]."'>".$image."</a>";

						$tpl = preg_replace ("#<!-- ARG_TITLE -->#", $link, $tpl, 1);
						$tpl = preg_replace ("#<!-- ARG_DESC -->#", $value["description"], $tpl, 1);
						$tpl = preg_replace ("#<!-- ARG_IMG -->#", $link_image, $tpl, 1);

						$threads = $this->show_last_reply (
							$this->findIdArgumentThread ($value["id_argument"]), 4);

						$last = "";
						if ($threads) {
							foreach ($threads as $v) {
								$last = '<table class="LastPoster">';
								$last.= '<tr>';
								$last.= '<th>Poster</th>';
								$last.= '<th>Oggetto</th>';
								$last.= '<th>Data</th>';

								if ($threads) {
									foreach ($threads as $key => $v2) {
										$last.= "<tr><td>".substr($v2["Poster"],0,20)."</td>\r\n";
										$last.= "<td>".substr($v2["Subject"],0,15)."</td>\r\n";
										$last.= "<td>".$this->_conv_data_iso($v2["DtISO"],$v2["Timer"])
										       ."</td></tr>\r\n";
									}
								}

								$last.= '</tr>';
								$last.= '</table>';
							}  // foreach $threads as $v
						} else {
							$last = "Nessun post o thread.";
						}  // else di if $threads

						$tpl = preg_replace ("#<!-- ARG_LAST -->#", $last, $tpl, 1);
					}  // foreach $ary_arguments
				}  // if $ary_arguments

				echo $tpl;
			}  // else di if let_ArgumentThread || let_id_thread
		}  // switch iniziale
	} // END FUNCTION SHOW
	
        public function findAll($table,$filter="",$fields="*"){ 
            $q = new Query();
            $q->fields = array($fields);
            $q->tables = array($table);
            if($filter!=""){
                $q->filters = "(".$filter.")";
            }

            if ($q->Open() ){
                    while($row = $q->GetNextRecord(true)){
                        $rows[]=$row;
                    }
                    return $rows;
            }else{
                    return false;
            }
        }
	
	public function set_id_thread($Id){
		if(is_numeric($Id)){			
			$this->id_thread = $Id;
		}else{
			$this->id_thread = 0;
		}
	}
	
	public function let_id_thread(){
		return $this->id_thread;
	}

	public function set_TitleThread($str){
		global $oValidate;
		if (is_string($str)) {
			$str = $oValidate->_sql($str);
			$this->TitleThread = $oValidate->_txt($str);
		 } else {
		 	echo "Nome thread non valido!";
		 	return false;
		 }
	}

	public function let_TitleThread(){
		return $this->TitleThread;
	}

	public function set_DescThread($str){
		global $oValidate;
		if (is_string($str)) {
			$str = $oValidate->_sql($str);
			$this->DescThread = $oValidate->_txt($str);
		 } else {
		 	echo "Descrizione thread non valida!";
		 	return false;
		 }
	}

	public function let_DescThread(){
		return $this->DescThread;
	}

	public function set_ModThread($str){
		global $oValidate;
		if (is_string($str)) {
			$str = $oValidate->_sql($str);
			$this->ModThread = $oValidate->_txt($str);
		 } else {
		 	echo "Moderatori thread non validi!";
		 	return false;
		 }
	}

	public function let_ModThread(){
		return $this->ModThread;
	}

	public function set_TypeThread($int){
		if (is_numeric($int)) {
			$this->TypeThread = $int;
		 } else {
		 	echo "Tipo thread non valido!";
		 	return false;
		 }
	}

	public function set_ArgumentThread($int){
		if (is_numeric($int)) {
			$this->ArgumentThread = $int;
		 } else {
		 	$this->ArgumentThread = 0;
		 	return false;
		 }
	}
        
	public function let_TypeThread(){
		return $this->TypeThread;
	}
        
        private function let_RecordByID($id,$table,$index_field="id"){
            $q = new Query();
            $q->fields = array("*");
            $q->tables = array($table);
            $q->filters = "((".$index_field." = '" . $id . "'))";

            if ($q->Open() ){
                    $row = $q->GetNextRecord();
                    if ($row[0]){
                            return $row[0];
                    }else{
                            return false;
                    }
            }else{
                    return false;
            }
        }
        
	public function let_ArgumentThread($verbose=0){
            if($verbose==0){
		return $this->ArgumentThread;
            }else{
                return $this->let_RecordByID($this->TypeThread,"tbl_forum_argument","id_argument");
            }
	}

	public function set_OrdThread($int){
		if (is_numeric($int)) {
			$this->OrdThread = $int;
		 } else {
		 	echo "Numero ordine thread non valido!";
		 	return false;
		 }
	}

	public function let_OrdThread(){
		return $this->OrdThread;
	}

	public function set_StatusThread($int){
		if (is_numeric($int)) {
			$this->StatusThread = $int;
		 } else {
		 	echo "Status thread non valido!";
		 	return false;
		 }
	}

	public function let_StatusThread(){
		return $this->StatusThread;
	}

	public function set_ImgThread($str){
		global $oValidate;
		//if ($oValidate->_url_img($str)) {
		if (is_string($str)) {
			$this->ImgThread = $str;
		 } else {
		 	echo "Percorso immagine thread non valido!";
		 	return false;
		 }
	}

	public function let_ImgThread(){
		return $this->ImgThread;
	}


	public function set_id_topic($Id){
		if(is_numeric($Id)){			
			$this->id_topic = $Id;
		}else{
			$this->id_topic = 0;
		}
	}

	public function let_id_topic(){
		return $this->id_topic;
	}	

	public function set_id_post($Id){
		if(is_numeric($Id)){			
			$this->id_post = $Id;
		}else{
			$this->id_post = 0;
		}
	}
	
	public function let_id_post(){
		return $this->id_post;
	}	

	public function set_id_pg($Id){
		if(is_numeric($Id)){			
			$this->id_pg = $Id;
		}else{
			$this->id_pg = 0;
		}
	}
	
	public function let_id_pg(){
		return $this->id_pg;
	}	

	public function set_NamePoster($str){
		global $oValidate;
		
		if (($str=="") || (is_null($str))) {
			echo $this->oUt->dati["Name"];
			$this->NamePoster = $this->oUt->dati["Name"];
		} else {
			$str = $oValidate->_sql($str);
			$this->NamePoster = $oValidate->_txt($str);	
		}
	}
	
	public function let_NamePoster(){		
		return $this->NamePoster;
	}

	public function set_ObjPost($str){
		global $oValidate;
		
		if (($str=="") || (is_null($str))) {
			$this->ObjPost = "Nessun oggetto!";
		} else {
			$str = $oValidate->_sql($str);
			$this->ObjPost = $oValidate->_txt($str);			
		}
	}
	
	public function let_ObjPost(){
		return $this->ObjPost;
	}

	public function set_BodyPost($str){
		global $oValidate;
		
		if (($str=="") || (is_null($str))) {
			$this->BodyPost = "Body message empty!";
		} else {
			$str = $oValidate->_sql($str);
			//$str = nl2br($str);
			$this->BodyPost = $oValidate->_txt($str);
		}
	}
	
	public function let_BodyPost(){
                $txt=$this->BodyPost;
                if($txt!=""){
                    return $txt;
                }else{
                    return $this->BodyPost;
                }
	}

	public function set_SignPost($str){
		global $oValidate;
		
		if (($str=="") || (is_null($str))) {
			$this->SignPost = "";
		} else {
			$str = $oValidate->_sql($str);
			$this->SignPost = $oValidate->_txt($str);
		}
	}
	
	public function let_SignPost(){
		return $this->SignPost;
	}

	public function read_title_topic($Id){
		$q = new Query();
		$q->fields = array("Subject");
		$q->tables = array("messagedb");
		$q->filters = "((ID = '" . $Id . "'))";
		
		if ($q->Open() ){
			$row = $q->GetNextRecord();
			if ($row[0]!=""){
				return $row[0];
			}else{
				return "** Nuovo topic **";
			}
		}else{
			return "Nessun topic!";
		}
	}

        /*restituisce il totale dei record di un topic*/
        public function tot_rec_topic($id_topic=0){
            $q = new Query();
            $q->fields = array("count(ID)");
            $q->tables = array("messagedb");
            if($id_topic==0){
                $q->filters = "((ReplyID = '" . $this->id_topic . "') or (ID=" . $this->id_topic . "))";
            }else{
                $q->filters = "((ReplyID = '" . $id_topic . "') or (ID=" . $id_topic . "))";
            }
            //$q->sortfields = array("Id");
            if ($q->Open() ){
                $result=$q->GetNextRecord();
                return $result[0];
            }else{
                return 0;
            }
        }

        
        
	//mostra la barra di posizione nel forum.
	public function show_bar(){
		$sBar="<a href=\"" . $this->url_script . "\">Generale</a>";
                $arg_id=$this->let_ArgumentThread();
                if($arg_id==""){
                    $argument=$this->findArgumentFromThread($this->let_id_thread());
                }else{
                    $argument=$this->findArgumentThread($this->let_ArgumentThread());
                }
                $sBar.="<a href=\"" . $this->url_script . "&IdArg=".$argument[0]["id_argument"]."\"> >> ".$argument[0]["arg_name"]."</a>";
		if($this->id_thread!=0){
			$q = new Query();
			$q->fields = array("Name");
			$q->tables = array("forumdb");
			$q->filters = "((ID = '" . $this->id_thread . "'))";
			
			if ($q->Open() ){
				$row = $q->GetNextRecord();
				$sBar.=" >> <a href=\"" . $this->url_script . "&IdTh=" . $this->id_thread . "\">" . $row[0] . "</a>";
			}
		}
		if($this->id_topic!=0){
			$q = new Query();
			$q->fields = array("Subject");
			$q->tables = array("messagedb");
			$q->filters = "((ID = '" . $this->id_topic . "'))";

			if ($q->Open() ){
				$row = $q->GetNextRecord();
				$sBar.=" >> " . $row[0];
			}
		}
		echo $sBar . "\r\n";
	}
	/*
         *Legge i permessi dell'oggetto forum e restituisce all'interno di un array, gli array per ogni singolo modulo previsto 
        */
        public function let_permission($id){
            /*
             * Genero un arrai contentente gli id dei moduli dei permessi di questa classe
             */
            $id_modul="";
            $q = new Query();
            $q->fields = array("id,modul");
            $q->tables = array("tbl_moduls");
            $q->filters = "( modul like '" . $this->classname . "%' )";
            $q->sortfields = array("id");
            if ($q->Open()){
                while($row = $q->GetNextRecord()){
                    $id_modul[$row[0]]=$row[1];
                }
            }
            $q->Close();

            /*
             * Genera un array con i permessi relativi alla classe specificata ciclando sugli id
             */

            foreach($id_modul as $key => $value){
                $permission[$value] = $this->oUt->fArrayPermission($this->let_id_sito(),$value,$id,$this->oUt->id, 'messagedb', 'SiteID', 'ID', 'IdUt');
            }
            return $permission;
            /*$q = new Query();
            $q->fields = array("tbl_permission.id","tbl_permission.id_modul", "tbl_permission.create", "tbl_permission.modify", "tbl_permission.delete", "tbl_permission.show");
            $q->tables = array("tbl_permission");
            $q->filters="";
            $flag=true;
            foreach($id_modul as $key => $value){
                if($flag){
                    $q->filters = " (( id_modul = " . $key . " )";
                    $flag=false;
                }else{
                    $q->filters .= " OR ( id_modul = " . $key . " )";
                }
            }
            $q->filters .= ") AND ( id_sito=" . $this->let_id_sito() . " )";
            $q->sortfields = array("id");
            if ($q->Open()){
                while($row = $q->GetNextRecord(true)){
                    echo array_search($row['id_modul'],$id_modul);
                    $permission[$id_modul[$row['id_modul']]]=$row;
                }
            }
            $q->Close();
            return $permission;
            unset($permission);
            unset($id_modul);*/
        }

	//mostra la sezione thread
	public function show_threads($sAllow,$IdArg=0){
		//if($sAllow["Show"]==1){
        if($this->aPermission["Show"]==1){
			$q = new Query();
			$q->fields = array("ID", "SiteID", "Name", "Description", "Moderators", "Tipo", "Immagine", "Ord", "status");
			$q->tables = array("forumdb");
			$q->filters = "(SiteID = '".$_SERVER["SITO"]."') and (Argument = ".$IdArg.")";
			$q->sortfields = array("Ord");
			if ($q->Open()){
				?>
				<div id="id_mnu_thread" style="">
				<?php				
				if($sAllow["Create"]==1){
					$cmd_create = "javascript:var thread = new Ajax.Request(";
					$cmd_create.= HTTP_AJAX . "/forum_form_newthread.php, ";
					$cmd_create.= "{ method: \"post\", parameters: txtThread }";
					$cmd_create.= ");";
					echo "<a id=\"aShowInsertThread\" href=\"#inizio\">Create thread</a>";
					//echo "<a href=\"void(0)\" onclick=\"" . $cmd_create . "\">Create thread</a><br \><br \>\r\n";
				}
				?>
				</div>
				<ul class="thread">
				<?php				
				while($row = $q->GetNextRecord()){
                                    $permission = $this->let_permission($row[0]);
                                    if ($permission["cForum"]["Show"]==1){
										$lastpost=$this->show_last_reply($row[0],3);
                                    ?>
						<div id="<?php echo $row[0]; ?>" style="display: block;width:100%;text-align:right;">
						</div>
						<li>
							<ul>
								<li class="th_col_imm"><br><img width="70" src="<?php echo $row[6] ?>"></li>
								<li class="th_col_title"><br><?php echo "<a href=\"" . $this->url_script . "&IdTh=" . $row[0] . "\">" . $row[2] . "</a><br><br>" . $row[3]; ?></li>
								<li class="th_col_desc">
						<?php
								if($sAllow["Modify"]==1){
									$url="url=" . $_SERVER["REQUEST_URI"] . "&id_post=" . $row[0];
									echo "<a id='aShowModifyThread' href='#inizio' ";
									echo "onclick='javascript:ShowThreadEdit(\"id_formEditThread\", \"" . HTTP_AJAX . "/forum_thread_form_edit.php\", \"get\", \"url=" . $_SERVER["REQUEST_URI"] . "&id_thread=" . $row[0] . "\", 1.5,\"compileThreadForm\");'>";
									echo "<img border=\"0\" height=\"22\" title=\"Edit\" border=0 src=\"" . SITE_IMG . "/ico/thread_edit.png\"></a>\r\n";
								}else{
									echo "<br>";
								}
								if(($sAllow["Delete"]==1) OR ($permission["cForum_thread"]["Delete"]==1)){
									echo " <a href='javascript:void(0);' onclick='javascript:var udiv = new Ajax.Updater(\"" . $row[0] . "\", \"" . HTTP_AJAX . "/forum_thread_delete.php\",{method: \"get\",parameters: \"url=" . $_SERVER["REQUEST_URI"] . "&answer=confirm&id_thread=" . $row[0] . "\"});'><img border=\"0\" height=\"22\" title=\"Delete\" border=0 src=\"" . SITE_IMG . "/ico/thread_delete.png\"></a>\r\n";
								}
						?>
								<table class="LastPoster">
									<tr>
										<th>Poster</th>
										<th>Oggetto</th>
										<th>Data</th>
										<?php
										if($lastpost){
											foreach($lastpost as $key => $value){
												echo "<tr><td>".substr($value["Poster"],0,20)."</td>\r\n";
												echo "<td>".substr($value["Subject"],0,15)."</td>\r\n";
												echo "<td>".$this->_conv_data_iso($value["DtISO"],$value["Timer"])."</td></tr>\r\n";
											}
										}
										?>
									</tr>
								</table>
								
							</ul>
						</li>
					<?php
                                    }
				}
			}else{
				echo "Nessuna sezione presente!";
			}
			$q->Close();
			echo "</ul>";
			echo "<div id='id_forum_menu'></ul>";
                        echo "<ul Class='users_data' style=''>";
                        $this->oUt->Leggi();
                        echo "<li><img class='avatar' src='".$this->oUt->dati["ImmUt"]."'></li>";
                        echo "<li>Username: <span>".$this->oUt->dati["Name"]."</span></li>";
                        echo "<li>Ultimo login: <span>".$this->oUt->dati["LastLogin"]."</span></li>";
                        echo "</ul>";
                        $args=$this->listArguments();
                        echo "<ul Class='arguments_list' style=''>";
                        echo "<li><p>Argomenti</p></li>";
                        foreach($args as $value){
                            echo "<li><a href='".$this->url_script."&IdArg=".$value[0]."'>".$value[1]."</a></li>";
                        }
			echo "</ul>";
                        $threads=$this->listThreads();
                        echo "<ul Class='threads_list' style=''>";
                        echo "<li><p>Lista threads</p></li>";
                        foreach($threads as $value){
                            echo "<li><a href='".$this->url_script."&IdTh=".$value[0]."'>".$value[2]."</a></li>";
                        }
                        echo "</ul></div>";
		}
	}


	public function listArguments(){
            $q = new Query();
            $q->fields = array("id_argument", "name");
            $q->tables = array("tbl_forum_argument");
            $q->filters = "(id_sito = ".$_SERVER["SITO"].")";
            
            $q->sortfields = array("id_argument");
            if ($q->Open()){
                while($row = $q->GetNextRecord()){
                    $aArgList[]=$row;
                }
                return $aArgList;
            }
        }
        
	public function listThreads($IdArg=""){
            $q = new Query();
            $q->fields = array("ID", "SiteID", "Name", "Description", "Moderators", "Tipo", "Immagine", "Ord", "status");
            $q->tables = array("forumdb");
            if($IdArg!=""){
                $q->filters = "(SiteID = '".$_SERVER["SITO"]."') and (Argument = ".$IdArg.")";
            }else{
                $q->filters = "(SiteID = '".$_SERVER["SITO"]."')";
            }
            $q->sortfields = array("Ord");
            if ($q->Open()){
                while($row = $q->GetNextRecord()){
                    $aThList[]=$row;
                }
                return $aThList;
            }
        }
                                

  // **************************************************************************
  // SCOPO: mostra la lista dei topic relativi alla sezione indicata
  // INPUT: $sAllow = permessi
  // STORIA:
  // **************************************************************************

	public function show_thread($sAllow){
                $aPermissionTopic = $this->oUt->fArrayPermission($this->let_id_sito(),'cForum_topic',$this->id_topic,$this->oUt->id, 'messagedb', 'SiteID', 'ID', 'IdUt');
		if($sAllow["Show"]==1){
			$q = new Query();
			$q->fields = array("ID",
									"SiteID",
									"Poster",
									"Subject",
									"LastPoster",
									"Time",
									"Replies",
									"ReplyID",
									"Message",
									"Locked",
									"IP",
									"Icon",
									"ForumID",
									"IdMaster",
									"Cat",
									"DtISO",
									"Timer",
									"IdAvv",
									"IdUt",
									"IdPg",
									"letture");
			$q->tables = array("messagedb");
			$q->filters = "((SiteID = '".$_SERVER["SITO"]."') and (Cat='" . $this->id_thread . "') and (ReplyID='0'))";
			$q->sortfields = array("DtISO desc, Timer desc");
			if ($q->Open() ){
				?>
				<div id="id_mnu_topic" style="">
				<?php				
				if(($sAllow["Create"]==1) OR ($aPermissionTopic["Create"]==1)) {
					echo "<a id=\"aShowReply\" href=\"#inizio\">Create thread</a><br><br>\r\n";
				}
				?>
				</div>
				
				<ul class="topic">
						<li class="to_head">
							<ul>
								<li class="to_head_col_imm"></li>
								<li class="to_head_col_title"><b>Master\Avventura</b></li>
								<li class="to_head_col_replies"><b>Replys</b></li>
								<li class="to_head_col_lasttopic"><b>Data ultimo post</b></li>
								<li class="to_head_col_lastposter">
								<b>Ultimo utente</b></li>
							</ul>
						</li>
				<?php

				while($row = $q->GetNextRecord()){
                                $aPermissionTopic = $this->oUt->fArrayPermission($this->let_id_sito(),'cForum_topic',$row[0],$this->oUt->id, 'messagedb', 'SiteID', 'ID', 'IdUt');

                                $totrec=(int)$this->tot_rec_topic($row[0]);
                                $recpag=(int)$this->RecPag;
                                $offset=(((int)(($totrec-1)/$recpag))*$recpag);
echo "tot = $totrec, recpag = $recpag, offset = $offset\n";
                                ?>
						<div style="display: block;width:100%;text-align:right;">
						</div>
						<div id="<?php echo $row[0];  ?>" style="display: block;width:100%;text-align:left;clear:both;">
						</div>
						<li class="topic">
							<ul>
								<li class="to_col_imm"><img height="25" src="<?php echo SITE_IMG ?>/avventura.png"></li>
								<li class="to_col_title"><?php echo "<a href=\"" . $this->url_script . "&IdTh=" . $row[14] . "&IdPs=" . $row[0] . "\">" . $row[2] . "</a><br>" . substr($row[3],0,30); ?></li>
								<li class="to_col_replies"><?php echo $row[6]; ?></li>
								<li class="to_col_lasttopic"><?php echo substr($row[15],6,2)."/".substr($row[15],4,2)."/".substr($row[15],0,4)." ".sprintf("%02d",(int)($row[16] / 3600)).":".sprintf("%02d",(int)(($row[16]-(3600*(int)($row[16] / 3600)))/60)); ?></li>
								<li class="to_col_lastposter">
                                                                <?php
                                                                echo "<a href=\"" . $this->url_script . "&IdTh=" . $row[14] . "&IdPs=" . $row[0] . "&Offset=" . $offset . "&rand=" . rand(1000, 10000) . "#fine\">" . $row[4] . "</a><br>\r\n";
								if(($sAllow["Modify"]==1) OR ($aPermissionTopic["Modify"]==1)){
									$url="url=" . $_SERVER["REQUEST_URI"] . "&id_post=" . $row[0];
									echo "<a id='aShowEditThread' href='#inizio' ";
									echo "onclick='javascript:ShowElement(\"formEdit\", \"" . HTTP_AJAX . "/forum_form_edit.php\", \"get\", \"url=" . $_SERVER["REQUEST_URI"] . "&id_post=" . $row[0] . "\", 1.5);'>";
									echo "<img border=\"0\" height=\"22\" title=\"Edit\" border=0 src=\"" . SITE_IMG . "/ico/thread_edit.png\"></a>\r\n";
								}
								if(($sAllow["Delete"]==1) OR ($aPermissionTopic["Modify"]==1)){
									echo " <a href='javascript:void(0);' onclick='javascript:var udiv = new Ajax.Updater(\"" . $row[0] . "\", \"" . HTTP_AJAX . "/forum_post_delete.php\",{method: \"get\",parameters: \"url=" . $_SERVER["REQUEST_URI"] . "&answer=confirm&id_post=" . $row[0] . "\"});'><img border=\"0\" height=\"22\" title=\"Delete\" border=0 src=\"" . SITE_IMG . "/ico/thread_delete.png\"></a>\r\n";
								}
								?>
								</li>
							</ul>
						</li>
					<?php
				}
			}else{
				echo "Nessuna sezione presente!";
			}
			$q->Close();
                        echo "</ul>";
			echo "<div id='id_forum_menu'>";
                        echo "<ul Class='users_data' style=''>";
                        $this->oUt->Leggi();
                        echo "<li><img class='avatar' src='".$this->oUt->dati["ImmUt"]."'></li>";
                        echo "<li>Username: <span>".$this->oUt->dati["Name"]."</span></li>";
                        echo "<li>Ultimo login: <span>".$this->oUt->dati["LastLogin"]."</span></li>";
                        echo "</ul>";
                        $args=$this->listArguments();
                        echo "<ul Class='arguments_list' style=''>";
                        echo "<li><p>Argomenti</p></li>";
                        foreach($args as $value){
                            echo "<li><a href='".$this->url_script."&IdArg=".$value[0]."'>".$value[1]."</a></li>";
                        }
			echo "</ul>";
                        $threads=$this->listThreads();
                        echo "<ul Class='threads_list' style=''>";
                        echo "<li><p>Lista threads</p></li>";
                        foreach($threads as $value){
                            echo "<li><a href='".$this->url_script."&IdTh=".$value[0]."'>".$value[2]."</a></li>";
                        }
                        echo "</ul></div>";
		}
	}

	//mostra la lista dei topic relativi alla sezione indicata
	public function read_post(){
				
            $q = new Query();
            $q->fields = array("ID",
                                                            "Poster",
                                                            "Subject",
                                                            "Message",
                                                            "Sign");
            $q->tables = array("messagedb");
            $q->filters = "(ID='" . $this->let_id_post() . "')";

            if ($q->Open() ){
                    if ($row = $q->GetNextRecord()) {
                            $this->set_id_post($row[0]);
                            $this->set_NamePoster($row[1]);
                            $this->set_ObjPost($row[2]);
                            $this->set_BodyPost($row[3]);
                            $this->set_SignPost($row[4]);
                    } else {
                            echo mysql_error();
                            echo "Post inesistente!";
                    }
            }
	}

	//restituisce il record con i dati della tabella forumDB
	public function read_thread($sAllow){
				
		if($sAllow["Modify"]==1){
			$q = new Query();
			$q->fields = array("ID",
						"Name",
						"Description",
						"Moderators",
						"SiteID",
						"Tipo",
						"Immagine",
						"Ord",
						"status",
                                                "argument");
			$q->tables = array("forumdb");
			$q->filters = "(ID='" . $this->let_id_thread() . "')";

			if ($q->Open() ){
				if ($row = $q->GetNextRecord()) {
					$this->set_id_thread($row[0]);
					$this->set_TitleThread($row[1]);
					$this->set_DescThread($row[2]);
					$this->set_ModThread($row[3]);
					$this->set_TypeThread($row[5]);
                                        $this->set_ArgumentThread($row[9]);
					$this->set_ImgThread($row[6]);
					$this->set_OrdThread($row[7]);
					$this->set_StatusThread($row[8]);
				} else {
					echo mysql_error();
					echo "Post inesistente!";
				}
			}
		}
	}


  // **************************************************************************
	// SCOPO: mostra la lista dei topic relativi alla sezione indicata
  // INPUT: $sAllow = array con i permessi
  // STORIA:
  // **************************************************************************

	public function show_topic ($sAllow) {
		$oUt = new cUtente ();
		$tpl = implode ("", file (SRV_ROOT . '/class/' . $this->classname . '/tpl/header.html'));
		$inner_html = "";

		// Controlla i permessi
		$aPermissionTopic = $this->oUt->fArrayPermission (
			$this->let_id_sito (), 'cForum_topic', $this->id_topic, $this->oUt->id,
			'messagedb', 'SiteID', 'ID', 'IdUt');

		$aPermissionPost = $this->oUt->fArrayPermission (
			$this->let_id_sito (), 'cForum_post', $this->id_topic, $this->oUt->id,
			'messagedb', 'SiteID', 'ID', 'IdUt');

		if (@$_GET["Offset"] == "") {
			$offset = 0;
		} else {
			$offset = @$_GET["Offset"];
		}

		if ($sAllow["Show"] == 1) {
			$q = new Query ();
			$q->fields = array ("ID",
									"SiteID",
									"Poster",
									"Subject",
									"LastPoster",
									"Time",
									"Replies",
									"ReplyID",
									"Message",
									"Sign",
									"Locked",
									"IP",
									"Icon",
									"ForumID",
									"IdMaster",
									"Cat",
									"DtISO",
									"Timer",
									"IdAvv",
									"IdUt",
									"IdPg",
									"letture");

			$q->tables = array ("messagedb");
			$q->filters = "(((SiteID = '".$_SERVER["SITO"]."') and (ReplyID='"
			             .$this->id_topic . "')) or (ID=" . $this->id_topic . "))";
			$q->sortfields = array("ID asc");
			$q->limit = $offset . ",10";

			if ($q->Open ()) {
				// ----- Scrive i link fra il menu' delle pagine e i post -----
				$inner_html = "<ul class='barra_reply'>";
				$inner_html.= "<a name='inizio'></a>";

				if (($aPermissionTopic['Create'] == 1) or ($aPermissionPost['Create'] == 1)) {
					$inner_html.= "<li><a href='#fine'>Andiamo in fondo</a> - "
					             ."<a id='aShowReply' href='#inizio'>Reply topic</a></li><br><br>";
				} else {
					$inner_html.="<li><a href='#fine'>Andiamo in fondo</a></li><br \>";
				}
				$inner_html.="</ul>";

				$tpl = preg_replace ("#<!-- REPLY_BAR -->#", $inner_html, $tpl);
        $inner_html = "";

				echo $tpl;

				// ----- Scrive i post -----                                
				while ($row = $q->GetNextRecord ()) {
					$tpl = implode ("", file (SRV_ROOT . '/class/' . $this->classname
						.'/tpl/post.html'));

					$aPermissionPost = $this->oUt->fArrayPermission (
						$this->let_id_sito (), 'cForum_post', $row[0], $this->oUt->id,
						'messagedb', 'SiteID', 'ID', 'IdUt');

					$oUt->id = intval ($row[19]);
					$oUt->Leggi ();

					$tpl = preg_replace ("#<!-- ID -->#", $row[0], $tpl);

					if ($this->check_thread_type () == 0) {
						if (!$this->check_master ($row[19])) {
							$oPg = new cPg;
							$oPg->set_id_pg ($row[20]);
							$oPg->leggi ();

							$tpl = preg_replace("#<!-- IMG_PROFILE -->#", $oPg->ary_descpg["photo"], $tpl);
							$tpl = preg_replace("#<!-- PG_NAME -->#", $oPg->ary_descpg["Username"], $tpl);
						} else {
							$tpl = preg_replace("#<!-- IMG_PROFILE -->#", $oUt->dati["ImmUt"], $tpl);
							$tpl = preg_replace("#<!-- PG_HIDE -->#", "display:none;", $tpl);
						}
					} else {
						$tpl = preg_replace ("#<!-- IMG_PROFILE -->#", $oUt->dati["ImmUt"], $tpl);
						$tpl = preg_replace ("#<!-- PG_HIDE -->#", "display:none;", $tpl);
					}

				  $tpl = preg_replace ("#<!-- USER -->#", $oUt->dati["Name"], $tpl);
					$tpl = preg_replace ("#<!-- DT_REG -->#",
						cDate::ConvertDataISO ($oUt->dati["DtISOCreazione"]), $tpl);
					$tpl = preg_replace("#<!-- ANZIANITA -->#", $oUt->DescAnzianita, $tpl);
                                    
					if (($sAllow["Modify"] == 1) OR ($aPermissionPost["Modify"] == 1)
					                             OR ($aPermissionTopic["Modify"] == 1)) {
						$url = "url=" . $_SERVER["REQUEST_URI"] . "&id_post=" . $row[0];
	          $inner_html = "<a id='aShowModifyPost' href='#inizio' ";
	          $inner_html.= "onclick='javascript:ShowElement(\"formEdit\", \""
						           .HTTP_AJAX . "/forum_form_edit.php\", \"get\", \"url="
						           .$_SERVER["REQUEST_URI"] . "&id_post=" . $row[0]
						           ."\", 1.5);'>";

						$inner_html.= "<img border=\"0\" height=\"22\" title=\"Edit\" border=0 src=\""
					             .SITE_IMG . "/ico/thread_edit.png\"></a>\r\n";
						$tpl = preg_replace ("#<!-- LINK_EDIT -->#", $inner_html, $tpl);

						$inner_html = "";
					}

					if (($sAllow["Delete"] == 1) OR ($aPermissionPost["Delete"] ==1 )
					                             OR ($aPermissionTopic["Delete"] == 1)
					                             OR ($aPermissionTopic["Delete"] == 1)) {
						$inner_html = " <a href='javascript:void(0);' " 
						             ."onclick='javascript:var udiv = new Ajax.Updater(\""
						             .$row[0] . "\", \"" . HTTP_AJAX
						             ."/forum_post_delete.php\",{method: \"get\",parameters: \"url="
						             .$_SERVER["REQUEST_URI"] . "&answer=confirm&id_topic="
						             .$this->let_id_topic () . "&id_post=" . $row[0]
						             ."\"});'><img border=\"0\" height=\"22\" title=\"Delete\" border=0 src=\""
						             . SITE_IMG . "/ico/thread_delete.png\"></a>\r\n";

	          $tpl = preg_replace("#<!-- LINK_DELETE -->#", $inner_html, $tpl);

						$inner_html = "";
					}

					$tpl = preg_replace ("#<!-- HC -->#", SITE_IMG."/ico/hc.png", $tpl);
					$tpl = preg_replace ("#<!-- POST_TITLE -->#", $row[3], $tpl);
					$tpl = preg_replace ("#<!-- POST_DETAIL -->#", $row[2] . " il " . $row[5], $tpl);

					if ($this->check_master ($row[19])) {
						$tpl = preg_replace ("#<!-- CLASS_MASTER -->#", "master", $tpl);
					}

					$tpl = preg_replace ("#<!-- POST_BODY -->#", nl2br ($row[8]), $tpl);
					$tpl = preg_replace("#<!-- POST_SIGN -->#", $row[9], $tpl);

					echo $tpl;
				}  // while $row
			} else {  // else di if $q->Open
				echo "Nessun post presente!";
			} // if $q->Open

			$tpl = implode("", file(SRV_ROOT . '/class/' . $this->classname . '/tpl/footer.html'));
			$inner_html = "<ul class='barra_reply'>";
			$inner_html.= "<a name='fine'></a>";

			if (($aPermissionTopic['Create'] == 1) or ($aPermissionPost['Create'] == 1)) {
				$inner_html.= "<li><a href='#inizio'>Torniamo in cima</a> - "
				  ."<a id='aShowReply' href='#inizio'>Reply topic</a></li><br><br \>";
			} else {
				$inner_html.= "<li><a href='#inizio'>Torniamo in cima</a></li><br \>";
			}
			$inner_html.="</ul>";

			$tpl = preg_replace("#<!-- REPLY_BAR -->#", $inner_html, $tpl);

			$inner_html="";
			echo $tpl;
			$q->Close ();
		}  // if ($sAllow["Show"] == 1)
	}  // END FUNCTION SHOW_TOPIC


	//mostra il contenuto di un singolo post, usato nella funzione di aggiornamento ajax
	public function show_single_post($sAllow){
		$this->oUt = new cUtente();
		
		if($sAllow["Show"]==1){
			$q = new Query();
			$q->fields = array("ID",
									"SiteID",
									"Poster",
									"Subject",
									"LastPoster",
									"Time",
									"Replies",
									"ReplyID",
									"Message",
									"Locked",
									"IP",
									"Icon",
									"ForumID",
									"IdMaster",
									"Cat",
									"DtISO",
									"Timer",
									"IdAvv",
									"IdUt",
									"IdPg",
									"letture");
			$q->tables = array("messagedb");
			$q->filters = "(ID=" . $this->let_id_post() . ")";
			
			if ($q->Open() ){				
				$row = $q->GetNextRecord();
				$oUt->id = intval($row[18]);
				$oUt->Leggi();
				echo "<div class=\"post_user\">";
				echo "<img width=\"130\" src=\"" . $oUt->dati["ImmUt"] . "\"><br>";
				echo "Utente: <i>" . $oUt->dati["Name"] . "</i><br>";
				echo "Data reg.: <i>" . cDate::ConvertDataISO($this->oUt->dati["DtISOCreazione"]) . "</i><br>";
				echo "Anzianit&agrave: <i>" . $oUt->DescAnzianita . "</i><br>";
				echo "<h1 class=\"menu_post\">";
				if($sAllow["Modify"]==1){
					echo "<a href='#inizio' onclick='javascript:var udiv = new Ajax.Updater(\"formEditPost\", \"" . HTTP_AJAX . "/forum_form_edit.php\",{method: \"get\",parameters: \"url=" . $_SERVER["REQUEST_URI"] . "&id_post=" . $row[0] . "\"});Effect.Appear(\"formEditPost\", { duration: 1.5 });'><img border=\"0\" height=\"22\" title=\"Edit\" border=0 src=\"" . SITE_IMG . "/ico/thread_edit.jpg\"></a>\r\n";
				}
				if($sAllow["Delete"]==1){
					echo " <a href='#'><img border=\"0\" height=\"22\" title=\"Delete\" border=0 src=\"" . SITE_IMG . "/ico/thread_delete.jpg\"></a>\r\n";
				}
				echo "</h1>";								
				echo "</div>";
				echo "<h3><a>" . htmlentities($row[3],ENT_COMPAT,'UTF-8') ."</a></h3>";
				echo "<p class=\"post\">by " . $row[2] . " il " . $row[5] . "</p>";
				echo "<div class=\"post_content\">";
				echo nl2br($row[8]);
				echo "</div>";								
				?>								
				</div>
				<?php
			}else{
				echo "Post inesistente!";
			}
			$q->Close();
		}
	}
	
	//cancella un post
	public function delete_post(){
		$err="";
		$oDb = Db::getInstance();


		if ($oDb->DeleteRecords("messagedb","ID=" . $this->let_id_post() . "")) {
				$this->update_replies_topic($this->let_id_topic(),true);
				$this->update_last_poster_topic($this->let_id_topic());
				$this->rescue_last_poster_topic($this->let_id_topic());
				$err[0] = true;
				return $err;
		} else {
				$err[0] = false;
				$err[1]=mysql_error();
				return $err;
		}
		
	}
	
	public function show_form_reply($sNomeUt,$new=0){
            $tpl = implode("", file(SRV_ROOT . '/class/' . $this->classname . '/tpl/form_reply_post.html'));
            $inner_html="";

            $tpl = preg_replace("#<!-- ID_DIV -->#", "DivReply", $tpl);
            $tpl = preg_replace("#<!-- ID_FORM -->#", "id_formReply", $tpl);
            $tpl = preg_replace("#<!-- ACTION -->#", $_SERVER["REQUEST_URI"], $tpl);
            $tpl = preg_replace("#<!-- ID_POST -->#", $this->let_id_post(), $tpl);
            $tpl = preg_replace("#<!-- ID_THREAD -->#", $this->let_id_thread(), $tpl);
            $tpl = preg_replace("#<!-- ID_TOPIC -->#", $this->let_id_topic(), $tpl);
            if($this->check_thread_type()==0){
                if($this->check_master($this->oUt->id)==true){
                    $tpl = preg_replace("#<!-- READONLY -->#", "", $tpl);
                    $tpl = preg_replace("#<!-- POSTER -->#", $sNomeUt, $tpl);
                }else{
                    $aPg=$this->let_pg_adventure($this->oUt->id);
                    $tpl = preg_replace("#<!-- READONLY -->#", "", $tpl);
                    $tpl = preg_replace("#<!-- ID_PG -->#", $aPg["ID"], $tpl);
                    $tpl = preg_replace("#<!-- POSTER -->#", $aPg["Username"], $tpl);
                }
            }else{
                $tpl = preg_replace("#<!-- READONLY -->#", "", $tpl);
                $tpl = preg_replace("#<!-- POSTER -->#", $sNomeUt, $tpl);
            }
            echo $tpl;
	}

  /**
   * Ritorna il pg associato all'avventura
   * @return array
   */	
	private function let_pg_adventure($IdUt){
		
			if($this->let_id_topic()!=0){
				$q = new Query();
				$q->fields = array("ID","Username");
				$q->tables = array("pgdb");
				$q->filters = "(IdUt = " . $IdUt . " and IdAvv = " . $this->let_id_topic() . ")";

				if ($q->Open() ){
					$row=$q->GetNextRecord(true);
					return $row;
				}else{
					return false;
				}
			}else{
				return true;
			}
     		
	}

  /**
   * Verifica se l'utente è il master del topic visualizzato, incrociando l'id utente autenticato con il campo IdMaster della tabella avventure
   * @return boolean
   */	
	private function check_master($IdUt){
		
			if($this->let_id_topic()!=0){
				$q = new Query();
				$q->fields = array("IdMaster");
				$q->tables = array("avventure");
				$q->filters = "(IdMaster = " . $IdUt . " and IdAvv = " . $this->let_id_topic() . ")";

				if ($q->Open() ){
					if($q->GetNextRecord()){
						return true;
					}else{
						return false;
					}
				}
			}else{
				return true;
			}
     		
	}

  /**
   * Verifica se il il thread in cui si sta visualizzando il topic è di gioco o di discussione, verifica il tipo dalla tabella forumdb
   * @return integer
   */	
	private function check_thread_type(){
            $q = new Query();
            $q->fields = array("Tipo");
            $q->tables = array("forumdb");
            $q->filters = "(ID = " . $this->let_id_thread() . " and SiteID = " . $this->let_id_sito() . ")";
            if ($q->Open() ){
				$result=$q->GetNextRecord();
				if($result){
					return $result[0];
				}else{
					return 1;
				}
			}
     		
	}

	public function show_form_edit($sNomeUt){
            global $oValidate;
            $tpl = implode("", file(SRV_ROOT . '/class/' . $this->classname . '/tpl/form_edit_post.html'));
            $inner_html="";

            $tpl = preg_replace("#<!-- ID_DIV -->#", "formEditPost", $tpl);
            $tpl = preg_replace("#<!-- ID_FORM -->#", "formEdit", $tpl);
            $tpl = preg_replace("#<!-- ACTION -->#", @$_POST["url"], $tpl);
            $tpl = preg_replace("#<!-- ID_POST -->#", $this->let_id_post(), $tpl);

            if($this->check_thread_type()==0){
                if($this->check_master($this->oUt->id)==true){
                    $tpl = preg_replace("#<!-- READONLY -->#", "", $tpl);
                    $tpl = preg_replace("#<!-- POSTER -->#", $sNomeUt, $tpl);
                }else{
                    $tpl = preg_replace("#<!-- READONLY -->#", "READONLY", $tpl);
                    $tpl = preg_replace("#<!-- POSTER -->#", $NomeUt, $tpl);
                }
            }else{
                $tpl = preg_replace("#<!-- READONLY -->#", "READONLY", $tpl);
                $tpl = preg_replace("#<!-- POSTER -->#", $NomeUt, $tpl);
            }
            $tpl = preg_replace("#<!-- OBJ -->#", $this->let_ObjPost(), $tpl);
            $tpl = preg_replace("#<!-- MSG -->#", $this->let_BodyPost(), $tpl);
            $tpl = preg_replace("#<!-- SIGN -->#", $this->let_SignPost(), $tpl);
            /*<td align="right">
                    <input type="button" value="Salva" name="bSalva" 
                    OnClick="javascript:
                    new Ajax.Updater('id_boxavvisi', '<?php echo HTTP_AJAX . "/forum_form_save.php"; ?>', {method: 'post',parameters: Form.serialize($('formEditPost'))}); 
                    ffade('formEditPost');
                    new Ajax.Updater(id_post, '<?php echo HTTP_AJAX . "/forum_post_read.php"; ?>', {method: 'post',parameters: Form.serialize($('formEditPost'))});
                    ">
            </td>*/
            echo $tpl;
	}

	public function show_thread_form_edit(){
		global $oValidate;
		$this->form = new cForm();
		//$this->div_smile();
		?>
		
		<?php
			$this->form->formOpenForm("cFormThreadEdit",@$_POST["url"],"post","","id=id_formEditThread");
			?> 
				<table>
					<tr>
						<td>
							<?php
							//$this->form->formLabel("lblUtente","IdPost","IdPost: ");
							?> 
						</td>
						<td>
							<?php
							$this->form->formInput("hidden","txtEditIdThread",$this->let_id_thread(),5,"");
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php
							$this->form->formLabel("lblTitolo","Titolo","Titolo: ");
							?> 
						</td>
						<td>
							<?php
							$this->form->formInput("text","txtEditTitleThread",$this->let_TitleThread(),70,"OnFocus=\"$('help_bar').innerHTML='Inserire titolo della sezione'\"");
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php
							$this->form->formLabel("lblDescrizione","Descrizione","Descrizione: ");
							?> 
						</td>
						<td>
							<?php
							$this->form->formTextarea("txtEditDescrizione",$this->let_DescThread(),"6","73","OnFocus=\"$('help_bar').innerHTML='Descrizione della sezione'\"");
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php
							$this->form->formLabel("lblModeratori","Moderatori","Moderatori: ");
							?> 
						</td>
						<td>
							<?php
							$this->form->formInput("text","txtEditModeratori",$this->let_ModThread(),70,"OnFocus=\"$('help_bar').innerHTML='Elenco moderatori separato dalla virgola.'\"");
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php
							$this->form->formLabel("lblTipo","Tipo","Tipo: ");
							?> 
						</td>
						<td>
							<?php
							$this->form->formInput("text","txtEditTipo",$this->let_TypeThread(),70,"OnFocus=\"$('help_bar').innerHTML='Inserire identificativo numerico del tipo ti sezione.'\"");
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php
							$this->form->formLabel("lblArgument","Argument","Argomento: ");
							?> 
						</td>
						<td>
							<?php
							$this->form->formInput("text","txtEditArgument",$this->let_ArgumentThread(),70,"OnFocus=\"$('help_bar').innerHTML='Inserire identificativo numerico dell'argomento.'\"");
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php
							$this->form->formLabel("lblImmagine","URL Immagine","URL immagine: ");
							?> 
						</td>
						<td>
							<?php
							$this->form->formInput("text","txtEditImmagine",$this->let_ImgThread(),70,"OnFocus=\"$('help_bar').innerHTML='Inserire indirizzo immagine nella forma: http://www.xxxxxxx.xxx/altro/nomeimmagine.jpg. I formati consentiti sono jpg, gif e png!'\"");
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php
							$this->form->formLabel("lblOrd","Ordine","Ordine: ");
							?> 
						</td>
						<td>
							<?php
							$this->form->formInput("text","txtEditOrdine",$this->let_OrdThread(),70,"OnFocus=\"$('help_bar').innerHTML='Numero che identifica il peso delle sezioni, più alto è il numero più in basso apparirà la sezione!'\"");
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php
							$this->form->formLabel("lblStatus","Status","Status: ");
							?> 
						</td>
						<td>
							<?php
							$this->form->formInput("text","txtEditStatus",$this->let_StatusThread(),70,"OnFocus=\"$('help_bar').innerHTML='Identifica lo stato della sezione, il valore 0 indica che è aperta, il valore 1 che è chiusa!'\"");
							?>
						</td>
					</tr>
					<tr>
						<td> 
						</td>
						<td align="right">
							<input type="button" value="Salva" name="bSalva" 
							OnClick="javascript: 
							new Ajax.Updater('formEditThread', '<?php echo HTTP_AJAX . "/forum_form_thread_save.php"; ?>', {method: 'post',parameters: Form.serialize($('formEditThread'))}); 
							new Ajax.Updater('<?php echo $this->let_id_thread(); ?>', '<?php echo HTTP_AJAX . "/forum_post_thread_read.php"; ?>', {method: 'post',parameters: Form.serialize($('formEditThread'))});">
						</td>
					</tr>
				</table>
				<h1 class="menu_bar">
				<!--<a id="aShowEditSmile" OnMouseOver="Element.show('iSmile');$('iSmile').style.top=-200+Event.pointerY(event)/2+'px';$('iSmile').style.left=20+Event.pointerX(event)/2+'px';">Smile</a> - <a id="aHideEditPost" href="#" onclick="Effect.Fade('formEditPost', { duration: 1.5 });">Chiudi Edit</a>-->
				<a id="aShowSmile">Smile</a> - <a id="aHideEditThread" href="#">Chiudi Edit</a>
				</h1>
				<h1 id="help_bar"></h1>
			<?php
			$this->form->formCloseForm();

	}

	public function show_thread_form_insert(){
		global $oValidate;
		$this->form = new cForm();
		//$this->div_smile();
		?>
		
		<?php
			$this->form->formOpenForm("cFormThreadInsert",@$_POST["url"],"post","","id=id_formInsertThread");
			?> 
				<table>
					<tr>
						<td>
							<?php
							//$this->form->formLabel("lblUtente","IdPost","IdPost: ");
							?> 
						</td>
						<td>
							<?php
							$this->form->formInput("hidden","txtIdThread","",5,"");
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php
							$this->form->formLabel("lblTitolo","Titolo","Titolo: ");
							?> 
						</td>
						<td>
							<?php
							$this->form->formInput("text","txtTitleThread","",70,"OnFocus=\"$('help_bar').innerHTML='Inserire titolo della sezione'\"");
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php
							$this->form->formLabel("lblDescrizione","Descrizione","Descrizione: ");
							?> 
						</td>
						<td>
							<?php
							$this->form->formTextarea("txtDescrizione","","6","73","OnFocus=\"$('help_bar').innerHTML='Descrizione della sezione'\"");
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php
							$this->form->formLabel("lblModeratori","Moderatori","Moderatori: ");
							?> 
						</td>
						<td>
							<?php
							$this->form->formInput("text","txtModeratori","",70,"OnFocus=\"$('help_bar').innerHTML='Elenco moderatori separato dalla virgola.'\"");
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php
							$this->form->formLabel("lblTipo","Tipo","Tipo: ");
							?> 
						</td>
						<td>
							<?php
							$this->form->formInput("text","txtTipo","",70,"OnFocus=\"$('help_bar').innerHTML='Inserire identificativo numerico del tipo ti sezione.'\"");
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php
							$this->form->formLabel("lblArgument","Argument","Argomento: ");
							?> 
						</td>
						<td>
							<?php
							$this->form->formInput("text","txtArgument","",70,"OnFocus=\"$('help_bar').innerHTML='Inserire identificativo numerico dell'argomento.'\"");
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php
							$this->form->formLabel("lblImmagine","URL Immagine","URL immagine: ");
							?> 
						</td>
						<td>
							<?php
							$this->form->formInput("text","txtImmagine","",70,"OnFocus=\"$('help_bar').innerHTML='Inserire indirizzo immagine nella forma: http://www.xxxxxxx.xxx/altro/nomeimmagine.jpg. I formati consentiti sono jpg, gif e png!'\"");
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php
							$this->form->formLabel("lblOrd","Ordine","Ordine: ");
							?> 
						</td>
						<td>
							<?php
							$this->form->formInput("text","txtOrdine","",70,"OnFocus=\"$('help_bar').innerHTML='Numero che identifica il peso delle sezioni, più alto è il numero più in basso apparirà la sezione!'\"");
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php
							$this->form->formLabel("lblStatus","Status","Status: ");
							?> 
						</td>
						<td>
							<?php
							$this->form->formInput("text","txtStatus","",70,"OnFocus=\"$('help_bar').innerHTML='Identifica lo stato della sezione, il valore 0 indica che è aperta, il valore 1 che è chiusa!'\"");
							?>
						</td>
					</tr>
					<tr>
						<td> 
						</td>
						<td align="right">
							<input type="button" value="Salva" name="bSalva" 
							OnClick="javascript: 
							new Ajax.Updater('formInsertThread', '<?php echo HTTP_AJAX . "/forum_form_thread_save.php"; ?>', {method: 'post',parameters: Form.serialize($('formInsertThread'))});">
						</td>
					</tr>
				</table>
				<h1 class="menu_bar">
				<!--<a id="aShowEditSmile" OnMouseOver="Element.show('iSmile');$('iSmile').style.top=-200+Event.pointerY(event)/2+'px';$('iSmile').style.left=20+Event.pointerX(event)/2+'px';">Smile</a> - <a id="aHideEditPost" href="#" onclick="Effect.Fade('formEditPost', { duration: 1.5 });">Chiudi Edit</a>-->
				<a id="aShowSmile">Smile</a> - <a id="aHideInsertThread" href="#">Chiudi Edit</a>
				</h1>
				<h1 id="help_bar"></h1>
			<?php
			$this->form->formCloseForm();

	}
	
	public function show_last_reply($thread,$num=3){
		$q = new Query();
		$q->tables = array("messagedb");
		$q->fields = array("ID","Poster","Subject","DtISO","Timer");
                if(!is_array($thread)){
                    $q->filters = "Cat=".$thread;
                }else{
                    $criteria="(";
                    foreach($thread as $value){
                        if($criteria=="("){
                            $criteria.="Cat=".$value;
                        }else{
                            $criteria.=" or Cat=".$value;
                        }
                    }
                    $criteria.=")";
                    $q->filters = $criteria;
                }
		$q->sortfields = array("ID desc");
		$q->limit = "0,".$num;
		
		if($q->Open()){;
			while ($row = $q->GetNextRecord(true)){
				$result[]=$row;
			}
		}else{
			return false;
		}
		
		return $result;
		
	}
	
	public function insert_post(){		

		$oLog = new cLog(2);
		$oLog->user_id=$this->oUt->id;
		$oLog->detail=serialize($_POST);
		$oLog->sReferer=$_SERVER["HTTP_REFERER"];
		$oLog->scrivi();

		if($this->oUt->id <= 0) exit;


		$permission = $this->let_permission(0);
		if ($permission["cForum_post"]["create"]==1){
			echo "Operation non permitted.";
			break;
		}
	
		$q = new Query();
		$q->tables = array("messagedb");
		$q->fields = array('SiteID' ,'Poster' ,'Subject' ,'LastPoster' ,'Time' ,'Replies' ,'ReplyID' ,
						'Message', 'Sign' ,'Locked' ,'IP' ,'Icon','ForumID' ,
						'IdMaster' ,'Cat' ,'DtISO' ,'Timer' ,'IdAvv' ,'IdUt' ,'IdPg' ,'letture');
		$q->values = array($this->let_id_sito(), $this->let_NamePoster(), $this->let_ObjPost(), 'NULL',
							 date("d/m/Y H:i:s"), 0, $this->let_id_topic(), $this->let_BodyPost(), $this->let_SignPost(), 0,
							 $this->oUt->dati["IP"], 1, 0, $this->oUt->dati["Master"], $this->let_id_thread(), date("Ymd"),
							 ((((date("H")*60)+(date("i")))*60)+date("s")), $this->let_id_topic(), $this->oUt->id, $this->let_id_pg(), 0);

        $iResult=$q->DoInsert();
		if ($iResult){
			$this->update_replies_topic($this->let_id_topic());
			return $iResult;
		} else {
			echo mysql_error();
			return false;
		}
	}

	public function update_replies_topic($id_topic,$delete=false){
		
		$q = new Query();
		$q->tables = array("messagedb");
		$q->fields = array("Replies");
        $q->filters = "id=".$id_topic;
		
		if($q->Open()){
			
			$tot_replies=$q->GetNextRecord();
			$q->Close();

			$q->tables = array("messagedb");
			$q->fields = array("Replies");
			if($delete){
				$q->values = array($tot_replies[0]-1);
			}else{
				$q->values = array($tot_replies[0]+1);
			}
	        $q->filters = "id=".$id_topic;
        
		    $iResult=$q->DoUpdate();
			if ($iResult){
				return true;
			} else {
				echo mysql_error();
				return false;
			}
		}
	}	
	
	public function update_last_date_topic($id_topic){
		
		$q = new Query();
		$q->tables = array("messagedb");
		$q->fields = array("Time","DtISO","Timer");
		$q->values = array(date("d/m/Y H.i.s"),date("Ymd"), date(H)*3600+date(i)*60+date(s));
		//$q->values = array(date("d/m/Y H.i.s"));
        $q->filters = "id=".$id_topic;
        
        $iResult=$q->DoUpdate();
		if ($iResult){
			return true;
		} else {
			echo mysql_error();
			return false;
		}
	}	

	public function update_last_poster_topic($id_topic,$Poster=""){
		
		if($Poster!=""){
			$q = new Query();
			$q->tables = array("messagedb");
			$q->fields = array("LastPoster");
			$q->values = array($Poster);
			//$q->values = array(date("d/m/Y H.i.s"));
			$q->filters = "id=".$id_topic;
			
			$iResult=$q->DoUpdate();
			if ($iResult){
				return true;
			} else {
				echo mysql_error();
				return false;
			}
		}else{
			$q = new Query();
			$q->tables = array("messagedb");
			$q->fields = array("Poster");
			$q->filters = "ReplyID=".$id_topic;
			$q->sortfields = array("ID desc");
			$q->limit = "0,1";
						
			if ($q->Open()){
				$iResult=$q->GetNextRecord();
				$q = new Query();
				$q->tables = array("messagedb");
				$q->fields = array("LastPoster");
				$q->values = array($iResult[0]);
				$q->filters = "id=".$id_topic;
				
				$iResult=$q->DoUpdate();
				if ($iResult){
					return true;
				} else {
					echo mysql_error();
					return false;
				}				
			} else {
				return false;
			}			
		}
	}
	
	public function rescue_last_poster_topic($id_topic){
		
		$q = new Query();
		$q->tables = array("messagedb");
		$q->fields = array("DtISO","Timer");
		$q->filters = "ReplyID=".$id_topic;
		$q->sortfields = array("ID desc");
		$q->limit = "0,1";
					
		if ($q->Open()){
			$iResult=$q->GetNextRecord();
			$q = new Query();
			$q->tables = array("messagedb");
			$q->fields = array("DtISO","Timer");
			$q->values = array($iResult[0],$iResult[1]);
			$q->filters = "id=".$id_topic;
			
			$iResult=$q->DoUpdate();
			if ($iResult){
				return true;
			} else {
				echo mysql_error();
				return false;
			}				
		} else {
			return false;
		}			

	}		

	public function insert_topic(){

	
		$q = new Query();
		$q->tables = array("messagedb");
		$q->fields = array('SiteID' ,'Poster' ,'Subject' ,'LastPoster' ,'Time' ,'Replies' ,'ReplyID' ,
						'Message', 'Sign' ,'Locked' ,'IP' ,'Icon','ForumID' ,
						'IdMaster' ,'Cat' ,'DtISO' ,'Timer' ,'IdAvv' ,'IdUt' ,'IdPg' ,'letture');
		$q->values = array($this->let_id_sito(), $this->let_NamePoster(), $this->let_ObjPost(), $this->oUt->dati["Name"],
							 date("d/m/Y H:i:s"), 0, 0, $this->let_BodyPost(), $this->let_SignPost(), 0,
							 $this->oUt->dati["IP"], 1, 0, $this->oUt->dati["Master"], $this->let_id_thread(), date("Ymd"),
							 ((((date("H")*60)+(date("i")))*60)+date("s")), 0, $this->oUt->id, $this->let_id_pg(), 0);
		
		if ($q->DoInsert()){
			return true;
		} else {
			echo mysql_error();
			return false;
		}
	}

	//insert record thread
	public function insert_thread(){
		
		$q = new Query();
		$q->tables = array("forumdb");
		$q->fields = array('SiteID' ,'Name', 'Description', 'Moderators', 'Argument', 'Tipo', 'Immagine', 'Ord', 'status');
		$q->values = array($this->let_id_sito(), $this->let_TitleThread(), $this->let_DescThread(), $this->let_ModThread(), $this->let_ArgumentThread(), $this->let_TypeThread(), $this->let_ImgThread(), $this->let_OrdThread(), $this->let_StatusThread());
		if ($q->DoInsert()){
			return true;
		} else {
			echo mysql_error();
			return false;
		}
	}

	public function update_post(){
		
		$q = new Query();
		$q->tables = array("messagedb");
		$q->fields = array('Poster' ,'Subject', 'Message', 'Sign');
		$q->values = array($this->let_NamePoster(), $this->let_ObjPost(), $this->let_BodyPost(), $this->let_SignPost());
		$q->filters = "ID = " . $this->let_id_post();
		if ($q->DoUpdate()){
			$this->update_last_poster_topic($this->let_id_post());
			return true;
		} else {
			echo mysql_error();
			return false;
		}
	}

	//update record thread
	public function update_thread(){
		
		$q = new Query();
		$q->tables = array("forumdb");
		$q->fields = array('SiteID' ,'Name', 'Description', 'Moderators', 'Argument', 'Tipo', 'Immagine', 'Ord', 'status');
		$q->values = array($this->let_id_sito(), $this->let_TitleThread(), $this->let_DescThread(), $this->let_ModThread(), $this->let_ArgumentThread(), $this->let_TypeThread(), $this->let_ImgThread(), $this->let_OrdThread(), $this->let_StatusThread());
		$q->filters = "ID = " . $this->let_id_thread();
		
		if ($q->DoUpdate()){
			return true;
		} else {
			echo mysql_error();
			return false;
		}
	}

	public function delete_thread($oUt){
		$oDb = Db::getInstance();
		$oDb->Open();
		if ($oDb->DeleteRecords("forumdb","id=" . $this->let_id_thread())){
			return true;
		}else{
			return false;
		}
	}


	public function div_smile(){
		$this->form = new cForm();

		$q = new Query();
		$q->fields = array("Id",
								"Stringa",
								"Immagine",
								"Principali");
		$q->tables = array("tblemo");
		//$q->filters = "(Principali = '0')";
		$q->sortfields = array("Id");	
		if ($q->Open() ){
			$this->form->formOpenDiv("iSmile","div_popup","Lista smile disponibile.","style=\"display:none;\" OnClick=\"Element.hide('iSmile')\"");
			?>
			<table width="100%" border="0" bgColor="#19051c" id="tblForum">
				<tr vAlign="bottom" bgColor="#666666">
					<td bgColor="#19051c"><b><font color="#ffffff">Testo</font></b></td>
					<td noWrap align="middle" width="25" bgColor="#19051c"><font color="#ffffff"><b>Em.</b></font></td>
					<td bgColor="#19051c"><b><font color="#ffffff">Testo</font></b></td>
					<td noWrap align="middle" width="25" bgColor="#19051c"><font color="#ffffff"><b>Em.</b></font></td>
					<td bgColor="#19051c"><b><font color="#ffffff">Testo</font></b></td>
					<td noWrap align="middle" width="25" bgColor="#19051c"><font color="#ffffff"><b>Em.</b></font></td>
					<td bgColor="#19051c"><b><font color="#ffffff">Testo</font></b></td>
					<td noWrap align="middle" width="25" bgColor="#19051c"><font color="#ffffff"><b>Em.</b></font></td>
				</tr>
				<tr bgColor="#19051c">
			
			<?php
			$iC=1;
			while($row = $q->GetNextRecord()){
				?>	
				<td align="middle" height="25"><font size="2"><?php echo $row[1] ?></font>
				</td>					
				<td align="middle" height="25"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="javascript:void(0)" OnClick="javascript:$('txtIdMessaggio').value+='<?php echo $row[1] ?>'"><IMG src="<?php echo $row[2] ?>" border="0"></a></font></td>
				<?php
				
				if((($iC / 4) - ((int)($iC / 4)))==0){
					?>
					</tr>
					<tr bgColor="#19051c">
					<?php
				}
			
				$iC++;
			
			}
			?>
			<tr><td><a id="aHideSmile" href="javascript:void(0)">Chiudi</a></td></tr>
			<?php
			echo "</table>";
			$this->form->formCloseDiv();
		}

	}
	
	public function page_bar($offset=0, $url="", $filter="") {
	
		//Dim Trec 'totalere record
		//Dim Prec 'record per pagina
		//Dim Tpag 'totalepagine
		//Dim Pag 'pagina corrente
		//Dim Spag 'pagina da cui far partire il conteggio delle pagine
		//Dim Pmax 'intervallo di pagine massimo
		//Dim PagStop 'valore massimo per cui contare le pagine da visualizzare*/
		if($offset==""){
			$offset=0;
		}

		//N=numero di post per pagina
		//k=numero di post totale
		//j=pagina corrente

		
                $Prec = $this->RecPag;
		$Pmax = 19;
		$Pag = (int)(($offset / 10));
		$Spag = ($Pag-$Pmax);
		$j=$offset/10;
		 
		
		if($Spag <= 0){
			$Spag=0;
		}
		if($Pag==0){
			$Pag=1;
		}else{
			$Pag++;
		}
		$q = new Query();
		$q->fields = array("count(ID)");
		$q->tables = array("messagedb");
		$q->filters = "(ReplyID = '" . $this->id_topic . "') or (ID=". $this->id_topic .")";
		if ($q->Open() ){
			
			$row = $q->GetNextRecord();
			$Trec = $row[0];
			$q->Close();
			$Tpag=(int)(ceil($Trec/$Prec));
			//$q = new Query();
			//$q->fields = array("ID");
			//$q->tables = array("messagedb");
			//$q->filters = "(ReplyID = '" . $this->id_topic . "')";
			//$q->sortfields = array("ID");
			//$a=($Prec*($Pag-1))+1;
			//$b=($Prec*($Pag-1))+$Prec;
			//$q->limit = "$a,$b";
			//if ($q->Open() ){
				
				?>
				<ol class="idPagine">		
					<?php
					if(((int)$Tpag)>1){
						?>
						Pagine:
						<?php
						//Ciclo per gestire la barra delle pagine.
						$iC = (($Spag)*10);
						$iPag=$Spag+1;
						//se il totale delle pagine raggiunge le pagine massimo visualizzate PagStop viene settata con la costante Pmax
						//altrimenti assume il valore delle pagine totali da visualizzare
						$PagStop = $Pag+$Pmax;
						if (((int)($Pag)) <= ((int)($Pmax))){ 
							//echo " <li><span title=\"Pagine concluse\" style=\"font-size:14px;\"><b><<</b></span></li>";
						}else{
							echo " <li><a title=\"Ci sono altre pagine.\" style=\"font-size:14px;\" href=\"" . $url . "&Offset=" . ($offset-10) . "\"><<</a></li>";
						}
						#for($i=$Pag;(int)($i)<(int)($PagStop); $i++) {
						$i=$Pag;
						$b=false;
						while ($i<=$PagStop+1){
							//se la pagine è uguale a quella corrente cambio visualizzazione 
							//del numero e disabilito il link
							if ((int)($Pag)==(int)($iPag)){
								echo " <li><a style=\"font-size:14px;font-weight:bold;\" href=\"" . $url . "&Offset=" . $iC . "\">" . (int)($iPag) . "</a></li>";
							}else{
								echo " <li><a href=\"" . $url . "&Offset=" . ((int)($iC)) . "\">" . (int)($iPag) . "</a></li>";
							}
							//se il numero di pagine raggiunge Pmax esco dal ciclo
							$iC=$iC+$Prec;
							$iPag=(int)($iPag+1);
							if($iPag>$Tpag){
								break;
							}
							$i++;
						}
						//Se il totale delle pagine da visualizzare è maggiore del massimo delle pagine visualizza le freccette
						if(((int)($Tpag))==((int)$iPag) || $Tpag<=$Pmax){
						//	echo " <li><span title=\"Pagine concluse\" style=\"font-size:14px;\"><b>>></b></span><li>";
						}else{
							echo " <li><a title=\"Ci sono altre pagine.\" style=\"font-size:14px;\" href=\"" . $url . "&Offset=" . ($offset+10) . "\">>></a></li>";
						}
						?>
					<?php 
					}
					?>
				</ol>				
				<?php
			//}			
		}
	}
	
	public function show_board($id_sito=1, $MaxPost=5, $iTipo=1){

		if($this->aPermission["Show"]==1){
			switch ($iTipo){
				case 1:
						$q = new Query();
						$q->fields = array("ID",
												"SiteID",
												"Poster",
												"Subject",
												"Time",
												"DtISO",
												"Timer",
												"IdAvv",
												"IdUt",
												"IdPg",
												"letture",
												"ReplyID",
                                                                                                "Cat");
						$q->tables = array("messagedb");
						$q->filters = "(SiteID = '" . $id_sito . "')";
						//$q->filters = "(SiteID = 1)";
						$q->sortfields = array("ID desc");
						$q->limit = "0," . $MaxPost;
						break;
					case 2:
						$q = new Query();
						$q->fields = array("IdAvv");
						$q->tables = array("avventure");
						$q->filters = "(IdMaster='" . $this->oUt->id . "')";
						$IdAvv="";
						if ($q->Open()){
							while ($row = $q->GetNextRecord()) {
								if ($IdAvv=="") {
									$IdAvv="ReplyID=" . $row[0];
								}else{
									$IdAvv.=" or ReplyID=" . $row[0];
								}
							}
						}
						$q->fields = array("IdAvv");
						$q->tables = array("pgdb");
						$q->filters = "(IdUt='" . $this->oUt->id . "')";
						if ($q->Open()){
							while ($row = $q->GetNextRecord()) {
								if ($IdAvv=="") {
									$IdAvv="ReplyID=" . $row[0];
								}else{
									$IdAvv.=" or ReplyID=" . $row[0];
								}
							}
						}
						
						$q->fields = array("ID",
												"SiteID",
												"Poster",
												"Subject",
												"Time",
												"DtISO",
												"Timer",
												"IdAvv",
												"IdUt",
												"IdPg",
												"letture",
												"ReplyID",
                                                                                                "Cat");
						$q->tables = array("messagedb");
						$q->filters = "(SiteID = '" . $id_sito . "' and (" . $IdAvv . "))";
						//$q->filters = "(SiteID = 1)";
						$q->sortfields = array("ID desc");
						$q->limit = "0," . $MaxPost;
						break;

				}
				//echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>" . $q->GetSQL();
				if ($q->Open()){
					$out.="<ol style='margin:0px;text-align:left;list-style: none; padding-left:0px;padding-right:0px;'>";
					while ($row = $q->GetNextRecord(true)) {
						$oUt = new cUtente();
						$oUt->id = (int) $row["IdUt"];
						$oUt->Leggi();
                                                $link=$_SERVER["DOMAIN"].htmlentities("index.php?IdP=108&IdTh=".$row["Cat"]."&IdPs=".$row["ReplyID"]."&Offset=".$this->returnOffset($row["ReplyID"])."#fine");
						//$out.="<div style='text-align:left;margin:4px;'>\r\n";
						$out.="<li style='padding-bottom: 6px;'><span style='line-height:1.2em;font-weight:bold;'>" . $row["Poster"] . " </span> - ";
						$out.="<span style='font-size:0.8em;font-style:normal;'>" . substr($row["DtISO"],6,2)."/".substr($row["DtISO"],4,2)."/".substr($row["DtISO"],0,4)." ".sprintf("%02d",(int)($row["Timer"] / 3600)).":".sprintf("%02d",(int)(($row["Timer"]-(3600*(int)($row["Timer"] / 3600)))/60)) . " </span> - ";
						$out.="<span style='font-size:1em;font-style:normal;'><a href='".$link."'>" . $this->read_title_topic($row["ReplyID"]) . "</a> </span><br>";
						$out.="<span style='color:viole;font-size:0.9em;font-style:oblique;'>" . $row["Subject"] . "</span></li>";
						//$out.="</div>\r\n";
					}
					$out.="</ol>";
				}else{
					$out="Nessun post presente!";
				}
				$q->Close();
		}else{
			$out="Permessi non sufficienti per vedere le news!\r<br>";
		}
		return $out;
	}
        
        private function returnOffset($id){
                $query = new Query();
                $query->tables = array("messagedb");
                $query->fields = array("count(*)");
                $query->filters = "ReplyID=".$id;
                if ($query->Open()){
                        $row = $query->GetNextRecord();
                        return floor($row[0]/10)*10;
                }else{
                        return 0;
                }
        }


}
?>

