<?php
	/** vim: set tabstop=4
	*  read user id and requested module from current session
	*/
	
	$user_id = $_SESSION['id'];
	$requested_module = $_REQUEST['module'];

    /**
	*  read groupname
	*/

	$sql = 'SELECT
				groups_id 
			FROM 
				'.TBL_RELUSERSGROUPS.' 
			WHERE 
				users_id = '.$user_id;
				
	$result = $db->db_query($sql);
	$group = $db->db_fetchrow($result);
	$group_id =  $group['groups_id'];

	/**
	*  read module id
	*/
	
	$sql = "SELECT
				id
			FROM				
				".TBL_CATEGORIES."
			WHERE
				name = '".$requested_module."'";
	
    $result = $db->db_query($sql);
    $module = $db->db_fetchrow($result);
	$requested_module_id = $module['id'];

	/**
    *  for testing ...
	*/  
	
	echo 'uid: '.$user_id.', gid: '.$group_id.', mod: '.$requested_module.' ('.$requested_module_id.')<br />';

    /**
	*  read and check module for access
	*/
	
	$sql = 'SELECT 
				groups_id
			FROM 
				'.TBL_CATAUTH.' 
			WHERE 
				groups_id = '.$group_id.' 
				AND categories_id = '.$requested_module_id;

	$result = $db->db_query($sql);
	$category = $db->db_fetchrow($result);
	
    if ($category)
		{
		echo "user has access.";
		}
	else
		{
		echo "access denied.";
		die;
		}
?>
