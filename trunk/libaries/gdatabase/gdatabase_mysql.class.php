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
    * include interface
    */
    require_once(LIB_PATH . 'interfaces/gdatabase.iface.php');
    
    
    /**
    * include common database class
    */
    require_once(LIB_PATH . 'gdatabase/gdatabase_common.class.php');


    /**
    * this class handles the mysql database connection
    *
    * @access  public
    * @author  GDev Team <devel@ghcif.de>
    * @since   04/09/2005
    * @version $Revision: $
    */
    class gdatabase_mysql extends gdatabase_common implements gdatabase_iface {
        /**
        * constructor
        *
        * @access public
        * @author GDev Team <devel@ghcif.de>
        * @since  24/08/2005
        * @return void
        */
        public function __construct() {

        }


        /**
        * destructor
        *
        * @access public
        * @author GDev Team <devel@ghcif.de>
        * @since  24/08/2005
        * @return void
        */
        public function __destruct() {

        }


        /**
        * select method
        *
        * @access public
        * @author GDev Team <devel@ghcif.de>
        * @since  24/08/2005
        * @return void
        */
        public function select() {

        }


        /**
        * insert method
        *
        * @access public
        * @author GDev Team <devel@ghcif.de>
        * @since  24/08/2005
        * @return void
        */
        public function insert() {

        }


        /**
        * update method
        *
        * @access public
        * @author GDev Team <devel@ghcif.de>
        * @since  24/08/2005
        * @return void
        */
        public function update() {

        }


        /**
        * delete method
        *
        * @access public
        * @author GDev Team <devel@ghcif.de>
        * @since  24/08/2005
        * @return void
        */
        public function delete() {

        }


        /**
        * escape string method
        *
        * @access public
        * @author GDev Team <devel@ghcif.de>
        * @since  24/08/2005
        * @return void
        */
        public function escape_string() {

        }
    } /* end of class gdatabase_mysql */
?>
