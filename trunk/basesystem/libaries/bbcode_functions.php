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
    * - smiley support
    */


    /**
    * general check
    */
    if(!defined('IN_GCMS')) {
        die('access denied');
    }


    /**
    * main bbcode function
    */
    function gcms_changetext($str, $clickable = false) {
        global $GCMS;

        /**
        * word wrapping
        */
        if(isset($GCMS['CONFIG']['word_wrap'])) {
            $str = preg_replace('/\S{' . $GCMS['CONFIG']['word_wrap'] . '}/', 
                '\\1 ', 
                $str);
        }
        
        /**
        * bold font - [b] [/b]
        */
        $str = preg_replace('#\[b\](.*?)\[/b\]#si',
            '<span style="font-weight: bold;">\\1</span>',
            $str);
        
        /**
        * italic font - [i] [/i]
        */
        $str = preg_replace('#\[i\](.*?)\[/i\]#si',
            '<span style="font-style: italic;">\\1</span>',
            $str);
        
        /**
        * underlined font - [u] [/u]
        */
        $str = preg_replace('#\[u\](.*?)\[/u\]#si',
            '<span style="text-decoration:underline;">\\1</span>',
            $str);

        /**
        * change textcolor - [color=#000000] [/color]
        */
        $str = preg_replace('#\[color=(\#[0-9A-F]{6}|[a-z\-]+)\](.*?)\[/color\]#si', 
            '<span style="color:#\\1;">\\2</span>', 
            $str);

        /**
        * change textsize - [size=10] [/size]
        */
        $str = preg_replace('#\[size=([1-2]?[0-9])\](.*?)\[/size\]#si', 
            '<span style="font-size:\\1px">\\2</span>', 
            $str);
        
        /**
        * images - [img] [/img]
        */
        $str = preg_replace('#\[img\]((http|ftp|https|ftps)://)([^ \?&=\#\"\n\r\t<]*?(\.(jpg|jpeg|gif|png)))\[/img\]#sie', 
            '<img src="\\1' . str_replace(' ', '%20', '\\3') . '" style="border-width:0px;" />', 
            $str);
        
        /**
        * url replacer
        */
        $str = preg_replace('#\[url\](.*)\[/url\]#si',
            '<a href="\\1"><span style="font-weight:bold;">\\1</span></a>',
            $str);
        
        $str = preg_replace('#\[url=(.*)\](.*)\[/url\]#si',
            '<a href="\\1"><span style="font-weight:bold;">\\2</span></a>',
            $str);

        $str = preg_replace('#(^|[^\"=]{1})(http://|ftp://|mailto:|news:)([^\s<>]+)([\s\n<>]|$)#si',
            '\1<a href="\2\3"><span style="font-weight:bold;">\2\3</span></a>\4',
            $str);

        /**
        * if needed make links automated clickable
        */
        if($clickable) {
            gcms_make_clickable($str);
        }

        /**
        * add html linebreaks
        */
        $str = nl2br($str);

        return $str;
    }


    /**
    * make clickable function
    */
    function gcms_make_clickable($text) {
        $text = preg_replace('#(script|about|applet|activex|chrome):#is', 
            "\\1&#058;", 
            $text);

        /**
        * pad it with a space so we can match things at the start of the 1st line.
        */
        $ret = ' ' . $text;

        /**
        * matches an "xxxx://yyyy" url at the start of a line, or after a space. 
        * xxxx can only be alpha characters. 
        * yyyy is anything up to the first space, newline, comma, double quote or < 
        */
        $ret = preg_replace("#(^|[\n ])([\w]+?://[^ \"\n\r\t<]*)#is", 
            "\\1<a href=\"\\2\">\\2</a>", 
            $ret);

        /**
        * matches a "www|ftp.xxxx.yyyy[/zzzz]" kinda lazy url thing 
        * must contain at least 2 dots. xxxx contains either alphanum, or "-" 
        * zzzz is optional.. will contain everything up to the first space, newline, 
        * comma, double quote or <. 
        */
        $ret = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r<]*)#is", 
            "\\1<a href=\"http://\\2\">\\2</a>", 
            $ret);

        /**
        * matches an email@domain type address at the start of a line, or after a space.
        * note: only the followed chars are valid; alphanums, "-", "_" and or ".".
        */
        $ret = preg_replace("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", 
            "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", 
            $ret);

        /**
        * remove our padding..
        */
        $ret = substr($ret, 1);

        return($ret);
    }
?>
