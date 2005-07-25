<?php
	/** 
	* $Id$
	* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
	*
	* This file is part of GCMS
	*
	* @author   GCMS Development Team <devel@ghcif.de>
	* @since    2005/07/12
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


    // charset
    $lang['charset'] = 'iso-8859-15';

    // seiten links weiter und zurueck
    $lang['prev_page'] = '&laquo;';
    $lang['next_page'] = '&raquo;';

    // pagination und seitennummern
    $lang['go_to'] = 'gehe zu';
    $lang['page_of'] = 'seite %d von %d';

    // footer
    $lang['generated_in'] = 'erstellt in';
    $lang['queries'] = 'queries';

    // error messages
    $lang['information'] = 'information';
    $lang['critical_information'] = 'kritische information';
    $lang['general_error'] = 'allgemeiner fehler';
    $lang['critical_error'] = 'kritischer fehler';
    $lang['an_error_occured'] = 'ein fehler ist aufgetreten.';
    $lang['a_critical_error'] = 'ein kritischer fehler ist aufgetreten.';
    $lang['forward'] = '...weiter';

    // message wenn sub module in modules/module/index.php nicht geladen werden kann
    $lang['failed_load_submodule'] = 'Das Untermodul konnte nicht geladen werden';
    
    // generelle meldung wenn nicht alle form felder uebergeben wurden
    $lang['use_the_forms'] = 'Nutzen sie bitte nur die vorgegebenen Formulare';

    // emailer klasse
    $lang['no_email_template_set'] = 'Es ist kein eMail Template definiert';
    $lang['cant_find_email_template'] = 'eMail Template %d kann nicht gefunden werden';
    $lang['failed_open_email_template'] = 'eMail Template %d konnte nicht eingelesen werden';
    $lang['no_subject'] = 'Kein Betreff';
    $lang['failed_send_email'] = 'Es konnte keine eMail versendet werden, bitte kontaktieren sie den Administrator';
?>
