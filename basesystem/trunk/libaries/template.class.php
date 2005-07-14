<?php
	/** 
	* $Id: $
	* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
	*
	* This file is part of GCMS
	*
	* @author   GCMS Development Team <devel@ghcif.de>
	* @since    2005/05/25
	* @version  $Revision: $
	* @credit   parts of the code based on phpbb template engine
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
	* check php version
	*/
	if(0 > version_compare(PHP_VERSION, '5')) {
		die('this file was generated for php5');
	}


	/**
	* gcms_template 
	*
	* this class handles the template files.
	*/
	class gcms_template {
		/**
		* template data array
		*                                                                                                                                              
		* @var array
		*/
		private $tpldata = array();


		/**
		* hash of filenames for each template handle
		*
		* @var array
		*/
		private $files = array();

		
		/**
		* root template directory
		*
		* @var string
		*/
		private $root = '';


		/**
		* this will hash handle names to the compiled code for that handle
		*
		* @var array
		*/
		private $compiled_code = array();


		/**
		* this will hold the uncompiled code for that handle
		*
		* @var array
		*/
		private $uncompiled_code = array();


		/**
		* constructor 
		*
		* @access   public
		* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
		* @since    2005/05/25                                                                                                                         
		* @version  0.5.3                                                                                                                              
		*/
		public function __construct($root = '.') {
			$this->set_rootdir($root);
		}


		/**                                                                                                                                            
		* destructor                                                                                                                                   
		*
		* @access   public
		* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
		* @since    2005/05/25
		* @version  0.5.3
		*/
		public function __destruct() {
			$this->destroy();
		}


		/**
		* sets the template root directory for this template object
		*
		* @access   public
		* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
		* @since    2005/05/25
		* @version  0.5.3
		*/
		public function set_rootdir($dir) {
			if(!is_dir($dir)) {
				return false;
			}
			
			$this->root = $dir;
		
			return true;
		}
																			  

		/**
		* destroys the template object
		*
		* @access   public
		* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
		* @since    2005/05/25
		* @version  0.5.3
		*/
		public function destroy() {
			$this->tpldata = array();
		}


		/**
		* sets the template filenames for handle
		*
		* @access   public
		* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
		* @since    2005/05/25
		* @version  0.5.3
		*/
		public function set_filenames($filename_array) {
			if(!is_array($filename_array)) {
				return false;
			}

			reset($filename_array);

			while(list($handle, $filename) = each($filename_array)) {
				$this->files[$handle] = $this->make_filename($filename);
			}

			return true;
		}


		/**
		* parse file for handle
		*
		* @access   public
		* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
		* @since    2005/05/25
		* @version  0.5.3
		*/
		public function pparse($handle) {
			if(!$this->loadfile($handle)) {
				die("template->pparse(): could not load template file for handle $handle");
			}

			/**
			* actually compile the template now
			*/
			if(!isset($this->compiled_code[$handle]) OR empty($this->compiled_code[$handle])) {
				/**
				* Actually compile the code now
				*/
				$this->compiled_code[$handle] = $this->compile($this->uncompiled_code[$handle]);
			}

			/**
			* run the compiled code
			*/
			eval($this->compiled_code[$handle]);
			
			return true;
		}


		/**
		* inserts uncompiled code for handle
		*
		* @access   public
		* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
		* @since    2005/05/25
		* @version  0.5.3
		*/
		public function assign_var_from_handle($varname, $handle) {
			if(!$this->loadfile($handle)) {
				die("template->assign_var_from_handle(): could not load template file for handle $handle");
			}

			/**
			* compile it, with the "no echo statements" option on
			*/
			$_str = "";
			$code = $this->compile($this->uncompiled_code[$handle], true, '_str');

			/**
			* evaluate the variable assignment
			*/
			eval($code);

			/**
			* assign the value of the generated variable to the given varname
			*/
			$this->assign_var($varname, $_str);

			return true;
		}


		/**
		* blocklevel variable assignment
		*
		* @access   public
		* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
		* @since    2005/05/25
		* @version  0.5.3
		*/
		public function assign_block_vars($blockname, $vararray) {
			if(strstr($blockname, '.')) {
				$blocks = explode('.', $blockname);
				$blockcount = sizeof($blocks) - 1;
				$str = '$this->tpldata';

				for($i = 0; $i < $blockcount; $i++) {
					$str .= '[\'' . $blocks[$i] . '.\']';
					eval('$lastiteration = sizeof(' . $str . ') - 1;');
					$str .= '[' . $lastiteration . ']';
				}

				$str .= '[\'' . $blocks[$blockcount] . '.\'][] = $vararray;';

				eval($str);
			} else {
				$this->tpldata[$blockname . '.'][] = $vararray;
			}
			
			return true;
		}


		/**
		* rootlevel variables assignment
		*
		* @access   public
		* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
		* @since    2005/05/25
		* @version  0.5.3
		*/
		public function assign_vars($vararray) {
			reset ($vararray);

			while (list($key, $val) = each($vararray)) {
				$this->tpldata['.'][0][$key] = $val;
			}

			return true;
		}


		/**
		* blocklevel variable assignment
		*
		* @access   public
		* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
		* @since    2005/05/25
		* @version  0.5.3
		*/
		public function assign_var($varname, $varval) {
			$this->tpldata['.'][0][$varname] = $varval;

			return true;
		}


		/**
		* generates a full path+filename for the given filename
		*
		* @access   public
		* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
		* @since    2005/05/25
		* @version  0.5.3
		*/
		public function make_filename($filename) {
			/**
			* check if it is an absolute or relative path
			*/
			if(substr($filename, 0, 1) != '/') {
				$filename = ($rp_filename = $this->root . '/' . $filename) ? $rp_filename : $filename;
			}

			if(!file_exists($filename)) {
				die("template->make_filename(): error - file $filename does not exist");
			}

			return $filename;
		}


		/**
		* load the file for the given handle
		*
		* @access   public
		* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
		* @since    2005/05/25
		* @version  0.5.3
		*/
		public function loadfile($handle) {
			/**
			* if the file for this handle is already loaded and compiled, do nothing
			*/
			if(isset($this->uncompiled_code[$handle]) AND !empty($this->uncompiled_code[$handle])) {
				return true;
			}	

			/**
			* if we do not have a file assigned to this handle, die
			*/
			if(!isset($this->files[$handle])) {
				die("template->loadfile(): no file specified for handle $handle");
			}

			$filename = $this->files[$handle];

			$str = implode("", @file($filename));
			if(empty($str)) {
				die("template->loadfile(): file $filename for handle $handle is empty");
			}

			$this->uncompiled_code[$handle] = $str;

			return true;
		}


		/**
		* compiles the given string of code, and returns the result in a string
		*
		* @access   public
		* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
		* @since    2005/05/25
		* @version  0.5.3
		*/
		function compile($code, $do_not_echo = false, $retvar = '') {
			/**
			* replace \ with \\ and then ' with \'
			*/
			$code = str_replace('\\', '\\\\', $code);
			$code = str_replace('\'', '\\\'', $code);

			/**
			* change template varrefs into php varrefs
			*
			* this one will handle varrefs WITH namespaces
			*/
			$varrefs = array();
			preg_match_all('#\{(([a-z0-9\-_]+?\.)+?)([a-z0-9\-_]+?)\}#is', $code, $varrefs);
			$varcount = sizeof($varrefs[1]);

			for($i = 0; $i < $varcount; $i++) {
				$namespace = $varrefs[1][$i];
				$varname = $varrefs[3][$i];
				$new = $this->generate_block_varref($namespace, $varname);

				$code = str_replace($varrefs[0][$i], $new, $code);
			}

			/**
			* this will handle the remaining root-level varrefs
			*/
			$code = preg_replace('#\{([a-z0-9\-_]*?)\}#is', '\' . ( ( isset($this->tpldata[\'.\'][0][\'\1\']) ) ? $this->tpldata[\'.\'][0][\'\1\'] : \'\' ) . \'', $code);

			/**
			* break it up into lines
			*/
			$code_lines = explode("\n", $code);

			$block_nesting_level = 0;
			$block_names = array();
			$block_names[0] = ".";

			/**
			* second: prepend echo ', append ' . "\n"; to each line
			*/
			$line_count = sizeof($code_lines);
			for($i = 0; $i < $line_count; $i++) {
				$code_lines[$i] = chop($code_lines[$i]);
				
				if(preg_match('#<!-- BEGIN (.*?) -->#', $code_lines[$i], $m)) {
					$n[0] = $m[0];
					$n[1] = $m[1];

					/**
					* added: dougk_ff7 - keeps templates from bombing if begin is on the same line as end.. i think. :)
					*/
					if(preg_match('#<!-- END (.*?) -->#', $code_lines[$i], $n)) {
						$block_nesting_level++;
						$block_names[$block_nesting_level] = $m[1];

						if($block_nesting_level < 2) {
							/**
							* block is not nested
							*/
							$code_lines[$i] = '$_' . $n[1] . '_count = ( isset($this->tpldata[\'' . $n[1] . '.\']) ) ?  sizeof($this->tpldata[\'' . $n[1] . '.\']) : 0;';
							$code_lines[$i] .= "\n" . 'for ($_' . $n[1] . '_i = 0; $_' . $n[1] . '_i < $_' . $n[1] . '_count; $_' . $n[1] . '_i++)';
							$code_lines[$i] .= "\n" . '{';
						} else {
							/**
							* this block is nested
							*/

							/**
							* generate a namespace string for this block
							*/
							$namespace = implode('.', $block_names);
							
							/**
							* strip leading period from root level..
							*/
							$namespace = substr($namespace, 2);
							
							/**
							* get a reference to the data array for this block that depends on the
							* current indices of all parent blocks
							*/
							$varref = $this->generate_block_data_ref($namespace, false);
							
							/**
							* create the for loop code to iterate over this block
							*/
							$code_lines[$i] = '$_' . $n[1] . '_count = ( isset(' . $varref . ') ) ? sizeof(' . $varref . ') : 0;';
							$code_lines[$i] .= "\n" . 'for ($_' . $n[1] . '_i = 0; $_' . $n[1] . '_i < $_' . $n[1] . '_count; $_' . $n[1] . '_i++)';
							$code_lines[$i] .= "\n" . '{';
						}

						/**
						* we have the end of a block
						*/
						unset($block_names[$block_nesting_level]);
						$block_nesting_level--;
						$code_lines[$i] .= '} // END ' . $n[1];
						$m[0] = $n[0];
						$m[1] = $n[1];
					} else {
						/**
						* we have the start of a block
						*/
						$block_nesting_level++;
						$block_names[$block_nesting_level] = $m[1];
						if($block_nesting_level < 2) {
							/**
							* block is not nested
							*/
							$code_lines[$i] = '$_' . $m[1] . '_count = ( isset($this->tpldata[\'' . $m[1] . '.\']) ) ? sizeof($this->tpldata[\'' . $m[1] . '.\']) : 0;';
							$code_lines[$i] .= "\n" . 'for ($_' . $m[1] . '_i = 0; $_' . $m[1] . '_i < $_' . $m[1] . '_count; $_' . $m[1] . '_i++)';
							$code_lines[$i] .= "\n" . '{';
						} else {
							/**
							* this block is nested
							*/

							/**
							* generate a namespace string for this block
							*/
							$namespace = implode('.', $block_names);

							/**
							* strip leading period from root level..
							*/
							$namespace = substr($namespace, 2);

							/**
							* get a reference to the data array for this block that depends on the
							* current indices of all parent blocks
							*/
							$varref = $this->generate_block_data_ref($namespace, false);
							
							/**
							* create the for loop code to iterate over this block
							*/
							$code_lines[$i] = '$_' . $m[1] . '_count = ( isset(' . $varref . ') ) ? sizeof(' . $varref . ') : 0;';
							$code_lines[$i] .= "\n" . 'for ($_' . $m[1] . '_i = 0; $_' . $m[1] . '_i < $_' . $m[1] . '_count; $_' . $m[1] . '_i++)';
							$code_lines[$i] .= "\n" . '{';
						}
					}
				} elseif(preg_match('#<!-- END (.*?) -->#', $code_lines[$i], $m)) {
					/**
					* we have the end of a block
					*/
					unset($block_names[$block_nesting_level]);
					$block_nesting_level--;
					$code_lines[$i] = '} // END ' . $m[1];
				} else {
					/**
					* we have an ordinary line of code
					*/
					if(!$do_not_echo) {
						$code_lines[$i] = 'echo \'' . $code_lines[$i] . '\' . "\\n";';
					} else {
						$code_lines[$i] = '$' . $retvar . '.= \'' . $code_lines[$i] . '\' . "\\n";'; 
					}
				}
			}

			/**
			* bring it back into a single string of lines of code
			*/
			$code = implode("\n", $code_lines);

			return $code;
		}


		/**
		* generates a reference to the given variable inside the given block namespace
		*
		* @access   public
		* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
		* @since    2005/05/25
		* @version  0.5.3
		*/
		function generate_block_varref($namespace, $varname) {
			/**
			* strip the trailing period
			*/
			$namespace = substr($namespace, 0, strlen($namespace) - 1);

			/**
			* get a reference to the data block for this namespace
			*/
			$varref = $this->generate_block_data_ref($namespace, true);
			/**
			* prepend the necessary code to stick this in an echo line
			*/

			/**
			* append the variable reference
			*/
			$varref .= '[\'' . $varname . '\']';

			$varref = '\' . ( ( isset(' . $varref . ') ) ? ' . $varref . ' : \'\' ) . \'';

			return $varref;
		}


		/**
		* generates a reference to the array of data values for the given  block namespace
		*
		* @access   public
		* @author   Thomas 'mosez' Boerger <mosez@ghcif.de>
		* @since    2005/05/25
		* @version  0.5.3
		*/
		function generate_block_data_ref($blockname, $include_last_iterator) {
			/**
			* get an array of the blocks involved
			*/
			$blocks = explode(".", $blockname);
			$blockcount = sizeof($blocks) - 1;
			$varref = '$this->tpldata';

			/**
			* build up the string with everything but the last child
			*/
			for($i = 0; $i < $blockcount; $i++) {
				$varref .= '[\'' . $blocks[$i] . '.\'][$_' . $blocks[$i] . '_i]';
			}

			/**
			* add the block reference for the last child
			*/
			$varref .= '[\'' . $blocks[$blockcount] . '.\']';

			/**
			* add the iterator for the last child if requried
			*/
			if($include_last_iterator) {
				$varref .= '[$_' . $blocks[$blockcount] . '_i]';
			}

			return $varref;
		}
	} /* end of class template */
?>
