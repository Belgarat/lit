<?php
//! feed generator
//! Default timezone Europe/Rome
date_default_timezone_set('Europe/Rome');

include_once("inclusions.php");

$Db = Db::getInstance();
$Db->Open();

$feed = new ForumFeeds("Play by Forum - Lux in Tenebra", "http://pbf.luxintenebra.net", "Aggiornamenti dei topic del forum");

$feed->setItemNumber(10);
$feed->writeFeed('public/forum.rss');

unset($feed);

?>
