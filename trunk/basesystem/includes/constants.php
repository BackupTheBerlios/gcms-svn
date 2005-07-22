<?php
	/** 
	* $Id$
	* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
	*
	* This file is part of GCMS
	*
	* @author   GCMS Development Team <devel@ghcif.de>
	* @since    2005/05/26
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
	* define guest constants
	*/
	define('GUEST', '1');


	/**
	* navigation constants
	*/
	define('NAVI_MAIN', 1);
	define('NAVI_SUB', 2);
	define('NAVI_FOOTER', 98);
	define('NAVI_MISC', 99);


    /**
    * error codes
    */
    define('GENERAL_MESSAGE', 200);
    define('GENERAL_ERROR', 202);
    define('CRITICAL_MESSAGE', 203);
    define('CRITICAL_ERROR', 204);
?>
