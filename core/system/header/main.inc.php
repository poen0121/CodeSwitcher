<?php
defined('BASEPATH') OR exit ('No direct script access allowed');
if (!class_exists('csl_header')) {
	/**
	 * @about - header-related functions.
	 */
	class csl_header {
		private static $build;
		/** Set header no-cache.
		 * @access - public function
		 * @return - boolean
		 * @usage - csl_header::nocache();
		 */
		public static function nocache() {
			if (!csl_func_arg :: delimit2error()) {
				$fileName = null;
				$lineNum = null;
				if (!headers_sent($fileName, $lineNum)) {
					// Expires in the past
					header('Expires: -1');
					// Always modified
					header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
					// HTTP/1.1
					header('Cache-Control: no-cache, no-store, must-revalidate');
					header('Cache-Control: post-check=0, pre-check=0', false);
					// HTTP/1.0
					header('Pragma: no-cache');
					return true;
				} else {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Cannot modify header information - headers already sent by (output started at ' . $fileName . ':' . $lineNum . ')', E_USER_WARNING, 1);
				}
			}
			return false;
		}
		/** Set header http status code.
		 * @access - public function
		 * @param - string $text (http status text)
		 * @param - integer $code (http status code)
		 * @return - boolean
		 * @usage - csl_header::http($text,$code);
		 */
		public static function http($text = null, $code = null) {
			if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0) && !csl_func_arg :: int2error(1)) {
				if ($code < 100 || $code > 999) {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): The parameter 2 number should be 100 ~ 999', E_USER_WARNING, 1);
				} else {
					$fileName = null;
					$lineNum = null;
					if (!headers_sent($fileName, $lineNum)) {
						header((isset ($_SERVER['SERVER_PROTOCOL'] { 0 }) && is_string($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1') . ' ' . $code . ' ' . $text, TRUE, $code);
						return true;
					} else {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Cannot modify header information - headers already sent by (output started at ' . $fileName . ':' . $lineNum . ')', E_USER_WARNING, 1);
					}
				}
			}
			return false;
		}
		/** Set var hidden input.
		 * @access - private function
		 * @param - array $list (list array)
		 * @param - string $key (main key)
		 * @return - string
		 * @usage - self::setInput($list,$key);
		 */
		private static function setInput($list = null, $key = null) {
			$input = '';
			foreach ($list as $var => $val) {
				$var = (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc() ? stripslashes($var) : $var);
				$varName = ($key ? $key . '[' . $var . ']' : $var);
				if (is_array($val)) {
					$input .= self :: setInput($val, $varName);
				} else {
					$input .= '<input type="hidden" name="' . htmlspecialchars($varName) . '" value="' . htmlspecialchars(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc() ? stripslashes($val) : $val) . '">' . PHP_EOL;
				}
			}
			return $input;
		}
		/** Set header location like form, the function can not be called continuously.
		 * @access - public function
		 * @param - string $url (url string)
		 * @param - string $transfer (transfer method `GET` or `POST`) : Default GET
		 * @param - string $target (target mode `_parent` , `_top` , `_self`) : Default _self
		 * @return - boolean
		 * @usage - csl_header::location($url,$transfer,$target);
		 */
		public static function location($url = null, $method = 'GET', $target = '_self') {
			if (!self :: $build) {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0) && !csl_func_arg :: string2error(1) && !csl_func_arg :: string2error(2)) {
					$useMethod = strtoupper($method);
					$useTarget = strtolower($target);
					if ($useMethod != 'POST' && $useMethod != 'GET') {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid transfer method specified', E_USER_WARNING, 1);
					}
					elseif ($useTarget != '_parent' && $useTarget != '_top' && $useTarget != '_self') {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid target mode specified', E_USER_WARNING, 1);
					} else {
						$fileName = null;
						$lineNum = null;
						if (!headers_sent($fileName, $lineNum)) {
							header('Content-Type: text/html; charset=utf-8');
							self :: nocache();
							//Cut URL
							$parseUrl = parse_url($url);
							$urlQuery = (isset ($parseUrl['query']) ? $parseUrl['query'] : '');
							$urlFragment = (isset ($parseUrl['fragment']) ? $parseUrl['fragment'] : '');
							$urlPrefix = ($urlQuery ? substr($url, 0, strpos($url, '?' . $urlQuery)) : ($urlFragment ? substr($url, 0, strpos($url, '#' . $urlFragment)) : $url));
							$urlPrefix = strtr((substr($urlPrefix, -1, 1) === '?' ? substr($urlPrefix, 0, -1) : $urlPrefix), '\\', '/');
							//Refresh URI
							$refreshUrl = $urlPrefix . ($urlFragment ? '#' : '') . $urlFragment;
							$queryList = array ();
							parse_str($urlQuery, $queryList);
							$form = '<!DOCTYPE html>' . PHP_EOL;
							$form .= '<html>' . PHP_EOL;
							$form .= '<head>' . PHP_EOL;
							$form .= '<title>Redirect</title>' . PHP_EOL;
							$form .= '</head>' . PHP_EOL;
							$form .= '<body>' . PHP_EOL;
							$form .= '<form target="' . $useTarget . '" id="send" action="' . $refreshUrl . '" method="' . $useMethod . '" >' . PHP_EOL;
							$form .= self :: setInput($queryList);
							$form .= '</form>' . PHP_EOL;
							$form .= '<script type="text/javascript">' . PHP_EOL;
							$form .= 'document.getElementById(\'send\').submit();' . PHP_EOL;
							$form .= '</script>' . PHP_EOL;
							$form .= '<noscript>Your browser does not support JavaScript!</noscript>' . PHP_EOL;
							$form .= '</body>' . PHP_EOL;
							$form .= '</html>';
							echo $form;
							return self :: $build = true;
						} else {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Cannot modify header information - headers already sent by (output started at ' . $fileName . ':' . $lineNum . ')', E_USER_WARNING, 1);
						}
					}
				}
			} else {
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Cannot build location information - location already sent', E_USER_WARNING, 1);
			}
			return false;
		}
	}
}
?>