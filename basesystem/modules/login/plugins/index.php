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
    * general check
    */
    if(!defined('IN_GCMS')) {
        die('access denied');
    }

    
	/**
	* define module title
	*/
	$module_title .= '';


    /**
    * define session vars if not done
    */
    if(!isset($_SESSION['gcmsuser'])) {
        $_SESSION['gcmsuser'] = '';
    }
    
    if(!isset($_SESSION['gcmspass'])) {
        $_SESSION['gcmspass'] = '';
    }


    /**
	* process form
	*/
	if(isset($_POST['submit'])) {
        if(!isset($_POST['gcmsuser'], $_POST['gcmspass'])) {
			message_die(GENERAL_MESSAGE, 'use_the_forms', $module_title, '', '', '', GCMS_URL . '/index.php?module=login');
        } else {
            $_SESSION['gcmsuser'] = $_POST['gcmsuser'];
			$_SESSION['gcmspass'] = $_POST['gcmspass'];
		
			if(trim(empty($_POST['gcmsuser'])) OR trim(empty($_POST['gcmspass']))) {
                message_die(GENERAL_MESSAGE, 'set_username_password', $module_title, '', '', '', GCMS_URL . '/index.php?module=login');				
            } else {
				if($userlogin = gcms_db_login_right($_SESSION['gcmsuser'], $_SESSION['gcmspass'])) {
					$_SESSION['id'] = $userlogin;
                   
                    /**
					* reload userdata cause new id is set
					*/
					load_userdata();

					/**
					* unset temporary vars
					*/
					unset($_SESSION['gcmsuser'], $_SESSION['gcmspass']);
		
                    message_die(GENERAL_MESSAGE, 'logged_in', $module_title, '', '', '', GCMS_URL . '/index.php?module=' . $GCMS['CONFIG']['default_module']);
				} else {
                    message_die(GENERAL_MESSAGE, 'user_password_dismatch', $module_title, '', '', '', GCMS_URL . '/index.php?module=login');
                }
            }
        }
    }	
    

    /**
    * if its guest show form else redirect
    */
    if($_SESSION['id'] == GUEST) {
        /**
        * set template filenames
        */
        $GCMS['TEMPLATE']->set_filenames(array(
            'login_index' => 'login/index.tpl'
        ));
        

        /**
        * generate loginform
        */
        require_once(GCMS_REAL_PATH . '/libaries/inputform.class.php');        
        
        $frm =& new gcms_inputform(append_sid(GCMS_RELATIVE_PATH . '/index.php?module=login'), "post", $GCMS['LANG']['LOGIN']['submit'], '', '', '', '', 'class="gbutton" ');
        
        $frm->addrow($GCMS['LANG']['LOGIN']['name'], $frm->text_box('gcmsuser', $_SESSION['gcmsuser'], 50, 50, false, 'class="gfield "'));
        $frm->addrow($GCMS['LANG']['LOGIN']['password'], $frm->text_box('gcmspass', $_SESSION['gcmspass'], 50, 50, true, 'class="gfield "'));

        $frm->addmessage('<a href="' . append_sid(GCMS_RELATIVE_PATH . '/index.php?module=profile&amp;section=register') . '">' . $GCMS['LANG']['LOGIN']['register'] . '</a> <span class="titleseperator">' . $GCMS['CONFIG']['title_separator'] . '</span> <a href="' . append_sid(GCMS_RELATIVE_PATH . '/index.php?module=login&amp;section=password') . '">' . $GCMS['LANG']['LOGIN']['forget_password'] . '</a>');

        $loginform = $frm->show(true);
        
            
        /** 
		* assign some template vars
		*/
		$GCMS['TEMPLATE']->assign_vars(array(
			'MOD_TITLE' => $module_title,

            'LOGINFORM' => $loginform
	    ));                                                                                                                                                          
        
        unset($loginform);

       
        /**                                                                                                                                                 
        * parse login index template    
        */
        $GCMS['TEMPLATE']->pparse('login_index');
    } else {
        message_die(GENERAL_MESSAGE, 'allready_logged_in', $module_title, '', '', '', GCMS_URL . '/index.php?module=' . $GCMS['CONFIG']['default_module']);
    }
?>
