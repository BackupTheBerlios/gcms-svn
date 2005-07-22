<?php
	/** 
	* $Id: editpost.php,v 1.1.1.1 2005/06/05 21:58:35 templis Exp $
	* vim: set tabstop=4:
	*
	* This file is part of gcms
	*
	* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
	* @since    2005/05/27
	* @version  1.7.2
	*
	* Copyright (C) 1999-2005 by ghcif.de <devel@ghcif.de>
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
	* define module title
	*/
	$module_title .= ' <span style="font-size:8px;">::</span> bearbeiten';


	/**
	* check authorisation
	*/
	if(in_array("Admin", $rights) OR in_array("News", $rights)) {
		if(isset($_REQUEST['id']) /*OR isset($_SESSION['newsid'])*/ ) {
			if(@preg_match('/^[a-fA-F\d]{10}$/', $_REQUEST['id']) /*OR @preg_match('/^[a-fA-F\d]{10}$/', $_SESSION['newsid'])*/ ) {
				/**
				* define some temporary session vars
				*/
				if(!isset($_SESSION['section'])) { 
					$_SESSION['section'] = ""; 
				}
				if(!isset($_SESSION['title'])) { 
					$_SESSION['title'] = ""; 
				}
				if(!isset($_SESSION['text'])) { 
					$_SESSION['text'] = ""; 
				}
				if(!isset($_SESSION['source'])) { 
					$_SESSION['source'] = ""; 
				}
				if(!isset($_SESSION['description'])) { 
					$_SESSION['description'] = ""; 
				}
				

				/**
				* process submit else parse post template
				*/
				if(isset($_POST['submit'])) {
					if(!isset($_POST['section'], $_POST['title'], $_POST['text'], $_POST['source'])) {								
						/**                                                                 
						* assign message vars       
						*/
						$template->assign_vars(array(                       
							'MOD_TITLE' => $module_title,
							'MESSAGE_TEXT' => 'bitte nur vorgegebene formulare nutzen<br /><br /><a href="' . $comments_link . '">zurueck</a>'
						)); 


						/**                         
						* parse message template    
						*/
						$template->pparse('message');
					} else {                	
						$_SESSION['section'] = addslashes(trim($_POST['section']));
						$_SESSION['title'] = addslashes(trim($_POST['title']));
						$_SESSION['text'] = addslashes(trim($_POST['text']));
						$_SESSION['source'] = addslashes(trim($_POST['source']));
									
						
						/**
						* check the fields
						*/
						if(trim(empty($_POST['section'])) OR trim(empty($_POST['title'])) OR trim(empty($_POST['text']))) {
							/**                                                                 
							* assign message vars       
							*/
							$template->assign_vars(array(                       
								'MOD_TITLE' => $module_title,                                        
								'MESSAGE_TEXT' => 'bitte alle felder ausfuellen<br /><br /><a href="' . append_sid(RELATIVE_PATH . '/index.php?module=news&amp;section=editpost&amp;id=' . $_SESSION['newsid']) . '">zurueck</a>'
							)); 

							/**                         
							* parse message template    
							*/
							$template->pparse('message');
						} else {
							$sql = "UPDATE ghcif_news
									SET section = '".htmlspecialchars($_SESSION['section'])."', title = '".htmlspecialchars($_SESSION['title'])."', content = '".htmlspecialchars($_SESSION['text'])."', source = '".htmlspecialchars($_SESSION['source'])."'
									WHERE cryptid = '".$_SESSION['newsid']."';";
							mysql_query($sql) OR die(mysql_error());


							/**
							* unset session vars
							*/
							$tmp_id = $_SESSION['newsid'];
							unset($_SESSION['section'], $_SESSION['title'], $_SESSION['text'], $_SESSION['source'], $_SESSION['newsid']);
							
							
							/**                                                                 
							* assign message vars       
							*/
							$template->assign_vars(array(                       
								'MOD_TITLE' => $module_title,                                        
								'MESSAGE_TEXT' => 'news gespeichert<br /><br /><a href="' . append_sid(RELATIVE_PATH . '/index.php?module=news&amp;section=comments&amp;id=' . $tmp_id) . '">zurueck</a>'
							)); 


							/**                         
							* parse message template    
							*/
							$template->pparse('message');
						}                            
					}
				} else {
					/**
					* set newsid to session
					*/
					if(isset($_GET['id'])) {
						$_SESSION['newsid'] = $_GET['id'];
					}


					/**
					* get news row from db
					*/
					$news_select_sql = "SELECT *
										FROM ghcif_news
										WHERE cryptid = '".$_SESSION['newsid']."';";
					$news_select = mysql_query($news_select_sql) OR die(mysql_error());
					
					
					/**
					* get the news row in a loop
					*/
					while($row = mysql_fetch_array($news_select)) {
						/**
						* generate section options
						*/
						$section_options = '';
						foreach($bereicharray AS $key => $value) {
							$section_options .= '<option value="' . $value . '"';
							if(trim(!empty($row['section'])) AND $value == $row['section']) {
								$section_options .= ' selected="selected"';
							}
							$section_options .= '>' . $key . '</option>';
						}
					
					
						/**
						* put out the form template                                                                                                                                              */
						$template->assign_block_vars('switch_addposting', array());
						$template->assign_block_vars('switch_posttrue', array());
						$template->assign_block_vars('switch_addsource', array());
					

						/**
						* assign form vars
						*/
						$template->assign_vars(array(
							'MOD_TITLE' => $module_title,
							'FORM_ACTION' => append_sid(RELATIVE_PATH . '/index.php?module=news&amp;section=editpost&amp;id=' . $_SESSION['newsid']),
							
							'TEMP_SECTIONOPTIONS' => $section_options,
							'TEMP_TITLE' => stripslashes($row['title']),
							'TEMP_TEXT' => stripslashes($row['content']),
							'TEMP_SOURCE' => stripslashes($row['source']),

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
