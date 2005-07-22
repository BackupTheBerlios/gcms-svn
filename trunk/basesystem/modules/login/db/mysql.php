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
	*/


	/**
	* general check
	*/
	if(!defined('IN_GCMS')) {
		die('access denied');
	}


    /**
    * check username and password
    */
    function gcms_db_login_right($username, $password) {
        global $GCMS;
                            
        $sql = "SELECT u.id FROM " . TBL_USERS . " AS u WHERE u.username = '" . addslashes(htmlspecialchars($username)) . "' AND u.passwd = MD5('" . addslashes(htmlspecialchars($password)) . "');";
        if(!($result = mysql_query($sql, $GCMS['DB']['LINK']))) {
            message_die(GENERAL_ERROR, 'Failed to check username and password', '', __LINE__, __FILE__, $sql);
        }

        $GCMS['DB']['COUNTER']++;

        $userdata = @mysql_fetch_array($result, MYSQL_ASSOC);
        @mysql_free_result($result);

        return $userdata['id'];
    }


    /**
    * get userdata by name
    */
    function gcms_db_get_user_by_name_email($name, $email) {
        global $GCMS;

        $sql = "SELECT u.id FROM " . TBL_USERS . " AS u WHERE u.username = '" . addslashes(htmlspecialchars($name)) . "' AND u.email = '" . addslashes(htmlspecialchars($email)) . "';";
        if(!($result = mysql_query($sql, $GCMS['DB']['LINK']))) {
            message_die(GENERAL_ERROR, 'Failed to get userid', '', __LINE__, __FILE__, $sql);
        }

        $GCMS['DB']['COUNTER']++;

        $userid = @mysql_fetch_array($result, MYSQL_ASSOC);
        @mysql_free_result($result);

        $userdata = load_userdata($userid['id']);
        
        return $userdata;
    }


    /**
    * set temporary password and actkey
    */
    function gcms_db_set_temp_password($uid, $newpassword, $activationkey) {
        global $GCMS;

        $sql = "UPDATE " . TBL_USERS . " SET newpasswd = '" . md5($newpassword) . "', actkey = '" . $activationkey . "' WHERE id = " . $uid . ";";
        if(!($result = mysql_query($sql, $GCMS['DB']['LINK']))) {
            message_die(GENERAL_ERROR, 'Could not update new password information', '', __LINE__, __FILE__, $sql);
        }
        @mysql_free_result($result);

        $GCMS['DB']['COUNTER']++;
    }


    /**
    * write temporary password to normal password field
    */
    function gcms_db_set_static_password($uid, $activationkey) {
        global $GCMS;
        
        $sql = "SELECT * FROM " . TBL_USERS . " WHERE id = " . (int)$uid . " AND actkey = '" . addslashes(htmlspecialchars($activationkey)) . "';";
        if(!($result = mysql_query($sql, $GCMS['DB']['LINK']))) {
            message_die(GENERAL_ERROR, 'Could not find user by activation key', '', __LINE__, __FILE__, $sql);
        }

        $GCMS['DB']['COUNTER']++;
        
        $result = mysql_fetch_array($result, MYSQL_ASSOC);
        @mysql_free_result($result);
        
        if($result) {
            $sql = "UPDATE " . TBL_USERS . " SET passwd = '" . $result['newpasswd'] . "', actkey = 0, newpasswd = NULL WHERE id = " . $result['id'] . ";";
            if(!($result = mysql_query($sql, $GCMS['DB']['LINK']))) {
                message_die(GENERAL_ERROR, 'Could not update new static password information', '', __LINE__, __FILE__, $sql);
            }

            $GCMS['DB']['COUNTER']++;
            
            @mysql_free_result($result);

            return true;
        }

        return false;
    }
?>
