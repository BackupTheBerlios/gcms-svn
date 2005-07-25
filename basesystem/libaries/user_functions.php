<?php
	/** 
	* $Id$
	* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
	*
	* This file is part of GCMS
	*
	* @author   GCMS Development Team <devel@ghcif.de>
	* @since    2005/03/28
	* @version  $Revision$
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
    */


    /**
    * general check
    */
    if(!defined('IN_GCMS')) {
        die('access denied');
    }


    /**
    * load userdata to main array
    */
    function load_userdata($uid = false) {
        global $GCMS;

        if($uid != false) {
            $row = gcms_db_get_userdata((int)$uid);

            foreach($row AS $key => $value) {
                $userdata[$key] = $value;
            }

            return $userdata;
        } else {
            if(!isset($_SESSION['id'])) {
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

        list($user, $host) = explode("@", trim($email));

        if(preg_match('/^' . $regex . '$/', trim($email)) AND checkdnsrr($host)) {
            return true;
        } else {
            return false;
        }
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
