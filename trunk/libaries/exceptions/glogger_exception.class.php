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
    * include base exception
    */
    require_once(LIB_PATH . 'exceptions/gexception.class.php');


    /**
    * this class extends the base exception class
    *
    * @access  public
    * @author  GDev Team <devel@ghcif.de>
    * @since   04/09/2005
    * @version $Revision: $
    */
    class glogger_exception extends gexception {
        /**
        * critical level
        *
        * @access protected
        * @var    int
        */
        protected $critical = 0;


        /**
        * logging level
        *
        * @access protected
        * @var    int
        */
        protected $logging = 0;


        /**
        * own error message
        *
        * @access public
        * @author GDev Team <devel@ghcif.de>
        * @since  24/08/2005
        * @return false
        */
        public function custom_message() {
            return false;
        }
    } /* end of class glogger_exception */
?>
