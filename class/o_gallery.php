<?php
/*
    Class that describes a picture gallery
*/
define("OGALL_ERR_OK", 0);
define("OGALL_ERR_WRONGSIZE", 1);
define("OGALL_ERR_WRONGTYPE", 2);
define("OGALL_ERR_UNKNOWN", 999);


class oGallery
{
  /* consts
  */
  
  
  /* instance variables
  */
  // read from database
  var $site_id;
  var $id;
  var $user_id;
  var $parent_id;
  var $title;
  var $description;
  var $gal_date;
  var $gal_time;
  var $b_public;
  var $hits;
  var $preview_pic_id;
  var $keywords;
  // read from other sources
  var $admin_role_min;            // min user role to administer gallery
  var $view_role_min;             // min user role to view gallery
  var $basedir;                   // base gallery directory
  var $errorcode;                 // returned error code
  var $childlist;                 // child list resultset
  var $piclist;                   // picture list resultset

  
  /* constructor
  */
  function oGallery()
  {
      $this->errorcode = OGALL_ERR_UNKNOWN;
      $this->id = -1;
      $this->site_id = -1;
      $this->admin_role_min = GALL_ADMIN_POWER;
      $this->view_role_min = GALL_VIEW_POWER;
      $this->basedir = GALL_BASEDIR;
      $this->errorcode = OGALL_ERR_OK;
      $this->childlist = false;
  }


  //
  // read info from database
  //
  function getinfo($site, $id)
  {
      $this->errorcode = OGALL_ERR_UNKNOWN;
      $sql =  " select site_id, id, user_id, parent_id,  title, " .
              " description, gal_date, gal_time, b_public, " .
              " hits, preview_pic_id, keywords " .
              " from cp_gallery " .
              " where site_id=" . $site . " and id=" . $id .
              " limit 0,1";
      $ris = mysql_query($sql);
      if (($ris != false) && (mysql_affected_rows() > 0)) {
          if ($riga = mysql_fetch_array($ris, MYSQL_ASSOC)) {
              $this->id = $riga['id'];
              $this->site_id = $riga['site_id'];
              $this->user_id = $riga['user_id'];
              $this->parent_id = $riga['parent_id'];
              $this->title = $riga['title'];
              $this->description = $riga['description'];
              $this->gal_date = $riga['gal_date'];
              $this->gal_time = $riga['gal_time'];
              $this->b_public = $riga['b_public'];
              $this->hits = $riga['hits'];
              $this->preview_pic_id = $riga['preview_pic_id'];
              $this->keywords = $riga['keywords'];
              $this->errorcode = OGALL_ERR_OK;
          }
          mysql_free_result($ris);
      }
  } // getinfo()


  //
  // add specified picture
  //
  function addpic($pic)
  {
      $ret = false;
      $this->errorcode = OGALL_ERR_UNKNOWN;
      if ($pic->upload_filesize < (GALL_MAX_UPLOAD_SIZE * 1024)) {
          $ret = $pic->add();
          if ($ret == false) {
              $this->errorcode = $pic->errorcode;
          }
      } else {
          $this->errorcode = OGALL_ERR_WRONGSIZE;
      }
      return $ret;
  }
  
  
  //
  //  Return nr of child galleries
  //
  function get_childnum()
  {
      $ret = 0;
      $sql =  " select count(id) " .
              " from cp_gallery " .
              " where site_id=" . $this->site_id .
              " and parent_id=" . $this->id;
      $ret = get_sql_value($sql);
      if (($ret < 0) || ($ret == false)) {
          $ret = 0;
      }
      return $ret;
  } // get_childnum()
  
  
  //
  //  Return nr of pictures in gallery
  //
  function get_picnum()
  {
      $ret = 0;
      $sql =  " select count(id) " .
              " from cp_gallery_images " .
              " where site_id=" . $this->site_id .
              " and gallery_id=" . $this->id;
      $ret = get_sql_value($sql);
      if (($ret < 0) || ($ret == false)) {
          $ret = 0;
      }
      return $ret;
  } // get_picnum()
  
  
  //
  // open recordset for list of child  galleries, starting from $offset
  //
  function childlist_open($offset=0)
  {
      $sortfield = "gal_date asc, gal_time asc ";
      $sql =  " select id " .
              " from cp_gallery " .
              " where site_id= " . $this->site_id .
              "   and parent_id=" . $this->id .
              " order by " . $sortfield .
              " limit " . $offset . ", " . GALL_ITEMS_PER_PAGE;
      if ($this->childlist != false) {
          mysql_free_result($this->childlist);
          $this->childlist = false;
      }
      $this->childlist = mysql_query($sql);
  } // childlist_open()
  
  
  //
  // returns next child gallery id.
  // requires an open childlist made with childlist_open()
  //
  function childlist_getnext()
  {
      $ret = -1;
      if ($this->childlist != false) {
          if ($riga = mysql_fetch_array($this->childlist, MYSQL_ASSOC)) {
              $ret = $riga["id"];
          }
      }
      return $ret;
  }
  
  
  //
  // closes recordset for list of child  galleries
  //
  function childlist_close()
  {
      if ($this->childlist != false) {
          mysql_free_result($this->childlist);
          $this->childlist = false;
      }
  } // childlist_close()
  
  
  //
  // open recordset for list of pictures, starting from $offset
  //
  function piclist_open($offset=0)
  {
      $sortfield = "img_date asc, img_time asc ";
      $sql =  " select id " .
              " from cp_gallery_images " .
              " where site_id= " . $this->site_id .
              "   and gallery_id=" . $this->id .
              " order by " . $sortfield .
              " limit " . $offset . ", " . GALL_ITEMS_PER_PAGE;
      if ($this->piclist != false) {
          mysql_free_result($this->piclist);
          $this->piclist = false;
      }
      //echo "<p>" . $sql . "</p>";
      $this->piclist = mysql_query($sql);
  } // piclist_open()


  //
  // returns next pic id.
  // requires an open piclist made with piclist_open()
  //
  function piclist_getnext()
  {
      $ret = -1;
      if ($this->piclist != false) {
          if ($riga = mysql_fetch_array($this->piclist, MYSQL_ASSOC)) {
              $ret = $riga["id"];
          }
      }
      //echo "<p>Immagien ritornata: " . $ret . "</p>";
      return $ret;
  }


  //
  // closes recordset for list of pictures
  //
  function piclist_close()
  {
      if ($this->piclist != false) {
          mysql_free_result($this->piclist);
          $this->piclist = false;
      }
  } // piclist_close()


  //
  // delete gallery (and restores default values)
  // return false if operation fails. else returns true
  //
  function delete() {
      $ris = false;
      if ($this->id >= 0) {
          // delete item
          $sql =  " delete from cp_gallery " .
                  " where site_id=" . $this->site_id . " and " .
                  "       id=" . $this->id;
          mysql_query($sql);
          if (mysql_affected_rows() > 0) {
              $ris = true;
          }
          // delete image comments
          $sql =  " delete from cp_gallery_comments " .
                  " where site_id=" . $this->site_id . " and " .
                  "       gallery_id=" . $this->gallery_id;
          mysql_query($sql);
          // reset picture properties
          $this->id = -1;
          $this->user_id = -1;
          $this->site_id = -1;
          $this->title = "";
          $this->description = "";
      }
      return $ris;
  }
  
} // class oGallery
?>
