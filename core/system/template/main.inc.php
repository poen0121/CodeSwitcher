<?php
defined('BASEPATH') OR exit ('No direct script access allowed');
if (!class_exists('csl_template')) {
	/**
	 * @about - template control.
	 */
	class csl_template {
		private static $process = array ();
		private static $path;
		/** View content.
		 * @access - public function
		 * @param - string $path (template file path)
		 * @param - array $data (template param data array) : Default empty
		 * @param - boolean $process (return content string mode) : Default false
		 * @return - boolean|string
		 * @usage - csl_template::view($path,$data,$process);
		 */
		public static function view($path = null, $data = array (), $process = false) {
			if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0) && !csl_func_arg :: array2error(1) && !csl_func_arg :: bool2error(2)) {
				if (isset ($path { 0 })) {
					clearstatcache();
					$normPath = csl_path :: norm($path);
					if (!csl_path :: is_absolute($normPath) && is_file($normPath) && is_readable($normPath) && preg_match('/^(.)*\.php$/i', $normPath)) {
						if (!ob_start()) {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Open output buffer failed to start', E_USER_ERROR, 1);
						}
						self :: $process[] = $process;
						self :: $path = $normPath;
						if (count($data) > 0) {
							extract($data);
						}
						//note that the error is all access
						include (self :: $path);
						$processMode = end(self :: $process);
						unset (self :: $process[count(self :: $process) - 1]);
						$output = ob_get_contents();
						if ($output === false) {
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid buffer ended', E_USER_WARNING, 1);
							return false;
						}
						ob_end_clean();
						if ($processMode) {
							return $output;
						} else {
							echo $output;
							return true;
						}
					} else {
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Unable to load template file ' . $normPath, E_USER_NOTICE, 1);
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