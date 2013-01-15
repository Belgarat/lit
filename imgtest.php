<?php
session_start();

require_once("./pbf_inclusions.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">
<HTML>
	<HEAD>
		<title>Lux In tenebra Forum gdr on line - <?php echo $oPage->page_title; ?></title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" >
		<script type="text/javascript" src="<?php echo HTTP_ROOT ?>/js/scriptaculous/lib/prototype.js"></script>
		<script type="text/javascript" src="<?php echo HTTP_ROOT ?>/js/scriptaculous/src/scriptaculous.js"></script>
		<link rel="stylesheet" type="text/css" href="css/pbf_amb.css">
		<link rel="stylesheet" type="text/css" href="css/pbf_style.css">
		<link rel="stylesheet" type="text/css" href="css/pbf_site.css">
		<link rel="stylesheet" type="text/css" href="css/pbf_multimenuvert.css">
		<link rel="stylesheet" type="text/css" href="css/noticeboard.css">
		<!--[if IE]>
			<link rel="stylesheet" type="text/css" href="css/pbf_site_ie.css">
			<link rel="stylesheet" type="text/css" href="css/pbf_multimenuvert_ie.css">
		<![endif]-->
		<script type="text/javascript" src="js/multimenu.js"></script>
		<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
		<script type="text/javascript" src="js/ajax.js"></script>
                <link rel="alternate" type="application/rss+xml" title="LiT - Tutte le news" href="feed/public/allnews.rss" />
                <link rel="alternate" type="application/rss+xml" title="LiT - Aggiornamenti dal forum in game e OT" href="feed/public/forum.rss" />
		<link rel="stylesheet" type="text/css" href="css/pbf_content.css">
		<LINK REL="SHORTCUT ICON" HREF="<?php echo SITE_IMG ?>/sword.ico" TYPE="image/x-icon">
		<meta name="description" content="un sito dedicato al gioco di ruolo e alla possibilità di giocare con altri appassionati di d&amp;d o ad&amp;d intorno su un forum creato appositamente">
		<meta name="keywords" content="Fantasy, fantasy, Fantasy-online, fantasy on line, libri fantasy, gdr on line, gdr on-line, giochi di ruolo fantasy, giochi di ruolo on line, giochi di ruolo on-line, d&amp;d, ad&amp;d, D&amp;D, AD&amp;D, forum di gioco, forum d&amp;d, Forum D&amp;D, forum d&amp;d on line, forum ad&amp;d, forum AD&amp;D, elfi, fate, mezzelfi, umani, razze d&amp;d, giocate, ambientazione ad&amp;d, ambientazione D&amp;D, ambientazione AD&amp;D, ambientazione d&amp;d, iscriviti forum d&amp;d, giocatori, master, Master, Master D&amp;D, Master d&amp;d, master d&amp;d, master D&amp;D, citt� virtuale fantasy, giocare on line, giocare on-line, draghi, storie">
		<meta name="copyright" content="&amp;copyright 2004 Lux In Tenebra Group">
		<script type="text/javascript">
				var MenuAttivo = null;
				var precedente;
				
				Event.observe(window.document,'dom:loaded',init);				
				
		        function apriupload(lForm)
		        {
		        open("./"+lForm, "", "width="+500+",height="+400+",scrollbars=yes, left="+((screen.width-500)/2)+",top="+((screen.height-600)/2)+"");
		        }
		        function Open_url(IdDiv,lForm)
		        {
		        	new Ajax.Updater(IdDiv, lForm);
		        }
			new Ajax.PeriodicalUpdater('id_num_online','src/ajax/utente_users_online.php',{frequency: 12});
                        function getcords(e,position){
                                mouseX = Event.pointerX(e) - position[0];
                                mouseY = Event.pointerY(e) - position[1];
                                //for testing put the mouse cords in a div for testing purposes
                                $('debug').innerHTML = 'mouseX:' + mouseX + '-- mouseY:' + mouseY;
                        }
                        //Event.observe(document, 'mousemove', getcords);
		</script>
	</HEAD>
        <STYLE>
            #map1 area:hover{
                background-color:red;
            }
            #test{
                top:79px;
                left:129px;
            }
        </STYLE>
        <body id="body">
            <!--<img onclick="javascript:getcords(event,$('imgtest').positionedOffset());" id="imgtest" src="../img/150italia.png">-->
            <div id="debug"></div>
            <div id="image">
                <h1 id="test">ciao</h1>
            <MAP id="map1" NAME="map1">
            <AREA id="new"
               HREF="new.html" ALT="New!" TITLE="New!"
               SHAPE=CIRCLE COORDS="128,79,10" />
            </MAP>

            <IMG onclick="javascript:getcords(event,$('imgtest').positionedOffset());" id="imgtest" src="../img/150italia.png"
               ALT="map of GH site" BORDER=0
               USEMAP="#map1">
            </div>
        </body>
</HTML>
