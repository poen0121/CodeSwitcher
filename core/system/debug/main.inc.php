<?php
defined('BASEPATH') OR exit ('No direct script access allowed');
if (!class_exists('csl_debug')) {
	/**
	 * @about - debug the operation mode.
	 */
	class csl_debug {
		/** Set PHP errors report mode.
		 * @access - public function
		 * @param - boolean $switch (open or close the report error mode) : Default true
		 * @note - $switch `true` is open the report error types E_ALL.
		 * @note - $switch `false` is close the report error types 0.
		 * @return - boolean
		 * @usage - csl_debug::report($switch);
		 */
		public static function report($switch = true) {
			if (!csl_func_arg :: delimit2error() && !csl_func_arg :: bool2error(0)) {
				ini_set('error_reporting', ($switch ? E_ALL : 0));
				return ($switch ? self :: is_all_report() : self :: is_close_report());
			}
			return false;
		}
		/** Check the PHP error reporting mode is strictly of type E_ALL.
		 * @access - public function
		 * @return - boolean
		 * @usage - csl_debug::is_all_report();
		 */
		public static function is_all_report() {
			if (!csl_func_arg :: delimit2error()) {
				return (error_reporting() & E_ALL ? true : false);
			}
			return false;
		}
		/** Check the PHP error reporting mode is strictly of type 0.
		 * @access - public function
		 * @return - boolean
		 * @usage - csl_debug::is_close_report();
		 */
		public static function is_close_report() {
			if (!csl_func_arg :: delimit2error()) {
				return (error_reporting() === 0 ? true : false);
			}
			return false;
		}
		/** Set PHP errors report display mode.
		 * @access - public function
		 * @param - boolean $switch (open or close the report display mode) : Default true
		 * @note - $switch `true` is open the report display.
		 * @note - $switch `false` is close the report display.
		 * @return - boolean
		 * @usage - csl_debug::display($switch);
		 */
		public static function display($switch = true) {
			if (!csl_func_arg :: delimit2error() && !csl_func_arg :: bool2error(0)) {
				ini_set('display_errors', $switch);
				return ($switch ? self :: is_display() : !self :: is_display());
			}
			return false;
		}
		/** Check the PHP error report display mode is open.
		 * @access - public function
		 * @return - boolean
		 * @usage - csl_debug::is_display();
		 */
		public static function is_display() {
			if (!csl_func_arg :: delimit2error()) {
				if (!self :: is_close_report()) {
					return (bool) preg_match('/^(on|(\+|-)?[0-9]*[1-9]+[0-9]*)$/i', ini_get('display_errors'));
				}
			}
			return false;
		}
		/** Set PHP log errors to specified default file.
		 * @access - public function
		 * @param - string $path (file path)
		 * @return - boolean
		 * @usage - csl_debug::error_log_file($path);
		 */
		public static function error_log_file($path = null) {
			if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0)) {
				if (strlen($path) == 0) {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Empty path supplied as input', E_USER_WARNING, 1);
				} else {
					if (!csl_path :: is_absolute($path) && csl_path :: is_files($path)) {
						$path = csl_path :: norm($path);
						ini_set('error_log', $path);
						return (csl_path :: norm(ini_get('error_log')) === $path ? true : false);
					}
				}
			}
			return false;
		}
		/** Set PHP error log storage.
		 * #note - Uncontrolled error_log function.
		 * @access - public function
		 * @param - boolean $switch (open or close the logs storage) : Default true
		 * @note - $switch `true` is open the logs storage.
		 * @note - $switch `false` is close the logs storage.
		 * @return - boolean
		 * @usage - csl_debug::record($switch);
		 */
		public static function record($switch = true) {
			if (!csl_func_arg :: delimit2error() && !csl_func_arg :: bool2error(0)) {
				ini_set('log_errors', $switch);
				return ($switch ? self :: is_record() : !self :: is_record());
			}
			return false;
		}
		/** Check the PHP error log storage mode is open.
		 * @access - public function
		 * @return - boolean
		 * @usage - csl_debug::is_record();
		 */
		public static function is_record() {
			if (!csl_func_arg :: delimit2error()) {
				if (!self :: is_close_report()) {
					return (bool) preg_match('/^(on|(\+|-)?[0-9]*[1-9]+[0-9]*)$/i', ini_get('log_errors'));
				}
			}
			return false;
		}
	}
}
?>