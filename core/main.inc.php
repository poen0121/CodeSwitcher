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
		private static $CLI;
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
		/** Trigger information.
		 * @access - private function
		 * @return - null
		 * @usage -  self::trigger();
		 */
		private static function trigger() {
			if (is_null(self :: $portal)) {
				clearstatcache();
				csl_error :: begin();
				csl_error :: cast_log_title('CS-PHP');
				csl_error :: trace(false); //system default error stack trace mode
				csl_error :: error_log_file(BASEPATH . 'storage/logs/CS-' . csl_time :: get_date('host') . '.log', true); //peel of system log file mode
				csl_debug :: report(true); //error mode E_ALL
				csl_debug :: record(true); //save error logs
				csl_debug :: display(true); //erorr display
				self :: $CLI = (isset ($_SERVER['argc']) && $_SERVER['argc'] >= 1 ? true : false);
				self :: $runEvent = false; //event running status
				self :: $rootDir = csl_path :: document_root();
				self :: $tester = false; //tester mode
				self :: $develop = false; //develop mode by tester
				self :: $portal = false; //portal script status
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
		 * @return - boolean
		 * @usage -  self::init($__FUNCTION__);
		 */
		private static function init($__FUNCTION__) {
			/*---load-system-config---*/
			self :: $tripSystem = true;
			$CS_CONF = self :: cue_config('cs');
			self :: $tripSystem = false; //system default running status
			$CS_CONF = (is_array($CS_CONF) ? $CS_CONF : array ()); //check CodeSwitcher config array type
			//intro page
			if (!isset ($CS_CONF['INTRO']) || !is_string($CS_CONF['INTRO'])) {
				csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . '(): Init failed - unknown introduction page configuration', E_USER_ERROR, 2);
				return false;
			}
			//timezone
			if (!isset ($CS_CONF['DEFAULT_TIMEZONE']) || !is_string($CS_CONF['DEFAULT_TIMEZONE'])) {
				csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . '(): Init failed - unknown timezone id configuration', E_USER_ERROR, 2);
				return false;
			}
			//languages xml version
			if (isset ($CS_CONF['LANGUAGE_XML_VERSION']) && is_string($CS_CONF['LANGUAGE_XML_VERSION'])) {
				if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $CS_CONF['LANGUAGE_XML_VERSION'])) {
					csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . '(): Init failed - invalid language XML version number \'' . $CS_CONF['LANGUAGE_XML_VERSION'] . '\' configuration', E_USER_ERROR, 2);
					return false;
				}
			} else {
				csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . '(): Init failed - unknown language XML version number configuration', E_USER_ERROR, 2);
				return false;
			}
			//languages xml enciding
			if (isset ($CS_CONF['LANGUAGE_XML_ENCODING']) && is_string($CS_CONF['LANGUAGE_XML_ENCODING'])) {
				if (!csl_inspect :: is_iconv_encoding($CS_CONF['LANGUAGE_XML_ENCODING'])) {
					csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . '(): Init failed - invalid language XML encoding scheme \'' . $CS_CONF['LANGUAGE_XML_ENCODING'] . '\' configuration', E_USER_ERROR, 2);
					return false;
				}
			} else {
				csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . '(): Init failed - unknown language XML encoding scheme configuration', E_USER_ERROR, 2);
				return false;
			}
			//error log storage directory
			if (!isset ($CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION']) || !is_string($CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION'])) {
				csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . '(): Init failed - unknown error log storage directory configuration', E_USER_ERROR, 2);
				return false;
			}
			//error stack trace mode
			if (!isset ($CS_CONF['ERROR_STACK_TRACE_MODE']) || !is_bool($CS_CONF['ERROR_STACK_TRACE_MODE'])) {
				csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . '(): Init failed - unknown error stack trace mode configuration', E_USER_ERROR, 2);
				return false;
			}
			//error log storage mode
			if (!isset ($CS_CONF['ERROR_LOG_MODE']) || !is_bool($CS_CONF['ERROR_LOG_MODE'])) {
				csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . '(): Init failed - unknown error log storage mode configuration', E_USER_ERROR, 2);
				return false;
			}
			//testers debug display mode
			if (!isset ($CS_CONF['TESTER_DEBUG_MODE']) || !is_bool($CS_CONF['TESTER_DEBUG_MODE'])) {
				csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . '(): Init failed - unknown testers debug display mode configuration', E_USER_ERROR, 2);
				return false;
			}
			//testers develop mode
			if (!isset ($CS_CONF['TESTER_DEVELOP_MODE']) || !is_bool($CS_CONF['TESTER_DEVELOP_MODE'])) {
				csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . '(): Init failed - unknown testers develop mode configuration', E_USER_ERROR, 2);
				return false;
			}
			/*---setting---*/
			//set intro page
			self :: $intro = trim(csl_path :: clean(self :: $rootDir . $CS_CONF['INTRO']), '/');
			//set timezone
			if (isset ($CS_CONF['DEFAULT_TIMEZONE'] { 0 }) && !csl_time :: set_timezone($CS_CONF['DEFAULT_TIMEZONE'])) {
				csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . '(): Init failed - change timezone id \'' . $CS_CONF['DEFAULT_TIMEZONE'] . '\' is invalid', E_USER_ERROR, 2);
				return false;
			}
			//build languages xml object
			self :: $language = new csl_language('language', $CS_CONF['LANGUAGE_XML_VERSION'], $CS_CONF['LANGUAGE_XML_ENCODING']);
			//set error log storage file
			if (isset ($CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION'] { 0 })) {
				$CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION'] = csl_path :: norm($CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION']);
				$CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION'] = (substr($CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION'], -1, 1) !== '/' ? $CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION'] . '/' : $CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION']);
				csl_error :: error_log_file($CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION'] . 'CS-' . csl_time :: get_date('host') . '.log', true); //peel of system log file mode
			} else {
				csl_error :: error_log_file(BASEPATH . 'storage/logs/CS-' . csl_time :: get_date('host') . '.log', true); //peel of system log file mode
			}
			//set error stack trace mode
			csl_error :: trace($CS_CONF['ERROR_STACK_TRACE_MODE']);
			//set error log storage mode
			csl_debug :: record($CS_CONF['ERROR_LOG_MODE']);
			//source IP verification tester
			self :: $tester = (self :: $CLI ? true : (preg_match('/^(localhost|127.0.0.1)$/i', (isset ($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '')) ? true : in_array(csl_browser :: info('ip'), (isset ($CS_CONF['TESTER_IP']) && is_array($CS_CONF['TESTER_IP']) ? $CS_CONF['TESTER_IP'] : array ()), true)));
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
			//set erorr 500 template dir path
			$error500 = 'templates/error/500';
			self :: $tripSystem = true;
			$version = self :: version($error500);
			self :: $tripSystem = false; //system default running status
			if ($version !== false) {
				$file = BASEPATH . $error500 . '/' . $version . '/main.inc.php';
				if (is_file($file) && is_readable($file)) {
					$file = BASEPATH . $error500 . '/' . $version . '/content.txt';
					if (is_file($file) && is_readable($file)) {
						self :: $error500 = BASEPATH . $error500 . '/' . $version . '/';
					} else {
						csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . '(): Init failed - could not load \'content.txt\' file for \'' . $error500 . '\'', E_USER_ERROR, 1);
						return false;
					}
				} else {
					csl_error :: cast(__CLASS__ . '::' . $__FUNCTION__ . '(): Init failed - could not load \'' . $error500 . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
					return false;
				}
			} else {
				return false;
			}
			return true;
		}
		/** Browse the output content.
		 * @access - public function
		 * @param - string $buffer (buffer content)
		 * @return - string
		 * @usage - self::browse($buffer);
		 */
		private static function browse($buffer) {
			if (is_array(error_get_last()) && !csl_debug :: is_display() && self :: $error500) {
				include (self :: $error500 . 'main.inc.php');
				return file_get_contents(self :: $error500 . 'content.txt');
			}
			return $buffer;
		}
		/** Returns the status of the interface cli type.
		 * @access - public function
		 * @return - boolean
		 * @usage - csl_mvc::is_cli();
		 */
		public static function is_cli() {
			self :: trigger();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error()) {
					return self :: $CLI;
				}
			} else {
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_NOTICE, 1);
			}
			return false;
		}
		/** Get the available version info from the file directory path name in the CodeSwitcher root directory.
		 * @access - public function
		 * @param - string $pathName (path name in framework)
		 * @param - boolean $mode (returns directory relative path or version number) : Default false
		 * @note - $mode `true` is returns directory relative path.
		 * @note - $mode `false` is returns version number.
		 * @return - string|boolean
		 * @usage - csl_mvc::version($pathName,$mode);
		 */
		public static function version($pathName = null, $mode = false) {
			self :: trigger();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0) && !csl_func_arg :: bool2error(1)) {
					if (!isset ($pathName { 0 }) || csl_path :: is_absolute($pathName) || !csl_path :: is_relative($pathName)) {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument by parameter 1', E_USER_WARNING, 1);
					} else {
						$cleanPath = trim(csl_path :: clean(self :: $rootDir . $pathName), '/');
						if (!isset ($cleanPath { 0 })) {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument by parameter 1', E_USER_WARNING, 1);
						}
						elseif (is_dir(BASEPATH . $cleanPath)) {
							$version = self :: $versionClass->get(BASEPATH . $cleanPath . '/ini', '', 'version.php'); //ini directory version
							if ($version) {
								$maxVersion = BASEPATH . $cleanPath . '/ini/' . $version . '/version.php';
								$maxVersion = (is_readable($maxVersion) ? csl_import :: from($maxVersion) : '');
								if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)*\.([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $maxVersion)) {
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Version failed - unknown \'' . $cleanPath . '\' defined version number', E_USER_ERROR, 1);
								}
								elseif (!self :: $versionClass->is_exists(BASEPATH . $cleanPath, $maxVersion)) {
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Version failed - defined \'' . $cleanPath . '\' version \'' . $maxVersion . '\' has not been established', E_USER_ERROR, 1);
								} else {
									$pathInfo = explode('/', $cleanPath);
									$version = self :: $versionClass->get(BASEPATH . $cleanPath, (!self :: $tester || !self :: $develop || (count($pathInfo) === 2 && $pathInfo[0] == 'configs' && $pathInfo[1] == 'cs') ? $maxVersion : '')); //cs directory is system config
									if ($version) {
										return ($mode ? csl_path :: relative(BASEPATH . $cleanPath . '/' . $version . '/') : $version);
									} else {
										csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Version failed - unable to get \'' . $cleanPath . '\' version', E_USER_ERROR, 1);
									}
								}
							} else {
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Version failed - unable to get ini directory version at \'' . $cleanPath . '\'', E_USER_ERROR, 1);
							}
						} else {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Version failed - target \'' . $cleanPath . '\' does not exist', E_USER_ERROR, 1);
						}
					}
				}
			} else {
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_NOTICE, 1);
			}
			return false;
		}
		/** Get the relative path from the file path name in the CodeSwitcher root directory.
		 * @access - public function
		 * @param - string $pathName (path name in framework)
		 * @param - boolean $uriMode (client URI analysis mode) : Default false
		 * @return - string|boolean
		 * @usage - csl_mvc::form_path($pathName,$uriMode);
		 */
		public static function form_path($pathName = null, $uriMode = false) {
			self :: trigger();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0) && !csl_func_arg :: bool2error(1)) {
					if (csl_path :: is_relative($pathName)) {
						$cleanPath = ltrim(csl_path :: clean(self :: $rootDir . $pathName), '/');
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
								$path = csl_path :: relative(BASEPATH . $cleanPath);
								$path = (self :: $uriLayer && strpos($path, './') === 0 ? substr($path, 2) : $path);
								return self :: $uriLayer . $path;
							}
						} else {
							return csl_path :: relative(BASEPATH . $cleanPath);
						}
					}
				}
			} else {
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_NOTICE, 1);
			}
			return false;
		}
		/** Captures the name of the script event that is currently running.
		 * @access - public function
		 * @return - string|boolean
		 * @usage - csl_mvc::script_event();
		 */
		public static function script_event() {
			self :: trigger();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error()) {
					return self :: $scriptEvent;
				}
			} else {
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_NOTICE, 1);
			}
			return false;
		}
		/** Returns the current tester status.
		 * @access - public function
		 * @return - boolean
		 * @usage - csl_mvc::is_tester();
		 */
		public static function is_tester() {
			self :: trigger();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error()) {
					return self :: $tester;
				}
			} else {
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_NOTICE, 1);
			}
			return false;
		}
		/** Returns whether the event index page file exists from the events script directory path name in the CodeSwitcher root directory.
		 * @access - public function
		 * @param - string $eventName (events script directory path name)
		 * @return - boolean
		 * @usage - csl_mvc::is_portal($eventName);
		 */
		public static function is_portal($eventName = null) {
			self :: trigger();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0)) {
					if (isset ($eventName { 0 }) && csl_path :: is_relative($eventName)) {
						$cleanPath = trim(csl_path :: clean(self :: $rootDir . $eventName), '/');
						if (isset ($cleanPath { 0 })) {
							if ($cleanPath == self :: $intro) {
								$file = BASEPATH . 'index.php';
								$file = (is_file($file) ? true : null);
								if (is_null($file)) {
									$file = BASEPATH . 'events/' . $cleanPath . '/index.php';
									$file = (is_file($file) ? true : null);
								}
							} else {
								$file = BASEPATH . 'events/' . $cleanPath . '/index.php';
								$file = (is_file($file) ? true : null);
							}
							return isset ($file);
						}
					}
				}
			} else {
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_NOTICE, 1);
			}
			return false;
		}
		/** Returns whether the event controller file exists from the events script directory path name in the CodeSwitcher root directory.
		 * @access - public function
		 * @param - string $eventName (events script directory path name)
		 * @return - boolean
		 * @usage - csl_mvc::is_event($eventName);
		 */
		public static function is_event($eventName = null) {
			self :: trigger();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0)) {
					if (isset ($eventName { 0 }) && csl_path :: is_relative($eventName)) {
						$cleanPath = trim(csl_path :: clean(self :: $rootDir . $eventName), '/');
						if (isset ($cleanPath { 0 })) {
							if (is_dir(BASEPATH . 'events/' . $cleanPath)) {
								$version = self :: $versionClass->get(BASEPATH . 'events/' . $cleanPath . '/ini', '', 'version.php'); //ini directory version
								if ($version) {
									$maxVersion = BASEPATH . 'events/' . $cleanPath . '/ini/' . $version . '/version.php';
									$maxVersion = (is_readable($maxVersion) ? csl_import :: from($maxVersion) : '');
									if (preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)*\.([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $maxVersion) && self :: $versionClass->is_exists(BASEPATH . 'events/' . $cleanPath, $maxVersion)) {
										$version = self :: $versionClass->get(BASEPATH . 'events/' . $cleanPath, (!self :: $tester || !self :: $develop ? $maxVersion : ''));
										if ($version) {
											$file = BASEPATH . 'events/' . $cleanPath . '/' . $version . '/main.inc.php';
											if (is_file($file) && is_readable($file)) {
												return true;
											}
										}
									}
								}
							}
						}
					}
				}
			} else {
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_NOTICE, 1);
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
			self :: trigger();
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
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_NOTICE, 1);
			}
			return false;
		}
		/** Returns debug display status 0 or 1, a temporarily change.
		 * @access - public function
		 * @param - boolean $mode (temporarily change mode does not support tester mode) : Default empty
		 * @return - integer|boolean
		 * @usage - csl_mvc::debug($mode);
		 */
		public static function debug($mode = false) {
			self :: trigger();
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
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_NOTICE, 1);
			}
			return false;
		}
		/** Load configuration data form the CodeSwitcher configs directory.
		 * @access - public function
		 * @param - string $model (model name)
		 * @return - data|error|boolean
		 * @usage - csl_mvc::cue_config($model);
		 */
		public static function cue_config($model = null) {
			self :: trigger();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0)) {
					if (!isset ($model { 0 }) || csl_path :: is_absolute($model) || !csl_path :: is_relative($model)) {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
					} else {
						$cleanPath = trim(csl_path :: clean(self :: $rootDir . $model), '/');
						if (!isset ($cleanPath { 0 })) {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
						}
						elseif (is_dir(BASEPATH . 'configs/' . $cleanPath)) {
							$version = self :: $versionClass->get(BASEPATH . 'configs/' . $cleanPath . '/ini', '', 'version.php'); //ini directory version
							if ($version) {
								$maxVersion = BASEPATH . 'configs/' . $cleanPath . '/ini/' . $version . '/version.php';
								$maxVersion = (is_readable($maxVersion) ? csl_import :: from($maxVersion) : '');
								if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)*\.([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $maxVersion)) {
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Config failed - unknown \'' . $cleanPath . '\' defined version number', E_USER_ERROR, 1);
								}
								elseif (!self :: $versionClass->is_exists(BASEPATH . 'configs/' . $cleanPath, $maxVersion)) {
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Config failed - defined \'' . $cleanPath . '\' version \'' . $maxVersion . '\' has not been established', E_USER_ERROR, 1);
								} else {
									$version = self :: $versionClass->get(BASEPATH . 'configs/' . $cleanPath, (!self :: $tester || !self :: $develop || $cleanPath == 'cs' ? $maxVersion : '')); //cs directory is system config
									if ($version) {
										$file = BASEPATH . 'configs/' . $cleanPath . '/' . $version . '/main.inc.php';
										if (is_file($file) && is_readable($file)) {
											$content = csl_import :: from($file);
											if ($content !== false) {
												return $content;
											} else {
												csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Config failed - terminate loading \'' . $cleanPath . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
											}
										} else {
											csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Config failed - could not load \'' . $cleanPath . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
										}
									} else {
										csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Config failed - unable to get \'' . $cleanPath . '\' version', E_USER_ERROR, 1);
									}
								}
							} else {
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Config failed - unable to get ini directory version at \'' . $cleanPath . '\'', E_USER_ERROR, 1);
							}
						} else {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Config failed - target \'' . $cleanPath . '\' does not exist', E_USER_ERROR, 1);
						}
					}
				}
			} else {
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_NOTICE, 1);
			}
			return false;
		}
		/** Load create a language object form the CodeSwitcher languages directory.
		 * @access - public function
		 * @param - string $model (model name)
		 * @return - object|error|boolean
		 * @usage - csl_mvc::cue_language($model);
		 */
		public static function cue_language($model = null) {
			self :: trigger();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0)) {
					if (!isset ($model { 0 }) || csl_path :: is_absolute($model) || !csl_path :: is_relative($model)) {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
					} else {
						$cleanPath = trim(csl_path :: clean(self :: $rootDir . $model), '/');
						if (!isset ($cleanPath { 0 })) {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
						}
						elseif (is_dir(BASEPATH . 'languages/' . $cleanPath)) {
							$version = self :: $versionClass->get(BASEPATH . 'languages/' . $cleanPath . '/ini', '', 'version.php'); //ini directory version
							if ($version) {
								$maxVersion = BASEPATH . 'languages/' . $cleanPath . '/ini/' . $version . '/version.php';
								$maxVersion = (is_readable($maxVersion) ? csl_import :: from($maxVersion) : '');
								if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)*\.([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $maxVersion)) {
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Language failed - unknown \'' . $cleanPath . '\' defined version number', E_USER_ERROR, 1);
								}
								elseif (!self :: $versionClass->is_exists(BASEPATH . 'languages/' . $cleanPath, $maxVersion)) {
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Language failed - defined \'' . $cleanPath . '\' version \'' . $maxVersion . '\' has not been established', E_USER_ERROR, 1);
								} else {
									$version = self :: $versionClass->get(BASEPATH . 'languages/' . $cleanPath, (!self :: $tester || !self :: $develop ? $maxVersion : ''));
									if ($version) {
										$file = BASEPATH . 'languages/' . $cleanPath . '/' . $version . '/main.inc.xml';
										if (is_file($file) && is_readable($file)) {
											$content = self :: $language->load($file);
											if ($content !== false) {
												return $content;
											} else {
												csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Language failed - terminate loading \'' . $cleanPath . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
											}
										} else {
											csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Language failed - could not load \'' . $cleanPath . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
										}
									} else {
										csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Language failed - unable to get \'' . $cleanPath . '\' version', E_USER_ERROR, 1);
									}
								}
							} else {
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Language failed - unable to get ini directory version at \'' . $cleanPath . '\'', E_USER_ERROR, 1);
							}
						} else {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Language failed - target \'' . $cleanPath . '\' does not exist', E_USER_ERROR, 1);
						}
					}
				}
			} else {
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_NOTICE, 1);
			}
			return false;
		}
		/** Returns the version number when the script file was loaded form the CodeSwitcher events directory.
		 * @access - public function
		 * @return - string|error|boolean
		 * @usage - csl_mvc::start();
		 */
		public static function start() {
			self :: trigger();
			//restrictions can only be called once
			if (!self :: $runEvent) {
				self :: $runEvent = true;
				//initialize system config
				if (self :: init(__FUNCTION__)) {
					//open output buffer
					if (ob_start('csl_mvc::browse')) {
						if (self :: $portal) {
							if (!csl_func_arg :: delimit2error()) {
								$model = (is_null(self :: $script) ? self :: $intro : self :: $script);
								if (isset ($model { 0 }) && is_dir(BASEPATH . 'events/' . $model)) {
									$version = self :: $versionClass->get(BASEPATH . 'events/' . $model . '/ini', '', 'version.php'); //ini directory version
									if ($version) {
										$maxVersion = BASEPATH . 'events/' . $model . '/ini/' . $version . '/version.php';
										$maxVersion = (is_readable($maxVersion) ? csl_import :: from($maxVersion) : '');
										if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)*\.([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $maxVersion)) {
											csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - unknown \'' . $model . '\' defined version number', E_USER_ERROR, 1);
										}
										elseif (!self :: $versionClass->is_exists(BASEPATH . 'events/' . $model, $maxVersion)) {
											csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - defined \'' . $model . '\' version \'' . $maxVersion . '\' has not been established', E_USER_ERROR, 1);
										} else {
											$version = self :: $versionClass->get(BASEPATH . 'events/' . $model, (!self :: $tester || !self :: $develop ? $maxVersion : ''));
											if ($version) {
												$file = BASEPATH . 'events/' . $model . '/' . $version . '/main.inc.php';
												if (is_file($file) && is_readable($file)) {
													self :: $scriptEvent = $model;
													self :: $tripSystem = true;
													$import = csl_import :: from($file);
													self :: $tripSystem = false;
													if ($import !== false) {
														return $version;
													} else {
														csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - terminate loading \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
													}
												} else {
													csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - could not load \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
												}
											} else {
												csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - unable to get \'' . $model . '\' version', E_USER_ERROR, 1);
											}
										}
									} else {
										csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - unable to get ini directory version at \'' . $model . '\'', E_USER_ERROR, 1);
									}
								} else {
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - intro page does not exist', E_USER_ERROR, 1);
								}
							}
						} else {
							if (self :: $script === false) {
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Unknown script path', E_USER_ERROR, 1);
							}
							elseif (!self :: $portal) {
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_NOTICE, 1);
							}
						}
					} else {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): System failed - open output buffer failed to start', E_USER_ERROR, 1);
					}
				}
			} else {
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Can not be called repeatedly', E_USER_NOTICE, 1);
			}
			return false;
		}
		/** Returns the version number when the event file was loaded form the CodeSwitcher events directory.
		 * @access - public function
		 * @param - string $model (model name)
		 * @return - string|error|boolean
		 * @usage - csl_mvc::import_event($model);
		 */
		public static function import_event($model = null) {
			self :: trigger();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0)) {
					if (!isset ($model { 0 }) || csl_path :: is_absolute($model) || !csl_path :: is_relative($model)) {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
					} else {
						$cleanPath = trim(csl_path :: clean(self :: $rootDir . $model), '/');
						if (!isset ($cleanPath { 0 })) {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
						}
						elseif (is_dir(BASEPATH . 'events/' . $cleanPath)) {
							$version = self :: $versionClass->get(BASEPATH . 'events/' . $cleanPath . '/ini', '', 'version.php'); //ini directory version
							if ($version) {
								$maxVersion = BASEPATH . 'events/' . $cleanPath . '/ini/' . $version . '/version.php';
								$maxVersion = (is_readable($maxVersion) ? csl_import :: from($maxVersion) : '');
								if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)*\.([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $maxVersion)) {
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - unknown \'' . $cleanPath . '\' defined version number', E_USER_ERROR, 1);
								}
								elseif (!self :: $versionClass->is_exists(BASEPATH . 'events/' . $cleanPath, $maxVersion)) {
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - defined \'' . $cleanPath . '\' version \'' . $maxVersion . '\' has not been established', E_USER_ERROR, 1);
								} else {
									$version = self :: $versionClass->get(BASEPATH . 'events/' . $cleanPath, (!self :: $tester || !self :: $develop ? $maxVersion : ''));
									if ($version) {
										$file = BASEPATH . 'events/' . $cleanPath . '/' . $version . '/main.inc.php';
										if (is_file($file) && is_readable($file)) {
											if (csl_import :: from($file) !== false) {
												return $version;
											} else {
												csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - terminate loading \'' . $cleanPath . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
											}
										} else {
											csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - could not load \'' . $cleanPath . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
										}
									} else {
										csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - unable to get \'' . $cleanPath . '\' version', E_USER_ERROR, 1);
									}
								}
							} else {
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - unable to get ini directory version at \'' . $cleanPath . '\'', E_USER_ERROR, 1);
							}
						} else {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - target \'' . $cleanPath . '\' does not exist', E_USER_ERROR, 1);
						}
					}
				}
			} else {
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_NOTICE, 1);
			}
			return false;
		}
		/** Returns the version number when the model file was loaded form the CodeSwitcher models directory.
		 * @access - public function
		 * @param - string $model (model name)
		 * @return - string|error|boolean
		 * @usage - csl_mvc::import_model($model);
		 */
		public static function import_model($model = null) {
			self :: trigger();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0)) {
					if (!isset ($model { 0 }) || csl_path :: is_absolute($model) || !csl_path :: is_relative($model)) {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
					} else {
						$cleanPath = trim(csl_path :: clean(self :: $rootDir . $model), '/');
						if (!isset ($cleanPath { 0 })) {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
						}
						elseif (is_dir(BASEPATH . 'models/' . $cleanPath)) {
							$version = self :: $versionClass->get(BASEPATH . 'models/' . $cleanPath . '/ini', '', 'version.php'); //ini directory version
							if ($version) {
								$maxVersion = BASEPATH . 'models/' . $cleanPath . '/ini/' . $version . '/version.php';
								$maxVersion = (is_readable($maxVersion) ? csl_import :: from($maxVersion) : '');
								if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)*\.([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $maxVersion)) {
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Model failed - unknown \'' . $cleanPath . '\' defined version number', E_USER_ERROR, 1);
								}
								elseif (!self :: $versionClass->is_exists(BASEPATH . 'models/' . $cleanPath, $maxVersion)) {
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Model failed - defined \'' . $cleanPath . '\' version \'' . $maxVersion . '\' has not been established', E_USER_ERROR, 1);
								} else {
									$version = self :: $versionClass->get(BASEPATH . 'models/' . $cleanPath, (!self :: $tester || !self :: $develop ? $maxVersion : ''));
									if ($version) {
										$file = BASEPATH . 'models/' . $cleanPath . '/' . $version . '/main.inc.php';
										if (is_file($file) && is_readable($file)) {
											if (csl_import :: from($file) !== false) {
												return $version;
											} else {
												csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Model failed - terminate loading \'' . $cleanPath . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
											}
										} else {
											csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Model failed - could not load \'' . $cleanPath . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
										}
									} else {
										csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Model failed - unable to get \'' . $cleanPath . '\' version', E_USER_ERROR, 1);
									}
								}
							} else {
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Model failed - unable to get ini directory version at \'' . $cleanPath . '\'', E_USER_ERROR, 1);
							}
						} else {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Model failed - target \'' . $cleanPath . '\' does not exist', E_USER_ERROR, 1);
						}
					}
				}
			} else {
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_NOTICE, 1);
			}
			return false;
		}
		/** Returns the version number when the library file was loaded form the CodeSwitcher libraries directory.
		 * @access - public function
		 * @param - string $model (model name)
		 * @return - string|error|boolean
		 * @usage - csl_mvc::import_library($model);
		 */
		public static function import_library($model = null) {
			self :: trigger();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0)) {
					if (!isset ($model { 0 }) || csl_path :: is_absolute($model) || !csl_path :: is_relative($model)) {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
					} else {
						$cleanPath = trim(csl_path :: clean(self :: $rootDir . $model), '/');
						if (!isset ($cleanPath { 0 })) {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
						}
						elseif (is_dir(BASEPATH . 'libraries/' . $cleanPath)) {
							$version = self :: $versionClass->get(BASEPATH . 'libraries/' . $cleanPath . '/ini', '', 'version.php'); //ini directory version
							if ($version) {
								$maxVersion = BASEPATH . 'libraries/' . $cleanPath . '/ini/' . $version . '/version.php';
								$maxVersion = (is_readable($maxVersion) ? csl_import :: from($maxVersion) : '');
								if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)*\.([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $maxVersion)) {
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Library failed - unknown \'' . $cleanPath . '\' defined version number', E_USER_ERROR, 1);
								}
								elseif (!self :: $versionClass->is_exists(BASEPATH . 'libraries/' . $cleanPath, $maxVersion)) {
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Library failed - defined \'' . $cleanPath . '\' version \'' . $maxVersion . '\' has not been established', E_USER_ERROR, 1);
								} else {
									$version = self :: $versionClass->get(BASEPATH . 'libraries/' . $cleanPath, (!self :: $tester || !self :: $develop ? $maxVersion : ''));
									if ($version) {
										$file = BASEPATH . 'libraries/' . $cleanPath . '/' . $version . '/main.inc.php';
										if (is_file($file) && is_readable($file)) {
											if (csl_import :: from($file) !== false) {
												return $version;
											} else {
												csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Library failed - terminate loading \'' . $cleanPath . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
											}
										} else {
											csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Library failed - could not load \'' . $cleanPath . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
										}
									} else {
										csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Library failed - unable to get \'' . $cleanPath . '\' version', E_USER_ERROR, 1);
									}
								}
							} else {
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Library failed - unable to get ini directory version at \'' . $cleanPath . '\'', E_USER_ERROR, 1);
							}
						} else {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Library failed - target \'' . $cleanPath . '\' does not exist', E_USER_ERROR, 1);
						}
					}
				}
			} else {
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_NOTICE, 1);
			}
			return false;
		}
		/** Load the page's template file to view the contents from the CodeSwitcher templates directory.
		 * @access - public function
		 * @param - string $model (model name)
		 * @param - array $data (data array) : Default empty
		 * @param - boolean $process (return content string mode) : Default false
		 * @return - string|error|boolean
		 * @usage - csl_mvc::view_template($model,$data,$process);
		 */
		public static function view_template($model = null, $data = array (), $process = false) {
			self :: trigger();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0) && !csl_func_arg :: array2error(1) && !csl_func_arg :: bool2error(2)) {
					if (!isset ($model { 0 }) || csl_path :: is_absolute($model) || !csl_path :: is_relative($model)) {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
					} else {
						$cleanPath = trim(csl_path :: clean(self :: $rootDir . $model), '/');
						if (!isset ($cleanPath { 0 })) {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
						}
						elseif (is_dir(BASEPATH . 'templates/' . $cleanPath)) {
							$version = self :: $versionClass->get(BASEPATH . 'templates/' . $cleanPath . '/ini', '', 'version.php'); //ini directory version
							if ($version) {
								$maxVersion = BASEPATH . 'templates/' . $cleanPath . '/ini/' . $version . '/version.php';
								$maxVersion = (is_readable($maxVersion) ? csl_import :: from($maxVersion) : '');
								if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)*\.([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $maxVersion)) {
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Template failed - unknown \'' . $cleanPath . '\' defined version number', E_USER_ERROR, 1);
								}
								elseif (!self :: $versionClass->is_exists(BASEPATH . 'templates/' . $cleanPath, $maxVersion)) {
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Template failed - defined \'' . $cleanPath . '\' version \'' . $maxVersion . '\' has not been established', E_USER_ERROR, 1);
								} else {
									$version = self :: $versionClass->get(BASEPATH . 'templates/' . $cleanPath, (!self :: $tester || !self :: $develop ? $maxVersion : ''));
									if ($version) {
										$file = BASEPATH . 'templates/' . $cleanPath . '/' . $version . '/main.inc.php';
										if (is_file($file) && is_readable($file)) {
											$content = csl_template :: view($file, $data, $process);
											if ($content !== false) {
												if (!$process) {
													return $version;
												} else {
													return $content;
												}
											} else {
												csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Template failed - terminate loading \'' . $cleanPath . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
											}
										} else {
											csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Template failed - could not load \'' . $cleanPath . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
										}
									} else {
										csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Template failed - unable to get \'' . $cleanPath . '\' version', E_USER_ERROR, 1);
									}
								}
							} else {
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Template failed - unable to get ini directory version at \'' . $cleanPath . '\'', E_USER_ERROR, 1);
							}
						} else {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Template failed - target \'' . $cleanPath . '\' does not exist', E_USER_ERROR, 1);
						}
					}
				}
			} else {
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_NOTICE, 1);
			}
			return false;
		}
		/** Get the relative path from the version of the anchor file in the CodeSwitcher resources directory.
		 * @access - public function
		 * @param - string $model (model name)
		 * @param - string $anchorName (anchor file name at version directory)
		 * @param - boolean $uriMode (client URI analysis mode) : Default true
		 * @return - string|error|boolean
		 * @usage - csl_mvc::resource_path($model,$anchorName,$uriMode);
		 */
		public static function resource_path($model = null, $anchorName = null, $uriMode = true) {
			self :: trigger();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0) && !csl_func_arg :: string2error(1) && !csl_func_arg :: bool2error(2)) {
					if (!isset ($model { 0 }) || csl_path :: is_absolute($model) || !csl_path :: is_relative($model)) {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument by parameter 1', E_USER_WARNING, 1);
					}
					elseif (preg_match('/[\\\\:\/]/i', $anchorName) || !isset ($anchorName { 0 })) {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Unknown anchor file name', E_USER_WARNING, 1);
					} else {
						$cleanPath = trim(csl_path :: clean(self :: $rootDir . $model), '/');
						if (!isset ($cleanPath { 0 })) {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
						}
						elseif (is_dir(BASEPATH . 'resources/' . $cleanPath)) {
							$version = self :: $versionClass->get(BASEPATH . 'resources/' . $cleanPath . '/ini', '', 'version.php'); //ini directory version
							if ($version) {
								$maxVersion = BASEPATH . 'resources/' . $cleanPath . '/ini/' . $version . '/version.php';
								$maxVersion = (is_readable($maxVersion) ? csl_import :: from($maxVersion) : '');
								if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)*\.([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $maxVersion)) {
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Resource failed - unknown \'' . $cleanPath . '\' defined version number', E_USER_ERROR, 1);
								}
								elseif (!self :: $versionClass->is_exists(BASEPATH . 'resources/' . $cleanPath, $maxVersion)) {
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Resource failed - defined \'' . $cleanPath . '\' version \'' . $maxVersion . '\' has not been established', E_USER_ERROR, 1);
								} else {
									$version = self :: $versionClass->get(BASEPATH . 'resources/' . $cleanPath, (!self :: $tester || !self :: $develop ? $maxVersion : ''));
									if ($version) {
										$file = BASEPATH . 'resources/' . $cleanPath . '/' . $version . '/' . $anchorName;
										if (is_file($file) && is_readable($file)) {
											return self :: form_path('resources/' . $cleanPath . '/' . $version . '/' . $anchorName, $uriMode);
										} else {
											csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Resource failed - could not load \'' . $cleanPath . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1);
										}
									} else {
										csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Resource failed - unable to get \'' . $cleanPath . '\' version', E_USER_ERROR, 1);
									}
								}
							} else {
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Resource failed - unable to get ini directory version at \'' . $cleanPath . '\'', E_USER_ERROR, 1);
							}
						} else {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Resource failed - target \'' . $cleanPath . '\' does not exist', E_USER_ERROR, 1);
						}
					}
				}
			} else {
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_NOTICE, 1);
			}
			return false;
		}
	}
}
?>