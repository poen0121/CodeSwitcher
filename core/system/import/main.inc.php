<?php
defined('BASEPATH') OR exit ('No direct script access allowed');
if (!class_exists('csl_import')) {
	/**
	 * @about - import file.
	 */
	class csl_import {
		/** Importing a file function for include.
		 * @access - public function
		 * @param - string $file (file path)
		 * @return - boolean|data
		 * @usage - csl_import::from($file);
		 */
		public static function from($file = null) {
			if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0)) {
				//note that the error is all access
				$result = include (strtr($file, '\\', '/'));
				return $result;
			}
			return false;
		}
		/** Importing a file function for include_once.
		 * @access - public function
		 * @param - string $file (file path)
		 * @return - boolean|data
		 * @usage - csl_import::from_once($file);
		 */
		public static function from_once($file = null) {
			if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0)) {
				//note that the error is all access
				$result = include_once (strtr($file, '\\', '/'));
				return $result;
			}
			return false;
		}
	}
}
?>