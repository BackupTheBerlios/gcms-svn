<?php
	/** 
	* $Id: $
	* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
	*
	* This file is part of GCMS
	*
	* @author   GCMS Development Team <devel@ghcif.de>
	* @since    2005/03/28
	* @version  $Revision: $
	*
	* Copyright (C) 2005 by ghcif.de <devel@ghcif.de>
	*   
	* This program is free software; you can redistribute it and/or modify
	* it under the terms of the GNU General Public License as published by
	* the Free Software Foundation; either version 2 of the License, or
	* (at your option) any later version.
	*
	* This program is distributed in the hope that it will be useful, 
	* but WITHOUT ANY WARRANTY; without even the implied warranty of
	* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	* GNU General Public License for more details.
	*
	* You should have received a copy of the GNU General Public License
	* along with this program; if not, write to the Free Software
	* Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
	*
    * TODO:
    * - error handling
    * - if language or template is set in url change it
    */


    /**
    * general check
    */
    if(!defined('IN_GCMS')) {
        die('access denied');
    }


    /**
    * strip slashes if magic quotes is activated
    */
    function array_stripslashes(&$var) {       
        if(is_string($var)) {
            $var = stripslashes($var);
        } else {   
            if(is_array($var)) {   
                foreach($var AS $key => $item) {   
                    array_stripslashes($var[$key]);
                }
            } 
        } 
    }
    
    
    /**
    * hang the session id to the url
    */
    function append_sid($url, $non_html_amp = false) {
        global $SID;

        /**
        * TODO:
        * - if language or template is set in url change it
        */

        if(!empty($SID) AND !preg_match('#' . session_name() . '#', $url)) {
            $url .= ((strpos($url, '?') != false) ? (($non_html_amp) ? '&' : '&amp;') : '?') . session_name() . '=' . session_id();
        }
        
        return $url;
    }


	/**
	* loads config file to main array
	*/
	function load_config($module = false, $configfile = false) {   
		global $GCMS;

        /**
        * if module is set write it to the module config section
        * else write it to the general config section
        */
        if($module != false) {
            $file  = GCMS_REAL_PATH . '/modules/' . $module . '/configs/';
            $file .= ($configfile != false) ? ($configfile) : ('base');
            $file .= '.php';
            
            if(file_exists($file)) {
                include($file); 
            } else {
                /**
                * TODO:
                * - error handling
                */
                die('failed to load ' . $module . ' module config - ' . $configfile);
            }
       
            foreach($config AS $key => $item) {
                $GCMS['CONFIG'][strtoupper($module)][$key] = $item;
            }
        } else {
            $file  = GCMS_REAL_PATH . '/configs/';
            $file .= ($configfile != false) ? ($configfile) : ('base');
            $file .= '.php';
            
            if(file_exists($file)) {
                include($file);
            } else {
                /**
                * TODO:
                * - error handling
                */
                die('failed to load base config - ' . $configfile);
            }
        
            foreach($config AS $key => $item) {
                $GCMS['CONFIG'][$key] = $item;
            }
        }
	}


    /**
    * loads language file to main array
    */
    function load_language($langfile = false, $module = false) {
        global $GCMS;
        
        /**
        * if module is set write it to the module language section
        * else write it to the general language section
        */
        if($module != false) {
            $file  = GCMS_REAL_PATH . '/modules/' . $module . '/languages/' . $GCMS['USER']['lang'] . '/';
            $file .= ($langfile != false) ? ($langfile) : ('base');
            $file .= '.php';
            if(file_exists($file)) {
                include($file);
            } else {
                /**
                * TODO:
                * - error handling
                */
                die('failed to load ' . $module . ' module language file - ' . $langfile);
            }
            
            foreach($lang AS $key => $item) {
                $GCMS['LANG'][strtoupper($module)][$key] = $item;
            }
        } else {
            $file  = GCMS_REAL_PATH . '/languages/' . $GCMS['USER']['lang'] . '/';
            $file .= ($langfile != false) ? ($langfile) : ('base');
            $file .= '.php';
            if(file_exists($file)) {
                include($file);
            } else {
                /**
                * TODO:
                * - error handling
                */
                die('failed to load base language file - ' . $langfile);
            }
            
            foreach($lang AS $key => $item) {
                $GCMS['LANG'][$key] = $item;
            }
        }
    }


    /**
    * load userdata to main array
    */
    function load_userdata($uid = false) {
        global $GCMS;

        if($uid != false) {
            $row = gcms_db_get_userdata($_SESSION['id']);

            foreach($row AS $key => $value) {
                $userdata[$key] = $value;
            }

            return $userdata;
        } else {
            if(!isset($_SESSION['id']) OR !is_int($_SESSION['id'])) {
                $_SESSION['id'] = GUEST;
            }
            
            $row = gcms_db_get_userdata($_SESSION['id']);
            
            foreach($row AS $key => $value) {
                $GCMS['USER'][$key] = $value;
            }

            /**
            * overwrite data if necessary
            */
            $GCMS['USER']['lang'] = ($GCMS['CONFIG']['overwrite_language']) ? ($GCMS['CONFIG']['default_language']) : ($GCMS['USER']['lang']);
            $GCMS['USER']['template'] = ($GCMS['CONFIG']['overwrite_template']) ? ($GCMS['CONFIG']['default_template']) : ($GCMS['USER']['template']);
        }
    }


    /**
    * load the template object
    */
    function load_template() {
        global $GCMS;

        define('GCMS_REAL_TPLPATH', GCMS_REAL_PATH . '/templates/' . $GCMS['USER']['template']);
        define('GCMS_RELATIVE_TPLPATH', GCMS_RELATIVE_PATH . '/templates/' . $GCMS['USER']['template']);
        
        require_once(GCMS_REAL_PATH . '/libaries/template.class.php');
        $GCMS['TEMPLATE'] = new gcms_template(GCMS_REAL_TPLPATH);
    }


    /**
    * load root module
    */
    function load_root_module() {
        global $GCMS;

        define('GCMS_MODULE_PATH', GCMS_REAL_PATH . '/modules/' . $GCMS['ROOTMODULE']['name']);

        if(is_dir(GCMS_MODULE_PATH)) {
            /**
            * load module db wrapper if it is available
            */
            if(is_dir(GCMS_MODULE_PATH . '/db') AND file_exists(GCMS_MODULE_PATH . '/db/' . $GCMS['DB']['DATA']['TYPE'] . '.php')) {
                include(GCMS_MODULE_PATH . '/db/' . $GCMS['DB']['DATA']['TYPE'] . '.php');
            }
            
            /**
            * load module config if it is available
            */
            if(is_dir(GCMS_MODULE_PATH . '/configs') AND file_exists(GCMS_MODULE_PATH . '/configs/base.php')) {
                load_config($GCMS['ROOTMODULE']['name'], false);
            }

            /**
            * load module language file if it is available
            */
            if(is_dir(GCMS_MODULE_PATH . '/languages') AND file_exists(GCMS_MODULE_PATH . '/languages/' . $GCMS['USER']['lang'] . '/base.php')) {
                load_language(false, $GCMS['ROOTMODULE']['name']);
            }
       
            /**
            * include module index
            */
            include(GCMS_MODULE_PATH . '/index.php');
        } else {
            /**
            * TODO:
            * - error handling
            */
            die('failed to include root module');
        }
    }













/**
* validate email adress
*/
function check_email($email) {
$nonascii = "\x80-\xff";
$nqtext = "[^\\\\$nonascii\015\012\"]";
$qchar = "\\\\[^$nonascii]";
$normuser = '[a-zA-Z0-9][a-zA-Z0-9_.-]*';
$quotedstring = "\"(?:$nqtext|$qchar)+\"";
$user_part = "(?:$normuser|$quotedstring)";
$dom_mainpart = '[a-zA-Z0-9][a-zA-Z0-9._-]*\\.';
$dom_subpart = '(?:[a-zA-Z0-9][a-zA-Z0-9._-]*\\.)*';
$dom_tldpart = '[a-zA-Z]{2,5}';
$domain_part = $dom_subpart . $dom_mainpart . $dom_tldpart;
$regex = $user_part . '\@' . $domain_part;

list($user, $host) = explode("@", $email);

if(preg_match('/^' . $regex . '$/',$email) AND checkdnsrr($host)) {
return true;
} else {
return false;
}
}








/**
* check login
*/
function login_right($username, $pass) {
global $db;
        
$sql = "SELECT * FROM " . TBL_USERS . " WHERE username = '" . $username . "' AND passwd = MD5('" . $pass . "');";
if(!($result = $db->db_query($sql))) {
/**
* TODO:
* - error handling
*/
$error = $db->db_error();
die($error['code'] . ': ' . $error['message']);
}
		
$row = $db->db_fetchrow($result);

return $row;
}


/**
* get user rights
*/
function get_rights($catid = null, $uid = null) {   
$uid = ($uid == null) ? ($_SESSION['id']) : ($uid);			
		
if(is_int($catid) AND is_int($uid)) {   

/*
$sql = 'SELECT userid, rights                                                                                                                
FROM ghcif_user_rights                                                                                                               
WHERE userid = '.$thisid.';';
$result = mysql_query($sql) OR die(mysql_error());
$rights = array();
while($row = mysql_fetch_assoc($result))
{
$rights[] = $row['rights'];
}
*/
}
		
return false;
}
?>
