<?php
	/** 
	* $Id: $
	* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
	*
	* This file is part of GCMS
	*
	* @author   GCMS Development Team <devel@ghcif.de>
	* @since    2005/07/12
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
	*/


    /**
    * general check
    */
    if(!defined('IN_GCMS')) {
        die('access denied');
    }
    
    $lang['login'] = 'login';
    $lang['logout'] = 'logout';

    $lang['register'] = 'registrieren';
    $lang['forget_password'] = 'passwort vergessen';
    $lang['password'] = 'passwort vergessen';

    $lang['activate'] = 'aktivieren';

    $lang['name'] = 'username';
    $lang['password'] = 'passwort';
    $lang['email'] = 'email';
    $lang['submit'] = 'senden';

    $lang['set_username_email'] = 'Geben sie ihren Usernamen und ihre eMail Adresse ein';
    $lang['no_send_account_inactive'] = 'Sie muessen ihren Account erst aktivieren um ein neues Passwort anfordern zu koennen';
    $lang['unknown_user'] = 'Sie haben einen unbekannten Usernamen angegeben';
    $lang['new_pw_is_send'] = 'Es wurde ein neues Passwort an ihre eMail Adresse gesendet, sie muessen dies jedoch erst bestaetigen';
    $lang['failed_to_send_emaildata'] = 'Es konnte kein neues Passwort uebertragen werden, bitte versuchen sie es spaeter nocheinmal';
    $lang['new_password_activation'] = 'Aktivierung des neuen Passwortes';
    
    $lang['logged_in'] = 'Sie wurden erfolgreich eingeloggt';
    $lang['set_username_password'] = 'Geben sie ihren Usernamen und ihr Passwort ein';
    $lang['user_password_dismatch'] = 'Ihr Username oder Passwort ist falsch';
    $lang['allready_logged_in'] = 'Sie sind bereits eingeloggt';

    $lang['logged_out'] = 'Sie sind nun ausgeloggt';
    $lang['not_logged_in'] = 'Sie sind nicht eingeloggt';
?>
