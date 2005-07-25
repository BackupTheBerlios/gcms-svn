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
	* define module title
	*/
	$module_title = '<span class="titleseperator">' . $GCMS['CONFIG']['title_separator'] . '</span> ' . $GCMS['LANG']['LOGIN']['logout'];


    /**
    * check if user is not logged in
    */
    if($_SESSION['id'] > GUEST) {
        /**
        * destroy session and restart it
        */
        session_destroy();
        
        session_start();

        $_SESSION['id'] = GUEST;

        message_die(GENERAL_MESSAGE, 'logged_out', $module_title, '', '', '', GCMS_URL . '/index.php?module=' . $GCMS['CONFIG']['default_module']);
	} else {
        message_die(GENERAL_MESSAGE, 'not_logged_in', $module_title, '', '', '', GCMS_URL . '/index.php?module=login');
	}
?>
