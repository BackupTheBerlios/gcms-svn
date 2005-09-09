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


    if (0 > version_compare(PHP_VERSION, '5')) {
        die('This file was generated for PHP 5');
    }


    /**
    * define absolute libaries path
    */
    if(!defined('LIB_PATH')) {
        define('LIB_PATH', dirname(__FILE__) . '/');
    }


    /**
    * include exception
    */
    require_once(LIB_PATH . 'exceptions/glanguage_exception.class.php');


    /**
    * this class handles as base class for everything related to language data
    *
    * @access  public
    * @author  GDev Team <devel@ghcif.de>
    * @since   23/08/2005
    * @version $Revision: $
    */
    class glanguage {
        /**
        * instance holder
        *
        * @access protected
        * @var    object
        */
        private static $ginstance = null;


        /**
        * get instance of the object
        *
        * @access public
        * @author GDev Team <devel@ghcif.de>
        * @since  23/08/2005
        * @return mixed
        */
        public static function get_instance() {
            if(self::instance != null) {
                return self::instance;
            } else {
                throw new glanguage_exception('Call factory method first time');
            }
        }


        /**
        * create object first time
        *
        * @access public
        * @author GDev Team <devel@ghcif.de>
        * @since  23/08/2005
        * @return mixed
        */
        public static function factory() {
            return false;
        }
    } /* end of class glanguage */
?>
