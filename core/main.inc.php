<?php
if (!class_exists('csl_mvc')) {
	//default document root directory
	$_SERVER['DOCUMENT_ROOT'] = dirname(dirname(__FILE__));
	//defines the path of the CodeSwitcher root directory
	define('BASEPATH', rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/') . '/');
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
		private static $versionClass;
		private static $rootDir;
		private static $script;
		private static $portal;
		private static $intro;
		private static $language;
		private static $tester;
		private static $develop;
		private static $tripSystem;
		private static $obStartLevel;
		/** Start info.
		 * @access - private function
		 * @return - null
		 * @usage -  self::start();
		 */
		private static function start() {
			if (is_null(self :: $portal)) {
				csl_debug :: report(true); //error mode E_ALL
				csl_debug :: record(true); //save error logs
				csl_debug :: display(true); //erorr display
				self :: $runEvent = false; //run event state
				self :: $rootDir = csl_path :: document_root();
				self :: $tripSystem = false; //system running state
				self :: $tester = false; //tester mode
				self :: $develop = false; //develop mode by tester
				self :: $obStartLevel = ob_get_level();
				self :: $portal = false; //portal script state
				self :: $script = (isset ($_SERVER['SCRIPT_FILENAME']) ? csl_path :: clean($_SERVER['SCRIPT_FILENAME']) : false); //script path
				if (self :: $script !== false) {
					$hostDir = csl_path :: clean(BASEPATH);
					self :: $portal = (bool) preg_match('/^' . str_replace('/', '\/', $hostDir) . '(events\/.+\/){0,1}index.php$/i', self :: $script);
					//get target script
					if (self :: $portal) {
						self :: $script = trim(substr(self :: $script, strlen($hostDir)), '/');
						self :: $script = (preg_match('/^index.php$/i', self :: $script) ? null : trim(substr(csl_path :: cutdir(self :: $script), 7), '/'));
					}
				}
				self :: init();
			}
		}
		/** Init system config info.
		 * @access - private function
		 * @return - null
		 * @usage -  self::init();
		 */
		private static function init() {
			clearstatcache();
			csl_debug :: display(false); //erorr display none
			self :: $versionClass = new csl_version();
			//----system-config-----
			$originalTripSystem = self :: $tripSystem;
			self :: $tripSystem = true;
			$CS_CONF = self :: cueConfig('CodeSwitcher');
			self :: $tripSystem = $originalTripSystem;
			$CS_CONF = (is_array($CS_CONF) ? $CS_CONF : array ()); //check CodeSwitcher config array type
			//set timezone
			if (array_key_exists('DEFAULT_TIMEZONE', $CS_CONF) && is_int($CS_CONF['DEFAULT_TIMEZONE'])) {
				if ($CS_CONF['DEFAULT_TIMEZONE'] >= -12 && $CS_CONF['DEFAULT_TIMEZONE'] <= 14) {
					csl_time :: set_timezone($CS_CONF['DEFAULT_TIMEZONE']);
				}
			}
			//set error stack trace mode
			csl_debug :: set_trace_error_handler(array_key_exists('ERROR_STACK_TRACE_MODE', $CS_CONF) ? ($CS_CONF['ERROR_STACK_TRACE_MODE'] === true ? true : false) : false);
			//set error log storage mode
			csl_debug :: record(array_key_exists('ERROR_LOG_MODE', $CS_CONF) ? ($CS_CONF['ERROR_LOG_MODE'] === true ? true : false) : true);
			//error log storage directory
			if (strlen(isset ($CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION']) && is_string($CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION']) ? $CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION'] : '') > 0) {
				$CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION'] = csl_path :: norm($CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION']);
				$CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION'] = (substr($CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION'], -1, 1) !== '/' ? $CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION'] . '/' : $CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION']);
				$logDate = date('Y-m-d'); //local date
				csl_debug :: error_log_file($CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION'] . 'CS-' . hash('crc32', md5($logDate)) . '-' . $logDate . '.log');
			}
			//intro page
			self :: $intro = (isset ($CS_CONF['INTRO']) && is_string($CS_CONF['INTRO']) ? $CS_CONF['INTRO'] : '');
			self :: $intro = trim(csl_path :: clean(self :: $rootDir . self :: $intro), '/');
			//languages xml version
			$langXmlVersion = (isset ($CS_CONF['LANGUAGE_XML_VERSION']) && is_string($CS_CONF['LANGUAGE_XML_VERSION']) ? $CS_CONF['LANGUAGE_XML_VERSION'] : '1.0');
			//languages xml enciding
			$langXmlEnciding = (isset ($CS_CONF['LANGUAGE_XML_ENCODING']) && is_string($CS_CONF['LANGUAGE_XML_ENCODING']) ? $CS_CONF['LANGUAGE_XML_ENCODING'] : 'utf-8');
			//source IP verification tester
			self :: $tester = (isset ($_SERVER['argc']) && $_SERVER['argc'] >= 1 ? true : (preg_match('/^(localhost|127.0.0.1)$/i', (isset ($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '')) ? true : in_array(csl_browser :: info('ip'), (isset ($CS_CONF['TESTER_IP']) && is_array($CS_CONF['TESTER_IP']) ? $CS_CONF['TESTER_IP'] : array ()), true)));
			//set tester mode
			if (self :: $tester) {
				//set debug mode
				csl_debug :: display(array_key_exists('TESTER_DEBUG_MODE', $CS_CONF) ? ($CS_CONF['TESTER_DEBUG_MODE'] === true ? true : false) : true);
				//develop mode
				self :: $develop = (array_key_exists('TESTER_DEVELOP_MODE', $CS_CONF) ? ($CS_CONF['TESTER_DEVELOP_MODE'] === true ? true : false) : true);
			}
			//check languages xml options
			if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $langXmlVersion)) {
				self :: ERROR_500();
				csl_error :: cast('System failed - invalid language configuration XML version number ' . $langXmlVersion, E_USER_ERROR, 3, 'CS');
			}
			elseif (!csl_inspect :: is_iconv_encoding($langXmlEnciding)) {
				self :: ERROR_500();
				csl_error :: cast('System failed - invalid language configuration XML encoding scheme ' . $langXmlEnciding, E_USER_ERROR, 3, 'CS');
			} else {
				self :: $language = new csl_language('language', $langXmlVersion, $langXmlEnciding);
			}
		}
		/** Output error 500.
		 * @access - private function
		 * @return - null
		 * @usage -  self::ERROR_500();
		 */
		private static function ERROR_500() {
			if (!csl_debug :: is_display() && !headers_sent()) {
				//turns off all output buffers
				while (ob_get_level() > self :: $obStartLevel) {
					ob_end_clean();
				}
				csl_header :: nocache();
				csl_header :: http('Internal Server Error', 500);
				$originalTripSystem = self :: $tripSystem;
				self :: $tripSystem = true;
				self :: viewTemplate('error/500');
				self :: $tripSystem = $originalTripSystem;
			}
			return;
		}
		/** Get the available version info form the CodeSwitcher root directory file script name.
		 * @access - public function
		 * @param - string $scriptName (script name in framework)
		 * @param - string $mode (returns directory relative path or version number) : Default false
		 * @note - $mode `true` is returns directory relative path.
		 * @note - $mode `false` is returns version number.
		 * @return - string|boolean
		 * @usage - csl_mvc::version($scriptName,$mode);
		 */
		public static function version($scriptName = null, $mode = false) {
			self :: start();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0) && !csl_func_arg :: bool2error(1)) {
					if (strlen($scriptName) == 0 || csl_path :: is_absolute($scriptName) || !csl_path :: is_relative($scriptName)) {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument by parameter 1', E_USER_WARNING, 1);
					} else {
						$scriptName = trim(csl_path :: clean(self :: $rootDir . $scriptName), '/');
						if (strlen($scriptName) == 0) {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument by parameter 1', E_USER_WARNING, 1);
						}
						elseif (is_dir(BASEPATH . $scriptName)) {
							$maxVersion = BASEPATH . $scriptName . '/ini/version.php';
							$maxVersion = (is_file($maxVersion) && is_readable($maxVersion) ? csl_import :: from($maxVersion) : '');
							if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)*\.([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $maxVersion)) {
								self :: ERROR_500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Version failed - unknown \'' . $scriptName . '\' defined version number', E_USER_ERROR, 1, 'CS');
							}
							if (!self :: $versionClass->is_exists(BASEPATH . $scriptName, $maxVersion)) {
								self :: ERROR_500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Version failed - defined \'' . $scriptName . '\' version \'' . $maxVersion . '\' has not been established', E_USER_ERROR, 1, 'CS');
							}
							$scriptInfo = explode('/', $scriptName);
							$version = self :: $versionClass->get(BASEPATH . $scriptName, (!self :: $tester || !self :: $develop || (count($scriptInfo) === 2 && $scriptInfo[0] == 'configs' && $scriptInfo[1] == 'CodeSwitcher') ? $maxVersion : '')); //CodeSwitcher is system config
							if ($version) {
								return ($mode ? csl_path :: relative(BASEPATH . $scriptName . '/' . $version . '/') : $version);
							} else {
								self :: ERROR_500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Version failed - unable to get \'' . $scriptName . '\' version', E_USER_ERROR, 1, 'CS');
							}
						} else {
							self :: ERROR_500();
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Version failed - target \'' . $scriptName . '\' does not exist', E_USER_ERROR, 1, 'CS');
						}
					}
				}
			} else {
				self :: ERROR_500();
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_ERROR, 1, 'CS');
			}
			return false;
		}
		/** Get the relative path form the CodeSwitcher root directory file script name.
		 * @access - public function
		 * @param - string $scriptName (script name in framework)
		 * @return - string|boolean
		 * @usage - csl_mvc::formPath($scriptName);
		 */
		public static function formPath($scriptName = null) {
			self :: start();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0)) {
					if (csl_path :: is_absolute($scriptName) || !csl_path :: is_relative($scriptName)) {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
					} else {
						$scriptName = ltrim(csl_path :: clean(self :: $rootDir . $scriptName), '/');
						return csl_path :: relative(BASEPATH . $scriptName);
					}
				}
			} else {
				self :: ERROR_500();
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_ERROR, 1, 'CS');
			}
			return false;
		}
		/** Returns the index page relative path form the CodeSwitcher controller events script directory path name.
		 * @access - public function
		 * @param - string $scriptNaame (events script directory path name)
		 * @return - string|boolean
		 * @usage - csl_mvc::index($scriptNaame);
		 */
		public static function index($scriptNaame = null) {
			self :: start();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0)) {
					if (strlen($scriptNaame) == 0 || csl_path :: is_absolute($scriptNaame) || !csl_path :: is_relative($scriptNaame)) {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
					} else {
						$scriptNaame = trim(csl_path :: clean(self :: $rootDir . $scriptNaame), '/');
						if (strlen($scriptNaame) == 0) {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
						} else {
							if ($scriptNaame == self :: $intro) {
								$file = BASEPATH . 'index.php';
								$file = (is_file($file) ? $file : null);
								if (is_null($file)) {
									$file = BASEPATH . 'events/' . $scriptNaame . '/index.php';
									$file = (is_file($file) ? $file : null);
								}
							} else {
								$file = BASEPATH . 'events/' . $scriptNaame . '/index.php';
								$file = (is_file($file) ? $file : null);
							}
							if (isset ($file)) {
								return csl_path :: relative($file);
							} else {
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Script \'' . $scriptNaame . '\' index page does not exist', E_USER_WARNING, 1);
							}
						}
					}
				}
			} else {
				self :: ERROR_500();
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_ERROR, 1, 'CS');
			}
			return false;
		}
		/** Returns error log storage status 0 or 1, a temporarily change.
		 * @access - public function
		 * @param - boolean $mode (temporarily change mode does not support tester mode) : Default null
		 * @return - integer|boolean
		 * @usage - csl_mvc::logs($mode);
		 */
		public static function logs($mode = null) {
			self :: start();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error()) {
					if (is_null($mode)) {
						return (csl_debug :: is_record() ? 1 : 0);
					}
					elseif (!csl_func_arg :: bool2error(0)) {
						if (!self :: $tester && csl_debug :: record($mode)) { //set error log storage mode
							return (csl_debug :: is_record() ? 1 : 0);
						}
						elseif (self :: $tester) {
							return (csl_debug :: is_record() ? 1 : 0);
						}
					}
				}
			} else {
				self :: ERROR_500();
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_ERROR, 1, 'CS');
			}
			return false;
		}
		/** Returns debug display state 0 or 1, a temporarily change.
		 * @access - public function
		 * @param - boolean $mode (temporarily change mode does not support tester mode) : Default null
		 * @return - integer|boolean
		 * @usage - csl_mvc::debug($mode);
		 */
		public static function debug($mode = null) {
			self :: start();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error()) {
					if (is_null($mode)) {
						return (csl_debug :: is_display() ? 1 : 0);
					}
					elseif (!csl_func_arg :: bool2error(0)) {
						if (!self :: $tester && csl_debug :: display($mode)) { //set debug mode
							return (csl_debug :: is_display() ? 1 : 0);
						}
						elseif (self :: $tester) {
							return (csl_debug :: is_display() ? 1 : 0);
						}
					}
				}
			} else {
				self :: ERROR_500();
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_ERROR, 1, 'CS');
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
					if (strlen($model) == 0 || csl_path :: is_absolute($model) || !csl_path :: is_relative($model)) {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
					} else {
						$model = trim(csl_path :: clean(self :: $rootDir . $model), '/');
						if (strlen($model) == 0) {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
						}
						elseif (is_dir(BASEPATH . 'configs/' . $model)) {
							$maxVersion = BASEPATH . 'configs/' . $model . '/ini/version.php';
							$maxVersion = (is_file($maxVersion) && is_readable($maxVersion) ? csl_import :: from($maxVersion) : '');
							if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)*\.([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $maxVersion)) {
								self :: ERROR_500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Config failed - unknown \'' . $model . '\' defined version number', E_USER_ERROR, 1, 'CS');
							}
							if (!self :: $versionClass->is_exists(BASEPATH . 'configs/' . $model, $maxVersion)) {
								self :: ERROR_500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Config failed - defined \'' . $model . '\' version \'' . $maxVersion . '\' has not been established', E_USER_ERROR, 1, 'CS');
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
											} else {
												self :: ERROR_500();
												csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Config failed - terminate loading \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1, 'CS');
											}
											return $content;
										} else {
											self :: ERROR_500();
											csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Config failed - terminate loading \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1, 'CS');
										}
									} else {
										self :: ERROR_500();
										csl_error :: cast('System failed - open output buffer failed to start', E_USER_ERROR, 1, 'CS');
									}
								} else {
									self :: ERROR_500();
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Config failed - could not load \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1, 'CS');
								}
							} else {
								self :: ERROR_500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Config failed - unable to get \'' . $model . '\' version', E_USER_ERROR, 1, 'CS');
							}
						} else {
							self :: ERROR_500();
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Config failed - target \'' . $model . '\' does not exist', E_USER_ERROR, 1, 'CS');
						}
					}
				}
			} else {
				self :: ERROR_500();
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_ERROR, 1, 'CS');
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
					if (strlen($model) == 0 || csl_path :: is_absolute($model) || !csl_path :: is_relative($model)) {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
					} else {
						$model = trim(csl_path :: clean(self :: $rootDir . $model), '/');
						if (strlen($model) == 0) {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
						}
						elseif (is_dir(BASEPATH . 'languages/' . $model)) {
							$maxVersion = BASEPATH . 'languages/' . $model . '/ini/version.php';
							$maxVersion = (is_file($maxVersion) && is_readable($maxVersion) ? csl_import :: from($maxVersion) : '');
							if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)*\.([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $maxVersion)) {
								self :: ERROR_500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Language failed - unknown \'' . $model . '\' defined version number', E_USER_ERROR, 1, 'CS');
							}
							if (!self :: $versionClass->is_exists(BASEPATH . 'languages/' . $model, $maxVersion)) {
								self :: ERROR_500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Language failed - defined \'' . $model . '\' version \'' . $maxVersion . '\' has not been established', E_USER_ERROR, 1, 'CS');
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
											self :: ERROR_500();
											csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Language failed - terminate loading \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1, 'CS');
										}
									} else {
										self :: ERROR_500();
										csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Language failed - could not load \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1, 'CS');
									}
								} else {
									self :: ERROR_500();
									csl_error :: cast('System failed - open output buffer failed to start', E_USER_ERROR, 1, 'CS');
								}
							} else {
								self :: ERROR_500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Language failed - unable to get \'' . $model . '\' version', E_USER_ERROR, 1, 'CS');
							}
						} else {
							self :: ERROR_500();
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Language failed - target \'' . $model . '\' does not exist', E_USER_ERROR, 1, 'CS');
						}
					}
				}
			} else {
				self :: ERROR_500();
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_ERROR, 1, 'CS');
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
							if (strlen($model) > 0 && is_dir(BASEPATH . 'events/' . $model)) {
								$maxVersion = BASEPATH . 'events/' . $model . '/ini/version.php';
								$maxVersion = (is_file($maxVersion) && is_readable($maxVersion) ? csl_import :: from($maxVersion) : '');
								if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)*\.([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $maxVersion)) {
									self :: ERROR_500();
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - unknown \'' . $model . '\' defined version number', E_USER_ERROR, 1, 'CS');
								}
								if (!self :: $versionClass->is_exists(BASEPATH . 'events/' . $model, $maxVersion)) {
									self :: ERROR_500();
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - defined \'' . $model . '\' version \'' . $maxVersion . '\' has not been established', E_USER_ERROR, 1, 'CS');
								}
								$version = self :: $versionClass->get(BASEPATH . 'events/' . $model, (!self :: $tester || !self :: $develop ? $maxVersion : ''));
								if ($version) {
									$file = BASEPATH . 'events/' . $model . '/' . $version . '/main.inc.php';
									if (is_file($file) && is_readable($file)) {
										self :: $tripSystem = true;
										$import = csl_import :: from($file);
										self :: $tripSystem = false;
										if ($import !== false) {
											if (ob_get_level() >= $obStartLevel) {
												$output = ob_get_contents();
												ob_end_clean();
												echo $output;
											} else {
												self :: ERROR_500();
												csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - terminate loading \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1, 'CS');
											}
											return $version;
										} else {
											self :: ERROR_500();
											csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - terminate loading \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1, 'CS');
										}
									} else {
										self :: ERROR_500();
										csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - could not load \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1, 'CS');
									}
								} else {
									self :: ERROR_500();
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - unable to get \'' . $model . '\' version', E_USER_ERROR, 1, 'CS');
								}
							} else {
								self :: ERROR_500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Event failed - intro page does not exist', E_USER_ERROR, 1, 'CS');
							}
						} else {
							self :: ERROR_500();
							csl_error :: cast('System failed - open output buffer failed to start', E_USER_ERROR, 1, 'CS');
						}
					}
				} else {
					csl_error :: cast('Cannot modify header information - headers already sent by (output started at ' . $fileName . ':' . $lineNum . ')', E_USER_WARNING, 1, 'CS');
				}
			} else {
				if (self :: $tripSystem && self :: $runEvent) {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Can not be called here', E_USER_NOTICE, 1, 'CS');
				}
				elseif (self :: $script === false) {
					self :: ERROR_500();
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Unknown script path', E_USER_ERROR, 1, 'CS');
				}
				elseif (!self :: $portal) {
					self :: ERROR_500();
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_ERROR, 1, 'CS');
				}
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
					if (strlen($model) == 0 || csl_path :: is_absolute($model) || !csl_path :: is_relative($model)) {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
					} else {
						$model = trim(csl_path :: clean(self :: $rootDir . $model), '/');
						if (strlen($model) == 0) {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
						}
						elseif (is_dir(BASEPATH . 'models/' . $model)) {
							$maxVersion = BASEPATH . 'models/' . $model . '/ini/version.php';
							$maxVersion = (is_file($maxVersion) && is_readable($maxVersion) ? csl_import :: from($maxVersion) : '');
							if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)*\.([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $maxVersion)) {
								self :: ERROR_500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Model failed - unknown \'' . $model . '\' defined version number', E_USER_ERROR, 1, 'CS');
							}
							if (!self :: $versionClass->is_exists(BASEPATH . 'models/' . $model, $maxVersion)) {
								self :: ERROR_500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Model failed - defined \'' . $model . '\' version \'' . $maxVersion . '\' has not been established', E_USER_ERROR, 1, 'CS');
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
											} else {
												self :: ERROR_500();
												csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Model failed - terminate loading \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1, 'CS');
											}
											return $version;
										} else {
											self :: ERROR_500();
											csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Model failed - terminate loading \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1, 'CS');
										}
									} else {
										self :: ERROR_500();
										csl_error :: cast('System failed - open output buffer failed to start', E_USER_ERROR, 1, 'CS');
									}
								} else {
									self :: ERROR_500();
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Model failed - could not load \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1, 'CS');
								}
							} else {
								self :: ERROR_500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Model failed - unable to get \'' . $model . '\' version', E_USER_ERROR, 1, 'CS');
							}
						} else {
							self :: ERROR_500();
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Model failed - target \'' . $model . '\' does not exist', E_USER_ERROR, 1, 'CS');
						}
					}
				}
			} else {
				self :: ERROR_500();
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_ERROR, 1, 'CS');
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
					if (strlen($model) == 0 || csl_path :: is_absolute($model) || !csl_path :: is_relative($model)) {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
					} else {
						$model = trim(csl_path :: clean(self :: $rootDir . $model), '/');
						if (strlen($model) == 0) {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
						}
						elseif (is_dir(BASEPATH . 'libraries/' . $model)) {
							$maxVersion = BASEPATH . 'libraries/' . $model . '/ini/version.php';
							$maxVersion = (is_file($maxVersion) && is_readable($maxVersion) ? csl_import :: from($maxVersion) : '');
							if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)*\.([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $maxVersion)) {
								self :: ERROR_500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Library failed - unknown \'' . $model . '\' defined version number', E_USER_ERROR, 1, 'CS');
							}
							if (!self :: $versionClass->is_exists(BASEPATH . 'libraries/' . $model, $maxVersion)) {
								self :: ERROR_500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Library failed - defined \'' . $model . '\' version \'' . $maxVersion . '\' has not been established', E_USER_ERROR, 1, 'CS');
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
											} else {
												self :: ERROR_500();
												csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Library failed - terminate loading \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1, 'CS');
											}
											return $version;
										} else {
											self :: ERROR_500();
											csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Library failed - terminate loading \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1, 'CS');
										}
									} else {
										self :: ERROR_500();
										csl_error :: cast('System failed - open output buffer failed to start', E_USER_ERROR, 1, 'CS');
									}
								} else {
									self :: ERROR_500();
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Library failed - could not load \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1, 'CS');
								}
							} else {
								self :: ERROR_500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Library failed - unable to get \'' . $model . '\' version', E_USER_ERROR, 1, 'CS');
							}
						} else {
							self :: ERROR_500();
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Library failed - target \'' . $model . '\' does not exist', E_USER_ERROR, 1, 'CS');
						}
					}
				}
			} else {
				self :: ERROR_500();
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_ERROR, 1, 'CS');
			}
			return false;
		}
		/** Load the page's template file to view the contents from the CodeSwitcher templates directory.
		 * @access - public function
		 * @param - string $model (model name)
		 * @param - array $data (data array) : Default void array
		 * @param - boolean $process (return content string mode) : Default false
		 * @return - string|error|boolean
		 * @usage - csl_mvc::viewTemplate($model,$data,$process);
		 */
		public static function viewTemplate($model = null, $data = array (), $process = false) {
			self :: start();
			if (self :: $tripSystem) {
				if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0) && !csl_func_arg :: array2error(1) && !csl_func_arg :: bool2error(2)) {
					if (strlen($model) == 0 || csl_path :: is_absolute($model) || !csl_path :: is_relative($model)) {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
					} else {
						$model = trim(csl_path :: clean(self :: $rootDir . $model), '/');
						if (strlen($model) == 0) {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid argument', E_USER_WARNING, 1);
						}
						elseif (is_dir(BASEPATH . 'templates/' . $model)) {
							$maxVersion = BASEPATH . 'templates/' . $model . '/ini/version.php';
							$maxVersion = (is_file($maxVersion) && is_readable($maxVersion) ? csl_import :: from($maxVersion) : '');
							if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]*)*\.([0-9]{1}|[1-9]{1}[0-9]*)\.([0-9]{1}|[1-9]{1}[0-9]*)$/', $maxVersion)) {
								self :: ERROR_500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Template failed - unknown \'' . $model . '\' defined version number', E_USER_ERROR, 1, 'CS');
							}
							if (!self :: $versionClass->is_exists(BASEPATH . 'templates/' . $model, $maxVersion)) {
								self :: ERROR_500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Template failed - defined \'' . $model . '\' version \'' . $maxVersion . '\' has not been established', E_USER_ERROR, 1, 'CS');
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
												} else {
													self :: ERROR_500();
													csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Template failed - terminate loading \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1, true, 'WVC');
												}
												return $version;
											} else {
												if (ob_get_level() >= $obStartLevel) {
													ob_end_clean();
												} else {
													self :: ERROR_500();
													csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Template failed - terminate loading \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1, true, 'WVC');
												}
												return $content;
											}
										} else {
											self :: ERROR_500();
											csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Template failed - terminate loading \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1, 'CS');
										}
									} else {
										self :: ERROR_500();
										csl_error :: cast('System failed - open output buffer failed to start', E_USER_ERROR, 1, 'CS');
									}
								} else {
									self :: ERROR_500();
									csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Template failed - could not load \'' . $model . '\' version \'' . $version . '\' main file', E_USER_ERROR, 1, 'CS');
								}
							} else {
								self :: ERROR_500();
								csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Template failed - unable to get \'' . $model . '\' version', E_USER_ERROR, 1, 'CS');
							}
						} else {
							self :: ERROR_500();
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Template failed - target \'' . $model . '\' does not exist', E_USER_ERROR, 1, 'CS');
						}
					}
				}
			} else {
				self :: ERROR_500();
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): No direct script access allowed', E_USER_ERROR, 1, 'CS');
			}
			return false;
		}
	}
}
?>
