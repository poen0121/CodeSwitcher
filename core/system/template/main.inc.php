<?php
defined('BASEPATH') OR exit ('No direct script access allowed');
if (!class_exists('csl_template')) {
	/**
	 * @about - template control.
	 */
	class csl_template {
		private static $process = array ();
		private static $path, $line, $selfError;
		/** Error handler.
		 * @access - public function
		 * @param - integer $errno (error number)
		 * @param - string $message (error message)
		 * @param - string $file (file path)
		 * @param - integer $line (file line number)
		 * @return - boolean|null
		 * @usage - set_error_handler(__CLASS__.'::ErrorHandler');
		 */
		public static function ErrorHandler($errno = null, $message = null, $file = null, $line = null) {
			if (!(error_reporting() & $errno)) {
				// This error code is not included in error_reporting
				return;
			}
			//replace message target function
			if ($file == __FILE__ && $line == self :: $line) {
				self :: $selfError = true;
				$caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
				$caller = end($caller);
				$message = __CLASS__ . '::' . $caller['function'] . '(): ' . $message;
			}
			//echo message
			$title = '';
			switch ($errno) {
				case E_PARSE :
				case E_ERROR :
				case E_CORE_ERROR :
				case E_COMPILE_ERROR :
				case E_USER_ERROR :
					if ($file == __FILE__ && $line == self :: $line) {
						csl_error :: cast($message, $errno, 3);
					}
					$title = 'Fatal error';
					break;
				case E_WARNING :
				case E_USER_WARNING :
				case E_COMPILE_WARNING :
				case E_RECOVERABLE_ERROR :
					if ($file == __FILE__ && $line == self :: $line) {
						csl_error :: cast($message, $errno, 3);
						return true;
					}
					$title = 'Warning';
					break;
				case E_NOTICE :
				case E_USER_NOTICE :
					if ($file == __FILE__ && $line == self :: $line) {
						csl_error :: cast($message, $errno, 3);
						return true;
					}
					$title = 'Notice';
					break;
				case E_STRICT :
					if ($file == __FILE__ && $line == self :: $line) {
						csl_error :: cast($message, $errno, 3);
						return true;
					}
					$title = 'Strict';
					break;
				case E_DEPRECATED :
				case E_USER_DEPRECATED :
					if ($file == __FILE__ && $line == self :: $line) {
						csl_error :: cast($message, $errno, 3);
						return true;
					}
					$title = 'Deprecated';
					break;
				default :
					if ($file == __FILE__ && $line == self :: $line) {
						csl_error :: cast($message, $errno, 3);
						return true;
					}
					$title = 'Error [' . $errno . ']';
					break;
			}
			$message = '<br /><b>' . $title . '</b>: ' . $message . ' in <b>' . $file . '</b> on line <b>' . $line . '</b><br />';
			if ((bool) (isset ($_SERVER['ERROR_STACK_TRACE']) ? $_SERVER['ERROR_STACK_TRACE'] : false)) { //error stack trace
				$baseDepth = 1;
				$caller = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT);
				$rows = count($caller);
				if ($rows > $baseDepth) {
					$message .= PHP_EOL . 'Stack trace:' . PHP_EOL . '<br />';
					for ($i = $baseDepth; $i < $rows; $i++) {
						$stack = & $caller[$i];
						$argsList = ''; //args info
						if (isset ($stack['args'])) {
							foreach ($stack['args'] as $sort => $args) {
								$argsList .= ($sort > 0 ? ', ' : '');
								switch (gettype($args)) {
									case 'string' :
										$argsList .= '\'' . (mb_strlen($args, 'utf-8') > 20 ? mb_substr($args, 0, 17, 'utf-8') . '...' : $args) . '\'';
										break;
									case 'array' :
										$argsList .= 'Array';
										break;
									case 'object' :
										$argsList .= get_class($args) . ' Object';
										break;
									case 'resource' :
										$argsList .= get_resource_type($args) . ' Resource';
										break;
									case 'boolean' :
										$argsList .= ($args ? 'true' : 'false');
										break;
									case 'NULL' :
										$argsList .= 'NULL';
										break;
									default :
										$argsList .= $args;
										break;
								}
							}
						}
						$message .= '#' . ($i - $baseDepth) . ' ' . $stack['file'] . '(' . $stack['line'] . '):' . (isset ($stack['class']) ? ' ' . $stack['class'] . $stack['type'] : ' ') . $stack['function'] . '(' . $argsList . ')' . ($i < ($rows -1) ? PHP_EOL : '') . '<br />';
					}
				}
			}
			if (preg_match('/^(on|(\+|-)?[0-9]*[1-9]+[0-9]*)$/i', ini_get('log_errors'))) {
				error_log('PHP ' . strip_tags($message), 0);
			}
			if (preg_match('/^(on|(\+|-)?[0-9]*[1-9]+[0-9]*)$/i', ini_get('display_errors'))) {
				echo $message . PHP_EOL;
			}
			if ($title == 'Fatal error') {
				exit;
			}
			/* Don't execute PHP internal error handler */
			return true;
		}
		/** View content.
		 * @access - public function
		 * @param - string $path (template file path)
		 * @param - array $data (template param data array) : Default void array
		 * @param - boolean $process (return content string mode) : Default false
		 * @return - boolean|string
		 * @usage - csl_template::view($path,$data,$process);
		 */
		public static function view($path = null, $data = array (), $process = false) {
			if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0) && !csl_func_arg :: array2error(1) && !csl_func_arg :: bool2error(2)) {
				if (strlen($path) > 0) {
					clearstatcache();
					$path = csl_path :: norm($path);
					if (!csl_path :: is_absolute($path) && is_file($path) && is_readable($path) && preg_match('/^(.)*\.php$/i', $path)) {
						if (!ob_start()) {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Open output buffer failed to start', E_USER_ERROR, 1);
						}
						self :: $process[] = $process;
						self :: $path = $path;
						if (count($data) > 0) {
							extract($data);
						}
						set_error_handler(__CLASS__ . '::ErrorHandler');
						//note that the error is all access
						self :: $line = __LINE__;
						include (self :: $path); //mark the line number
						restore_error_handler();
						$process = end(self :: $process);
						unset (self :: $process[count(self :: $process) - 1]);
						$output = ob_get_contents();
						if ($output === false) {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid buffer ended', E_USER_WARNING, 1);
							return false;
						}
						ob_end_clean();
						if (!self :: $selfError) {
							if ($process) {
								return $output;
							} else {
								echo $output;
								return true;
							}
						} else {
							self :: $selfError = false;
							echo $output;
							return false;
						}
					} else {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Unable to load template file ' . $path, E_USER_NOTICE, 1);
					}
				} else {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Empty path supplied as input', E_USER_WARNING, 1);
				}
			}
			return false;
		}
	}
}
?>