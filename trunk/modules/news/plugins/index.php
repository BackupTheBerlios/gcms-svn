<?php
	/** 
	* $Id: $
	* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
	*
	* This file is part of GCMS
	* 
	* @author   GCMS Development Team <devel@ghcif.de>
	* @since    2005/05/26
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
	    * set template filenames
		    */
			    $GCMS['TEMPLATE']->set_filenames(array(
				        'news_index' => 'news_index.tpl',
						        'news_work' => 'news_work.tpl',
								        'news_comments' => 'news_comments.tpl',
										        'news_pagination' => 'pagination.tpl',
												        'post_form' => 'post_form.tpl',
																    ));





	/**
	* set some session vars if not allready done
	*/
	if(isset($_SESSION['comment_name'])) { 
		$_SESSION['comment_name'] = ''; 
	}
	if(isset($_SESSION['comment_email'])) { 
		$_SESSION['comment_email'] = ''; 
	}
	if(isset($_SESSION['comment_text'])) { 
		$_SESSION['comment_text'] = ''; 
	}
		
	
	/**
	* define module title
	*/
	$module_title .= '';


	/**
	* check startpage
	*/
	$start = (isset($_GET['start'])) ? intval($_GET['start']) : 0;
		
		
	/**
	* count news and generate pagination
	*/
	$news_count_sql = "SELECT * 
					   FROM ghcif_news;";
	$news_count = mysql_query($news_count_sql) OR die(mysql_error());
	$total_news = mysql_num_rows($news_count);

	if($total_news AND $total_news > 0) {
		$pagination = generate_pagination("?module=news&amp;section=index", $total_news, 5, $start);
		$page_number = sprintf('seite <span style="font-weight:bold;">%d</span> von <span style="font-weight:bold;">%d</span>', (floor($start / 5) + 1 ), ceil($total_news / 5));
	} else {
		$pagination = '';
		$page_number = '';
	}
		
		
	/**
	* read out page news
	*/
	$newssql = "SELECT ghcif_news.*, ghcif_users.*
				FROM ghcif_news, ghcif_users
				WHERE ghcif_news.author = ghcif_users.id
				ORDER BY ghcif_news.date DESC
				LIMIT ".$start.", 5;";
	$newsselect = mysql_query($newssql) OR die(mysql_error());
	if($total_news) {
		/**
		* read news
		*/
		for($i=1; $row = mysql_fetch_assoc($newsselect); $i++) {
			/**
			* count unlocked comments
			*/
			$comments_falsesql = "SELECT COUNT(*) as anzahl
								  FROM ghcif_news_comments
								  WHERE newsid = '".$row['cryptid']."' AND locking = 'false'";
			$comments_false = mysql_query($comments_falsesql) OR die(mysql_error());
			$comments_false_anzahl = mysql_result($comments_false, 0);
                
			
			/**
			* generate comment link text
			*/
			if($comments_false_anzahl > 0) {
				$comments_text = ($comments_false_anzahl > 1) ? ('kommentare lesen (' . $comments_false_anzahl . ')') : ('kommentar lesen');
			} else {
				$comments_text = 'kommentar schreiben';
			}
			
			$comments_link = append_sid(GCMS_RELATIVE_PATH . '/index.php?module=news&amp;section=comments&amp;id=' . $row['cryptid']); 
					
					
			/**
			* generate author line
			*/
			$authorline = 'am ' . date("d.m.y" , $row['date']) . ' von ' . $row['name'];
			

			/**
			* prepare newsrow content
			*/
			$newscontent = strip_tags(changetext($row['content']));
			if(strlen($newscontent) > 300) {
				$newscontent  = substr($newscontent, 0, 300);
				$newscontent .= '... <a href="' . $comments_link . '">[mehr]</a>';
			}
		
		
			/**
			* allow or diasallow administrate menue
			*/
			if(in_array("Admin", $rights) OR in_array("News", $rights)) {
				$administrate = '&nbsp;&nbsp;&nbsp;<a href="' . append_sid(GCMS_RELATIVE_PATH . '/index.php?module=news&amp;section=deletepost&amp;id=' . $row['cryptid']) . '" title="delete post">[d]</a> <a href="' . append_sid(GCMS_RELATIVE_PATH . '/index.php?module=news&amp;section=editpost&amp;id=' . $row['cryptid']) . '" title="edit post">[e]</a>';
			} else {
				$administrate = '';
			}
				
				
			/**
			* set news blockvar
			*/
			$template->assign_block_vars('newsposts', array(
				'SECTION' => $row['section'],
				'TITLE' => $row['title'],

				'AUTHORLINE' => $authorline,
		
				'CONTENT' => $newscontent,
		
				'COMMENTS' => $comments_text,
				'LINK_COMMENTS' => $comments_link,

				'ADMINISTRATE' => $administrate
			));
		} /* end for read news */
        		
				
		/**
		* assign news_index vars
		*/
		$template->assign_vars(array(
			'MOD_TITLE' => $module_title,
				
			'PAGINATION' => $pagination,
			'PAGENUMBER' => $page_number
		));
		
			
		/**
		* parse pagination template
		*/
		$template->assign_var_from_handle('PAGINATION_CONTENT', 'news_pagination');


		/**
		* parse news index template
		*/
		$template->pparse('news_index');
	} else {
		/**                                                                 
		* assign message vars       
		*/
		$template->assign_vars(array(       
			'MOD_TITLE' => $module_title,
			'MESSAGE_TEXT' => 'keine news'
		));                                                                         
	

		/**                                                                                              
		* parse message template    
		*/
		$template->pparse('message');
	}
?>
