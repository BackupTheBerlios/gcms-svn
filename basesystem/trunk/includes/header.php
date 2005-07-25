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
	* define header template file
	*/
	$GCMS['TEMPLATE']->set_filenames(array(
		'header' => 'base/header.tpl',
	));


    /**
    * load navigation language file
    */
    load_language('navigation');


	/**
	* load root module data from db
	*/
	if(isset($_REQUEST['module']) AND trim(!empty($_REQUEST['module']))) {
		/**
        * write rootmodule data to var
        */
        $GCMS['ROOTMODULE'] = gcms_db_get_cat_by_name($_REQUEST['module']);
        $GCMS['ROOTMODULE']['AUTH'] = (is_array($GCMS['ROOTMODULE'])) ? (gcms_db_get_cat_auth($GCMS['ROOTMODULE']['id'])) : (false);
        
		
		/**     
		* if module does not exist redirect to default module
		*/
		if(!$GCMS['ROOTMODULE']['AUTH']) {
			header("Location: " . append_sid(GCMS_URL . "/index.php?module=" . $GCMS['CONFIG']['default_module']));
			exit;
		}

		
        /**
		* define root page title
		*/
		$main_title = (isset($GCMS['LANG'][$GCMS['ROOTMODULE']['title']])) ? ($GCMS['LANG'][$GCMS['ROOTMODULE']['title']]) : ('_' . $GCMS['ROOTMODULE']['title']);
		$sub_title = '';


		/**
		* load sub module data from db
		*/
		if(isset($_REQUEST['section']) AND trim(!empty($_REQUEST['section']))) {
            /**
            * write rootmodule data to var
            */
            $GCMS['SUBMODULE'] = gcms_db_get_cat_by_name($_REQUEST['section'], $GCMS['ROOTMODULE']['id']);
            $GCMS['SUBMODULE']['AUTH'] = (is_array($GCMS['SUBMODULE'])) ? (gcms_db_get_cat_auth($GCMS['SUBMODULE']['id'])) : (false);
            
            
            /**         
            * if module does not exist redirect to root module
            */
            if(!$GCMS['SUBMODULE']['AUTH']) {
                header("Location: " . append_sid(GCMS_URL . "/index.php?module=" . $GCMS['ROOTMODULE']['name']));
                exit;
            }


			/**
			* define sub page title
			*/
			$sub_title = (isset($GCMS['LANG'][$GCMS['SUBMODULE']['title']])) ? ($GCMS['LANG'][$GCMS['SUBMODULE']['title']]) : ('_' . $GCMS['SUBMODULE']['title']);
		}
	} else {
		/**
		* if there is no module set redirect to default module
		*/
		header("Location: " . append_sid(GCMS_URL . "/index.php?module=" . $GCMS['CONFIG']['default_module'])); 
		exit;
	}


    /**
    * load navigation data
    */
    $navioriginal = gcms_db_get_navigation();

	
    /**
    * generate navigation menu
    */
	$mainnavi  = '<ul id="menu">' . "\n";
	for($i = 0; $i < $navioriginal['counter']; $i++) {
		$navitext = (isset($GCMS['LANG'][$navioriginal['data'][$i]['title']])) ? ($GCMS['LANG'][$navioriginal['data'][$i]['title']]) : ('_' . $navioriginal['data'][$i]['title']);
	
		if(($navioriginal['data'][$i]['id'] == $GCMS['ROOTMODULE']['id']) OR (isset($GCMS['SUBMODULE']) AND $navioriginal['data'][$i]['id'] == $GCMS['SUBMODULE']['id'])) {
			$mainnavi .= '<li><a id="current" href="' . append_sid(GCMS_RELATIVE_PATH . '/' . $navioriginal['data'][$i]['link']) . '">' . $navitext . '</a></li>' . "\n";
		} elseif($i == $navioriginal['counter']) {
			$mainnavi .= '<li id="last"><a href="' . append_sid(GCMS_RELATIVE_PATH . '/' . $navioriginal['data'][$i]['link']) . '">' . $navitext . '</a></li>' . "\n";
		} else {                            
			$mainnavi .= '<li><a href="' . append_sid(GCMS_RELATIVE_PATH . '/' . $navioriginal['data'][$i]['link']) . '">' . $navitext . '</a></li>' . "\n";
		}
	}
	$mainnavi .= '</ul>';
    
    unset($navioriginal);


    /**
    * load sub navigation data
    */
    $navioriginal = gcms_db_get_navigation($GCMS['ROOTMODULE']['id']);


    /**
    * generate sub navigation menu
    */
	if($navioriginal['counter'] > 0) {
		$subnavi  = '<ul id="submenu">' . "\n";
		for($i = 0; $i < $navioriginal['counter']; $i++) {
			$navitext = (isset($GCMS['LANG'][$navioriginal['data'][$i]['title']])) ? ($GCMS['LANG'][$navioriginal['data'][$i]['title']]) : ('_' . $navioriginal['data'][$i]['title']);
			
			if(($navioriginal['data'][$i]['id'] == $GCMS['ROOTMODULE']['id']) OR (isset($GCMS['SUBMODULE']) AND $navioriginal['data'][$i]['id'] == $GCMS['SUBMODULE']['id'])) {
				$subnavi .= '<li><a id="subcurrent" href="' . append_sid(GCMS_RELATIVE_PATH . '/' . $navioriginal['data'][$i]['link']) . '">' . $navitext . '</a></li>' . "\n";
			} elseif($i == $navioriginal['counter']) {
				$subnavi .= '<li id="sublast"><a href="' . append_sid(GCMS_RELATIVE_PATH . '/' . $navioriginal['data'][$i]['link']) . '">' . $navitext . '</a></li>' . "\n";
			} else {
				$subnavi .= '<li><a href="' . append_sid(GCMS_RELATIVE_PATH . '/' . $navioriginal['data'][$i]['link']) . '">' . $navitext . '</a></li>' . "\n";
			}
		}
		$subnavi .= '</ul>';
	} else {
		$subnavi = '';
	}
    unset($navioriginal);

	
    /**
	* full page title
	*/
	$page_title = ($sub_title != '') ? ($main_title . ' - ' . $sub_title) : ($main_title);
	

	/**
	* asign template vars
	*/
	$GCMS['TEMPLATE']->assign_vars(array( 
		'GCMS_TITLE' => $GCMS['CONFIG']['gcms_name'],
		'PAGE_TITLE' => $page_title,
		'CHARSET' => $GCMS['LANG']['charset'],

        'METADATA' => load_metadata(),
		
		'TEMPLATE_DIR' => GCMS_RELATIVE_TPLPATH,
		'BASE_DIR' => GCMS_RELATIVE_PATH,

		'MAIN_NAVI' => $mainnavi,
		'SUB_NAVI' => $subnavi
	));


    /**
	* parse header template
	*/
	$GCMS['TEMPLATE']->pparse('header');


    /**
    * define constant that header is allready included
    */
	define('HEADER_INC', '1');
?>
