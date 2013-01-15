<?php
/*
 *      image.php
 *      
 *      Copyright 2011 Marco Brunet <marco@belgaratmobile>
 *      
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 2 of the License, or
 *      (at your option) any later version.
 *      
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *      
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 */
	
	$larghezza_thumb = 200;
	$altezza_thumb = 200;
	#$originale = "../" . str_replace("http://pbf.luxintenebra.int/","","http://pbf.luxintenebra.int/public/galleria/1433.jpg");
	$originale = "../" . @$_GET["url_img"];
	$ext=pathinfo($originale);

	if($ext["extension"]=="jpg"){
		$immagine = imagecreatefromjpeg($originale);
	}else{
		$immagine = imagecreatefrompng($originale);
	}
	

	$larghezza = imagesx($immagine);
	$altezza = imagesy($immagine);
	$scala = min($larghezza_thumb/$larghezza, $altezza_thumb/$altezza);

	if ($scala < 1)
	{
	  $nuova_larghezza = floor($scala*$larghezza);
	  $nuova_altezza = floor($scala*$altezza);

	  $immagine_temporanea = imagecreatetruecolor($nuova_larghezza, $nuova_altezza);

	  imagecopyresized($immagine_temporanea, $immagine,0,0,0,0,$nuova_larghezza, $nuova_altezza, $larghezza, $altezza);
	  imagedestroy($immagine);
	  $immagine = $immagine_temporanea;
	}
	
	if($ext["extension"]=="jpg"){
		header("Content-type: image/jpeg");
		imagejpeg($immagine);
	}else{
		header("Content-type: image/png");
		imagepng($immagine);
	}
	
?>
