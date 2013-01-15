<?php
	/**
	* classe che estende cSyndication e crea il feed per le news
	* \author Giacomo "KloWn" Bella <foliestere@gmail.com>
	*/

	require_once("inclusions.php");
	
	class PbfNewsFeed extends cSyndication{
		
		protected function retrieveData(){
			$items = array();
			$query = new Query();
			$query->tables = array("tbl_news");
			$query->fields = array("tbl_news.*");
			$query->limit = strval($this->itemNumber);
			$query->filters = "id_sito = 1";
			$query->sortfields = array('datetime desc');
			
			if ($query->Open()){
				while($row=$query->GetNextRecord(true)){
					$items[]=$row;
				}
				return $items;
			}else{
			 return false;
			}
		}

		private function newsLink($idSito, $idNews){
			$query = new Query();
			$query->tables = array("tbl_site");
			$query->fields = array("subdomain_name");
			$query->filters = "id=".$idSito;
			if ($query->Open()){
				while($row = $query->GetNextRecord()){
					$link='http://'.$row[0].'/index.php?IdN='.$idNews;
				}
			}
			return $link;
		}

		private function newsType($id){
			$query = new Query();
			$query->tables = array("tbl_news_tipo");
			$query->fields = array("descrizione");
			$query->filters = "id=".$id;
			if ($query->Open()){
				while ($row = $query->GetNextRecord()){
					$category = $row[0];
				}
				return $category;
			}else{
				return false;
			}
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
				$newsLink = $this->newsLink($element["id_sito"], $element["ID"]);
				$item = "<item>\n\t\t\t\t";
				$item .= "<title>".strip_tags($element["title"])."</title>\n\t\t\t\t";
				$item .= "<link>".$newsLink."</link>\n\t\t\t\t";
				$item .= "<description>".htmlentities($element["News"])."</description>\n\t\t\t\t";
                //var_dump($element["News"]);
				$item .= "<guid>".$newsLink."</guid>\n\t\t\t\t";
				$item .= "<category>".$this->newsType($element["id_tipo"])."</category>\n\t\t\t";
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
