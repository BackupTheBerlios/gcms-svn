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
	*
	* TODO:
	* - error handling
	* - error handling if needed var is not given
	*/


	/**
	* general check
	*/
	if(!defined('IN_GCMS')) {
		die('access denied');
	}


	/**
	* db tables
	*/
	define('TBL_CATEGORIES', $GCMS['DB']['DATA']['PREFIX'] . 'categories');
	define('TBL_CATAUTH', $GCMS['DB']['DATA']['PREFIX'] . 'categories_auth');
	define('TBL_USERS', $GCMS['DB']['DATA']['PREFIX'] . 'users');
	define('TBL_GROUPS', $GCMS['DB']['DATA']['PREFIX'] . 'groups');
	define('TBL_RELUSERSGROUPS', $GCMS['DB']['DATA']['PREFIX'] . 'rel_users_groups');


	/**
	* set query counter to zero
	*/
	$GCMS['DB']['COUNTER'] = 0;
	
	
	/**
	* connect to the db
	*/
	function gcms_db_connect() {
		global $GCMS;

		if(is_array($GCMS['DB']['DATA'])) {
			if($GCMS['DB']['LINK'] = @mysql_pconnect($GCMS['DB']['DATA']['HOST'], $GCMS['DB']['DATA']['USER'], $GCMS['DB']['DATA']['PASSWORD'])) {
				$GCMS['DB']['DATABASE'] = @mysql_select_db($GCMS['DB']['DATA']['DATABASE']);
				
				if(!$GCMS['DB']['DATABASE']) {
					@mysql_close($GCMS['DB']['LINK']);
					/**
					* TODO:
					* - error handling
					*/
					$error = gcms_db_error();
					die("failed to select db<br /><br />\n" . $error['code'] . ': ' . $error['message']);
				}
			} else {
				/**
				* TODO:
				* - error handling
				*/
				$error = gcms_db_error();
				die("failed to connect to db<br /><br />\n" . $error['code'] . ': ' . $error['message']);
			}
		}
	}


	/**
	* db error
	*/
	function gcms_db_error() {
		global $GCMS;
		
		$result["message"] = @mysql_error($GCMS['DB']['LINK']);
		$result["code"] = @mysql_errno($GCMS['DB']['LINK']);
		
		return $result;
	}


	/**
	* disconnect from db
	*/
	function gcms_db_close() {
		global $GCMS;
	
		if($GCMS['DB']['LINK']) {
			@mysql_close($GCMS['DB']['LINK']);
		}
	}
	
	
	/**
	* pulls userdata as array
	*/
	function gcms_db_get_userdata($uid) {
		global $GCMS;

		$sql = "SELECT u.* FROM " . TBL_USERS . " AS u WHERE u.id = " . (int)$uid . " LIMIT 1;";
		if(!($result = mysql_query($sql, $GCMS['DB']['LINK']))) {
			/**
			* TODO:
			* - error handling
			*/
			$error = gcms_db_error();
			die("failed to load userdata<br /><br />\n" . $error['code'] . ': ' . $error['message']);
		}

		$GCMS['DB']['COUNTER']++;
		
		$row = @mysql_fetch_array($result, MYSQL_ASSOC);
		@mysql_free_result($result);

		return $row;
	}


	/**
	* pulls categorie by name
	*/
	function gcms_db_get_cat_by_name($catname, $rootid = false) {
		global $GCMS;
		
		$whereclause = ($rootid != false) ? ("AND c.root_id = '" . (int)$rootid . "' ") : ('');
		
		$sql = "SELECT c.* FROM " . TBL_CATEGORIES . " AS c WHERE c.name = '" . addslashes(htmlspecialchars($catname)) . "' " . $whereclause . "LIMIT 1;";
		if(!($result = mysql_query($sql, $GCMS['DB']['LINK']))) {
			/**
			* TODO:
			* - error handling
			*/
			$error = gcms_db_error();
			die("failed to load categories data<br /><br />\n" . $error['code'] . ': ' . $error['message']);
		}

		$GCMS['DB']['COUNTER']++;

		$row = @mysql_fetch_array($result, MYSQL_ASSOC);
		@mysql_free_result($result);

		return $row;
	}


	/**
	* get authorisation for category id
	*/
	function gcms_db_get_cat_auth($catid) {
		global $GCMS;

		$sql = "SELECT c.* FROM " . TBL_CATAUTH . " AS c WHERE c.categories_id = '" . (int)$catid . "';";
		if(!($result = mysql_query($sql, $GCMS['DB']['LINK']))) {
			/**
			* TODO:
			* - error handling
			*/
			$error = gcms_db_error();
			die("failed to load categories authorisation<br /><br />\n" . $error['code'] . ': ' . $error['message']);
		}

		$GCMS['DB']['COUNTER']++;

        $rows = array();

		while($row = @mysql_fetch_array($result, MYSQL_ASSOC)) {
			$rows[] = $row;
		}
		@mysql_free_result($result);

		return $rows;
	}


	/**
	* load navigation data with auth check
	*/
	function gcms_db_get_navigation($rootid = 0) {
		global $GCMS;

		$whereclause = ($rootid > 0) ? (" AND c.root_id = '" . (int)$rootid . "' AND c.show_on = " . NAVI_SUB) : (" AND c.show_on = " . NAVI_MAIN);
		
		$sql = "SELECT c.* 
				FROM " . TBL_CATEGORIES . " AS c, " . TBL_CATAUTH . " AS ca, " . TBL_GROUPS . " AS g, " . TBL_RELUSERSGROUPS . " AS relug 
				WHERE relug.users_id = '" . $GCMS['USER']['id'] . "' AND ca.groups_id = relug.groups_id AND ca.categories_id = c.id" . $whereclause . "
				GROUP BY id
				ORDER BY c.sort_order ASC;";
		if(!($result = mysql_query($sql, $GCMS['DB']['LINK']))) {
			/**
			* TODO:
			* - error handling
			*/
			$error = gcms_db_error();
			die("failed to load root navigation<br /><br />\n" . $error['code'] . ': ' . $error['message']);
		}

		$GCMS['DB']['COUNTER']++;

		$rows = array();

		/**
		* count the navirows
		*/
		$rows['counter'] = mysql_num_rows($result);
		
		while($row = @mysql_fetch_array($result, MYSQL_ASSOC)) {
			$rows['data'][] = $row;
		}
		@mysql_free_result($result);
		
		return $rows;
	}
?>
