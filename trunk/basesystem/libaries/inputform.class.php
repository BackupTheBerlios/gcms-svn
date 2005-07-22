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
    * gcms_inputform 
    *       
    * this class handles the form generation.
    */ 
    class gcms_inputform {
        var $_rows;
        var $_hiddens;
        var $_action;
        var $_method;
        var $_target;
        var $_enctype;
        var $_events;
        var $_submit;
        var $_reset;
        var $_buttonextra;
        var $_help;


        /**
        * constructor
        */
        function gcms_inputform($action = '', $method = 'get', $submit = 'submit', $target = '', $enctype = '', $events = '', $reset = '', $buttonextra = '') {
            $this->_action = (empty($action)) ? $_SERVER["PHP_SELF"] : $action;
            $this->_method = $method;
            $this->_target = $target;
            $this->_enctype = $enctype;
            $this->_events = $events;
            $this->_submit = $submit;
            $this->_reset = $reset;
            $this->_buttonextra = $buttonextra;
        }


        /**
        * set hidden fields
        */
        function hidden($name, $value) {
            $this->_hiddens[$name]=$value;
        }

        
        /**
        * add rows
        */
        function addrow($title, $contents = '', $extraleft = '', $extraright = '') {
            $this->_rows[]=array(
                "title" => $title,
                "contents" => $contents,
                "extraleft" => $extraleft,
                "extraright" => $extraright
            );
    
            end($this->_rows);

            return key($this->_rows);
        }


        /**
        * add help
        */
        function addhelp($row, $title, $text) {
            $this->_help[$row] = array($title, $text);
        }


        /**
        * add form title
        */
        function addtitle($title) {
            $this->_rows[] = array("title" => $title);
        }
        

        /**
        * add break
        */
        function addbreak($break = '&nbsp;') {
            $this->_rows[] = array("break" => $break);

            end($this->_rows);

            return key($this->_rows);
        }

        
        /**
        * add message
        */
        function addmessage($message) {
            $this->_rows[] = array("message" => $message);
        }


        /**
        * show the form
        */
        function show($as_var = false) {
            $content = '<form action="' . $this->_action . '" method="' . $this->_method . '"';
            if(!empty($this->_target)) {
                $content .= ' target="' . $this->_target . '"';
            }
            if(!empty($this->_enctype)) {
                $content .= ' enctype="' . $this->_enctype . '"';
            }
            if(!empty($this->_events)) {
                $content .= ' ' . $this->_events;
            }
            $content .= '>' . "\n";

            if(is_array($this->_hiddens)) {
                foreach($this->_hiddens AS $name => $value) {
                    $content .= '<input type="hidden" name="' . $name . '" value="' . $value . '">' . "\n";
                }
            }


            /**
            * generate the css code
            */
            $content .= '<div class="inputform_container">' . "\n";

            if(is_array($this->_rows)) {
                foreach($this->_rows AS $key => $row) {
                    if(isset($row["break"])) {
                        $title = $row["break"];
                        
                        if(isset($this->_help[$key])) {
                            $title = $title . help_link($this->_help[$key][0], $this->_help[$key][1]);
                        }

                        $content .= '<div class="inputform_break">' . $title . '</div>' . "\n";
                    } elseif(isset($row["message"])) {
                        $content .= '<div class="inputform_message">' . "\n";
                        $content .= $row['message'] . "\n";
                        $content .= '</div>' . "\n";                        
                    } else { 
                        $title = $row["title"];
                    
                        if(isset($this->_help[$key])) {
                            $title = $title . help_link($this->_help[$key][0], $this->_help[$key][1]);
                        }

                        $content .= '<div class="inpuform_row">' . "\n";
                        $content .= '<div class="inputform_leftcell" ' . $row['extraleft'] . '>' . $title . '</div>' . "\n";
                        $content .= '<div class="inputform_rightcell" ' . $row['extraright'] . '>' . $row['contents'] . '</div>' . "\n";
                        $content .= '</div>' . "\n";
                    }
                }
            }

            $content .= '<div class="inputform_buttons">';
            if(!empty($this->_reset)) {
                $content .= '<input type="reset" name="reset" value="' . $this->_reset . '" ' . $this->_buttonextra . '/>&nbsp;&nbsp;&nbsp;';
            }
            $content .= '<input type="submit" name="submit" value="' . $this->_submit . '" ' . $this->_buttonextra . '/></div>' . "\n";
            $content .= '</div>' . "\n";
            $content .= '</form>' . "\n";

            if($as_var) {
                return $content;
            } else {
                echo($content);
            }
        }


        /**
        * generate time select
        */
        function time_select($prefix, $blank_line = true, $time = '') {
            if(empty($time)) {
                $time = date("H:i:s");
            }
            list($hour, $minute, $second) = explode("-", $time);

            if($hour>12) {
                $hour -= 12;
                $ampm = 'PM';
            } else {
                $ampm = 'AM';
            }

            for($x=0; $x<=12; $x++) {
                if($x==0 AND $blank_line) {
                    $values[0] = '';
                } else {
                    $key = ($x<10) ? ('0' . $x) : ($x);
                    $values[$key] = $x;
                }
            }
            $data = $this->select_tag($prefix."hour", $values, $hour)." : ";

            array_merge($values, range(13, 60));

            $data .= $this->select_tag($prefix."minute", $values, $minute)." : ";
            $data .= $this->select_tag($prefix."second", $values, $second)." ";

            $data .= $this->select_tag($prefix."ampm", array("AM"=>"AM","PM"=>"PM"), $ampm);
        }


        /**
        * generate date select
        */
        function date_select($prefix, $blank_line = true, $date = 'TODAY', $year_start = '', $year_end = '') {
            if($date == 'TODAY') {
                $date=date("Y-m-d");
            }
            list($year, $month, $day)=explode("-", $date);

            if(empty($year_start)) {
                $year_start = date("Y");
            }

            if(empty($year_end)) {
                $year_end = date("Y")+2;
            }

            for($x=0; $x<=12; $x++) {
                if($x==0 AND $blank_line) {
                    $values[0] = '';
                } elseif($x>0) {
                    $key = ($x<10) ? ('0' . $x) : ($x);
                    $values[$key] = date("F", mktime(0, 0, 0, $x));
                }
            }

            $data = $this->select_tag($prefix."month", $values, $month)." ";

            for($x=0; $x<=31; $x++) {
                if($x==0 AND $blank_line) {
                    $values[0] = '';
                } elseif($x>0) {
                    $key = ($x<10) ? ('0' . $x) : ($x);
                    $values[$key] = $x;
                }
            }

            $data .= $this->select_tag($prefix."day", $values, $day).", ";

            unset($values);

            if($blank_line) {
                $values = array();
            }

            for($x=$year_start; $x<=$year_end; $x++) {
                $values[$x]=$x;
            }
            
            $data .= $this->select_tag($prefix."year", $values, $year);

            return $data;
        }


        /**
        * generate text field
        */
        function text_box($name, $value, $size = 0, $maxlength = 0, $password = false, $extra = '') {
            $type = ($password) ? ('password') : ('text');

            $data = '<input type="' . $type . '" name="' . $name . '"';
            if($size > 0) {
                $data .= ' size="' . $size . '"';
            }
            if($maxlength > 0) {
                $data .= ' maxlength="' . $maxlength . '"';
            }
            $data .= ' value="' . $value . '" ' . $extra . '/>';

            return $data;
        }


        /**
        * generate text area
        */
        function textarea($name, $value, $cols = 30, $rows = 5, $extra = '') {
            $data = '<textarea name="' . $name . '" cols="' . $cols . '" rows="' . $rows . '" ' . $extra . '>' . $value . '</textarea>';

            return $data;
        }


        /**
        * generate select box
        */
        function select_tag($name, $values, $selected = '', $extra = '') {
            $data = '<select name="' . $name . '" ' . $extra . '>';

            foreach($values AS $value => $text) { 
                $data .= '<option value="' . $value . '"';
                if($value == $selected) {
                    $data .= ' selected="selected"';
                }
                $data .= '>' . $text . '</option>';
            }
            
            $data .= '</select>';

            return $data;
        }


        /**
        * generate select box with value as key
        */
        function select_tag_valaskey($name, $values, $selected = '', $extra = '') {
            $data = '<select name="' . $name . '" ' . $extra . '>';

            foreach($values AS $value => $text) {
                $data .= '<option value="' . $text . '"';
                if($text == $selected) {
                    $data .= ' selected="selected"';
                }
                $data .= '>' . $text . '</option>';
            }
            
            $data .= '</select>';
            
            return $data;
        }


        /**
        * generate radiobuttons
        */
        function radio_button($name, $values, $selected = '', $separator = '&nbsp;&nbsp;', $extra = '') {
            foreach($values AS $value => $text) {
                $data .= '<input type="radio" name="' . $name . '" value="' . $value . '"';
                if($selected == $value) {
                    $data .= ' checked="checked"';
                }        
                    
                $data .= ' ' . $extra . ' />&nbsp;' . $text . $separator;
            }

            return $data;
        }


        /**
        * generate checkbox
        */
        function checkbox($name, $value, $caption, $checked = 0, $extra = '') {
            $is_checked = (!empty($checked)) ? (' checked="checked"') : ('');

            $data = '<input type="checkbox" name="' . $name . '" value="' . $value . '"' . $is_checked . ' ' . $extra . '/>&nbsp;' . $caption;

            return $data;
        }


        /**
        * generates a list of checkboxes
        * $list and $checklist are both associative and should have the same indicies
        */
        function checkbox_list($prefix, $list, $separator = '&nbsp;&nbsp;', $checklist = 0) {
            /**
            * get the listing of options to check into a array function library usable format
            */
            if(empty($checklist)) {
                $checked_items = array();
            } else {
                if(!is_array($checklist)) {
                    $checked_items = array($checklist);
                } else {
                    $checked_items = $checklist;
                }
            }

            /**
            * loop through all the array elements and call function to generate the appropriate input tag
            */
            foreach($list AS $index => $info) {
                $check_name = $prefix.'['.$index.']';
                $check_value = $info['value'];
                $check_caption = $info['caption'];
                $is_checked = (in_array($check_value, $checked_items)) ? 1 : 0;

                $data .= $this->checkbox($check_name, $check_value, $check_caption, $is_checked) . $separator;
            }

            return $data;
        }
    } /* end of class gcms_inputform */
?>
