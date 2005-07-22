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
    * @credit   parts of the code based on phpbb code
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
    */


    /**
    * general check
    */
    if(!defined('IN_GCMS')) {
        die('access denied');
    }


    /**
    * strip slashes if magic quotes is activated
    */
    function array_stripslashes(&$var) {       
        if(is_string($var)) {
            $var = stripslashes($var);
        } else {   
            if(is_array($var)) {   
                foreach($var AS $key => $item) {   
                    array_stripslashes($var[$key]);
                }
            } 
        } 
    }


    /**
    * this does exactly what preg_quote() does in php 4-ish
    * if you just need the 1-parameter preg_quote call, then don't bother using this.
    */
    function gcms_preg_quote($str, $delimiter) {   
        $text = preg_quote($str);
        $text = str_replace($delimiter, '\\' . $delimiter, $text);

        return $text;
    }

    
    /**
    * hang the session id to the url
    */
    function append_sid($url, $non_html_amp = false) {
        global $SID;

        if(!empty($SID) AND !preg_match('#' . session_name() . '#', $url)) {
            $url .= ((strpos($url, '?') != false) ? (($non_html_amp) ? '&' : '&amp;') : '?') . session_name() . '=' . session_id();
        }
        
        return $url;
    }


    /**
    * generate gcms like passwords
    */
    function gcms_generate_string($length) {
        $password = substr(md5(microtime()), 0, (int)$length);

        return $password;
    }


	/**
	* loads config file to main array
	*/
	function load_config($module = false, $configfile = false) {   
		global $GCMS;

        /**
        * if module is set write it to the module config section
        * else write it to the general config section
        */
        if($module != false) {
            $file  = GCMS_REAL_PATH . '/modules/' . $module . '/configs/';
            $file .= ($configfile != false) ? ($configfile) : ('base');
            $file .= '.php';
            
            if(file_exists($file)) {
                include($file); 
            } else {
                /**
                * TODO:
                * - error handling
                */
                die('failed to load ' . $module . ' module config - ' . $configfile);
            }
       
            foreach($config AS $key => $item) {
                $GCMS['CONFIG'][strtoupper($module)][$key] = $item;
            }
        } else {
            $file  = GCMS_REAL_PATH . '/configs/';
            $file .= ($configfile != false) ? ($configfile) : ('base');
            $file .= '.php';
            
            if(file_exists($file)) {
                include($file);
            } else {
                /**
                * TODO:
                * - error handling
                */
                die('failed to load base config - ' . $configfile);
            }
        
            foreach($config AS $key => $item) {
                $GCMS['CONFIG'][$key] = $item;
            }
        }
	}


    /**
    * loads language file to main array
    */
    function load_language($langfile = false, $module = false) {
        global $GCMS;
        
        /**
        * if module is set write it to the module language section
        * else write it to the general language section
        */
        if($module != false) {
            $file  = GCMS_REAL_PATH . '/modules/' . $module . '/languages/' . $GCMS['USER']['lang'] . '/';
            $file .= ($langfile != false) ? ($langfile) : ('base');
            $file .= '.php';
            if(file_exists($file)) {
                include($file);
            } else {
                /**
                * TODO:
                * - error handling
                */
                die('failed to load ' . $module . ' module language file - ' . $langfile);
            }
            
            foreach($lang AS $key => $item) {
                $GCMS['LANG'][strtoupper($module)][$key] = $item;
            }
        } else {
            $file  = GCMS_REAL_PATH . '/languages/' . $GCMS['USER']['lang'] . '/';
            $file .= ($langfile != false) ? ($langfile) : ('base');
            $file .= '.php';
            if(file_exists($file)) {
                include($file);
            } else {
                /**
                * TODO:
                * - error handling
                */
                die('failed to load base language file - ' . $langfile);
            }
            
            foreach($lang AS $key => $item) {
                $GCMS['LANG'][$key] = $item;
            }
        }
    }


    /**
    * load the template object
    */
    function load_template() {
        global $GCMS;

        define('GCMS_REAL_TPLPATH', GCMS_REAL_PATH . '/templates/' . $GCMS['USER']['template']);
        define('GCMS_RELATIVE_TPLPATH', GCMS_RELATIVE_PATH . '/templates/' . $GCMS['USER']['template']);
        
        require_once(GCMS_REAL_PATH . '/libaries/template.class.php');
        $GCMS['TEMPLATE'] = new gcms_template(GCMS_REAL_TPLPATH);
    }


    /**
    * loads meta tags
    */
    function load_metadata() {
        global $GCMS;

        $metatags = array(
            'description' => $GCMS['CONFIG']['meta_description'],
            'keywords' => $GCMS['CONFIG']['meta_keywords'],
            'robots' => $GCMS['CONFIG']['meta_robots'],
            'revisit-after' => $GCMS['CONFIG']['meta_revisit-after'],
            'author' => $GCMS['CONFIG']['meta_author'],
            'publisher' => $GCMS['CONFIG']['meta_publisher'],
            'copyright' => $GCMS['CONFIG']['meta_copyright'],
            'language' => $GCMS['CONFIG']['meta_language'],
            'date' => $GCMS['CONFIG']['meta_date'],
            'audience' => $GCMS['CONFIG']['meta_audience']
        );
   
        $metas = '';
        $i = 0;
        foreach($metatags AS $key => $item) {
            $i++;
            
            $metas .= '<meta name="' . $key . '" content="' . $item . '" />';
            if($i != count($metatags)) {
                $metas .= "\n";
            }
        }

        return $metas;
    }


    /**
    * load root module
    */
    function load_root_module() {
        global $GCMS;

        define('GCMS_MODULE_PATH', GCMS_REAL_PATH . '/modules/' . $GCMS['ROOTMODULE']['name']);

        if(is_dir(GCMS_MODULE_PATH)) {
            /**
            * load module db wrapper if it is available
            */
            if(is_dir(GCMS_MODULE_PATH . '/db') AND file_exists(GCMS_MODULE_PATH . '/db/' . $GCMS['DB']['DATA']['TYPE'] . '.php')) {
                include(GCMS_MODULE_PATH . '/db/' . $GCMS['DB']['DATA']['TYPE'] . '.php');
            }
            
            /**
            * load module config if it is available
            */
            if(is_dir(GCMS_MODULE_PATH . '/configs') AND file_exists(GCMS_MODULE_PATH . '/configs/base.php')) {
                load_config($GCMS['ROOTMODULE']['name'], false);
            }

            /**
            * load module language file if it is available
            */
            if(is_dir(GCMS_MODULE_PATH . '/languages') AND file_exists(GCMS_MODULE_PATH . '/languages/' . $GCMS['USER']['lang'] . '/base.php')) {
                load_language(false, $GCMS['ROOTMODULE']['name']);
            }
       
            /**
            * include module index
            */
            include(GCMS_MODULE_PATH . '/index.php');
        } else {
            /**
            * TODO:
            * - error handling
            */
            die('failed to include root module');
        }
    }


    /**
    * generat pagelinks and pagenumber
    */
    function generate_pagination($base_url, $num_items, $per_page, $start_item, $add_prevnext_text = true) {
        global $GCMS;

        $total_pages = ceil($num_items / $per_page);

        if($total_pages == 1) {   
            return '';
        }

        $on_page = floor($start_item / $per_page) + 1;

        $page_string = '';
        
        if($total_pages > 10) {   
            $init_page_max = ( $total_pages > 3 ) ? 3 : $total_pages;

            for($i = 1; $i < $init_page_max + 1; $i++) {   
                $page_string .= ($i == $on_page) ? '<span class="pagenumber">' . $i . '</span>' : '<a href="' . append_sid($base_url . "&amp;page=" . (($i - 1) * $per_page)) . '" class="pagenumber">' . $i . '</a>';
                if($i <  $init_page_max) {   
                    $page_string .= ", ";
                }
            }

            if($total_pages > 3) {   
                if($on_page > 1 AND $on_page < $total_pages) {   
                    $page_string .= ($on_page > 5) ? ' ... ' : ', ';

                    $init_page_min = ($on_page > 4) ? $on_page : 5;
                    $init_page_max = ($on_page < $total_pages - 4) ? $on_page : $total_pages - 4;

                    for($i = $init_page_min - 1; $i < $init_page_max + 2; $i++) {   
                        $page_string .= ($i == $on_page) ? '<span class="pagenumber">' . $i . '</span>' : '<a href="' . append_sid($base_url . "&amp;page=" . (($i - 1) * $per_page)) . '" class="pagenumber">' . $i . '</a>';
                        if($i <  $init_page_max + 1) {   
                            $page_string .= ', ';
                        }
                    }

                    $page_string .= ($on_page < $total_pages - 4) ? ' ... ' : ', ';
                } else {   
                    $page_string .= ' ... ';                                                                                                                
                }                                                                                                                                           

                for($i = $total_pages - 2; $i < $total_pages + 1; $i++) {
                    $page_string .= ($i == $on_page) ? '<span class="pagenumber">' . $i . '</span>'  : '<a href="' . append_sid($base_url . "&amp;page=" . (($i - 1) * $per_page)) . '" class="pagenumber">' . $i . '</a>';
                    if($i <  $total_pages) {
                        $page_string .= ", ";
                    }
                }
            }
        } else {
            for($i = 1; $i < $total_pages + 1; $i++) {
                $page_string .= ($i == $on_page) ? '<span class="pagenumber">' . $i . '</span>' : '<a href="' . append_sid($base_url . "&amp;page=" . (($i - 1) * $per_page)) . '" class="pagenumber">' . $i . '</a>';
                if($i <  $total_pages) {
                    $page_string .= ', ';
                }
            }
        }

        if($add_prevnext_text) {
            if($on_page > 1) {
                $page_string = ' <a href="' . append_sid($base_url . "&amp;page=" . (($on_page - 2) * $per_page)) . '" class="pagenumber">' . $GCMS['LANG']['prev_page'] . '</a>&nbsp;&nbsp;' . $page_string;
            }
            if($on_page < $total_pages) {
                $page_string .= '&nbsp;&nbsp;<a href="' . append_sid($base_url . "&amp;start=" . ($on_page * $per_page)) . '" class="pagenumber">' . $GCMS['LANG']['next_page'] . '</a>';
            }
        }

        $page_string = $GCMS['LANG']['go_to'] . ' ' . $page_string;

        return $page_string;
    }


    /**
    * generate the pagenumber
    */
    function generate_pagenumber($start, $total, $per_page) {
        global $GCMS;
    
        $page_number = sprintf($GCMS['LANG']['page_of'], '<span class="pagenumber">' . (floor($start / $per_page) + 1) . '</span>', '<span class="pagenumber">' . ceil($total / $per_page) . '</span>');

        return $page_number;
    }


    /**
    * generate help link
    */
    //function help_link($title, $text) {   
    //    $text = urlencode("<p style=\"font-weight: bold\">$title</p>$text");
    //    return '<a href=""><img style="padding-left: 5px; padding-right: 5px; padding-bottom: 1px;" align="absmiddle" alt="" border="0" src="images/qmark.gif" height="16" width="16" /></a>';
    //}
















    /**
    * general die message
    * 
    * $msg_code can be one of these constants:
    *
    * GENERAL_MESSAGE : Use for any simple text message, eg. results 
    * of an operation, authorisation failures, etc.
    *
    * GENERAL ERROR : Use for any error which occurs _AFTER_ the 
    * core.php include and session code, ie. most errors in 
    * pages/functions
    *
    * CRITICAL_MESSAGE : Used when basic config data is available but 
    * a session may not exist, eg. banned users
    *
    * CRITICAL_ERROR : Used when config data cannot be obtained, eg
    * no database connection. Should _not_ be used in 99.5% of cases
    */
    function message_die($msg_code, $msg_text = '', $msg_title = '', $err_line = '', $err_file = '', $sql = '', $link = '') {
        global $GCMS;

        if(defined('HAS_DIED')) {
            die("die message was called multiple times");
        }

        define('HAS_DIED', 1);

        /**
        * save sql string
        */
        $sql_store = $sql;

        /**
        * get sql error if we are debugging
        */
        if($GCMS['CONFIG']['debug'] AND ($msg_code == GENERAL_ERROR OR $msg_code == CRITICAL_ERROR)) {   
            $sql_error = gcms_db_error();
            
            $debug_text = '';
            
            if($sql_error['message'] != '') {   
                $debug_text .= '<br /><br />SQL Error : ' . $sql_error['code'] . ' - ' . $sql_error['message'];
            }

            if($sql_store != '') {
                $debug_text .= '<br /><br />' . $sql_store;
            }

            if($err_line != '' && $err_file != '') {
                $debug_text .= '</br /><br />Line : ' . $err_line . '<br />File : ' . basename($err_file);
            }
        }
        
        if(!isset($GCMS['USER']) AND ($msg_code == GENERAL_MESSAGE OR $msg_code == GENERAL_ERROR)) {
            load_userdata();
        }

        if(!defined('HEADER_INC') AND $msg_code != CRITICAL_ERROR) {
            if(!isset($GCMS['LANG'])) {
                load_language();
            }

            if(!is_object($GCMS['TEMPLATE'])) {
                load_template();
            }

            /**
            * load the page header
            */
            if(!defined('IN_ADMIN')) {
                include(GCMS_REAL_PATH . '/includes/header.php');
            } else {
                include(GCMS_REAL_PATH . '/admin/header_admin.php');
            }
        }

        
        switch($msg_code) {
            case GENERAL_MESSAGE:
                if($msg_title == '') {
                    $msg_title = '<span class="titleseperator">' . $GCMS['CONFIG']['title_separator'] . '</span> ' . $GCMS['LANG']['information'];
                }
                if($link != '') {
                    $link = '<a href="' . $link . '">' . $GCMS['LANG']['forward'] . '</a>';
                }
            break;

            case CRITICAL_MESSAGE:
                if($msg_title == '') {
                    $msg_title = '<span class="titleseperator">' . $GCMS['CONFIG']['title_separator'] . '</span> ' . $GCMS['LANG']['critical_information'];
                }
                if($link != '') {
                    $link = '<a href="' . $link . '">' . $GCMS['LANG']['forward'] . '</a>';
                }
            break;

            case GENERAL_ERROR:
                if($msg_text == '') {
                    $msg_text = $GCMS['LANG']['an_error_occured'];
                }
                if($msg_title == '') {
                    $msg_title = '<span class="titleseperator">' . $GCMS['CONFIG']['title_separator'] . '</span> ' . $GCMS['LANG']['general_error'];
                }
                if($link != '') {
                    $link = '<a href="' . $link . '">' . $GCMS['LANG']['forward'] . '</a>';
                }
            break;

            case CRITICAL_ERROR:
                load_language();
                
                if($msg_text == '') {
                    $msg_text = $GCMS['LANG']['a_critical_error'];
                }
                if($msg_title == '') {
                    $msg_title = 'gcms : <span style="font-weight:bold;">' . $GCMS['LANG']['critical_error'] . '</span>';
                }
            break;
        }

        if($GCMS['CONFIG']['debug'] AND ($msg_code == GENERAL_ERROR OR $msg_code == CRITICAL_ERROR)) {
            if($debug_text != '') {
                $msg_text = $msg_text . '<br /><br /><span style="font-weight:bold; font-style:italic;">DEBUG MODE</span>' . $debug_text;
            }
        }
        
        if($msg_code != CRITICAL_ERROR) {
            if(isset($GCMS['LANG'][$msg_text])) {
                $msg_text = $GCMS['LANG'][$msg_text];
            }

            if(is_array($GCMS['ROOTMODULE'])) {
                if(isset($GCMS['LANG'][strtoupper($GCMS['ROOTMODULE']['name'])][$msg_text])) {
                    $msg_text = $GCMS['LANG'][strtoupper($GCMS['ROOTMODULE']['name'])][$msg_text];
                }
            }
            
            if(!defined('IN_ADMIN')) {
                $GCMS['TEMPLATE']->set_filenames(array(
                    'message_body' => 'base/message_body.tpl')
                );
            } else {
                $GCMS['TEMPLATE']->set_filenames(array(
                    'message_body' => 'admin/message_body.tpl')
                );
            }
            
            $outlink = ($link == '') ? ('') : ('<span class="error_link">' . $link . '</span>');
            
            $GCMS['TEMPLATE']->assign_vars(array(
                'MESSAGE_TITLE' => $msg_title,
                'MESSAGE_TEXT' => $msg_text,
                'MESSAGE_LINK' => $outlink
            ));
            
            $GCMS['TEMPLATE']->pparse('message_body');
            
            if(!defined('IN_ADMIN')) {
                include(GCMS_REAL_PATH . '/includes/footer.php');
            } else {
                include(GCMS_REAL_PATH . '/admin/footer_admin.php');
            }
        } else {
            echo("<html>\n<body>\n" . $msg_title . "\n<br /><br />\n" . $msg_text . "\n</body>\n</html>");
        }
        
        exit;
    }


























    
?>
