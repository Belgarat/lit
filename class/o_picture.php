<?php
/*
    Class that describes a picture
*/
define("OPIC_ERR_WRONGTYPE", 2);


class oPicture
{
  /* consts
  */
  
  
  /* instance variables
  */
  // read from database
  var $site_id;
  var $id;
  var $user_id;
  var $gallery_id;
  var $title;
  var $description;
  var $img_date;
  var $img_time;
  var $filename;
  var $b_originalsize;
  var $b_watermark;
  var $hits;
  var $border_size;
  var $border_color;
  var $keywords;
  // other properties
  var $upload_filename;
  var $upload_filesize;
  var $upload_fileerror;
  var $upload_filetmpname;
  var $errorcode;

  
  /* constructor
  */
  function oPicture()
  {
      $this->id = -1;
      $this->site_id = -1;
      $this->gallery_id = -1;
      $this->user_id = -1;
      $this->title = "";
      $this->description = "";
      $this->filename = "";
      $this->b_originalsize = 1;
      $this->b_watermark = 0;
      $this->hits = 0;
      $this->border_size = 0;
      $this->border_color = 0;
      $this->keywords = "";
      $this->upload_filename = "";
      $this->errorcode = 0;
  }


  //
  // read info from database
  //
  function getinfo($site, $id)
  {
      $sql =  " select site_id, id, user_id, gallery_id,  title, " .
              " description, img_date, img_time, filename, " .
              " b_originalsize, b_watermark, hits, " .
              " border_size, border_color, keywords " .
              " from cp_gallery_images " .
              " where site_id=" . $site . " and id=" . $id .
              " limit 0,1";
      //echo "<p>" . $sql . "</p>";
      $ris = mysql_query($sql);
      if (($ris != false) && (mysql_affected_rows() > 0)) {
          if ($riga = mysql_fetch_array($ris, MYSQL_ASSOC)) {
              $this->id = $riga['id'];
              $this->site_id = $riga['site_id'];
              $this->user_id = $riga['user_id'];
              $this->gallery_id = $riga['gallery_id'];
              $this->title = $riga['title'];
              $this->description = $riga['description'];
              $this->img_date = $riga['img_date'];
              $this->img_time = $riga['img_time'];
              $this->filename = $riga['filename'];
              $this->b_originalsize = $riga['b_originalsize'];
              $this->b_watermark = $riga['b_watermark'];
              $this->hits = $riga['hits'];
              $this->border_size = $riga['border_size'];
              $this->border_color = $riga['border_color'];
              $this->keywords = $riga['keywords'];
          }
          mysql_free_result($ris);
      }
  } // getinfo()
  
  
  //
  // returns true if specified filename is already in the gallery
  //
  function exists_picture_file($filename) {
      $ret = false;
      $sql =  " select filename " .
              " from cp_gallery_images " .
              " where filename = '" . $filename . "'";
      $x = get_sql_value($sql);
      if ($x != false) {
          $ret = true;
      }
      return $ret;
  }

  
  //
  // add current picture in the desired gallery.
  // updates filename and id properties with the new generated values.
  // returns true if ok. else return false.
  //
  function add()
  {
      $ret = false;
      $filename = '';
      $hash = '';
      $extension = '';
      $this->errorcode = 0;
      // generate filename
      // file naming: siteid_id_hash.extension
      // extract extension from file name
      $filename = strtolower($this->upload_filename);
      if ((strpos($filename, '.jpg') != false) || (strpos($filename, '.jpeg') != false)) {
          $extension = '.jpg';
      } else if (strpos($filename, '.gif') != false) {
          $extension = '.gif';
      } else if (strpos($filename, '.png') != false) {
          $extension = '.png';
      } else {
          $this->errorcode = OPIC_ERR_WRONGTYPE;
      }
      if ($this->errorcode == 0) {
          do {
            $hash = get_random_chars(6);
            $this->filename = 'gallery/' . $this->site_id . '_' . $this->gallery_id . '_' . $hash . $extension;
            //echo "<p>Trovato: " . $this->filename . "</p>";
          } while ($this->exists_picture_file($filename) == true);
          // copy file into gallery directory
          //echo "<p>Da: " . $this->upload_filetmpname . "</p>";
          //echo "<p> A: " . $this->filename . "</p>";
          if (move_uploaded_file($this->upload_filetmpname, $this->filename)) {
              // build sql string
              $sql =  " insert into cp_gallery_images " .
                      " (site_id, user_id, gallery_id, title, description, img_date, " .
                      " img_time, filename, b_originalsize, b_watermark, hits, border_size, " .
                      " border_color, keywords) " .
                      " values (" . $this->site_id . ", " . $this->user_id . ", " .
                      $this->gallery_id . ", '" . $this->title . "', '" . $this->description .
                      "', " . int_date() . ", " . int_time() . ", '" . $this->filename . "', " .
                      $this->b_originalsize . ", " . $this->b_watermark . ", 0, " .
                      $this->border_size . ", " . $this->border_color . ", '" .
                      $this->keywords . "')";
              // exec sql
              //echo "<p>" . $sql . "</p>";
              mysql_query($sql);
              if (mysql_affected_rows() <= 0) {
                  unlink($this->filename);
              } else {
                  $ret = true;
              }
          } else {
              $ret = false;
          }
      }
      return $ret;
  } // add()
  
  
  //
  // get image size
  //
  function getsize() {
      return getimagesize($this->filename);
  } // getsize()
  
  
  //
  // increases visit counter for image
  //
  function increasevisitcounter() {
      $this->hits = $this->hits +1;
      $sql =  " update cp_gallery_images " .
              " set hits = hits+1 " .
              " where site_id = " . $this->site_id . " and " .
              "     id=" . $this->id;
      mysql_query($sql);
  } // increasevisitcounter()

  
  //
  // delete picture (and restores default values)
  // return false if operation fails. else returns true
  //
  function delete() {
      $ris = false;
      if ($this->id >= 0) {
          // delete image
          $sql =  " delete from cp_gallery_images " .
                  " where site_id=" . $this->site_id . " and " .
                  "       id=" . $this->id;
          mysql_query($sql);
          if (mysql_affected_rows() > 0) {
              $ris = true;
          }
          // delete image comments
          $sql =  " delete from cp_gallery_comments " .
                  " where site_id=" . $this->site_id . " and " .
                  "       gallery_id=" . $this->gallery_id . " and " .
                  "       image_id=" . $this->id;
          mysql_query($sql);
          // delete file
          unlink($this->filename);
          // reset picture properties
          $this->id = -1;
          $this->gallery_id = -1;
          $this->user_id = -1;
          $this->site_id = -1;
          $this->title = "";
          $this->description = "";
      }
      return $ris;
  }
  
//
// return the nr of user comments for this item
//
function get_comment_num()
{
	$ret = 0;	
	$sql = " select count(id) " .
		" from cp_gallery_comments " .
		" where site_id=" . $this->site_id . " and " .
		" image_id=" . $this->id;	
	$ret = get_sql_value($sql);
	if (!($ret > 0)) {
		$ret = 0;
	}
	return $ret;
}
  
  
} // class oGallery
?>
