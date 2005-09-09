<?php
    /**
    * $Id: $
    * vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
    *
    * This file is part of GCMS
    *
    * @author  GDev Team <devel@ghcif.de>
    * @since   04/09/2005
    * @version $Revision: $
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
	* database data
	*/
    define('DB_DSN', 'mysql://username:password@hostspec/database_name');


    /**
    * database prefix
    */
    define('DB_PREFIX', 'gcms_');
    

    /**
    * debug level
    */
    define('DEBUG', 1);


    /**
    * ----- change the settings below the line only if you know what you do -----
    */
   

    /**
    * real path
    */
    define('REAL_PATH', dirname(__FILE__) . '/');


    /**
    * libaries path
    */
    define('LIB_PATH', REAL_PATH . 'libaries/');


    /**
    * includes path
    */
    define('INC_PATH', REAL_PATH . 'includes/');


    /**
    * relative path
    */
    define('RELATIVE_PATH',  (dirname($_SERVER['PHP_SELF']) == '/') ? (dirname($_SERVER['PHP_SELF'])) : (dirname($_SERVER['PHP_SELF']) . '/'));
                
    
    /**
    * images path
    */
    define('IMG_PATH', RELATIVE_PATH . 'images/');


    /**
    * styles path
    */
    define('CSS_PATH', RELATIVE_PATH . 'styles/');


    /** 
    * gcms url
    */
    define('GCMS_URL', 'http://' . $_SERVER['SERVER_NAME'] . RELATIVE_PATH);
?>
