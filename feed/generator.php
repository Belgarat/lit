<?php
//! feed generator
//! Default timezone Europe/Rome
date_default_timezone_set('Europe/Rome');

include_once("inclusions.php");

$Db = Db::getInstance();
$Db->Open();

$feed = new AllNewsFeed("All News", "http://www.luxintenebra.net", "Tutte le news dal mondo di LiT");

$feed->setItemNumber(5);
$feed->writeFeed('public/allnews.rss');

$feed->setItemNumber(10);
$feed->writeFeed('public/allnews_long.rss');

unset ($feed);

$feed = new PbfNewsFeed("Play by Forum News", "http://pbf.luxintenebra.net", "News dal Play by Forum");

$feed->setItemNumber(5);
$feed->writeFeed('public/pbfnews.rss');

$feed->setItemNumber(10);
$feed->writeFeed('public/pbfnews_long.rss');

unset ($feed);

$feed = new AssociazioneNewsFeed("Associazione Ludico-letteraria Lux in Tenebra", "http://associazione.luxintenebra.net", "News dall'associazione");

$feed->setItemNumber(5);
$feed->writeFeed('public/assocnews.rss');

$feed->setItemNumber(10);
$feed->writeFeed('public/assocnews_long.rss');

unset($feed);

?>
