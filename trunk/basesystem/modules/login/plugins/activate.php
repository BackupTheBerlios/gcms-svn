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
	*/
	
    
    /**
    * general check
    */
    if(!defined('IN_GCMS')) {
        die('access denied');
    }

    
	/**
	* define module title
	*/
	$module_title .= ' <span class="titleseperator">' . $GCMS['CONFIG']['title_separator'] . '</span> ' . $GCMS['LANG']['LOGIN']['activate'];


    /**
    * check if user is not logged in
    */
    if($_SESSION['id'] == GUEST) {
        if(isset($_GET['uid']) AND is_numeric($_GET['uid'])) {
            if(isset($_GET['actkey']) AND @preg_match('/^[a-fA-F\d]{' . $GCMS['CONFIG']['LOGIN']['activation_key_length'] . '}$/', $_GET['actkey'])) {
                /**
                * every check is ok so update the db
                */
                if(gcms_db_set_static_password($_GET['uid'], $_GET['actkey'])) {
                    message_die(GENERAL_MESSAGE, 'account_is_activated', $module_title, '', '', '', GCMS_URL . '/index.php?module=login');
                } else {
                    message_die(GENERAL_MESSAGE, 'failed_to_activate', $module_title, '', '', '', GCMS_URL . '/index.php?module=login');
                }
            } else {
                message_die(GENERAL_MESSAGE, 'bad_activation_key', $module_title, '', '', '', GCMS_URL . '/index.php?module=login');
            }
        } else {
            message_die(GENERAL_MESSAGE, 'bad_activation_uid', $module_title, '', '', '', GCMS_URL . '/index.php?module=login');
        }
    } else {
        message_die(GENERAL_MESSAGE, 'allready_logged_in', $module_title, '', '', '', GCMS_URL . '/index.php?module=' . $GCMS['CONFIG']['default_module']);
	}
?>
