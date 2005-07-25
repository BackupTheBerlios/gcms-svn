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
	* base constants
	*/
	define('IN_GCMS', 1);
    
    define('GCMS_REAL_PATH', dirname(__FILE__));
	define('GCMS_RELATIVE_PATH', dirname($_SERVER['PHP_SELF']));
	
	define('GCMS_URL', 'http' . (($_SERVER['HTTPS'] == 'on') ? ('s') : ('')) . '://' . $_SERVER['SERVER_NAME'] . GCMS_RELATIVE_PATH);


	/**
	* core include
	*/
	require_once(GCMS_REAL_PATH . '/includes/core.php');


	/**
	* include root module
	*/
    load_root_module();


    /**
    * footer include
    */
    include(GCMS_REAL_PATH . '/includes/footer.php');
?>
