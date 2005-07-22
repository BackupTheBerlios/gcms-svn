<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<!--
* $Id: $
*
* gcms - yet anothe content managment system
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
//-->

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
<title>{GCMS_TITLE} :: {PAGE_TITLE}</title>
<meta http-equiv="Content-Type" content="text/html; charset={CHARSET}" />

{METADATA}

<style type="text/css">
@import url("{TEMPLATE_DIR}/main.css");

body, html {
	background: #F2F2F2 url("{TEMPLATE_DIR}/images/bg.jpg") repeat-y 50% 0;
}

#logo a {
	background: url("{TEMPLATE_DIR}/images/ghcif_index.jpg") no-repeat center top;
}
</style>

<link rel="shortcut icon"  href="{TEMPLATE_DIR}/images/favicon.ico" type="image/x-icon" />
</head>
	
<body>
<div id="main">

<h1 id="logo"><a href="{BASE_DIR}/" title="germans home of cybernetic information and facts">ghcif</a></h1>

{MAIN_NAVI}

{SUB_NAVI}

<div id="content">
