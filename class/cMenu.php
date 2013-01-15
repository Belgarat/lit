<?php
/*
 * Created on 03/set/07
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
//
// Show class in preview area
//
require_once("cPage.php");
class cMenu{
	
	public $testo;
	private $classname;
	private $aPermission;
	private $id_sito;
	private $oPage;
	protected $oUt;
	
	public function __construct()
	{ 
	 	$this->classname = "cMenu";
	 	$version = "Lux in tenebra <br> 2010-11 www.luxintenebra.net";
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

	
	public function set_user($id_sito,$obj){
		if (is_object($obj)) {
			$this->oUt = $obj;
		 	$this->aPermission = $this->oUt->fArrayPermission($id_sito,$this->classname);
		 } else {
		 	echo "Non e' un oggetto.";
		 	return false;
		 }
	}

        public function ShowMenu($id_sito,$id_padre=0,$menu_name="Menu"){

            $sSql = "select * from tbl_menu where id_sito='" . $id_sito . "' and padre='" . $id_padre . "' order by menu_order asc";
            $grp_mnu = mysql_query($sSql);
            if ($grp_mnu) {

                if($menu_name=="Menu"){
                    echo "<a href=\"javascript:void(0);\" onclick=\"javascript:Effect.toggle('id_mnu_main','appear');\" class=\"btn_menu\" id=\"btn_menu\">.: ".$menu_name." :.</a>";
                    echo "<table style='display:none;' id='id_mnu_main' class='mnu'><tr>";
                }else{
                    echo "<a href=\"javascript:void(0);\" onclick=\"javascript:Effect.toggle('id_mnu_".$menu_name."','appear');\" class=\"btn_menu\" id=\"btn_menu\">.: ".$menu_name." :.</a>";
                    echo "<table style='display:none;' id='id_mnu_".$menu_name."' class='mnu'><tr>";
                }
                if($id_padre!=0){
                    echo "<td class=\"mnu_submain\">";
                    echo "<ul class='submnu' style='display:block;' id='id_mnu_".$id_padre."'>";
                    echo "<p class='mnugroup'>Menu generale</p>";
                }
                while($row=mysql_fetch_assoc($grp_mnu)){
                    //echo "<li class=\"mnu mnu_main\"><a href=\"javascript:void(0);\" onclick=\"javascript:Effect.toggle('id_mnu_".$row["id"]."','appear');\">.: ".$row['title']." :. </a></li>";
                    if(!$this->checklastchild($row['id'])){
                        $flag=true;
                        if( $flag==true and $display==1 ){
                            echo "<p class='submnu_opt'><a href=\"javascript:void(0);\" onclick=\"javascript:$('id_mnu_".$id_padre."').hide();$('id_mnu_".$id_back."').toggle('appear');\" title=\"Indietro\"> <-".$label."</a></p>";
                            $flag=false;
                        }
                        $data = "./index.php?IdP=" . $row["id_pagina"];
                        $link = $data;
                        // if menu item has a link
                        if ($row["id_pagina"]!=0) {//($link <> "") {
                            if($this->checklastchild($row['id'])){
                                $title = "<a href=\"javascript:void(0);\" onclick=\"javascript:Effect.toggle('id_mnu_".$row['id']."','appear');$('id_mnu_".$row['padre']."').toggle('appear');\" title=\"" . $row["title"] . "\">" . $row["title"] . "</a>";
                            }else{
                                $title = "<a href=\"" . $link . "\" title=\"" . $row["title"] . "\">" . $row["title"] . "</a>";
                            }
                        } else {
                                $title = "<a href=\"#\" title=\"" . $row["title"] . "\">" . $row["title"] . "</a>";
                        }
                        echo "<li>".$title."</li>";
                    }else{
                        $tree_menu[$row['id']]=$row['title'];
                    }
                }
                if($id_padre!=0){
                    echo "</ul>";
                    echo "</td>";
                }
                foreach($tree_menu as $k => $value){
                    echo "<td class=\"mnu_submain\">";
                    echo "<p class='mnugroup'>".$value."</p>";
                    $this->ShowSubmenu($k,$id_padre);
                    echo "</td>";
                }
                echo "</tr></table>";
                //$grp_mnu = mysql_query($sSql);
                //while($row=mysql_fetch_assoc($grp_mnu)){
                    //$this->ShowSubmenu($row["id"],$id_padre);
                //}
            } 
        }
        
        public function ShowSubmenu($id_padre,$id_back,$display=0,$label=""){
            
            $sSql = "select * from tbl_menu where padre='" . $id_padre . "' order by menu_order asc";
            $grp_submnu = mysql_query($sSql);
            
            if ($grp_submnu) {
                $row="";
                while($row=mysql_fetch_assoc($grp_submnu)){
                    $this->ShowSubmenu($row["id"],$id_padre,1,$row["title"]);
                }
                $row="";
                $sSql = "select * from tbl_menu where padre='" . $id_padre . "' order by menu_order asc";
                $grp_submnu = mysql_query($sSql);
                if($display==0){
                    echo "<ul class='submnu' style='display:block;' id='id_mnu_".$id_padre."'>";
                }else{
                    echo "<ul class='submnu' style='display:none;' id='id_mnu_".$id_padre."'>";
                }
                $flag=true;
                while($row=mysql_fetch_assoc($grp_submnu)){
                    if( $flag==true and $display==1 ){
                        echo "<p class='submnu_opt'><a href=\"javascript:void(0);\" onclick=\"javascript:$('id_mnu_".$id_padre."').hide();$('id_mnu_".$id_back."').toggle('appear');\" title=\"Indietro\"> <-".$label."</a></p>";
                        $flag=false;
                    }
                    $data = "./index.php?IdP=" . $row["id_pagina"];
                    $link = $data;
                    // if menu item has a link
                    if ($row["id_pagina"]!=0) {//($link <> "") {
                        if($this->checklastchild($row['id'])){
                            $title = "<a href=\"javascript:void(0);\" onclick=\"javascript:Effect.toggle('id_mnu_".$row['id']."','appear');$('id_mnu_".$row['padre']."').toggle('appear');\" title=\"" . $row["title"] . "\">" . $row["title"] . "</a>";
                        }else{
                            $title = "<a href=\"" . $link . "\" title=\"" . $row["title"] . "\">" . $row["title"] . "</a>";
                        }
                    } else {
                            $title = "<a href=\"#\" title=\"" . $row["title"] . "\">" . $row["title"] . "</a>";
                    }
                    echo "<li>".$title."</li>";
                }
                echo "</ul>";
            }
        }
        
	public function ShowPreview($id_sito,$id)
	{
		$root_id = 0;
		// look for a Content with parent_id==0 (menu root)
		$sSql="select * from tbl_menu where id_sito='" . $id_sito . "' and padre='" . $root_id . "' order by menu_order asc";
		$risultato=mysql_query($sSql);
		
		if ($risultato) {
			$style_id_name="vertnav";
			if ($this->aPermission["Modify"]==1){
				?>
				<div class="BarraMenu" style="text-align:right;">
					<a target="_blank" title="Tool di gestione menu" href="../src/gest_menu.php">Manage</a>
				</div>
				<?php
			}
			if ($this->aPermission["Show"]==1){
				while($row = mysql_fetch_assoc($risultato)){
			
					if ($row != false) {
						$root_id = $row["id"];
					}
			
					// if a menu root is existant
					if ($root_id > 0) {
						echo "<div class=\"side\">";
					
						// show menu starting from root. -1 is to not tabulate root direct childs, as root is not printed
					
							?>
							<div class="BarraMenu">
								<?php echo $row["title"] ?>							
							</div>
							<?php
							$this->ShowMenuOld($id_sito,$root_id, -1,$root_id,$style_id_name);
							$style_id_name="vertnav1";
						echo "</div>";
					}
				}
			}
		}
	}
		
	private function checklastchild($id){
            $sSql="select id from tbl_menu where padre='" . $id . "'";
            $risultato=mysql_query($sSql);
            if(mysql_num_rows($risultato)>0){
                return true;
            }else{
                return false;
            }
        }
        
	//
	// Show menu for the end-user
	//
	protected function ShowMenuOld($id_sito,$content_id, $depth,$id,$style_id_name)
	{
		// read content item
		$sSql="select * from tbl_menu where id_sito='" . $id_sito . "' and id='" . $content_id . "' order by menu_order asc";
		$risultato=mysql_query($sSql);
		if($risultato){
			$row=mysql_fetch_assoc($risultato);
		}
		// dont print root (or invalid items)
		
		if ($row["padre"] > 0) {
			$data = "./index.php?IdP=" . $row["id_pagina"];
			$link = $data;
			// if menu item has a link
			if ($row["id_pagina"]!=0) {//($link <> "") {								
				$title = "<a href=\"" . $link . "\" title=\"" . $row["title"] . "\">" . str_repeat("&nbsp;&nbsp;&nbsp;",$depth) . $row["title"] . "</a>";
			} else {
				$title = "<a href=\"#\" title=\"" . $row["title"] . "\">" . str_repeat("&nbsp;&nbsp;&nbsp;",$depth) . $row["title"] . "</a>";
			}
			//Out::Html("<span class=\"preview-listitem\" style=\"padding-left: " . $depth . "0px\">" . $title . "</span><br />");
			
			echo "<li>" . $title . "\r\n";
		}
		// read every item's child
		$sSql="select * from tbl_menu where id_sito='" . $id_sito . "' and padre='" . $row["id"] . "' order by menu_order asc";
		$risultato=mysql_query($sSql);
		if($risultato){
			$bUl = false;	// is true if has been printed an <ul> tag			
			// apply this function to every child
			while ($row=mysql_fetch_assoc($risultato)) {
				$childid = $row["id"];
				if ($childid > 0) {
					if (!$bUl) {
						if ($row["padre"]<>$id){
							echo "<ul style='padding:0;'>" . "\r\n";
							echo "<div id='div_vert'>" . "\r\n";
						}else{
							echo "<ul id='$style_id_name' style='padding:0;'>" . "\r\n";
							echo "<div>" . "\r\n";
						}
						$bUl = true;
					}
					$this->ShowMenuOld($id_sito,$childid, $depth+1,$row["padre"],$style_id_name);
				}
			}			
			// if an <ul> tag has been opened, close it.
			if ($bUl) {
				echo "</div>" . "\r\n";
				echo "</ul>" . "\r\n";
				echo "</li>" . "\r\n";
				$bUl = false;
			}
		}
	}


	public function ShowList($id_sito,$id)
	{
		$this->oPage = new cPage();
		$this->oPage->set_id_sito($id_sito);

		$root_id = 0;
		// look for a Content with parent_id==0 (menu root)
		$sSql="select * from tbl_menu where id_sito='" . $this->let_id_sito() . "' and padre='" . $root_id . "' order by menu_order asc";
		$risultato=mysql_query($sSql);
		
		if ($risultato) {
			while($row = mysql_fetch_assoc($risultato)){
				
				if ($row != false) {
					$root_id = $row["id"];
				}
				
				// if a menu root is existant
				if ($root_id > 0) {
					//echo "<div class=\"side\">";
					
					// show menu starting from root. -1 is to not tabulate root direct childs, as root is not printed
					$t="";
					$this->ReturnDelimiter($id_sito,$root_id, -1,$root_id);
					
					
					//echo "</div>";
				}
			}
			return $this->testo;
		}
	}

	protected function ReturnDelimiter($id_sito,$content_id, $depth,$id)
	{

		// read content item
		$sSql="select * from tbl_menu where id_sito='" . $id_sito . "' and id='" . $content_id . "' order by menu_order asc";
		$risultato=mysql_query($sSql);
		if($risultato){
			$row=mysql_fetch_assoc($risultato);
		}
		// dont print root (or invalid items)
		if (($row["padre"] > 0) and ($this->oPage->read_pag_id_cont($row["id_pagina"]) != 0)) {
			$data =  "V" . $row["id_pagina"] . ",";
			$link = $data;
			// if menu item has a link
			$title = $data . str_repeat("_____",$depth) . $row["title"] . ";";
			$this->testo.=$title;
		}elseif($row["id_pagina"]==-1){
			$data =  "G" . $row["id"] . ",";
			$link = $data;
			// if menu item has a link
			$title = $data . "+" . $row["title"] . ";";
			$this->testo.=$title;
		}
		// read every item's child
		$sSql="select * from tbl_menu where id_sito='" . $id_sito . "' and padre='" . $row["id"] . "' order by menu_order asc";
		$risultato=mysql_query($sSql);
		if($risultato){
			$bUl = false;			// is true if has been printed an <ul> tag			
			// apply this function to every child
			while ($row=mysql_fetch_assoc($risultato)) {
				$childid = $row["id"];
				if ($childid > 0) {
					if (!$bUl) {
						if ($row["padre"]<>$id){							
							//echo "<ul style='padding:0;'>";
							//echo "<div id='div_vert'>";
						}else{
							//echo "<ul id='vertnav' style='padding:0;'>";
							//echo "<div>";
						}
						$bUl = true;
					}
					$this->ReturnDelimiter($id_sito,$childid, $depth+1,$row["padre"]);
				}
			}			
			// if an <ul> tag has been opened, close it.
			if ($bUl) {
				//echo "</div>";
				//echo "</ul>";
				//echo "</li>";
				$bUl = false;
			}
		}		
	}

	public function ShowListGroupMenu($id_sito,$id)
	{
		$root_id = $id;
		// look for a Content with parent_id==0 (menu root)
		$sSql="select * from tbl_menu where id_sito='" . $id_sito . "' and padre='" . $root_id . "' order by menu_order asc";
		$risultato=mysql_query($sSql);
		
		if ($risultato) {
			echo "<table width='100%'>\r\n";
			echo "<tr><th width='3%'>Ordine</th><th width='82%'>Nome menu</th><th width='15%'>Azioni</th></tr>\r\n";
			while($row = mysql_fetch_assoc($risultato)){
	
				if ($row != false) {
					$root_id = $row["id"];
				}
				
				// if a menu root is existant
				if ($root_id > 0) {
					//echo "<div class=\"side\">";
					
					// show menu starting from root. -1 is to not tabulate root direct childs, as root is not printed
					$t="";
					echo "<ul>";
					$this->ShowMenuEdit($id_sito,$root_id, 0,$root_id,"");
					echo "</ul>";					
					//echo "</div>";
				}
			}
			return $this->testo;
			echo "</table>";					
		}
	}

	//
	// Show menu for administration
	//
	protected function ShowMenuEdit($id_sito,$content_id, $depth,$id,$style_id_name)
	{
		// read content item
		$sSql="select * from tbl_menu where id_sito='" . $id_sito . "' and id='" . $content_id . "' order by menu_order asc";
		$risultato=mysql_query($sSql);
		if($risultato){
			$row=mysql_fetch_assoc($risultato);
		}
		// dont print root (or invalid items)
		
		if ($row["padre"] > 0) {
			// if menu item has a link
			if ($row["id_pagina"]!=0) {//($link <> "") {								
				$title = str_repeat("....",$depth) . $row["title"];
			} else {
				$title = str_repeat("....",$depth) . $row["title"];
			}
			//Out::Html("<span class=\"preview-listitem\" style=\"padding-left: " . $depth . "0px\">" . $title . "</span><br />");
			
			$mod_area="<div id='id_modarea_" . $row["id"] . "' style='display:none;margin-left:30px;background-color:grey;'></div>\r\n";
			
			//ajax script per mostrare il form di rename del menu
			$onrename="onclick='javascript:var modarea = new Ajax.Updater(\"id_modarea_" . $row["id"] . "\", \"" . HTTP_ROOT . "/src/ajax/menu_form_rename.php\", ";
			$onrename.="{ method: \"post\", parameters: \"action=showform&mnu_id=" . $row["id"] . "\"});Effect.Appear(\"id_modarea_" . $row["id"] . "\",1.5);'";

			$onchangegroup="onclick='javascript:var modarea = new Ajax.Updater(\"id_modarea_" . $row["id"] . "\", \"" . HTTP_ROOT . "/src/ajax/menu_form_change_group.php\", ";
			$onchangegroup.="{ method: \"post\", parameters: \"action=showform&mnu_id=" . $row["id"] . "\"});Effect.Appear(\"id_modarea_" . $row["id"] . "\",1.5);'";
			
			$onchangeorder="onclick='javascript:var modarea = new Ajax.Updater(\"id_modarea_" . $row["id"] . "\", \"" . HTTP_ROOT . "/src/ajax/menu_form_change_order.php\", ";
			$onchangeorder.="{ method: \"post\", parameters: \"action=showform&mnu_id=" . $row["id"] . "\"});Effect.Appear(\"id_modarea_" . $row["id"] . "\",1.5);'";
			
			$option="<a href='javascript:void(0);' " . $onchangegroup . " title='Change group'><img src='" . HTTP_ROOT . "/img/pbf/ico/categories.gif'></a><a href='javascript:void(0);' " . $onrename . " title='Rename'><img src='" . HTTP_ROOT . "/img/pbf/ico/post.gif'></a><a href='javascript:void(0);' " . $onchangeorder . " title='Change order'><img src='" . HTTP_ROOT . "/img/pbf/ico/last.gif'></a><a title='Delete'><img src='" . HTTP_ROOT . "/img/pbf/ico/trash.gif'></a>";
			
			echo "<td style='border: 1px grey;border-style: solid;boder-width:1px;' width='3%'>" . $row["menu_order"] . 
				"</td><td style='border: 1px grey;border-style: solid;boder-width:1px;' width='80%'><li id='mnu_" . $row["id"] . "'>" . $title . $mod_area . "</td><td style='text-align:right;border: 1px grey;border-style: solid;boder-width:1px;' width='18%'>" . $option . "</td>\r\n";
		}
		// read every item's child
		$sSql="select * from tbl_menu where id_sito='" . $id_sito . "' and padre='" . $row["id"] . "' order by menu_order asc";
		$risultato=mysql_query($sSql);
		if($risultato){
			$bUl = false;	// is true if has been printed an <ul> tag
			// apply this function to every child
			while ($row=mysql_fetch_assoc($risultato)) {
				$childid = $row["id"];
				echo "<tr>";
				if ($childid > 0) {
					if (!$bUl) {
						if ($row["padre"]<>$id){
							echo "<ul style='padding:0;'>" . "\r\n";
							echo "<div>" . "\r\n";
						}else{
							echo "<ul id='$style_id_name' style='padding:0;'>" . "\r\n";
							echo "<div>" . "\r\n";
						}
						$bUl = true;
					}
					$this->ShowMenuEdit($id_sito,$childid, $depth+1,$row["padre"],$style_id_name);
				}
				
			}
			echo "</tr>";		
			// if an <ul> tag has been opened, close it.
			if ($bUl) {
				echo "</div>" . "\r\n";
				echo "</ul>" . "\r\n";
				echo "</li>" . "\r\n";
				$bUl = false;
			}
		}
	}

	public function ShowGroupsMenu()
	{
		$q = new Query();
		$q->fields = array("id","title","menu_order");
		$q->tables = array("tbl_menu");
		$q->filters = "((id_sito = " . $this->let_id_sito() . ") and (id_pagina = -1))";
		$q->sortfields = array("menu_order");
		if ($q->Open()){

			echo "<table style='width: 100%;'>\r\n";
			echo "<tr>\r\n";
			echo "<th>\r\n";
			echo "Ordine";
			echo "</th>\r\n";
			echo "<th>\r\n";
			echo "Nome gruppo menu";
			echo "</th>\r\n";
			echo "<th>\r\n";
			echo "</th>\r\n";
			echo "<th>\r\n";
			echo "Azioni";
			echo "</th>\r\n";
			echo "</tr>\r\n";
			while($row = $q->GetNextRecord()){

			$mod_area="<div id='id_modarea_" . $row[0] . "' style='display:none;margin-left:30px;;background-color:grey;'></div>\r\n";
			
			//ajax script per mostrare il form di rename del menu
			$onrename="onclick='javascript:var modarea = new Ajax.Updater(\"id_modarea_" . $row[0] . "\", \"" . HTTP_ROOT . "/src/ajax/menu_form_rename.php\", ";
			$onrename.="{ method: \"post\", parameters: \"action=showform&mnu_id=" . $row[0] . "\", onSuccess: function(){ Effect.Appear(\"id_modarea_" . $row[0] . "\",1.5)}, onFailure: \"\" });'";

			$onchangegroup="onclick='javascript:var modarea = new Ajax.Updater(\"id_modarea_" . $row[0] . "\", \"" . HTTP_ROOT . "/src/ajax/menu_form_change_group.php\", ";
			$onchangegroup.="{ method: \"post\", parameters: \"action=showform&mnu_id=" . $row[0] . "\", onSuccess: function(){ Effect.Appear(\"id_modarea_" . $row[0] . "\",1.5)}, onFailure: \"\" });'";
			
			$onchangeorder="onclick='javascript:var modarea = new Ajax.Updater(\"id_modarea_" . $row[0] . "\", \"" . HTTP_ROOT . "/src/ajax/menu_form_change_order.php\", ";
			$onchangeorder.="{ method: \"post\", parameters: \"action=showform&mnu_id=" . $row[0] . "\", onSuccess: function(){ Effect.Appear(\"id_modarea_" . $row[0] . "\",1.5)}, onFailure: \"\" });'";
			
			$option="<a href='javascript:void(0);' " . $onchangegroup . " title='Change group'><img src='" . HTTP_ROOT . "/img/pbf/ico/categories.gif'></a><a href='javascript:void(0);' " . $onrename . " title='Rename'><img src='" . HTTP_ROOT . "/img/pbf/ico/post.gif'></a><a href='javascript:void(0);' " . $onchangeorder . " title='Change order'><img src='" . HTTP_ROOT . "/img/pbf/ico/last.gif'></a><a title='Delete'><img src='" . HTTP_ROOT . "/img/pbf/ico/trash.gif'></a>";
			
				echo "<tr>\r\n";
				echo "<td style='border-style: dotted;border-size:1px grey;'>\r\n";
				echo $row[2];
				echo "</td>\r\n";
				echo "<td style='border-style: dotted;border-size:1px grey;'>\r\n";
				echo "<a title='Menu group name' href='?IdP=116&opt=showgroup,mnu_id=" . $row[0] . "'>" . $row[1] . "</a>";
				echo "</td>\r\n";
				echo "<td style='border-style: dotted;border-size:1px grey;'>\r\n";
				echo "-" . $mod_area;
				echo "</td>\r\n";
				echo "<td style='border-style: dotted;border-size:1px grey;'>\r\n";
				echo $option;
				echo "</td>\r\n";
				echo "</tr>\r\n";
			}
			echo "</table>\r\n";
		}
	}

	public function ListGroupsMenu($id_excl=0)
	{
		$q = new Query();
		$q->fields = array("id","title");
		$q->tables = array("tbl_menu");
		$q->filters = "((id_sito = " . $this->let_id_sito() . ") and (id_pagina = -1))";
		$q->sortfields = array("menu_order");
		if ($q->Open()){
			while($row = $q->GetNextRecord()){
				if ($row[0]!=$id_excl){
					$list .= $row[0] . "," . $row[1] . ";";
				}
			}
			return substr($list,0,-1);
		}
	}

	public function ListOrderGroupsMenu($padre, $id_excl=0)
	{
		$prec=-1;
		$i=0;
		$q = new Query();
		$q->fields = array("id","menu_order");
		$q->tables = array("tbl_menu");
		$q->filters = "((id_sito = " . $this->let_id_sito() . ") and (padre = " . $padre . "))";
		$q->sortfields = array("menu_order desc");
		if ($q->Open()){
			$row = $q->GetNextRecord();
			while($i <= $row[1]){
				if ($i != $id_excl){
					$list .= $i . "," . $i . ";";
				}
				$i++;
			}
			return substr($list,0,-1);
		}
	}

	//restituisce un array con tutti i record del menu, come chiave il nome del campo.
	public function read_menu_item($id){
	
		$id=(int)$id;
		
		$q = new Query();
		$q->fields = array("id_sito","padre","id_pagina","title","menu_order");
		$q->tables = array("tbl_menu");
		$q->filters = "(id = " . $id . ")";
		if ($q->Open()){
			$row = $q->GetAssoc();
			return $row;
		}
		
	}

	public function rename_menu_item($id, $name) {
		if ($name!="") {
			$q = new Query();
			$q->fields = array("title");
			$q->tables = array("tbl_menu");
			$q->values = array($name);
			$q->filters = "(id = " . $id . ")";
			$q->DoUpdate();
			return true;
		} else {
			return false;
		}
	}

	public function change_group_menu_item($id, $id_padre) {
		if ($id_padre!="") {
			$q = new Query();
			$q->fields = array("padre");
			$q->tables = array("tbl_menu");
			$q->values = array($id_padre);
			$q->filters = "(id = " . $id . ")";
			$q->DoUpdate();
			return true;
		} else {
			return false;
		}
	}

	public function change_order_menu_item($padre,$id, $ord)
	{
		$q = new Query();
		$q->fields = array("id","menu_order");
		$q->tables = array("tbl_menu");
		$q->filters = "((id_sito = " . $this->let_id_sito() . ") and (padre = " . $padre . "))";
		$q->sortfields = array("menu_order");		
		if ($q->Open()){
			while($row = $q->GetNextRecord()){
				if ($row[1]>=$ord) {
					$q_upd = new Query();
					$q_upd->fields = array("menu_order");
					$q_upd->tables = array("tbl_menu");
					$q_upd->values = array($row[1] + 1);
					$q_upd->filters = "((id_sito = " . $this->let_id_sito() . ") and (padre = " . $padre . ") and (menu_order >= " . $ord . "))";
					$q_upd->DoUpdate();
				}
				
			}
			$q_upd = new Query();
			$q_upd->fields = array("menu_order");
			$q_upd->tables = array("tbl_menu");
			$q_upd->values = array($ord);
			$q_upd->filters = "(id = " . $id . ")";
			$q_upd->DoUpdate();
			return true;
		} else {
			return false;
		}
	}
	
	public function show($opt=""){
		$attr=explode(",",$opt);
		switch ($attr[0]) 
		{
			case 'showgroup':
				$id_menu=explode("=",$attr[1]);
				echo "&Egrave ora possibile con i tasti funzione presenti a sinistra: 
				cambiare il gruppo di appartenenza, rinominare, spostare e cancellare le varie voci.\r\n";
				$this->ShowListGroupMenu($this->let_id_sito(),$id_menu["mnu_id"]);
				break;
			default:
				echo "Selezionare il gruppo di menu da modificare e gestire cliccando sul nome.\r\n";
				$this->ShowGroupsMenu();
				break;
		}
		
	}

}
?>
