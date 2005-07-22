<?php
	/** 
	* $Id: $
	* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
	*
	* This file is part of GCMS
	* 
	* @author   GCMS Development Team <devel@ghcif.de>
	* @since    2005/05/27
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


	/**                                                                                                                                                 
	* define module title
	*/
	$module_title .= ' <span class="titleseperator">' . $GCMS['CONFIG']['title_separator'] . '</span> ' . $GCMS['LANG']['NEWS']['delete'];
	

	/**
	* check authorisation
	*/
	if(in_array("Admin", $rights) OR in_array("News", $rights)) {
		if(isset($_REQUEST['id'])) {			
			if(@preg_match('/^[a-fA-F\d]{10}$/', $_REQUEST['id'])) {
				/**
				* process form else put it out
				*/
				if(isset($_POST['submit'])) {
					if(isset($_POST['deltrue']) AND $_POST['deltrue'] == 'true') {
						/**
						* delete defined posts
						*/
						$sql = "DELETE FROM ghcif_news
								WHERE cryptid = '".$_SESSION['newsid']."';";
						mysql_query($sql) OR die(mysql_error());
						$sql = "DELETE FROM ghcif_news_comments
								WHERE newsid = '" . $_SESSION['newsid'] . "';";
						mysql_query($sql) OR die(mysql_error());
						$sql = "DELETE FROM ghcif_news_comments
								WHERE cryptid = '" . $_SESSION['newsid'] . "';";
						mysql_query($sql) OR die(mysql_error());
						
						
						/**
						* unset temporary session var
						*/
						unset($_SESSION['newsid']);
								
						
						/**                                                                 
						* assign message vars       
						*/
						$template->assign_vars(array(
							'MOD_TITLE' => $module_title,
							'MESSAGE_TEXT' => 'beitrag geloescht<br /><br /><a href="' . append_sid(RELATIVE_PATH . '/index.php?module=news') . '">zurueck</a>'
						)); 


						/**                         
						* parse message template    
						*/       
						$template->pparse('message');
					} else {
						/**                                                                 
						* assign message vars       
						*/
						$template->assign_vars(array(
							'MOD_TITLE' => $module_title,
							'MESSAGE_TEXT' => 'du musst das loeschen bestaetigen<br /><br /><a href="' . append_sid(RELATIVE_PATH . '/index.php?module=news&amp;section=deletepost&amp;id=' . $_SESSION['newsid']) . '">zurueck</a>'
						));  

	
						/**                         
						* parse message template    
						*/       
						$template->pparse('message');
					}
				} else {
					/**     
					* set newsid to session
					*/      
					if(isset($_GET['id'])) {
						$_SESSION['newsid'] = $_GET['id'];
					} 

							
					/**
					* put out the form template
					*/
					$template->assign_block_vars('switch_confirmation', array());
							
							
					/**
					* assign form vars
					*/
					$template->assign_vars(array(
						'MOD_TITLE' => $module_title,
						'FORM_ACTION' => append_sid(RELATIVE_PATH . '/index.php?module=news&amp;section=deletepost&amp;id=' . $_SESSION['newsid']),
			
						'LINK_BACK' => append_sid(RELATIVE_PATH . '/index.php?module=news&amp;section=comments&amp;id=' . $_SESSION['newsid'])
					));


					/**
					* parse post form template
					*/
					$template->assign_var_from_handle('NEWS_FORM', 'post_form');


					/**
					* parse news index template                                                                                                      
					*/                                                                                                                               
					$template->pparse('news_work');
				}
			} else {
				/**                                                                 
				* assign message vars       
				*/
				$template->assign_vars(array(
					'MOD_TITLE' => $module_title,
					'MESSAGE_TEXT' => 'ungueltige id<br /><br /><a href="' . append_sid(RELATIVE_PATH . '/index.php?module=news') . '">zurueck</a>'
				)); 


				/**                         
				* parse message template    
				*/       
				$template->pparse('message');
			}
		} else {
			/**                                                                 
			* assign message vars       
			*/
			$template->assign_vars(array(
				'MOD_TITLE' => $module_title,
				'MESSAGE_TEXT' => 'keine id<br /><br /><a href="' . append_sid(RELATIVE_PATH . '/index.php?module=news') . '">zurueck</a>'
			));


			/**                         
			* parse message template    
			*/       
			$template->pparse('message');
		}
	} else {
		/**                                                                 
		* assign message vars       
		*/
		$template->assign_vars(array(                       
			'MOD_TITLE' => $module_title,                               
			'MESSAGE_TEXT' => 'keine berechtigung<br /><br /><a href="' . append_sid(RELATIVE_PATH . '/index.php?module=news') . '">zurueck</a>'
		)); 


		/**                         
		* parse message template    
		*/
		$template->pparse('message');
	}
?>
