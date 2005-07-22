<?php
	/** 
	* $Id: unlockpost.php,v 1.1.1.1 2005/06/05 21:58:35 templis Exp $
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
	$module_title .= ' <span style="font-size:8px;">::</span> unlock';


	/**
	* check authorisation
	*/
	if(in_array("Admin", $rights) OR in_array("News", $rights)) {
		if(isset($_REQUEST['id'])) {
			if(preg_match('/^[a-fA-F\d]{10}$/', $_REQUEST['id'])) {
				/**
				* look if the post exists
				*/
				$sql = "SELECT *
						FROM ghcif_news_comments
						WHERE cryptid = '".$_REQUEST['id']."';";
				$result = mysql_query($sql) OR die(mysql_error());
        
		
				/**
				* if it exists is good else put out the error
				*/
				if($comment = mysql_fetch_array($result)) {							
					$sql = "UPDATE ghcif_news_comments
							SET locking = 'false'
							WHERE cryptid = '".$comment['cryptid']."';";
					mysql_query($sql) OR die(mysql_error());


					/**                                                                 
					* assign message vars       
					*/
					$template->assign_vars(array(
						'MOD_TITLE' => $module_title,
						'MESSAGE_TEXT' => 'kommentar freigeschaltet<br /><br /><a href="' . append_sid(RELATIVE_PATH . '/index.php?module=news&amp;section=comments&amp;id=' . $comment['newsid']) . '">zurueck</a>'
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
