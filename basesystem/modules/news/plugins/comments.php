<?php
	/** 
	* $Id$
	* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
	*
	* This file is part of GCMS
	* 
	* @author   GCMS Development Team <devel@ghcif.de>
	* @since    2005/05/27
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
	$module_title .= ' <span class="titleseperator">' . $GCMS['CONFIG']['title_separator'] . '</span> ' . $GCMS['LANG']['NEWS']['comments'];










	
	if(isset($_REQUEST['id'])) {
		if(preg_match('/^[a-fA-F\d]{10}$/', $_REQUEST['id'])) {
			/**
			* define session vars if not done
			*/
			if(!isset($_SESSION['comment_name'])) { 
				$_SESSION['comment_name'] = ''; 
			}
			if(!isset($_SESSION['comment_email'])) { 
				$_SESSION['comment_email'] = ''; 
			}
			if(!isset($_SESSION['comment_text'])) { 
				$_SESSION['comment_text'] = ''; 
			}
			
			$newsid = $_REQUEST['id'];

			
			/**
			* select base newsrow
			*/
			$sql = "SELECT 
						n.*, u.*
					FROM 
						ghcif_news n, ghcif_users u
					WHERE 
						n.author = u.id AND n.cryptid = '" . $newsid . "';";
			$result = mysql_query($sql) OR die(mysql_error());
        
			if($newsbeitrag = mysql_fetch_assoc($result)) {
				/**
				* set start page
				*/
				$start = (isset($_GET['start'])) ? intval($_GET['start']) : 0;


				/**         
				* set link to this comment page
				*/
				$comments_link = append_sid(GCMS_RELATIVE_PATH . '/index.php?module=news&amp;section=comments&amp;id=' . $newsbeitrag['cryptid']);
				

				/**
				* get rights and check is user admin or newsposter
				*/
				$rights = array();
				if(in_array("Admin", $rights) OR in_array("News", $rights)) {
					$whereclause = "";
				} else {
					$whereclause = " AND locking = 'false'";
				}
				

				/**
				* count news and generate pagination
				*/
				$comments_count_sql = "SELECT * 
								   	   FROM ghcif_news_comments
								   	   WHERE newsid = '" . $newsbeitrag['cryptid'] . "'" . $whereclause . ";";
				$comments_count = mysql_query($comments_count_sql) OR die(mysql_error());
				$total_comments = mysql_num_rows($comments_count);
				if($total_comments AND $total_comments > 0) {
					$pagination = generate_pagination($comments_link, $total_comments, 2, $start);
					$page_number = sprintf('seite <span style="font-weight:bold;">%d</span> von <span style="font-weight:bold;">%d</span>', (floor($start / 2) + 1 ), ceil($total_comments / 2));
				} else {
					$pagination = '';
					$page_number = '';
				}
				

				/**
				* look at ip table to surfers ip
				*/
				$ipblocksql = "SELECT *
							   FROM ghcif_news_comments_ip
							   WHERE ip = '" . $_SERVER['REMOTE_ADDR'] . "' AND newsid = '" . $newsbeitrag['cryptid'] . "';";
				$ipblock_select = mysql_query($ipblocksql) OR die(mysql_error());
				$ipblocking = mysql_num_rows($ipblock_select);
		
				
				/**
				* process the submit
				*/
				if(isset($_POST['submit'])) {
					if($ipblocking) {
						/**                                                                 
						* assign message vars       
						*/                          
						$template->assign_vars(array(
							'MOD_TITLE' => $module_title,	
							'MESSAGE_TEXT' => 'du hast bereits in den letzten 5 minuten einen beitrag geschrieben<br /><br /><a href="' . $comments_link . '">zurueck</a>'
						));                                                                                                                                                                                              
                        
						/**                         
						* parse message template    
						*/       
						$template->pparse('message');
					} else {
						if(!isset($_POST['name'], $_POST['email'], $_POST['text'])) {
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
							$_SESSION['comment_name'] = addslashes(htmlspecialchars($_POST['name']));
							$_SESSION['comment_email'] = addslashes(htmlspecialchars($_POST['email']));
							$_SESSION['comment_text'] = addslashes(htmlspecialchars($_POST['text']));
					
							if(trim(empty($_POST['name'])) OR trim(empty($_POST['text']))) {
								/** 
								* assign message vars
								*/
								$template->assign_vars(array(
									'MOD_TITLE' => $module_title,
									'MESSAGE_TEXT' => 'bitte namen und kommentar angeben<br /><br /><a href="' . $comments_link . '">zurueck</a>'
								));


								/**
								* parse message template
								*/
								$template->pparse('message');
							} else {
								if(trim(!empty($_POST['email']))) {
									if(check_email($_POST['email'])) {
										$playback = 'true';
									} else {
										/** 
										* assign message vars
										*/
										$template->assign_vars(array(
											'MOD_TITLE' => $module_title,
											'MESSAGE_TEXT' => 'ungueltige emailadresse<br /><br /><a href="' . $comments_link . '">zurueck</a>'                  
										));


										/**
										* parse message template
										*/
										$template->pparse('message');
									
									
										$playback = 'false';
									}
								} else {
									$playback = 'true';
								}
								
								if($playback == 'true') {
									/**
									* work with the input vars
									*/
									$comment_name_insert = trim($_SESSION['comment_name']);
									$comment_email_insert = trim($_SESSION['comment_email']);
									$comment_text_insert = $_SESSION['comment_text'];
									

									/**
									* generate my cryptid
									*/
									$crypt_id = substr(md5(microtime()),0,10);
									
									
									/**
									* write comment to db
									*/
									$comment_insert_sql = "INSERT INTO 
														       ghcif_news_comments (cryptid, name, email, comment, newsid, ip, locking, date, isp)
														   VALUES 
														       ('".$crypt_id."', '".$comment_name_insert."', '".$comment_email_insert."', '".$comment_text_insert."', '".$newsbeitrag['cryptid']."', '".$_SERVER['REMOTE_ADDR']."', 'true', UNIX_TIMESTAMP(), '".gethostbyaddr($_SERVER['REMOTE_ADDR'])."')";

									mysql_query($comment_insert_sql) OR die(mysql_error());

									$ipblocker_sql = "INSERT INTO 
													      ghcif_news_comments_ip (ip, time, newsid)
													  VALUES 
													      ('".$_SERVER['REMOTE_ADDR']."', UNIX_TIMESTAMP(), '".$newsbeitrag['cryptid']."')";

									mysql_query($ipblocker_sql) OR die(mysql_error());


									/**
									* unset the temporary session vars
									*/
									unset($_SESSION['comment_name'], $_SESSION['comment_email'], $_SESSION['comment_text']);
							

									/**                                                                 
									* assign message vars       
									*/                          
									$template->assign_vars(array(
										'MOD_TITLE' => $module_title,
										'MESSAGE_TEXT' => 'kommentar gespeichert, es kann aber etwas dauern bis dieser freigegeben wird<br /><br /><a href="' . $comments_link . '">zurueck</a>'
									));                                                                                                                                                                                              

									/**                         
									* parse message template    
									*/       
									$template->pparse('message');
								}
							}
						}
					}
				} else {
					/**
					* generate news author line
					*/
					$authorline = 'am ' . date("d.m.y" , $newsbeitrag['date']) . ' von ' . $newsbeitrag['name'];
					
					
					/**
					* parse news content
					*/
					$newscontent = changetext($newsbeitrag['content']);
					$newscontent = wordwrap($newscontent, 95, "\n", 1);


					/**
					* allow or diasallow administrate menue
					*/
					if(in_array("Admin", $rights) OR in_array("News", $rights)) {
						$administrate = '&nbsp;&nbsp;&nbsp;<a href="' . append_sid(RELATIVE_PATH . '/index.php?module=news&amp;section=deletepost&amp;id=' . $newsbeitrag['cryptid']) . '" title="delete post">[d]</a> <a href="' . append_sid(RELATIVE_PATH . '/index.php?module=news&amp;section=editpost&amp;id=' . $newsbeitrag['cryptid']) . '" title="edit post">[e]</a>';
					} else {
						$administrate = '';
					}
				

					/**
					* set source of news
					*/
					if(trim(empty($newsbeitrag['source']))) {
						$source = 'keine angabe';
					} else {
						$source = '<a href="' . $newsbeitrag['source'] . '">' . $newsbeitrag['source'] . '</a>';
					}
				
					
					/**
					* set newsrow vars 
					*/
					$template->assign_vars(array(
						'SECTION' => $newsbeitrag['section'],
						'TITLE' => $newsbeitrag['title'],
						'LINK_COMMENTS' => $comments_link,
						
						'AUTHORLINE' => $authorline,
						'CONTENT' => $newscontent,
						'ADMINISTRATE' => $administrate,
						'SOURCE' => $source
					));

					
					/**
					* select comments from db
					*/
					$comment_sql = "SELECT *
									FROM ghcif_news_comments
									WHERE newsid = '" . $newsbeitrag['cryptid'] . "'" . $whereclause . "
									ORDER BY date DESC
									LIMIT " . $start . ", 2;";
					$comment_select = mysql_query($comment_sql) OR die(mysql_error());
					if($commentcount = mysql_num_rows($comment_select)) {
						/**
						* loop to put out comments
						*/
						for($i = $start; $row = mysql_fetch_assoc($comment_select); $i++) {
							/**
							* set administration
							*/
							if(in_array("Admin", $rights) OR in_array("News", $rights)) {
								if($row['locking'] == 'true') {
									$administrate = '&nbsp;&nbsp;&nbsp;<a href="' . append_sid(RELATIVE_PATH . '/index.php?module=news&amp;section=unlockpost&amp;id=' . $row['cryptid']) . '" title="unlock post" style="color:green;">[u]</a>';
								} else {
									$administrate = '&nbsp;&nbsp;&nbsp;<a href="' . append_sid(RELATIVE_PATH . '/index.php?module=news&amp;section=lockpost&amp;id=' . $row['cryptid']) . '" title="lock post" style="color:red;">[l]</a>';
								}

								$administrate .= ' <a href="' . append_sid(RELATIVE_PATH . '/index.php?module=news&amp;section=deletepost&amp;id=' . $row['cryptid']) . '" title="delete post">[d]</a>';
							} else {
								$administrate = '';
							}


							/**
							* generate comment author line
							*/
							$namemail  = (trim(empty($row['email']))) ? ($row['name']) : ('<a href="mailto:' . $row['email'] . '">' . $row['name'] . '</a>');
							$namemail .= ' schrieb am ' . date("d.m.y" , $row['date']);


							/**
							* set news blockvar
							*
							* TODO:
							* - start number is in that case bad, 
							*   it doesnt really work with pagination
							*/
							$template->assign_block_vars('newscomments', array(
								'COMMENTNUMBER' => '<a href="' . append_sid(RELATIVE_PATH .'/index.php?module=news&amp;section=comments&amp;id=' . $newsbeitrag['cryptid']) . '&amp;start=' . $i . '">#' . $i . '</a>',
								'COMMENTWRITER' => $namemail,
								'COMMENT' => changetext($row['comment']),	
								'ADMINISTRATE' => $administrate
							));
						}
					} else {
						/**
						* switch to set no comments text in template
						*/
						$template->assign_block_vars('switch_nocomments', array());
					}

					
					/**
					* set the post form
					*/
					if($ipblocking) {
						/**
						* put out the error message that user has written comment
						*/
						$template->assign_block_vars('switch_nopost', array());
					} else {
						/**
						* put out the form template
						*/
						$template->assign_block_vars('switch_anoncomment', array());
						$template->assign_block_vars('switch_posttrue', array());


						/**
						* assign form vars
						*/
						$template->assign_vars(array(
							'FORM_ACTION' => $comments_link,

							'TEMP_NAME' => stripslashes($_SESSION['comment_name']),
							'TEMP_EMAIL' => stripslashes($_SESSION['comment_email']),
							'TEMP_TEXT' => stripslashes($_SESSION['comment_text']),

							'LINK_BACK' => RELATIVE_PATH . '/index.php?module=news'
						));
					}
					
					
					/**
					* parse post form template
					*/
					$template->assign_var_from_handle('COMMENT_FORM', 'post_form');



					/** 
					* assign news_index vars
					*/
					$template->assign_vars(array(
						'MOD_TITLE' => $module_title,

						'PAGINATION' => $pagination,
						'PAGENUMBER' => $page_number,

						'LINK_BACK' => RELATIVE_PATH . '/index.php?module=news'
					)); 
																							            
																										            
					/**
					* parse pagination template
					*/  
					$template->assign_var_from_handle('PAGINATION_CONTENT', 'news_pagination');

																									           
					/**
					* parse news index template
					*/
					$template->pparse('news_comments');
				}
			} else {
				/**                                                                 
				* assign message vars       
				*/                          
				$template->assign_vars(array(
					'MOD_TITLE' => $module_title,
					'MESSAGE_TEXT' => 'unbekannte id<br /><br /><a href="' . append_sid(RELATIVE_PATH . '/index.php?module=news') . '">zurueck</a>'
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
?>
