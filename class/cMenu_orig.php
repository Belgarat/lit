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
class cMenu{

	public function ShowPreview()
	{
		$root_id = 0;
		// look for a Content with parent_id==0 (menu root)
		$sSql="select * from tbl_menu where padre='" . $root_id . "'";
		$risultato=mysql_query($sSql);
		
		if ($risultato) {
			$row = mysql_fetch_assoc($risultato);
			if ($row != false) {
				$root_id = $row["id"];
			}
		}
		// if a menu root is existant
		if ($root_id > 0) {
			//echo "<div class=\"side\">";
			
			// show menu starting from root. -1 is to not tabulate root direct childs, as root is not printed
			$this->ShowMenu($root_id, -1);
			//echo "</div>";
		}
	}
		
		
	//
	// Show menu for the end-user
	//
	protected function ShowMenu($content_id, $depth)
	{
		// read content item
		$sSql="select * from tbl_menu where id='" . $content_id . "'";
		$risultato=mysql_query($sSql);
		if($risultato){
			$row=mysql_fetch_assoc($risultato);
		}
		// dont print root (or invalid items)
		
		if ($row["padre"] > 0) {
			$data = "./index.php?IdP=" . $row["id_pagina"];
			$link = $data;
			// if menu item has a link
			if (true) {//($link <> "") {								
				$title = "<a id=\"link" . $row["id"] . "\" OnClick=menu_show('m" . $row["id"] . "')>[+]</a><a class=\"cLinkMenu\" href=\"" . $link . "\" title=\"" . $row["title"] . "\">" . str_repeat("&nbsp;&nbsp;&nbsp;",$depth) . $row["title"] . "</a>";
				//$title = "<a class=\"cLinkMenu\" href=\"" . $link . "\" title=\"" . $row["title"] . "\">" . str_repeat("&nbsp;&nbsp;&nbsp;",$depth) . $row["title"] . "</a>";
				//$title = "<a OnMouseOver=setTimeout(\"fClickMostra('m" . $row["id"] . "')\",1000) class=\"cLinkMenu\" href=\"" . $link . "\" title=\"" . $row["title"] . "\">" . str_repeat("&nbsp;&nbsp;&nbsp;",$depth) . $row["title"] . "</a>";				
				//$title = "<a class=\"cLinkMenu\" href=\"" . $link . "\" title=\"" . $row["title"] . "\">" . str_repeat("&nbsp;&nbsp;&nbsp;",$depth) . $row["title"] . "</a>";
			} else {
				$title = "boooo";
			}
			//Out::Html("<span class=\"preview-listitem\" style=\"padding-left: " . $depth . "0px\">" . $title . "</span><br />");
			echo "<li>" . $title . "</li>";
		}
		// read every item's child
		$sSql="select * from tbl_menu where padre='" . $row["id"] . "'";
		$risultato=mysql_query($sSql);		
		if($risultato){
			$bUl = false;			// is true if has been printed an <ul> tag			
			// apply this function to every child
			while ($row=mysql_fetch_assoc($risultato)) {
				$childid = $row["id"];
				if ($childid > 0) {
					if (!$bUl) {
						if ($row["padre"]<>"1"){
							echo "<div style='padding:5px;background-color: rgb(25,5,28);border: #3b1251 1px solid;position:absolute;margin: -16px 0px 0px 150px !important;margin: 0px 0px 0px 52px;z-index:500;display:none;visibility:hidden;' id='m" . $row["padre"] . "'>";
							//echo "<div OnMouseOut=menu_hide('m" . $row["padre"] . "') OnMouseOver=menu_show('m" . $row["padre"] . "') style='padding:5px;background-color: rgb(25,5,28);border: #3b1251 1px solid;position:absolute;margin: -16px 0px 0px 150px !important;margin: 0px 0px 0px 52px;z-index:500;display:none;visibility:hidden;' id='m" . $row["padre"] . "'>";
							//echo "<div OnMouseOut=setTimeout(\"fClickNascondi('m" . $row["padre"] . "')\",1000) OnMouseOver=setTimeout(\"fClickMostra('m" . $row["padre"] . "')\",1000) style='padding:5px;background-color: rgb(25,5,28);border: #3b1251 1px solid;position:absolute;z-index:500;display:none;visibility:hidden;' id='m" . $row["padre"] . "'>";
							//echo "<div style='padding:5px;margin:5px;background-color: rgb(25,5,28);border: #3b1251 1px solid;position:absolute;z-index:500;visibility:hidden;' id='m" . $row["padre"] . "'>";							
							echo "<ul class='menu3'>";
						}else{
							echo "<div id='" . $row["padre"] . "'>";
							echo "<ul class='menu3'>";
						}
						echo "<li class=\"cLinkMenu\">";
						$bUl = true;
					}
					$this->ShowMenu($childid, $depth+1);
				}
			}			
			// if an <ul> tag has been opened, close it.
			if ($bUl) {
				echo "</ul>";
				echo "</div>";
				//echo "</ul>";
				$bUl = false;
			}
		}
	}
}
?>
