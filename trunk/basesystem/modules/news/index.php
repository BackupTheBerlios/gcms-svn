<?php
    /** 
	* $Id: $
	* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
	*
	* This file is part of GCMS
	* 
	* @author   GCMS Development Team <devel@ghcif.de>
	* @since    2005/05/28
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
	$module_title = ' <span class="titleseperator">' . $GCMS['CONFIG']['title_separator'] . '</span> ' . $GCMS['LANG']['NEWS']['news'];
	
	
	/**
	* base plugin switch
	*/
	if(isset($GCMS['SUBMODULE']) AND is_array($GCMS['SUBMODULE'])) {
		if(file_exists(GCMS_MODULE_PATH . '/plugins/' . $GCMS['SUBMODULE']['name'] . '.php')) {
			require_once(GCMS_MODULE_PATH . '/plugins/' . $GCMS['SUBMODULE']['name'] . '.php');                                                         
		} else {                                                                                                                                        
			message_die(GENERAL_ERROR, 'failed_load_submodule');
		}
	} else {
		header("Location: " . GCMS_URL . "/index.php?module=news&section=index");
		exit;
	}
?>
