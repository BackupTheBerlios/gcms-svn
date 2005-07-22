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
	* general check
	*/
	die('access denied');





		/**
		* base query method 
		*
		* @access   public
		* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
		* @since    2005/03/28
		* @version  0.0.1
		*/
		function db_query($query = '', $transaction = FALSE) {
			/**
			* remove any pre-existing queries
			*/
			unset($this->query_result);

			if($query != '') {
				$this->num_queries++;

				$this->query_result = @mysql_query($query, $this->db_connect_id);
			}

			if($this->query_result) {
				unset($this->row[$this->query_result]);
				unset($this->rowset[$this->query_result]);
				
				return $this->query_result;
			} else {
				return ($transaction == END_TRANSACTION) ? true : false;
			}
		}


		/**
		* numrows method 
		*
		* @access   public
		* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
		* @since    2005/03/28
		* @version  0.0.1
		*/
		function db_numrows($query_id = 0) {
			if(!$query_id) {
				$query_id = $this->query_result;
			}

			if($query_id) {
				$result = @mysql_num_rows($query_id);
				
				return $result;
			} else {
				return false;
			}
		}


		/**
		* affectedrows method 
		*
		* @access   public
		* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
		* @since    2005/03/28
		* @version  0.0.1
		*/
		function db_affectedrows() {
			if($this->db_connect_id) {
				$result = @mysql_affected_rows($this->db_connect_id);

				return $result;
			} else {
				return false;
			}
		}


		/**
		* numfields method 
		*
		* @access   public
		* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
		* @since    2005/03/28
		* @version  0.0.1
		*/
		function db_numfields($query_id = 0) {
			if(!$query_id) {
				$query_id = $this->query_result;
			}

			if($query_id) {
				$result = @mysql_num_fields($query_id);

				return $result;
			} else {
				return false;
			}
		}


		/**
		* fieldname method 
		*
		* @access   public
		* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
		* @since    2005/03/28
		* @version  0.0.1
		*/
		function db_fieldname($offset, $query_id = 0) {
			if(!$query_id) {
				$query_id = $this->query_result;
			}

			if($query_id) {
				$result = @mysql_field_name($query_id, $offset);
			
				return $result;
			} else {
				return false;
			}
		}


		/**
		* fieldtype method 
		*
		* @access   public
		* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
		* @since    2005/03/28
		* @version  0.0.1
		*/
		function db_fieldtype($offset, $query_id = 0) {
			if(!$query_id) {
				$query_id = $this->query_result;
			}

			if($query_id) {
				$result = @mysql_field_type($query_id, $offset);

				return $result;
			} else {
				return false;
			}
		}


		/**
		* fetchrow method 
		*
		* @access   public
		* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
		* @since    2005/03/28
		* @version  0.0.1
		*/
		function db_fetchrow($query_id = 0) {
			if(!$query_id) {
				$query_id = $this->query_result;
			}

			if($query_id) {
				$this->row[$query_id] = @mysql_fetch_array($query_id, MYSQL_ASSOC);

				return $this->row[$query_id];
			} else {
				return false;
			}
		}


		/**
		* fetchrow method 
		*
		* @access   public
		* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
		* @since    2005/03/28
		* @version  0.0.1
		*/
		function db_fetchrowset($query_id = 0) {
			if(!$query_id) {
				$query_id = $this->query_result;
			}
			
			if($query_id) {
				unset($this->rowset[$query_id]);
				unset($this->row[$query_id]);

				//while($this->rowset[$query_id] = @mysql_fetch_array($query_id)) {
				while($this->rowset[$query_id] = $this->db_fetchrow($query_id)) {
					$result[] = $this->rowset[$query_id];
				}

				return $result;
			} else {
				return false;
			}
		}


		/**
		* fetchfield method 
		*
		* @access   public
		* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
		* @since    2005/03/28
		* @version  0.0.1
		*/
		function db_fetchfield($field, $rownum = -1, $query_id = 0) {
			if(!$query_id) {
				$query_id = $this->query_result;
			}

			if($query_id) {
				if($rownum > -1) {
					$result = @mysql_result($query_id, $rownum, $field);
				} else {
					if(empty($this->row[$query_id]) && empty($this->rowset[$query_id])) {
						if($this->db_fetchrow()) {
							$result = $this->row[$query_id][$field];
						}
					} else {
						if($this->rowset[$query_id]) {
							$result = $this->rowset[$query_id][$field];
						} elseif($this->row[$query_id]) {
							$result = $this->row[$query_id][$field];
						}
					}
				}

				return $result;
			} else {
				return false;
			}
		}


		/**
		* rowseek method 
		*
		* @access   public
		* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
		* @since    2005/03/28
		* @version  0.0.1
		*/
		function db_rowseek($rownum, $query_id = 0) { 
			if(!$query_id) {
				$query_id = $this->query_result;
			}

			if($query_id) {
				$result = @mysql_data_seek($query_id, $rownum);

				return $result;
			} else {
				return false;
			}
		}


		/**
		* nextid method 
		*
		* @access   public
		* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
		* @since    2005/03/28
		* @version  0.0.1
		*/
		function db_nextid() {
			if($this->db_connect_id) {
				$result = @mysql_insert_id($this->db_connect_id);

				return $result;
			} else {
				return false;
			}
		}

		
		/**
		* freeresult method 
		*
		* @access   public
		* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
		* @since    2005/03/28
		* @version  0.0.1
		*/
		function db_freeresult($query_id = 0) {
			if(!$query_id) {
				$query_id = $this->query_result;
			}

			if($query_id) {
				unset($this->row[$query_id]);
				unset($this->rowset[$query_id]);

				@mysql_free_result($query_id);

				return true;
			} else {
				return false;
			}
		}


		/**
		* error method 
		*
		* @access   public
		* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
		* @since    2005/03/28
		* @version  0.0.1
		*/
		function db_error($query_id = 0) {
			$result["message"] = @mysql_error($this->db_connect_id);
			$result["code"] = @mysql_errno($this->db_connect_id);

			return $result;
		}
?>
