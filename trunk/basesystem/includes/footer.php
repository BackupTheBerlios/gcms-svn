<?php
	/** 
	* $Id: $
	* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
	*
	* This file is part of GCMS
	*
	* @author   GCMS Development Team <devel@ghcif.de>
	* @since    2005/03/28
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
	* define template file
	*/
	$GCMS['TEMPLATE']->set_filenames(array(
		'footer' => 'base/footer.tpl'
	));


	/**
	* get base data for footer
	*/
	$gcms_copyright = $GCMS['CONFIG']['gcms_copyright'];
	$gcms_version = $GCMS['CONFIG']['gcms_version'];
	$db_query = $GCMS['DB']['COUNTER'] . ' ' . $GCMS['LANG']['queries'];


	/**
	* get generation time
	*/
	$mtime = microtime();
	$mtime = explode(" ",$mtime);
	$mtime = $mtime[1] + $mtime[0];
	$GCMS['GENTIME']['endtime'] = $mtime;
	$tmp_gentime = $GCMS['LANG']['generated_in'] . ' ' . round(($GCMS['GENTIME']['endtime'] - $GCMS['GENTIME']['starttime']), 3) . 's';


	/**
	* asign template vars
	*/
	$GCMS['TEMPLATE']->assign_vars(array( 	
		'IMPRINT' => '<a href="' . GCMS_RELATIVE_PATH . '/index.php?module=imprint">impressum</a>',
		'COPYRIGHT' => $gcms_copyright,
		'GCMS_VERSION' => $gcms_version,
		'DBQUERY' => $db_query,
		'GENTIME' => $tmp_gentime
	));


																				    
	/**
	* parse header template
	*/
	$GCMS['TEMPLATE']->pparse('footer'); 


	/**
	* close db connection
	*/
	gcms_db_close();

	
	/**
	* end caching and put out the content
	*/
	$content = ob_get_contents();
	ob_end_clean();

	echo($content);
?>
