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
    * check php version
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
    require_once(LIB_PATH . 'exceptions/gdatabase_exception.class.php');


    /**
    * this class handles as base class for the database connection
    *
    * @access  public
    * @author  GDev Team <devel@ghcif.de>
    * @since   23/08/2005
    * @version $Revision: $
    */
    class gdatabase {
        /**
        * instance holder
        *
        * @access private
        * @var    object
        */
        private static $ginstance = null;


        /**
        * get instance of the object
        *
        * @access public
        * @author GDev Team <devel@ghcif.de>
        * @since  23/08/2005
        * @return mixed  database opbject or exception
        */
        public static function get_instance() {
            if(self::instance != null) {
                return self::instance;
            } else {
                throw new gdatabase_exception('call factory method first time');
            }
        }


        /**
        * create object first time
        *
        * @access public
        * @author GDev Team <devel@ghcif.de>
        * @since  23/08/2005
        * @param  mixed  $dsn  connection data as string or array
        * @param  array  $options  runtime options
        * @return mixed  database object or exception
        */
        public static function factory($dsn, $options = false) {
            /**
            * create options array
            */
            if(!is_array($options)) {
                $options = array('persistent' => $options);
            }

            /**
            * set the dsn array
            */
            try {
                $mydsn = self::parse_dsn($dsn);
            }

            catch(gdatabase_exception $e) {
                printf(
                    'database dsn: %s',
                    $e->custom_message()
                );
            }            
            
            /**
            * make dbtype lower case
            */
            $mydbtype = strtolower($mydsn['dbtype']);

            /**
            * include specific database class file
            */
            if(isset($options['debug']) AND $options['debug'] > 0) {
                require_once(LIB_PATH . 'gdatabase/gdatabase_' . $mydbtype . '.class.php');
            } else {
                @require_once(LIB_PATH . 'gdatabase/gdatabase_' . $mydbtype . '.class.php');
            }
            
            /**
            * define class name
            */
            $classname = 'gdatabase_' . $mydbtype;

            /**
            * if class does not exist throw new exception
            */
            if(!class_exists($classname)) {
                throw new gdatabase_exception('specific database class does not exist');
            }
            
            /**
            * create object of the specific database class
            */
            try {
                @$myobject = new $classname;
            }

            catch(gdatabase_exception $e) {
                printf(
                    'database %s: %s',
                    $mydbtype,
                    $e->custom_message()
                );
            }             
                
            /**
            * set the options
            */
            foreach($options as $option => $value) {
                try {
                    $myoptions = $myobject->set_option($option, $value);
                }

                catch(gdatabase_exception $e) {
                    printf(
                        'database options: %s',
                        $e->custom_message()
                    );
                }
            }
            
            /**
            * write object to instance holder
            */
            self::instance = $myobject
            
            /**
            * return the object
            */    
            return $myobject;        
        }

        
        /**
        * parse data source name
        *
        * <code>
        *  allowed types of dsn:
        *
        *
        *  dbtype://username:password@hostspec/database_name
        *
        *  array(
        *    'dbtype' => dbtype,
        *    'username' => username,
        *    'password' => password,
        *    'hostspec' => hostspec,
        *    'database' => database
        *  )
        * </code>        
        *
        * @access private
        * @author GDev Team <devel@ghcif.de>
        * @since  09/09/2005
        * @param  mixed  $dsn  connection data as string or array
        * @return mixed  parsed dns array or exception
        */
        private static function parse_dsn($dsn) {
            /**
            * define dsn array
            */
            $parsed = array(
                'dbtype' => false,
                'username' => false,
                'password' => false,
                'hostspec' => false,
                'database' => false
            );
            
            /**
            * if dsn is allready an array return it
            */
            if(is_array($dsn)) {
                $dsn = array_merge($parsed, $dsn);

                return $dsn;
            }

            /**
            * find and set the db type
            *
            * $dsn => dbtype://username:password@hostspec/database_name
            */
            if(($pos = strpos($dsn, '://')) !== false) {
                $str = substr($dsn, 0, $pos);
                $dsn = substr($dsn, $pos + 3);
                
                $parsed['dbtype'] = $str;
            } else {
                throw new gdatabase_exception('failed to locate database type');
            }

            /**
            * find and set username and password
            *
            * $dsn => username:password@hostspec/database_name
            */
            if(($at = strrpos($dsn,'@')) !== false) {
                $str = substr($dsn, 0, $at);
                $dsn = substr($dsn, $at + 1);
            
                if(($pos = strpos($str, ':')) !== false) {
                    $parsed['username'] = rawurldecode(substr($str, 0, $pos));
                    $parsed['password'] = rawurldecode(substr($str, $pos + 1));
                } else {
                    throw new gdatabase_exception('failed to locate username and password');
                }
            }
            
            /**
            * find and set hostspec and database
            *
            * $dsn => hostspec/database
            */
            if(strpos($dsn, '/') !== false) {
                list($host, $db) = explode('/', $dsn, 2);
                
                $parsed['hostspec'] = rawurldecode($host);
                $parsed['database'] = rawurldecode($db);
            } else {
                throw new gdatabase_exception('failed to locate hostname and database');
            }

            return $parsed;
        }        
    } /* end of class gdatabase */
?>
