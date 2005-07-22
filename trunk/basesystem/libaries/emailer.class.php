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
	* @credits  this code is based on phpbb emailer class
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
	* gcms_emailer 
	*       
	* this class handles the email sending.
	*/
	class gcms_emailer {
		var $msg, $subject, $extra_headers;
		var $addresses, $reply_to, $from;
		var $use_smtp;

		var $tpl_msg = array();


		/**
		* constructor
		*/
		function gcms_emailer($use_smtp = '') {
			$this->reset();
			$this->use_smtp = $use_smtp;
			$this->reply_to = $this->from = '';
		}


		/**
		* resets all the data (address, template file, etc etc to default
		*/
		function reset() {
			$this->addresses = array();
			$this->vars = $this->msg = $this->extra_headers = '';
		}


		/**
		* sets an email address to send to
		*/
		function email_address($address) {
			$this->addresses['to'] = trim($address);
		}


		/**
		* adds carbon copy adress
		*/
		function cc($address) {
			$this->addresses['cc'][] = trim($address);
		}


		/**
		* adds blind copy adress
		*/
		function bcc($address) {
			$this->addresses['bcc'][] = trim($address);
		}

		
		/**
		* adds reply adress
		*/
		function replyto($address) {
			$this->reply_to = trim($address);
		}


		/**
		* adds sender adress
		*/
		function from($address) {
			$this->from = trim($address);
		}


		/**
		* set up subject for mail
		*/
		function set_subject($subject = '') {
			$this->subject = trim(preg_replace('#[\n\r]+#s', '', $subject));
		}


		/**
		* set up extra mail headers
		*/
		function extra_headers($headers) {
			$this->extra_headers .= trim($headers) . "\n";
		}


		/**
		* set email template
		*/
		function use_template($template_file, $template_lang = '', $module = '') {
			global $GCMS;

			if(trim($template_file) == '') {
				message_die(GENERAL_ERROR, 'no_email_template_set', '', __LINE__, __FILE__);
			}

			if(trim($template_lang) == '') {
				$template_lang = $GCMS['CONFIG']['default_language'];
			}

			if(empty($this->tpl_msg[$template_lang . $template_file])) {
				if(trim($module) != '') {
					$tpl_file = GCMS_REAL_PATH . '/modules/' . $module . '/languages/' . $template_lang . '/email/' . $template_file . '.tpl';
				} else {
					$tpl_file = GCMS_REAL_PATH . '/languages/' . $template_lang . '/email/' . $template_file . '.tpl';
				}

				if(!@file_exists($tpl_file)) {
					$tpl_file = GCMS_REAL_PATH . 'languages/' . $GCMS['CONFIG']['default_language'] . '/email/' . $template_file . '.tpl';

					if(!@file_exists($tpl_file)) {
						message_die(GENERAL_ERROR, sprintf($GCMS['LANG']['cant_find_email_template'], $template_file), '', __LINE__, __FILE__);
					}
				}

				if(!($fd = @fopen($tpl_file, 'r'))) {
					message_die(GENERAL_ERROR, sprintf($GCMS['LANG']['failed_open_email_template'], $tpl_file), '', __LINE__, __FILE__);
				}

				$this->tpl_msg[$template_lang . $template_file] = fread($fd, filesize($tpl_file));
				fclose($fd);
			}

			$this->msg = $this->tpl_msg[$template_lang . $template_file];

			return true;
		}

		
		/**
		* assign email template vars
		*/
		function assign_vars($vars) {
			$this->vars = (empty($this->vars)) ? ($vars) : ($this->vars . $vars);
		}


		/**
		* send the mail out to the recipients set previously in var $this->address
		*/
		function send() {
			global $GCMS;

			/**
			* escape all quotes, else the eval will fail.
			*/
			$this->msg = str_replace("'", "\'", $this->msg);
			$this->msg = preg_replace('#\{([a-z0-9\-_]*?)\}#is', "' . $\\1 . '", $this->msg);

			/**
			* set vars
			*/
			@reset($this->vars);
			
            foreach($this->vars AS $key => $val) {
				$$key = $val;
			}

			eval("\$this->msg = '$this->msg';");

			/**
			* clear vars
			*/
			@reset($this->vars);
			foreach($this->vars AS $key => $val) {
				unset($$key);
			}

			/**
			* we now try and pull a subject from the email body ... if it exists,
			* do this here because the subject may contain a variable
			*/
			$drop_header = '';
			$match = array();

			if(preg_match('#^(Subject:(.*?))$#m', $this->msg, $match)) {
				$this->subject = (trim($match[2]) != '') ? (trim($match[2])) : (($this->subject != '') ? ($this->subject) : ($GCMS['LANG']['no_subject']));
				$drop_header .= '[\r\n]*?' . gcms_preg_quote($match[1], '#');
			} else {
				$this->subject = ($this->subject != '') ? ($this->subject) : ($GCMS['LANG']['no_subject']);
			}

			if(preg_match('#^(Charset:(.*?))$#m', $this->msg, $match)) {
				$this->encoding = (trim($match[2]) != '') ? (trim($match[2])) : (trim($GCMS['LANG']['charset']));
				$drop_header .= '[\r\n]*?' . gcms_preg_quote($match[1], '#');
			} else {
				$this->encoding = trim($GCMS['LANG']['charset']);
			}

			if($drop_header != '') {
				$this->msg = trim(preg_replace('#' . $drop_header . '#s', '', $this->msg));
			}

			$to = (isset($this->addresses['to'])) ? ($this->addresses['to']) : ('');
			$cc = (isset($this->addresses['cc']) AND count($this->addresses['cc'])) ? (implode(', ', $this->addresses['cc'])) : ('');
			$bcc = (isset($this->addresses['bcc']) AND count($this->addresses['bcc'])) ? (implode(', ', $this->addresses['bcc'])) : ('');

			/**
			* build header
			*/
			$this->extra_headers = '';
			if($this->reply_to != '') {
				$this->extra_headers .= "Reply-to: " . $this->reply_to . "\n";
			} 
			if($this->from != '') {				
				$this->extra_headers .= "From: " . $this->from . "\n";
			} else {
				$this->extra_headers .= "From: " . $GCMS['CONFIG']['gcms_email'] . "\n";
			}
			$this->extra_headers .= "Return-Path: " . $GCMS['CONFIG']['gcms_email'] . "\n";
			$this->extra_headers .= "Message-ID: <" . md5(uniqid(time())) . "@" . $_SERVER['SERVER_NAME'] . ">\n";
			$this->extra_headers .= "MIME-Version: 1.0\n";
			$this->extra_headers .= "Content-type: text/plain; charset=" . $this->encoding . "\n";
			$this->extra_headers .= "Content-transfer-encoding: 8bit\n";
			$this->extra_headers .= "Date: " . date('r', time()) . "\n";
			$this->extra_headers .= "X-Priority: 3\n";
			$this->extra_headers .= "X-MSMail-Priority: Normal\n";
			$this->extra_headers .= "X-Mailer: PHP\n";
			$this->extra_headers .= "X-MimeOLE: Produced by GCMS\n";
			if($cc != '') {
				$this->extra_headers .= "Cc: " . $cc . "\n";
            }
			if($bcc != '') {
				$this->extra_headers .= "Bcc: " . $bcc . "\n";
			} 

			$to = ($to == '') ? ('') : ($to);

			
            /**
			* send message... 
			*/
			if($this->use_smtp) {
				require_once(GCMS_REAL_PATH . '/libaries/smtp_functions.php');
				$result = smtpmail($to, $this->subject, $this->msg, $this->extra_headers);
			} else {

                $result = mail($to, $this->subject, preg_replace("#(?<!\r)\n#s", "\n", $this->msg), $this->extra_headers);
                //$result = mail($to, 'test', 'aaa');
            }

			/**
			* did it work?
			*/
			if(!$result) {
				message_die(GENERAL_ERROR, sprintf($GCMS['LANG']['failed_send_email'], (($this->use_smtp) ? ('SMTP') : ('PHP'))), '', __LINE__, __FILE__);
			}

			return true;
		}


		/**
		* encodes the given string for proper display for this encoding ... nabbed 
		* from php.net and modified. there is an alternative encoding method which 
		* may produce lesd output but it's questionable as to its worth in this 
		* scenario imo
		*/
		function encode($str) {
			if ($this->encoding == '') {
				return $str;
			}

			/**
			* define start delimimter, end delimiter and spacer
			*/
			$end = '?=';
			$start = '=?' . $this->encoding . '?B?';
			$spacer = $end . "\r\n " . $start;

			/**
			* determine length of encoded text within chunks and ensure length is even
			*/
			$length = 75 - strlen($start) - strlen($end);
			$length = floor($length / 2) * 2;

			/**
			* encode the string and split it into chunks with spacers after each chunk
			*/
			$str = chunk_split(base64_encode($str), $length, $spacer);

			/**
			* remove trailing spacer and add start and end delimiters
			*/
			$str = preg_replace('#' . gcms_preg_quote($spacer, '#') . '$#', '', $str);

			return $start . $str . $end;
		}


        /**
        * get mime headers
        */
        function get_mime_headers($filename, $mime_filename = '') {
            $mime_boundary = "--==================_846811060==_";

            if($mime_filename) {
                $filename = $mime_filename;
            }

            $out  = "MIME-Version: 1.0\n";
            $out .= "Content-Type: multipart/mixed;\n\tboundary=\"" . $mime_boundary . "\"\n\n";
            $out .= "This message is in MIME format. Since your mail reader does not understand\n";
            $out .= "this format, some or all of this message may not be legible.";

            return $out;
        }


        /**
        * split string by RFC 2045 semantics (76 chars per line, end with \r\n).
        */
        function chunky_split($str) {
            $stmp = $str;
            $len = strlen($stmp);
            $out = "";

            while($len > 0) {
                if($len >= 76) {
                    $out .= substr($stmp, 0, 76) . "\r\n";
                    $stmp = substr($stmp, 76);
                    $len = $len - 76;
                } else {
                    $out .= $stmp . "\r\n";
                    $stmp = "";
                    $len = 0;
                }
            }
            
            return $out;
        }


        /**
        * split the specified file up into a string and return it
        */
        function encode_file($sourcefile) {
            if(is_readable($sourcefile)) {
                $fd = fopen($sourcefile, "r");
                
                $contents = fread($fd, filesize($sourcefile));
                $encoded = $this->chunky_split(base64_encode($contents));
                
                fclose($fd);
            }

            return $encoded;
        }
    } /* end of class gcms_emailer */
?>
