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
    * - rewrite to db wrapper, not class based
	*/


    /**
    * general check
    */
    if(!defined('IN_GCMS')) {
        die('access denied');
    }


	/**
	* error reporting
	*/
	error_reporting(E_ALL);


	/**
	* base arrays
	*/
	$GCMS = array();
	

	/**
	* generation time
	*/
	$mtime = microtime();
	$mtime = explode(" ",$mtime);
	$mtime = $mtime[1] + $mtime[0];
	$GCMS['GENTIME']['starttime'] = $mtime;
	unset($mtime);


	/**
	* start caching
	*/
	ob_start();


	/**
	* start session   
	*/     
	session_start();
    session_name('sid');


	/**
	* base includes
	*/
	require_once(GCMS_REAL_PATH . '/config.php');
	require_once(GCMS_REAL_PATH . '/includes/constants.php');
	require_once(GCMS_REAL_PATH . '/libaries/functions.php');


	/**
	* include db wrapper 
	*/
	if(file_exists(GCMS_REAL_PATH . '/libaries/db/' . $GCMS['DB']['DATA']['TYPE'] . '.php')) {
		require_once(GCMS_REAL_PATH . '/libaries/db/' . $GCMS['DB']['DATA']['TYPE'] . '.php');

        /**
        * connect to the db
        */
		gcms_db_connect();
	} else {
		/**
		* TODO:
		* - error handling
		*/
		die('failed to load base db wrapper');
	}
	
	
	/**
	* strip slashes if magic quotes is activated
	*/
	if(get_magic_quotes_gpc()) {
		array_stripslashes($_GET);
		array_stripslashes($_POST);
		array_stripslashes($_COOKIE);
	}


	/**
	* basic load functions
	*/
	load_config();
    load_userdata();
    load_language();
    load_template();
    

    /**
    * header include
    */
    require_once(GCMS_REAL_PATH . '/includes/header.php');
?>
