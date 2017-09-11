<?php
if (!class_exists('csl_mvc')) {
	//default document root directory
	$_SERVER['DOCUMENT_ROOT'] = (isset ($_SERVER['DOCUMENT_ROOT'] { 0 }) && is_string($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : dirname(dirname(__FILE__)));
	//defines the path of the CodeSwitcher root directory
	define('BASEPATH', rtrim(strtr(dirname(dirname(__FILE__)), '\\', '/'), '/') . '/');
	//including the CodeSwitcher system library
	include (BASEPATH . 'core/system/error/main.inc.php');
	include (BASEPATH . 'core/system/func_arg/main.inc.php');
	include (BASEPATH . 'core/system/header/main.inc.php');
	include (BASEPATH . 'core/system/inspect/main.inc.php');
	include (BASEPATH . 'core/system/path/main.inc.php');
	include (BASEPATH . 'core/system/import/main.inc.php');
	include (BASEPATH . 'core/system/file/main.inc.php');
	include (BASEPATH . 'core/system/language/main.inc.php');
	include (BASEPATH . 'core/system/template/main.inc.php');
	include (BASEPATH . 'core/system/version/main.inc.php');
	include (BASEPATH . 'core/system/browser/main.inc.php');
	include (BASEPATH . 'core/system/time/main.inc.php');
	include (BASEPATH . 'core/system/debug/main.inc.php');
	/**
	 * @about - this is the code version control framework.
	 */
	class csl_mvc {
		private static $runEvent;
		private static $scriptEvent;
		private static $versionClass;
		private static $rootDir;
		private static $script;
		private static $portal;
		private static $intro;
		private static $language;
		private static $tester;
		private static $develop;
		private static $tripSystem;
		private static $error500;
		private static $uriLayer;
		/** Start info.
		 * @access - private function
		 * @return - null
		 * @usage -  self::start();
		 */
		private static function start() {
			if (is_null(self :: $portal)) {
				clearstatcache();
				csl_debug :: trace_error_handler(false); //system default error stack trace mode
				csl_debug :: error_log_file(BASEPATH . 'storage/logs/CS-' . csl_time :: get_date('host') . '.log'); //PHP system log file
				csl_debug :: error_log_file(BASEPATH . 'storage/logs/CS-' . csl_time :: get_date('host') . '.log', 'PHP-CS'); //peel of system log file
				csl_debug :: report(true); //error mode E_ALL
				csl_debug :: record(true); //save error logs
				csl_debug :: display(true); //erorr display
				self :: $runEvent = false; //event running state
				self :: $rootDir = csl_path :: document_root();
				self :: $tester = false; //tester mode
				self :: $develop = false; //develop mode by tester
				self :: $portal = false; //portal script state
				self :: $versionClass = new csl_version(); //version controller
				self :: $script = (isset ($_SERVER['SCRIPT_FILENAME']) && is_string($_SERVER['SCRIPT_FILENAME']) ? csl_path :: clean(realpath($_SERVER['SCRIPT_FILENAME'])) : false); //script path
				if (self :: $script !== false) {
					$hostDir = csl_path :: clean(BASEPATH);
					self :: $portal = (bool) preg_match('/^' . str_replace('/', '\/', $hostDir) . '(events\/.+\/){0,1}index.php$/i', self :: $script);
					//get target script
					if (self :: $portal) {
						self :: $script = trim(substr(self :: $script, strlen($hostDir)), '/');
						self :: $script = (preg_match('/^index.php$/i', self :: $script) ? null : trim(substr(csl_path :: cutdir(self :: $script), 7), '/'));
					}
				}
			}
		}
		/** Initialize system config info.
		 * @access - private function
		 * @param - string $__FUNCTION__ (error display function name)
		 * @return - null
		 * @usage -  self::init($__FUNCTION__);
		 */
		private static function init($__FUNCTION__) {
			/*---load-system-config---*/
			self :: $tripSystem = true;
			$CS_CONF = self :: cueConfig('CodeSwitcher');
			self :: $tripSystem = false; //system default running state
			$CS_CONF = (is_array($CS_CONF) ? $CS_CONF : array ()); //check CodeSwitcher config array type
			//intro page
			if (!isset ($CS_CONF['INTRO']) || !is_string($CS_CONF['INTRO'])) {
				csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . ': Init failed - unknown introduction page configuration', E_USER_ERROR, 2);
			}
			//timezone
			if (!isset ($CS_CONF['DEFAULT_TIMEZONE']) || !is_string($CS_CONF['DEFAULT_TIMEZONE'])) {
				csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . ': Init failed - unknown timezone id configuration', E_USER_ERROR, 2);
			}
			//languages xml version
			if (isset ($CS_CONF['LANGUAGE_XML_VERSION']) && is_string($CS_CONF['LANGUAGE_XML_VERSION'])) {
				if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $CS_CONF['LANGUAGE_XML_VERSION'])) {
					csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . ': Init failed - invalid language XML version number \'' . $CS_CONF['LANGUAGE_XML_VERSION'] . '\' configuration', E_USER_ERROR, 2);
				}
			} else {
				csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . ': Init failed - unknown language XML version number configuration', E_USER_ERROR, 2);
			}
			//languages xml enciding
			if (isset ($CS_CONF['LANGUAGE_XML_ENCODING']) && is_string($CS_CONF['LANGUAGE_XML_ENCODING'])) {
				if (!csl_inspect :: is_iconv_encoding($CS_CONF['LANGUAGE_XML_ENCODING'])) {
					csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . ': Init failed - invalid language XML encoding scheme \'' . $CS_CONF['LANGUAGE_XML_ENCODING'] . '\' configuration', E_USER_ERROR, 2);
				}
			} else {
				csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . ': Init failed - unknown language XML encoding scheme configuration', E_USER_ERROR, 2);
			}
			//error log storage directory
			if (!isset ($CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION']) || !is_string($CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION'])) {
				csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . ': Init failed - unknown error log storage directory configuration', E_USER_ERROR, 2);
			}
			//error stack trace mode
			if (!isset ($CS_CONF['ERROR_STACK_TRACE_MODE']) || !is_bool($CS_CONF['ERROR_STACK_TRACE_MODE'])) {
				csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . ': Init failed - unknown error stack trace mode configuration', E_USER_ERROR, 2);
			}
			//error log storage mode
			if (!isset ($CS_CONF['ERROR_LOG_MODE']) || !is_bool($CS_CONF['ERROR_LOG_MODE'])) {
				csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . ': Init failed - unknown error log storage mode configuration', E_USER_ERROR, 2);
			}
			//testers debug display mode
			if (!isset ($CS_CONF['TESTER_DEBUG_MODE']) || !is_bool($CS_CONF['TESTER_DEBUG_MODE'])) {
				csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . ': Init failed - unknown testers debug display mode configuration', E_USER_ERROR, 2);
			}
			//testers develop mode
			if (!isset ($CS_CONF['TESTER_DEVELOP_MODE']) || !is_bool($CS_CONF['TESTER_DEVELOP_MODE'])) {
				csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . ': Init failed - unknown testers develop mode configuration', E_USER_ERROR, 2);
			}
			/*---setting---*/
			//set intro page
			self :: $intro = trim(csl_path :: clean(self :: $rootDir . $CS_CONF['INTRO']), '/');
			//set timezone
			if (isset ($CS_CONF['DEFAULT_TIMEZONE'] { 0 }) && !csl_time :: set_timezone($CS_CONF['DEFAULT_TIMEZONE'])) {
				csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . ': Init failed - change timezone id \'' . $CS_CONF['DEFAULT_TIMEZONE'] . '\' is invalid', E_USER_ERROR, 2);
			}
			//build languages xml object
			self :: $language = new csl_language('language', $CS_CONF['LANGUAGE_XML_VERSION'], $CS_CONF['LANGUAGE_XML_ENCODING']);
			//set error log storage file
			if (isset ($CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION'] { 0 })) {
				$CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION'] = csl_path :: norm($CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION']);
				$CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION'] = (substr($CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION'], -1, 1) !== '/' ? $CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION'] . '/' : $CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION']);
				csl_debug :: error_log_file($CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION'] . 'CS-' . csl_time :: get_date('host') . '.log'); //PHP system log file
				csl_debug :: error_log_file($CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION'] . 'CS-' . csl_time :: get_date('host') . '.log', 'PHP-CS'); //peel of system log file
			} else {
				csl_debug :: error_log_file(BASEPATH . 'storage/logs/CS-' . csl_time :: get_date('host') . '.log'); //PHP system log file
				csl_debug :: error_log_file(BASEPATH . 'storage/logs/CS-' . csl_time :: get_date('host') . '.log', 'PHP-CS'); //peel of system log file
			}
			//set error stack trace mode
			csl_debug :: trace_error_handler($CS_CONF['ERROR_STACK_TRACE_MODE']);
			//set error log storage mode
			csl_debug :: record($CS_CONF['ERROR_LOG_MODE']);
			//source IP verification tester
			self :: $tester = (isset ($_SERVER['argc']) && $_SERVER['argc'] >= 1 ? true : (preg_match('/^(localhost|127.0.0.1)$/i', (isset ($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '')) ? true : in_array(csl_browser :: info('ip'), (isset ($CS_CONF['TESTER_IP']) && is_array($CS_CONF['TESTER_IP']) ? $CS_CONF['TESTER_IP'] : array ()), true)));
			//set tester mode
			if (self :: $tester) {
				//set debug mode
				csl_debug :: display($CS_CONF['TESTER_DEBUG_MODE']);
				//set develop mode
				self :: $develop = $CS_CONF['TESTER_DEVELOP_MODE'];
			} else {
				//set erorr display none
				csl_debug :: display(false);
			}
		}
		/** Turns off all output buffers by the output control function.
		 * @access - private function
		 * @return - null
		 * @usage -  self::obClean();
		 */
		private static function obClean() {
			while (ob_get_level() > 0) {
				ob_end_clean();
			}
			return;
		}
		/** Output error 500.
		 * @access - private function
		 * @return - null
		 * @usage -  self::ERROR500();
		 */
		private static function ERROR500() {
			if (!csl_debug :: is_display() && !headers_sent() && !self :: $error500) {
				self :: $error500 = true;
				self :: obClean();
				self :: $tripSystem = true;
				self :: viewTemplate('error/500');
				self :: $tripSystem = false;
			}
			return;
		}
		/** Get the available version info from the file directory path name of the CodeSwitcher root directory.
		 * @access - public function
		 * @param - string $pathName (path name in framework)
		 * @param - string $mode (returns directory relative path or version number) : Default false
		 * @note - $mode `true` is returns directory relative path.
		 * @note - $mode `false` is returns version number.
		 * @return - string|boolean
		 * @usage - csl_mvc::version($pathName,$mode);
		 */
		public static function version($pathName = null, $mode = false) {
			self :: start();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0) && !csl_func_arg :: bool2error(1)) {
					if (!isset ($pathName {
						0 }) || csl_path :: is_absolute($pathName) || !csl_path :: is_relative($pathName)) {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument by parameter 1', E_USER_WARNING, 1);
					} else {
						$pathName = trim(csl_path :: clean(self :: $rootDir . $pathName), '/');
						if (!isset ($pathName {
							0 })) {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument by parameter 1', E_USER_WARNING, 1);
						}
						elseif (is_dir(BASEPATH . $pathName)) {
							$maxVersion = BASEPATH . $pathName . '/ini/version.php';
							$maxVersion = (is_file($maxVersion) && is_readable($maxVersion) ? csl_import :: from($maxVersion) : '');
							if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)*\.([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $maxVersion)) {
								self :: ERROR500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Version failed - unknown \'' . $pathName . '\' defined version number', E_USER_ERROR, 1);
							}
							if (!self :: $versionClass->is_exists(BASEPATH . $pathName, $maxVersion)) {
								self :: ERROR500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Version failed - defined \'' . $pathName . '\' version \'' . $maxVersion . '\' has not been established', E_USER_ERROR, 1);
							}
							$pathInfo = explode('/', $pathName);
							$version = self :: $versionClass->get(BASEPATH . $pathName, (!self :: $tester || !self :: $develop || (count($pathInfo) === 2 && $pathInfo[0] == 'configs' && $pathInfo[1] == 'CodeSwitcher') ? $maxVersion : '')); //CodeSwitcher is system config
							if ($version) {
								return ($mode ? csl_path :: relative(BASEPATH . $pathName . '/' . $version . '/') : $version);
							} else {
								self :: ERROR500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Version failed - unable to get \'' . $pathName . '\' version', E_USER_ERROR, 1);
							}
						} else {
							self :: ERROR500();
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Version failed - target \'' . $pathName . '\' does not exist', E_USER_ERROR, 1);
						}
					}
				}
			} else {
				self :: ERROR500();
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_ERROR, 1);
			}
			return false;
		}
		/** Get the relative path from the file path name of the CodeSwitcher root directory.
		 * @access - public function
		 * @param - string $pathName (path name in framework)
		 * @param - string $uriMode (client URI analysis mode) : Default false
		 * @return - string|boolean
		 * @usage - csl_mvc::formPath($pathName,$uriMode);
		 */
		public static function formPath($pathName = null, $uriMode = false) {
			self :: start();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0) && !csl_func_arg :: bool2error(1)) {
					if (csl_path :: is_relative($pathName)) {
						$pathName = ltrim(csl_path :: clean(self :: $rootDir . $pathName), '/');
						if ($uriMode) {
							if (isset ($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME']) && is_string($_SERVER['REQUEST_URI']) && is_string($_SERVER['SCRIPT_NAME'])) {
								if (is_null(self :: $uriLayer)) {
									self :: $uriLayer = 0;
									$partStop = false;
									$uriPart = explode('/', $_SERVER['REQUEST_URI']);
									$uriEnd = count($uriPart) - 1;
									$scriptPart = explode('/', csl_path :: clean($_SERVER['SCRIPT_NAME']));
									$partEnd = count($scriptPart) - 1;
									$partName = current($scriptPart);
									$partKey = key($scriptPart);
									foreach ($uriPart as $uriKey => $uriName) {
										if (!$partStop && $uriName === $partName) {
											if ($partKey !== $partEnd) {
												$partName = next($scriptPart);
												$partKey = key($scriptPart);
											} else {
												$partStop = true;
											}
										} else {
											if (!$partStop && ($uriName !== '' || $uriKey == $uriEnd)) {
												$partStop = true;
											} else {
												self :: $uriLayer++;
											}
										}
									}
									self :: $uriLayer = str_repeat('../', self :: $uriLayer);
								}
								$path = csl_path :: relative(BASEPATH . $pathName);
								$path = (self :: $uriLayer && strpos($path, './') === 0 ? substr($path, 2) : $path);
								return self :: $uriLayer . $path;
							}
						} else {
							return csl_path :: relative(BASEPATH . $pathName);
						}
					}
				}
			} else {
				self :: ERROR500();
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_ERROR, 1);
			}
			return false;
		}
		/** Captures the name of the script event that is currently running.
		 * @access - public function
		 * @return - string|boolean
		 * @usage - csl_mvc::scriptEvent();
		 */
		public static function scriptEvent() {
			self :: start();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error()) {
					return self :: $scriptEvent;
				}
			} else {
				self :: ERROR500();
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_ERROR, 1);
			}
			return false;
		}
		/** Returns whether the event index page file exists from the events script directory path name of the CodeSwitcher root directory.
		 * @access - public function
		 * @param - string $eventName (events script directory path name)
		 * @return - boolean
		 * @usage - csl_mvc::isPortal($eventName);
		 */
		public static function isPortal($eventName = null) {
			self :: start();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0)) {
					if (isset ($eventName {
						0 }) && csl_path :: is_relative($eventName)) {
						$eventName = trim(csl_path :: clean(self :: $rootDir . $eventName), '/');
						if (isset ($eventName {
							0 })) {
							if ($eventName == self :: $intro) {
								$file = BASEPATH . 'index.php';
								$file = (is_file($file) ? true : null);
								if (is_null($file)) {
									$file = BASEPATH . 'events/' . $eventName . '/index.php';
									$file = (is_file($file) ? true : null);
								}
							} else {
								$file = BASEPATH . 'events/' . $eventName . '/index.php';
								$file = (is_file($file) ? true : null);
							}
							return isset ($file);
						}
					}
				}
			} else {
				self :: ERROR500();
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_ERROR, 1);
			}
			return false;
		}
		/** Returns whether the event controller file exists from the events script directory path name of the CodeSwitcher root directory.
		 * @access - public function
		 * @param - string $eventName (events script directory path name)
		 * @return - boolean
		 * @usage - csl_mvc::isEvent($eventName);
		 */
		public static function isEvent($eventName = null) {
			self :: start();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0)) {
					if (isset ($eventName {
						0 }) && csl_path :: is_relative($eventName)) {
						$eventName = trim(csl_path :: clean(self :: $rootDir . $eventName), '/');
						if (isset ($eventName {
							0 })) {
							if (is_dir(BASEPATH . 'events/' . $eventName)) {
								$maxVersion = BASEPATH . 'events/' . $eventName . '/ini/version.php';
								$maxVersion = (is_file($maxVersion) && is_readable($maxVersion) ? csl_import :: from($maxVersion) : '');
								if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)*\.([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $maxVersion)) {
									return false;
								}
								if (!self :: $versionClass->is_exists(BASEPATH . 'events/' . $eventName, $maxVersion)) {
									return false;
								}
								$version = self :: $versionClass->get(BASEPATH . 'events/' . $eventName, (!self :: $tester || !self :: $develop ? $maxVersion : ''));
								if ($version) {
									$file = BASEPATH . 'events/' . $eventName . '/' . $version . '/main.inc.php';
									if (is_file($file) && is_readable($file)) {
										return true;
									}
								}
							}
						}
					}
				}
			} else {
				self :: ERROR500();
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_ERROR, 1);
			}
			return false;
		}
		/** Returns error log storage status 0 or 1, a temporarily change.
		 * @access - public function
		 * @param - boolean $mode (temporarily change mode does not support tester mode) : Default empty
		 * @return - integer|boolean
		 * @usage - csl_mvc::logs($mode);
		 */
		public static function logs($mode = false) {
			self :: start();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: bool2error(0)) {
					$numargs = func_num_args();
					if ($numargs == 0) {
						return (csl_debug :: is_record() ? 1 : 0);
					}
					elseif (!self :: $tester && csl_debug :: record($mode)) { //set error log storage mode
						return (csl_debug :: is_record() ? 1 : 0);
					}
					elseif (self :: $tester) {
						return (csl_debug :: is_record() ? 1 : 0);
					}
				}
			} else {
				self :: ERROR500();
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_ERROR, 1);
			}
			return false;
		}
		/** Returns debug display state 0 or 1, a temporarily change.
		 * @access - public function
		 * @param - boolean $mode (temporarily change mode does not support tester mode) : Default empty
		 * @return - integer|boolean
		 * @usage - csl_mvc::debug($mode);
		 */
		public static function debug($mode = false) {
			self :: start();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: bool2error(0)) {
					$numargs = func_num_args();
					if ($numargs == 0) {
						return (csl_debug :: is_display() ? 1 : 0);
					}
					elseif (!self :: $tester && csl_debug :: display($mode)) { //set debug mode
						return (csl_debug :: is_display() ? 1 : 0);
					}
					elseif (self :: $tester) {
						return (csl_debug :: is_display() ? 1 : 0);
					}
				}
			} else {
				self :: ERROR500();
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_ERROR, 1);
			}
			return false;
		}
		/** Load configuration data form the CodeSwitcher configs directory.
		 * @access - public function
		 * @param - string $model (model name)
		 * @return - data|error|boolean
		 * @usage - csl_mvc::cueConfig($model);
		 */
		public static function cueConfig($model = null) {
			self :: start();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0)) {
					if (!isset ($model {
						0 }) || csl_path :: is_absolute($model) || !csl_path :: is_relative($model)) {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
					} else {
						$model = trim(csl_path :: clean(self :: $rootDir . $model), '/');
						if (!isset ($model {
							0 })) {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
						}
						elseif (is_dir(BASEPATH . 'configs/' . $model)) {
							$maxVersion = BASEPATH . 'configs/' . $model . '/ini/version.php';
							$maxVersion = (is_file($maxVersion) && is_readable($maxVersion) ? csl_import :: from($maxVersion) : '');
							if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)*\.([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $maxVersion)) {
								self :: ERROR500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Config failed - unknown \'' . $model . '\' defined version number', E_USER_ERROR, 1);
							}
							if (!self :: $versionClass->is_exists(BASEPATH . 'configs/' . $model, $maxVersion)) {
								self :: ERROR500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Config failed - defined \'' . $model . '\' version \'' . $maxVersion . '\' has not been established', E_USER_ERROR, 1);
							}
							$version = self :: $versionClass->get(BASEPATH . 'configs/' . $model, (!self :: $tester || !self :: $develop || $model == 'CodeSwitcher' ? $maxVersion : '')); //CodeSwitcher is system config
							if ($version) {
								$file = BASEPATH . 'configs/' . $model . '/' . $version . '/main.inc.php';
								if (is_file($file) && is_readable($file)) {
									if (ob_start()) {
										$obStartLevel = ob_get_level();
										$content = csl_import :: from($file);
										if ($content !== false) {
											if (ob_get_level() >= $obStartLevel) {
												$output = ob_get_contents();
												ob_end_clean();
												echo $output;
												return $content;
											} else {
												self :: ERROR500();
												csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Config failed - terminate the buffer loading \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
											}
										} else {
											self :: ERROR500();
											csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Config failed - terminate loading \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
										}
									} else {
										self :: ERROR500();
										csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): System failed - open output buffer failed to start', E_USER_ERROR, 1);
									}
								} else {
									self :: ERROR500();
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Config failed - could not load \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
								}
							} else {
								self :: ERROR500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Config failed - unable to get \'' . $model . '\' version', E_USER_ERROR, 1);
							}
						} else {
							self :: ERROR500();
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Config failed - target \'' . $model . '\' does not exist', E_USER_ERROR, 1);
						}
					}
				}
			} else {
				self :: ERROR500();
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_ERROR, 1);
			}
			return false;
		}
		/** Load create a language object form the CodeSwitcher languages directory.
		 * @access - public function
		 * @param - string $model (model name)
		 * @return - object|error|boolean
		 * @usage - csl_mvc::cueLanguage($model);
		 */
		public static function cueLanguage($model = null) {
			self :: start();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0)) {
					if (!isset ($model {
						0 }) || csl_path :: is_absolute($model) || !csl_path :: is_relative($model)) {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
					} else {
						$model = trim(csl_path :: clean(self :: $rootDir . $model), '/');
						if (!isset ($model {
							0 })) {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
						}
						elseif (is_dir(BASEPATH . 'languages/' . $model)) {
							$maxVersion = BASEPATH . 'languages/' . $model . '/ini/version.php';
							$maxVersion = (is_file($maxVersion) && is_readable($maxVersion) ? csl_import :: from($maxVersion) : '');
							if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)*\.([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $maxVersion)) {
								self :: ERROR500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Language failed - unknown \'' . $model . '\' defined version number', E_USER_ERROR, 1);
							}
							if (!self :: $versionClass->is_exists(BASEPATH . 'languages/' . $model, $maxVersion)) {
								self :: ERROR500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Language failed - defined \'' . $model . '\' version \'' . $maxVersion . '\' has not been established', E_USER_ERROR, 1);
							}
							$version = self :: $versionClass->get(BASEPATH . 'languages/' . $model, (!self :: $tester || !self :: $develop ? $maxVersion : ''));
							if ($version) {
								if (ob_start()) {
									$file = BASEPATH . 'languages/' . $model . '/' . $version . '/main.inc.xml';
									if (is_file($file) && is_readable($file)) {
										$content = self :: $language->load($file);
										if ($content !== false) {
											ob_end_clean();
											return $content;
										} else {
											self :: ERROR500();
											csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Language failed - terminate loading \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
										}
									} else {
										self :: ERROR500();
										csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Language failed - could not load \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
									}
								} else {
									self :: ERROR500();
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): System failed - open output buffer failed to start', E_USER_ERROR, 1);
								}
							} else {
								self :: ERROR500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Language failed - unable to get \'' . $model . '\' version', E_USER_ERROR, 1);
							}
						} else {
							self :: ERROR500();
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Language failed - target \'' . $model . '\' does not exist', E_USER_ERROR, 1);
						}
					}
				}
			} else {
				self :: ERROR500();
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_ERROR, 1);
			}
			return false;
		}
		/** Load the begin program form the CodeSwitcher begin directory.
		 * @access - private function
		 * @param - string $__FUNCTION__ (error display function name)
		 * @return - error|boolean
		 * @usage - self::begin();
		 */
		private static function begin($__FUNCTION__) {
			if (is_dir(BASEPATH . 'begin')) {
				$maxVersion = BASEPATH . 'begin/ini/version.php';
				$maxVersion = (is_file($maxVersion) && is_readable($maxVersion) ? csl_import :: from($maxVersion) : '');
				if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)*\.([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $maxVersion)) {
					self :: ERROR500();
					csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . ': Begin failed - unknown defined version number', E_USER_ERROR, 2);
				}
				if (!self :: $versionClass->is_exists(BASEPATH . 'begin', $maxVersion)) {
					self :: ERROR500();
					csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . ': Begin failed - defined version \'' . $maxVersion . '\' has not been established', E_USER_ERROR, 2);
				}
				$version = self :: $versionClass->get(BASEPATH . 'begin', (!self :: $tester || !self :: $develop ? $maxVersion : ''));
				if ($version) {
					$file = BASEPATH . 'begin/' . $version . '/main.inc.php';
					if (is_file($file) && is_readable($file)) {
						if (ob_start()) {
							$obStartLevel = ob_get_level();
							self :: $tripSystem = true;
							$import = csl_import :: from($file);
							self :: $tripSystem = false;
							if (ob_get_level() >= $obStartLevel) {
								$output = ob_get_contents();
								ob_end_clean();
								if ($import !== true) {
									self :: obClean();
								}
								echo $output;
								return ($import === true ? true : false);
							} else {
								self :: ERROR500();
								csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . ': Begin failed - terminate the buffer loading version \'' . $version . '\' main file', E_USER_ERROR, 2);
							}
						} else {
							self :: ERROR500();
							csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . ': System failed - open output buffer failed to start', E_USER_ERROR, 2);
						}
					} else {
						self :: ERROR500();
						csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . ': Begin failed - could not load version \'' . $version . '\' main file', E_USER_ERROR, 2);
					}
				} else {
					self :: ERROR500();
					csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . ': Begin failed - unable to get version', E_USER_ERROR, 2);
				}
			} else {
				self :: ERROR500();
				csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . ': Begin failed - file does not exist', E_USER_ERROR, 2);
			}
			return false;
		}
		/** Load the commit program form the CodeSwitcher commit directory.
		 * @access - private function
		 * @param - string $__FUNCTION__ (error display function name)
		 * @return - error|boolean
		 * @usage - self::commit($__FUNCTION__);
		 */
		private static function commit($__FUNCTION__) {
			if (is_dir(BASEPATH . 'commit')) {
				$maxVersion = BASEPATH . 'commit/ini/version.php';
				$maxVersion = (is_file($maxVersion) && is_readable($maxVersion) ? csl_import :: from($maxVersion) : '');
				if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)*\.([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $maxVersion)) {
					self :: ERROR500();
					csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . ': Commit failed - unknown defined version number', E_USER_ERROR, 2);
				}
				if (!self :: $versionClass->is_exists(BASEPATH . 'commit', $maxVersion)) {
					self :: ERROR500();
					csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . ': Commit failed - defined version \'' . $maxVersion . '\' has not been established', E_USER_ERROR, 2);
				}
				$version = self :: $versionClass->get(BASEPATH . 'commit', (!self :: $tester || !self :: $develop ? $maxVersion : ''));
				if ($version) {
					$file = BASEPATH . 'commit/' . $version . '/main.inc.php';
					if (is_file($file) && is_readable($file)) {
						if (ob_start()) {
							$obStartLevel = ob_get_level();
							self :: $tripSystem = true;
							$import = csl_import :: from($file);
							self :: $tripSystem = false;
							if (ob_get_level() >= $obStartLevel) {
								$output = ob_get_contents();
								ob_end_clean();
								if ($import !== true) {
									self :: obClean();
								}
								echo $output;
								return ($import === true ? true : false);
							} else {
								self :: ERROR500();
								csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . ': Commit failed - terminate the buffer loading version \'' . $version . '\' main file', E_USER_ERROR, 2);
							}
						} else {
							self :: ERROR500();
							csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . ': System failed - open output buffer failed to start', E_USER_ERROR, 2);
						}
					} else {
						self :: ERROR500();
						csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . ': Commit failed - could not load version \'' . $version . '\' main file', E_USER_ERROR, 2);
					}
				} else {
					self :: ERROR500();
					csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . ': Commit failed - unable to get version', E_USER_ERROR, 2);
				}
			} else {
				self :: ERROR500();
				csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . ': Commit failed - file does not exist', E_USER_ERROR, 2);
			}
			return false;
		}
		/** Returns the version number when the script file was loaded form the CodeSwitcher events directory.
		 * @access - public function
		 * @return - string|error|boolean
		 * @usage - csl_mvc::callEvent();
		 */
		public static function callEvent() {
			self :: start();
			self :: init(__FUNCTION__);
			//restrictions can only be called once
			if (!self :: $runEvent && self :: $portal) {
				self :: $runEvent = true;
				$fileName = null;
				$lineNum = null;
				if (!headers_sent($fileName, $lineNum)) {
					if (!csl_func_arg :: delimit2error()) {
						if (ob_start()) {
							$obStartLevel = ob_get_level();
							$model = (is_null(self :: $script) ? self :: $intro : self :: $script);
							if (isset ($model {
								0 }) && is_dir(BASEPATH . 'events/' . $model)) {
								$maxVersion = BASEPATH . 'events/' . $model . '/ini/version.php';
								$maxVersion = (is_file($maxVersion) && is_readable($maxVersion) ? csl_import :: from($maxVersion) : '');
								if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)*\.([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $maxVersion)) {
									self :: ERROR500();
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - unknown \'' . $model . '\' defined version number', E_USER_ERROR, 1);
								}
								if (!self :: $versionClass->is_exists(BASEPATH . 'events/' . $model, $maxVersion)) {
									self :: ERROR500();
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - defined \'' . $model . '\' version \'' . $maxVersion . '\' has not been established', E_USER_ERROR, 1);
								}
								$version = self :: $versionClass->get(BASEPATH . 'events/' . $model, (!self :: $tester || !self :: $develop ? $maxVersion : ''));
								if ($version) {
									$file = BASEPATH . 'events/' . $model . '/' . $version . '/main.inc.php';
									if (is_file($file) && is_readable($file)) {
										self :: $scriptEvent = $model;
										if (self :: begin(__FUNCTION__)) {
											self :: $tripSystem = true;
											$import = csl_import :: from($file);
											self :: $tripSystem = false;
											if ($import !== false) {
												if (ob_get_level() >= $obStartLevel) {
													$output = ob_get_contents();
													ob_end_clean();
													if (self :: commit(__FUNCTION__)) {
														echo $output;
													}
												} else {
													self :: ERROR500();
													csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - terminate the buffer loading \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
												}
											} else {
												self :: ERROR500();
												csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - terminate loading \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
											}
										}
										return $version;
									} else {
										self :: ERROR500();
										csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - could not load \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
									}
								} else {
									self :: ERROR500();
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - unable to get \'' . $model . '\' version', E_USER_ERROR, 1);
								}
							} else {
								self :: ERROR500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - intro page does not exist', E_USER_ERROR, 1);
							}
						} else {
							self :: ERROR500();
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): System failed - open output buffer failed to start', E_USER_ERROR, 1);
						}
					}
				} else {
					csl_error :: cast('Cannot modify header information - headers already sent by (output started at ' . $fileName . ':' . $lineNum . ')', E_USER_ERROR, 1);
				}
			} else {
				if (self :: $tripSystem && self :: $runEvent) {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Can not be called here', E_USER_NOTICE, 1);
				}
				elseif (self :: $script === false) {
					self :: ERROR500();
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Unknown script path', E_USER_ERROR, 1);
				}
				elseif (!self :: $portal) {
					self :: ERROR500();
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_ERROR, 1);
				}
			}
			return false;
		}
		/** Returns the version number when the event file was loaded form the CodeSwitcher events directory.
		 * @access - public function
		 * @param - string $model (model name)
		 * @return - string|error|boolean
		 * @usage - csl_mvc::importEvent($model);
		 */
		public static function importEvent($model = null) {
			self :: start();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0)) {
					if (!isset ($model {
						0 }) || csl_path :: is_absolute($model) || !csl_path :: is_relative($model)) {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
					} else {
						$model = trim(csl_path :: clean(self :: $rootDir . $model), '/');
						if (!isset ($model {
							0 })) {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
						}
						elseif (is_dir(BASEPATH . 'events/' . $model)) {
							$maxVersion = BASEPATH . 'events/' . $model . '/ini/version.php';
							$maxVersion = (is_file($maxVersion) && is_readable($maxVersion) ? csl_import :: from($maxVersion) : '');
							if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)*\.([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $maxVersion)) {
								self :: ERROR500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - unknown \'' . $model . '\' defined version number', E_USER_ERROR, 1);
							}
							if (!self :: $versionClass->is_exists(BASEPATH . 'events/' . $model, $maxVersion)) {
								self :: ERROR500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - defined \'' . $model . '\' version \'' . $maxVersion . '\' has not been established', E_USER_ERROR, 1);
							}
							$version = self :: $versionClass->get(BASEPATH . 'events/' . $model, (!self :: $tester || !self :: $develop ? $maxVersion : ''));
							if ($version) {
								$file = BASEPATH . 'events/' . $model . '/' . $version . '/main.inc.php';
								if (is_file($file) && is_readable($file)) {
									if (ob_start()) {
										$obStartLevel = ob_get_level();
										if (csl_import :: from($file) !== false) {
											if (ob_get_level() >= $obStartLevel) {
												$output = ob_get_contents();
												ob_end_clean();
												echo $output;
												return $version;
											} else {
												self :: ERROR500();
												csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - terminate the buffer loading \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
											}
										} else {
											self :: ERROR500();
											csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - terminate loading \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
										}
									} else {
										self :: ERROR500();
										csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): System failed - open output buffer failed to start', E_USER_ERROR, 1);
									}
								} else {
									self :: ERROR500();
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - could not load \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
								}
							} else {
								self :: ERROR500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - unable to get \'' . $model . '\' version', E_USER_ERROR, 1);
							}
						} else {
							self :: ERROR500();
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - target \'' . $model . '\' does not exist', E_USER_ERROR, 1);
						}
					}
				}
			} else {
				self :: ERROR500();
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_ERROR, 1);
			}
			return false;
		}
		/** Returns the version number when the model file was loaded form the CodeSwitcher models directory.
		 * @access - public function
		 * @param - string $model (model name)
		 * @return - string|error|boolean
		 * @usage - csl_mvc::importModel($model);
		 */
		public static function importModel($model = null) {
			self :: start();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0)) {
					if (!isset ($model {
						0 }) || csl_path :: is_absolute($model) || !csl_path :: is_relative($model)) {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
					} else {
						$model = trim(csl_path :: clean(self :: $rootDir . $model), '/');
						if (!isset ($model {
							0 })) {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
						}
						elseif (is_dir(BASEPATH . 'models/' . $model)) {
							$maxVersion = BASEPATH . 'models/' . $model . '/ini/version.php';
							$maxVersion = (is_file($maxVersion) && is_readable($maxVersion) ? csl_import :: from($maxVersion) : '');
							if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)*\.([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $maxVersion)) {
								self :: ERROR500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Model failed - unknown \'' . $model . '\' defined version number', E_USER_ERROR, 1);
							}
							if (!self :: $versionClass->is_exists(BASEPATH . 'models/' . $model, $maxVersion)) {
								self :: ERROR500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Model failed - defined \'' . $model . '\' version \'' . $maxVersion . '\' has not been established', E_USER_ERROR, 1);
							}
							$version = self :: $versionClass->get(BASEPATH . 'models/' . $model, (!self :: $tester || !self :: $develop ? $maxVersion : ''));
							if ($version) {
								$file = BASEPATH . 'models/' . $model . '/' . $version . '/main.inc.php';
								if (is_file($file) && is_readable($file)) {
									if (ob_start()) {
										$obStartLevel = ob_get_level();
										if (csl_import :: from($file) !== false) {
											if (ob_get_level() >= $obStartLevel) {
												$output = ob_get_contents();
												ob_end_clean();
												echo $output;
												return $version;
											} else {
												self :: ERROR500();
												csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Model failed - terminate the buffer loading \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
											}
										} else {
											self :: ERROR500();
											csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Model failed - terminate loading \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
										}
									} else {
										self :: ERROR500();
										csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): System failed - open output buffer failed to start', E_USER_ERROR, 1);
									}
								} else {
									self :: ERROR500();
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Model failed - could not load \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
								}
							} else {
								self :: ERROR500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Model failed - unable to get \'' . $model . '\' version', E_USER_ERROR, 1);
							}
						} else {
							self :: ERROR500();
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Model failed - target \'' . $model . '\' does not exist', E_USER_ERROR, 1);
						}
					}
				}
			} else {
				self :: ERROR500();
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_ERROR, 1);
			}
			return false;
		}
		/** Returns the version number when the library file was loaded form the CodeSwitcher libraries directory.
		 * @access - public function
		 * @param - string $model (model name)
		 * @return - string|error|boolean
		 * @usage - csl_mvc::importLibrary($model);
		 */
		public static function importLibrary($model = null) {
			self :: start();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0)) {
					if (!isset ($model {
						0 }) || csl_path :: is_absolute($model) || !csl_path :: is_relative($model)) {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
					} else {
						$model = trim(csl_path :: clean(self :: $rootDir . $model), '/');
						if (!isset ($model {
							0 })) {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
						}
						elseif (is_dir(BASEPATH . 'libraries/' . $model)) {
							$maxVersion = BASEPATH . 'libraries/' . $model . '/ini/version.php';
							$maxVersion = (is_file($maxVersion) && is_readable($maxVersion) ? csl_import :: from($maxVersion) : '');
							if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)*\.([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $maxVersion)) {
								self :: ERROR500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Library failed - unknown \'' . $model . '\' defined version number', E_USER_ERROR, 1);
							}
							if (!self :: $versionClass->is_exists(BASEPATH . 'libraries/' . $model, $maxVersion)) {
								self :: ERROR500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Library failed - defined \'' . $model . '\' version \'' . $maxVersion . '\' has not been established', E_USER_ERROR, 1);
							}
							$version = self :: $versionClass->get(BASEPATH . 'libraries/' . $model, (!self :: $tester || !self :: $develop ? $maxVersion : ''));
							if ($version) {
								$file = BASEPATH . 'libraries/' . $model . '/' . $version . '/main.inc.php';
								if (is_file($file) && is_readable($file)) {
									if (ob_start()) {
										$obStartLevel = ob_get_level();
										if (csl_import :: from($file) !== false) {
											if (ob_get_level() >= $obStartLevel) {
												$output = ob_get_contents();
												ob_end_clean();
												echo $output;
												return $version;
											} else {
												self :: ERROR500();
												csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Library failed - terminate the buffer loading \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
											}
										} else {
											self :: ERROR500();
											csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Library failed - terminate loading \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
										}
									} else {
										self :: ERROR500();
										csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): System failed - open output buffer failed to start', E_USER_ERROR, 1);
									}
								} else {
									self :: ERROR500();
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Library failed - could not load \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
								}
							} else {
								self :: ERROR500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Library failed - unable to get \'' . $model . '\' version', E_USER_ERROR, 1);
							}
						} else {
							self :: ERROR500();
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Library failed - target \'' . $model . '\' does not exist', E_USER_ERROR, 1);
						}
					}
				}
			} else {
				self :: ERROR500();
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_ERROR, 1);
			}
			return false;
		}
		/** Load the page's template file to view the contents from the CodeSwitcher templates directory.
		 * @access - public function
		 * @param - string $model (model name)
		 * @param - array $data (data array) : Default empty
		 * @param - boolean $process (return content string mode) : Default false
		 * @return - string|error|boolean
		 * @usage - csl_mvc::viewTemplate($model,$data,$process);
		 */
		public static function viewTemplate($model = null, $data = array (), $process = false) {
			self :: start();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0) && !csl_func_arg :: array2error(1) && !csl_func_arg :: bool2error(2)) {
					if (!isset ($model {
						0 }) || csl_path :: is_absolute($model) || !csl_path :: is_relative($model)) {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
					} else {
						$model = trim(csl_path :: clean(self :: $rootDir . $model), '/');
						if (!isset ($model {
							0 })) {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
						}
						elseif (is_dir(BASEPATH . 'templates/' . $model)) {
							$maxVersion = BASEPATH . 'templates/' . $model . '/ini/version.php';
							$maxVersion = (is_file($maxVersion) && is_readable($maxVersion) ? csl_import :: from($maxVersion) : '');
							if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)*\.([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $maxVersion)) {
								self :: ERROR500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Template failed - unknown \'' . $model . '\' defined version number', E_USER_ERROR, 1);
							}
							if (!self :: $versionClass->is_exists(BASEPATH . 'templates/' . $model, $maxVersion)) {
								self :: ERROR500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Template failed - defined \'' . $model . '\' version \'' . $maxVersion . '\' has not been established', E_USER_ERROR, 1);
							}
							$version = self :: $versionClass->get(BASEPATH . 'templates/' . $model, (!self :: $tester || !self :: $develop ? $maxVersion : ''));
							if ($version) {
								$file = BASEPATH . 'templates/' . $model . '/' . $version . '/main.inc.php';
								if (is_file($file) && is_readable($file)) {
									if (ob_start()) {
										$obStartLevel = ob_get_level();
										$content = csl_template :: view($file, $data, $process);
										if ($content !== false) {
											if (!$process) {
												if (ob_get_level() >= $obStartLevel) {
													$output = ob_get_contents();
													ob_end_clean();
													echo $output;
													return $version;
												} else {
													self :: ERROR500();
													csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Template failed - terminate the buffer loading \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
												}
											} else {
												if (ob_get_level() >= $obStartLevel) {
													ob_end_clean();
													return $content;
												} else {
													self :: ERROR500();
													csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Template failed - terminate the buffer loading \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
												}
											}
										} else {
											self :: ERROR500();
											csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Template failed - terminate loading \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
										}
									} else {
										self :: ERROR500();
										csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): System failed - open output buffer failed to start', E_USER_ERROR, 1);
									}
								} else {
									self :: ERROR500();
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Template failed - could not load \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
								}
							} else {
								self :: ERROR500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Template failed - unable to get \'' . $model . '\' version', E_USER_ERROR, 1);
							}
						} else {
							self :: ERROR500();
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Template failed - target \'' . $model . '\' does not exist', E_USER_ERROR, 1);
						}
					}
				}
			} else {
				self :: ERROR500();
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_ERROR, 1);
			}
			return false;
		}
	}
}
?>