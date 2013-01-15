<?php
	/**
	* classe che estende cSyndication e crea il feed per le news
	* \author Giacomo "KloWn" Bella <foliestere@gmail.com>
	*/

	require_once("inclusions.php");
	
	class ForumFeeds extends cSyndication{
                private $dataelement;
		
		protected function retrieveData(){
			$items = array();
			$query = new Query();
			$query->tables = array("messagedb");
			$query->fields = array("*");
			$query->limit = strval($this->itemNumber);
			$query->filters = "SiteID = 1";
			$query->sortfields = array('ID desc');
			
			if ($query->Open()){
				while($row=$query->GetNextRecord(true)){
					$items[]=$row;
				}
				return $items;
			}else{
			 return false;
			}
		}

		private function newsLink($id){
			$link="http://".htmlentities("pbf.luxintenebra.net/index.php?IdP=108&IdTh=".$this->dataelement["Cat"]."&IdPs=".$this->dataelement["ReplyID"]."&Offset=".$this->returnOffset($id)."&rand=3971#fine");
			return $link;
		}

		private function returnOffset($id){
			$query = new Query();
			$query->tables = array("messagedb");
			$query->fields = array("count(*)");
			$query->filters = "ReplyID=".$id;
			if ($query->Open()){
				$row = $query->GetNextRecord();
                                //var_dump(floor(($row[0]/10))*10);
				return floor(($row[0]/10))*10;
			}else{
				return 0;
			}
		}
                
		private function newsType($id){
			$query = new Query();
			$query->tables = array("forumdb");
			$query->fields = array("Name");
			$query->filters = "ID=".$id;
			if ($query->Open()){
				while ($row = $query->GetNextRecord()){
					$category = $row[0];
				}
				return $category;
			}else{
				return false;
			}
		}
		
		private function normalized_data_iso($dt,$t){
			$year=substr($dt,0,4);
			$month=substr($dt,4,2);
			$day=substr($dt,6,2);
			$hour=floor((($t/60)/60));
			$min=floor((($t-($hour*3600))/60));
			$sec=$t-($hour*3600)-($min*60);
			return date('r',mktime($hour, $min, $sec, $month, $day, $year));
		}

		public function writeFeed($file){
			$this->setFile("Syndication/template.xml");
			$feed = implode("", file($this->file));
			$today = date('r');
			$news = $this->retrieveData();

			//channel part (elements passed on __construct)
			$feed = preg_replace("#<!-- Title -->#", $this->channelTitle, $feed);
			$feed = preg_replace("#<!-- Link -->#", $this->channelLink, $feed);
			$feed = preg_replace("#<!-- Description -->#", $this->channelDescription, $feed);
			$feed = preg_replace("#<!-- Language -->#", "it", $feed); ///\todo migliorare
			$feed = preg_replace("#<!-- LBD -->#", $today, $feed);
			
			//items part (elements coming from DB)
			foreach ($news as $element){
                                $this->dataelement=$element;
				$newsLink = $this->newsLink($element["ReplyID"]);
				$item = "<item>\n\t\t\t\t";
				$item .= "<title>".$element["Poster"].": ".strip_tags($element["Subject"])."</title>\n\t\t\t\t";
				$item .= "<link>".$newsLink."</link>\n\t\t\t\t";
				$item .= "<description>".strip_tags($element["Message"])."</description>\n\t\t\t\t";
                //var_dump($element["News"]);
				//$item .= "<guid>".$newsLin."</guid>\n\t\t\t\t";
				$item .= "<category>".$this->newsType($element["Cat"])."</category>\n\t\t\t";
				$item .= "<pubDate>".$this->normalized_data_iso($element["DtISO"],$element["Timer"])."</pubDate>\n\t\t\t\t";
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
