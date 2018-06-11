<?php
defined('BASEPATH') OR exit ('No direct script access allowed');
if (!class_exists('csl_language_content') && !class_exists('csl_language')) {
	/**
	 * @about - language XML content processing.
	 * @param - string &$handle (a valid pointer)
	 * @return - object
	 * @usage - Object var name=new csl_language_content(&$handle);
	 */
	class csl_language_content {
		private $handle;
		function __construct(& $handle = null) {
			$caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
			$caller = end($caller);
			if (isset ($caller['class']) && $caller['class'] == 'csl_language') {
				$this->handle = $handle;
			} else {
				csl_error :: cast('Cannot instantiate class ' . __CLASS__, E_USER_ERROR, 1);
			}
		}
		/** Gets tag content.
		 * @access - public function
		 * @param - string $tag (text tag name)
		 * @param - boolean $html (html encode mode) : Default true
		 * @return - string|boolean
		 * @usage - Object->gets($tag,$html);
		 */
		public function gets($tag = null, $html = true) {
			$itself = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
			$itself = current($itself);
			if (isset ($itself['type']) && $itself['type'] == '->') {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0) && !csl_func_arg :: bool2error(1)) {
					if (!preg_match('/^[a-z]+[-_.0-9a-z]*$/i', $tag)) {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid tag name ' . $tag, E_USER_WARNING, 1);
					} else {
						$item = $this->handle->getElementsByTagName($tag);
						return ($item->length > 0 ? ($html ? htmlspecialchars($item->item(0)->nodeValue) : $item->item(0)->nodeValue) : '');
					}
				}
			} else {
				csl_error :: cast('Call the method failed for ' . __CLASS__ . '::' . __FUNCTION__ . '()', E_USER_ERROR, 1);
			}
			return false;
		}
	}
	/**
	 * @about - language XML file processing.
	 * @param - string $tag (xml main tag name) : Default language
	 * @param - string $version (xml version number) : Default 1.0
	 * @param - string $encoding (xml encoding type) : Default utf-8
	 * @return - object
	 * @usage - Object var name=new csl_language($tag,$version,$encoding);
	 */
	class csl_language {
		private $tag;
		private $doc;
		function __construct($tag = 'language', $version = '1.0', $encoding = 'utf-8') {
			$this->tag = 'language';
			if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0) && !csl_func_arg :: string2error(1) && !csl_func_arg :: string2error(2)) {
				if (!preg_match('/^[a-z]+[-_.0-9a-z]*$/i', $tag)) {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid xml main tag name ' . $tag, E_USER_WARNING, 1);
				}
				elseif (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $version)) {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid xml version number ' . $version, E_USER_WARNING, 1);
				}
				elseif (!csl_inspect :: is_iconv_encoding($encoding)) {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid xml encoding scheme ' . $encoding, E_USER_WARNING, 1);
				} else {
					$this->tag = $tag;
					$this->doc = new DOMDocument($version, $encoding);
				}
			}
		}
		/** Load localhost language XML file.
		 * @access - public function
		 * @param - string $path (file path)
		 * @return - object|boolean
		 * @usage - Object->load($path);
		 */
		public function load($path = null) {
			$object = false;
			if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0)) {
				clearstatcache();
				$normPath = csl_path :: norm($path);
				if (!isset ($normPath { 0 })) {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Empty path supplied as input', E_USER_WARNING, 1);
				}
				elseif (csl_path :: is_absolute($normPath) || (!csl_path :: is_root_model($normPath) && !csl_path :: is_relative($normPath))) {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
				}
				elseif (!is_file($normPath) || !is_readable($normPath) || !preg_match('/^(.)*\.xml$/i', $normPath)) {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Unable to load language file ' . $normPath, E_USER_NOTICE, 1);
				} else {
					if (!is_null($this->doc)) {
						if (@ $this->doc->load($normPath)) {
							$root = $this->doc->getElementsByTagName($this->tag);
							if ($root->item(0)) {
								$handle = $root->item(0);
								$object = new csl_language_content($handle);
							}
						}
						if ($object === false) {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Unable to load language file ' . $normPath, E_USER_NOTICE, 1);
						}
					} else {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(' . $normPath . '): failed to open stream: Wrong XML declaration', E_USER_WARNING, 1);
					}
				}
			}
			return $object;
		}
	}
}
?>