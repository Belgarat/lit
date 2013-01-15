<?php
	/**
	* classe che estende cSyndication e crea il feed per le news
	* \author Giacomo "KloWn" Bella <foliestere@gmail.com>
	* \note incmplete
	*/

	require_once("inclusions.php");
	
	class ForumFeed extends cSyndication{
		
		protected function retrieveData(){
			$items = array();
			$query = new Query();
			$query->tables = array("messagedb");
			//campi utili: Poster e Subject, ID e SiteID, IdAvv e IdPg, Time & ForumID (IdF)
			$query->fields = array("Poster", "Subject","Message", "ID", "SiteID", "IdAvv", "IdPg", "Time", "ForumID");
			$query->limit = strval($this->itemNumber);
			$query->sortfields = array('ID desc');
			// todo: join per oggetto DB
			if ($query->Open()){
				while($row=$query->GetNextRecord(true)){
					$items[]=$row;
				}
				$query->Close();
				return $items;
			}else{
			 return false;
			}
		}

		private function newsLink($idPost, $idPg, $idType){
			$link='http://forum.luxintenebra.net/Default.asp?action=read&id='.$idPost.'&Pg='.$idPg.'&IdF='.$idType.'#FinePg';
			return $link;
		}


		public function writeFeed($file){
			$this->setFile("Syndication/template.xml");
			$feed = implode("", file($this->file));
			$today = date('c');
			$news = $this->retrieveData();

			//channel part (elements passed on __construct)
			$feed = preg_replace("#<!-- Title -->#", $this->channelTitle, $feed);
			$feed = preg_replace("#<!-- Link -->#", $this->channelLink, $feed);
			$feed = preg_replace("#<!-- Description -->#", $this->channelDescription, $feed);
			$feed = preg_replace("#<!-- Language -->#", "it_IT", $feed); ///\todo migliorare
			$feed = preg_replace("#<!-- LBD -->#", $today, $feed);
			
			//items part (elements coming from DB)
			foreach ($news as $element){
				$newsLink = $this->newsLink($element["ID"], $element["IdPg"], $element["ForumID"]);
				$item = "<item>\n\t\t\t\t";
				$item .= "<title>".strip_tags($element["Subject"])."</title>\n\t\t\t\t";
				$item .= "<link>".$newsLin."</link>\n\t\t\t\t";
				$item .= "<description>".htmlentities(substr($element["Message"],0,100))."</description>\n\t\t\t\t";
				$item .= "<guid>".$newsLin."</guid>\n\t\t\t\t";
				$item .= "<category>PbF</category>\n\t\t\t";
				$item .= "</item>\n\t\t\t";
				$item .= "<!-- ITEMS -->";

				$feed = preg_replace("#<!-- ITEMS -->#", $item, $feed);
			}

			$feed = preg_replace("#<!-- ITEMS -->#", "", $feed);
			
			if (($file != "") && $file){
				$feedFile = fopen($file, 'w');
				fwrite($feedFile, $feed); 
				fclose($feedFile);
			}
		}
	}
?>
