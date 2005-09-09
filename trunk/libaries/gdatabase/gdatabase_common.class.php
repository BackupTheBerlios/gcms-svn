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
    * gdatabase common class to set options and so on
    *
    * @access  public
    * @author  GDev Team <devel@ghcif.de>
    * @since   23/08/2005
    * @version $Revision: $
    */
    class gdatabase_common {
        /**
        * database connection id
        *
        * @access protected
        * @var    int
        */
        protected $connid = 0;
        
            
        /**
        * options
        *
        * <code>
        *  most things of it not implemented yet
        * </code>
        *
        * @access protected
        * @var    array
        */
        protected $options = array(
            'persistent' => false,
            'ssl' => false,
            'debug' => 0,
            'autofree' => false 
        );


        /**
        * set option
        *
        * @access public
        * @author GDev Team <devel@ghcif.de>
        * @since  23/08/2005
        * @param  string  $option  option key
        * @param  string  $value  option value
        * @return mixed  database object or exception
        */
        public function set_option($option, $value) {
            if(isset($this->options[$option])) {
                $this->options[$option] = $value;
            }
            
            throw new gdatabase_exception('unknown option' . $option);
        }


        /**
        * get option
        *
        * @access public
        * @author GDev Team <devel@ghcif.de>
        * @since  23/08/2005
        * @param  string  $option  option key
        * @return mixed  database opbject or exception
        */
        public function get_option($option) {
            if(isset($this->options[$option])) {
                return $this->options[$option];
            }
            
            throw new gdatabase_exception('unknown option' . $option);
        }        
    } /* end of class gdatabase */
?>
