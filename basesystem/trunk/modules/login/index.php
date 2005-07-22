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
	$module_title = '<span class="verysmallsize">::</span> login';


	/**
	* base plugin switch
	*/
	if(isset($GCMS['SUBMODULE']) AND is_array($GCMS['SUBMODULE'])) {
		if(file_exists(GCMS_MODULE_PATH . '/plugins/' . $GCMS['SUBMODULE']['name'] . '.php')) {
			require_once(GCMS_MODULE_PATH . '/plugins/' . $GCMS['SUBMODULE']['name'] . '.php');
		} else {
            /**
            * TODO:
            * - error handling
            */
			die('failed to include submodule');
		}
	} else {
		require_once(GCMS_MODULE_PATH . '/plugins/index.php');
	}
?>
