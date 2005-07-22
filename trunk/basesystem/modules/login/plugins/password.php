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
    *
    * TODO:
    * - rewrite emailer functions
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
	$module_title .= ' <span class="titleseperator">' . $GCMS['CONFIG']['title_separator'] . '</span> ' . $GCMS['LANG']['LOGIN']['forget_password'];


    /**
    * define session vars if not done
    */
    if(!isset($_SESSION['gcmsuser'])) {
        $_SESSION['gcmsuser'] = '';
    }
    if(!isset($_SESSION['gcmsemail'])) {
        $_SESSION['gcmsemail'] = '';
    }

    
    /**
    * process form
    */
    if(isset($_POST['submit'])) {
        if(!isset($_POST['gcmsuser'], $_POST['gcmsemail'])) {
            message_die(GENERAL_MESSAGE, 'use_the_forms', $module_title, '', '', '', GCMS_URL . '/index.php?module=login&amp;section=password');
        } else {
            $_SESSION['gcmsuser'] = $_POST['gcmsuser'];
            $_SESSION['gcmsemail'] = $_POST['gcmsemail'];

            if(trim(empty($_POST['gcmsuser'])) OR trim(empty($_POST['gcmsemail']))) {
                message_die(GENERAL_MESSAGE, 'set_username_email', $module_title, '', '', '', GCMS_URL . '/index.php?module=login&amp;section=password');
            } else {
                
                /**
                * get userdata
                */
                $user = gcms_db_get_user_by_name_email($_POST['gcmsuser'], $_POST['gcmsemail']);        

                if(isset($user) AND is_array($user)) {
                    if(!$user['active']) {
                        message_die(GENERAL_MESSAGE, 'no_send_account_inactive', $module_title, '', '', '', GCMS_URL . '/index.php?module=login&amp;section=password');
                    }

                    /**
                    * generate new password and activation key
                    */
                    $password = gcms_generate_string($GCMS['CONFIG']['LOGIN']['gen_password_length']);
                    $actkey = gcms_generate_string($GCMS['CONFIG']['LOGIN']['activation_key_length']);
                    
                    /**
                    * write key and password to db
                    */
                    gcms_db_set_temp_password($user['id'], $password, $actkey);

                    
                    /**
                    * send email to the user
                    */
                    require_once(GCMS_REAL_PATH . '/libaries/emailer.class.php');
                    $emailer = new gcms_emailer();

                    $emailer->from($GCMS['CONFIG']['gcms_email']);
                    $emailer->replyto($GCMS['CONFIG']['gcms_email']);
                    
                    $emailer->use_template('activate_password', $user['lang'], 'login');
                    
                    $emailer->email_address($user['email']);

                    $emailsig = (!empty($GCMS['CONFIG']['email_signature'])) ? (str_replace('<br />', "\n", $GCMS['CONFIG']['email_signature'])) : ('');

                    $emailer->assign_vars(array(
                        'SITENAME' => $GCMS['CONFIG']['gcms_name'],
                        'USERNAME' => $user['username'],
                        'PASSWORD' => $password,
                        'EMAIL_SIG' => "-- \n" . $emailsig,
                        'U_ACTIVATE' => GCMS_URL . '/index.php?module=login&section=activate&uid=' . $user['id'] . '&actkey=' . $actkey)
                    );

                    if($emailer->send()) {
                        message_die(GENERAL_MESSAGE, 'new_pw_is_send', $module_title, '', '', '', GCMS_URL . '/index.php?module=login');
                    } else {
                        message_die(GENERAL_MESSAGE, 'failed_to_send_emaildata', $module_title, '', '', '', GCMS_URL . '/index.php?module=login&amp;section=password');
                    }

                    $emailer->reset();
                } else {
                    message_die(GENERAL_MESSAGE, 'unknown_user', $module_title, '', '', '', GCMS_URL . '/index.php?module=login&amp;section=password');
                }
            }
        }
    }


    /**
    * check if user is not logged in
    */
    if($_SESSION['id'] == GUEST) {
        /**
        * set template filenames
        */
        $GCMS['TEMPLATE']->set_filenames(array(
            'login_password' => 'login/index.tpl'
        ));


        /**
        * generate loginform
        */
        require_once(GCMS_REAL_PATH . '/libaries/inputform.class.php');

        $frm =& new gcms_inputform(append_sid(GCMS_RELATIVE_PATH . '/index.php?module=login&amp;section=password'), "post", $GCMS['LANG']['LOGIN']['submit'], '', '', '', '', 'class="gbutton" ');

        $frm->addrow($GCMS['LANG']['LOGIN']['name'], $frm->text_box('gcmsuser', $_SESSION['gcmsuser'], 50, 50, false, 'class="gfield "'));
        $frm->addrow($GCMS['LANG']['LOGIN']['email'], $frm->text_box('gcmsemail', $_SESSION['gcmsemail'], 50, 50, false, 'class="gfield "'));
        $passwordform = $frm->show(true);


        /** 
        * assign some template vars
        */
        $GCMS['TEMPLATE']->assign_vars(array(
            'MOD_TITLE' => $module_title,
            'LOGINFORM' => $passwordform
        ));
        
        unset($passwordform);
        

        /**                                                                                                                                                 
        * parse login index template    
        */          
        $GCMS['TEMPLATE']->pparse('login_password');
	} else {
        message_die(GENERAL_MESSAGE, 'allready_logged_in', $module_title, '', '', '', GCMS_URL . '/index.php?module=' . $GCMS['CONFIG']['default_module']);
	}
?>