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

    $config['debug'] = 1;

    $config['last_update'] = date("Y-m-d");

    $config['gcms_name'] = 'ghcif';
    
    $config['gcms_email'] = 'noreply@ghcif.de';
    $config['email_signature'] = 'GHCIF Administration Team';
    
    $config['gcms_version'] = '1.6.2';
    $config['gcms_copyright'] = '&copy; 1999-2005 by <a href="http://www.ghcif.de">ghcif</a>';

    $config['meta_description'] = 'German Security Page, get your self a little bit more informed and more secure';
    $config['meta_keywords'] = 'ghcif,security,linux,unix,windows,exploit,shellcode,code,c,vb,visual,sicherheit,basic,perl,python,coder,programmierer,entwickler,schwachstellen,cisco,news,german,hack,crack,illegal,force,exploit,pdf,operating,systems,knowlegde,anti,virus,trojaner,rat,dos,ddos,scan,bugs,forum,help,sourceforge,freshmeat,rpm,redhat,suse,debian,gentoo,sploit,0day,iss,spam,assassin,irc,smp,ftp,ssh,banner,fake,list,newsletter,irssi,mirc,dcc,id,t4c,mosez,berger,boerger';
    $config['meta_robots'] = 'index,follow';
    $config['meta_revisit-after'] = '1 week';
    $config['meta_author'] = 'GHCIF Community';
    $config['meta_publisher'] = 'GHCIF';
    $config['meta_copyright'] = '(C) 1999-2005 by GHCIF';
    $config['meta_language'] = 'de';
    $config['meta_date'] = $config['last_update'];
    $config['meta_audience'] = 'All';

    $config['default_template'] = 'ghcif';
    $config['overwrite_template'] = 1;

    $config['default_language'] = 'deutsch';
    $config['overwrite_language'] = 1;

    $config['default_module'] = 'news';

    $config['title_separator'] = '::';
?>
