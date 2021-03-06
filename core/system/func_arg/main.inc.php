<?php
defined('BASEPATH') OR exit ('No direct script access allowed');
if (!class_exists('csl_func_arg')) {
	/**
	 * @about - Check the variable types from a user-defined function's argument list.
	 */
	class csl_func_arg {
		/** Get function parameter data.
		 * @access - private function
		 * @param - integer &$var (number id)
		 * @param - array &$itself (backtrace itself array)
		 * @param - array &$caller (backtrace caller array)
		 * @return - array(value)|null
		 * @usage - self::parameter(&$var,&$itself,&$caller);
		 */
		private static function parameter(& $var, & $itself, & $caller) {
			if (isset ($caller['class']) && $caller['class'] == __CLASS__) {
				csl_error :: cast($caller['class'] . '::' . $caller['function'] . '(): Called from the global scope - no function context', E_USER_WARNING, 2);
			}
			elseif (count($itself['args']) > 1) {
				csl_error :: cast($itself['class'] . '::' . $itself['function'] . '(): Expects at most 1 parameters, ' . count($itself['args']) . ' given', E_USER_WARNING, 2);
			}
			elseif (!is_int($var)) {
				csl_error :: cast($itself['class'] . '::' . $itself['function'] . '(): Expects parameter 1 to be integer, ' . strtolower(gettype($var)) . ' given', E_USER_WARNING, 2);
			}
			elseif (is_int($var) && $var < 0) {
				csl_error :: cast($itself['class'] . '::' . $itself['function'] . '(): The argument number should be >= 0', E_USER_WARNING, 2);
			} else {
				//get parameters data
				if (array_key_exists($var, $caller['args'])) {
					return array (
						$caller['args'][$var]
					);
				} else {
					if (isset ($caller['class'])) { //class function parameters
						$ref = new ReflectionMethod($caller['class'], $caller['function']);
					} else { //function parameters
						$ref = new ReflectionFunction($caller['function']);
					}
					//get parameters default data
					$getParameters = $ref->getParameters();
					if (array_key_exists($var, $getParameters)) {
						$arg = & $getParameters[$var];
						return array (
							($arg->isDefaultValueAvailable() ? $arg->getDefaultValue() : NULL)
						);
					} else {
						csl_error :: cast($itself['class'] . '::' . $itself['function'] . '(): Argument ' . $var . ' not passed to function', E_USER_WARNING, 2);
					}
				}
			}
			return;
		}
		/** Check the amount of defined arguments.
		 * @access - public function
		 * @return - boolean
		 * @usage - csl_func_arg::delimit2error();
		 */
		public static function delimit2error() {
			$backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
			$caller = end($backtrace);
			$numArgs = func_num_args();
			if (isset ($caller['class']) && $caller['class'] == __CLASS__) {
				csl_error :: cast($caller['class'] . '::' . $caller['function'] . '(): Called from the global scope - no function context', E_USER_WARNING, 1);
			}
			elseif ($numArgs > 0) {
				csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Expects at most 0 parameters, ' . $numArgs . ' given', E_USER_WARNING, 1);
			}
			elseif (isset ($caller['class'])) { //class function parameters
				$ref = new ReflectionMethod($caller['class'], $caller['function']);
				if (count($caller['args']) > count($ref->getParameters())) {
					csl_error :: cast($caller['class'] . '::' . $caller['function'] . '(): Expects at most ' . count($ref->getParameters()) . ' parameters, ' . count($caller['args']) . ' given', E_USER_WARNING, 2);
				} else {
					return false;
				}
			} else { //function parameters
				$ref = new ReflectionFunction($caller['function']);
				if (count($caller['args']) > count($ref->getParameters())) {
					csl_error :: cast($caller['function'] . '(): Expects at most ' . count($ref->getParameters()) . ' parameters, ' . count($caller['args']) . ' given', E_USER_WARNING, 2);
				} else {
					return false;
				}
			}
			return true;
		}
		/** Check the array type.
		 * @access - public function
		 * @param - integer $var (number id)
		 * @return - boolean
		 * @usage - csl_func_arg::array2error($var);
		 */
		public static function array2error($var = null) {
			$backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
			$itself = current($backtrace);
			$caller = end($backtrace);
			$parameter = self :: parameter($var, $itself, $caller);
			if (is_array($parameter)) {
				if (!is_array($parameter[0])) { //get parameter value
					csl_error :: cast((isset ($caller['class']) ? $caller['class'] . '::' : '') . $caller['function'] . '(): Expects parameter ' . ($var +1) . ' to be array, ' . strtolower(gettype($parameter[0])) . ' given', E_USER_WARNING, 2);
				} else {
					return false;
				}
			}
			return true;
		}
		/** Check the boolean type.
		 * @access - public function
		 * @param - integer $var (number id)
		 * @return - boolean
		 * @usage - csl_func_arg::bool2error($var);
		 */
		public static function bool2error($var = null) {
			$backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
			$itself = current($backtrace);
			$caller = end($backtrace);
			$parameter = self :: parameter($var, $itself, $caller);
			if (is_array($parameter)) {
				if (!is_bool($parameter[0])) { //get parameter value
					csl_error :: cast((isset ($caller['class']) ? $caller['class'] . '::' : '') . $caller['function'] . '(): Expects parameter ' . ($var +1) . ' to be boolean, ' . strtolower(gettype($parameter[0])) . ' given', E_USER_WARNING, 2);
				} else {
					return false;
				}
			}
			return true;
		}
		/** Check the double type.
		 * @access - public function
		 * @param - integer $var (number id)
		 * @return - boolean
		 * @usage - csl_func_arg::double2error($var);
		 */
		public static function double2error($var = null) {
			$backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
			$itself = current($backtrace);
			$caller = end($backtrace);
			$parameter = self :: parameter($var, $itself, $caller);
			if (is_array($parameter)) {
				if (!is_float($parameter[0])) { //get parameter value
					csl_error :: cast((isset ($caller['class']) ? $caller['class'] . '::' : '') . $caller['function'] . '(): Expects parameter ' . ($var +1) . ' to be double, ' . strtolower(gettype($parameter[0])) . ' given', E_USER_WARNING, 2);
				} else {
					return false;
				}
			}
			return true;
		}
		/** Check the integer type.
		 * @access - public function
		 * @param - integer $var (number id)
		 * @return - boolean
		 * @usage - csl_func_arg::int2error($var);
		 */
		public static function int2error($var = null) {
			$backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
			$itself = current($backtrace);
			$caller = end($backtrace);
			$parameter = self :: parameter($var, $itself, $caller);
			if (is_array($parameter)) {
				if (!is_int($parameter[0])) { //get parameter value
					csl_error :: cast((isset ($caller['class']) ? $caller['class'] . '::' : '') . $caller['function'] . '(): Expects parameter ' . ($var +1) . ' to be integer, ' . strtolower(gettype($parameter[0])) . ' given', E_USER_WARNING, 2);
				} else {
					return false;
				}
			}
			return true;
		}
		/** Check the NULL type.
		 * @access - public function
		 * @param - integer $var (number id)
		 * @return - boolean
		 * @usage - csl_func_arg::null2error($var);
		 */
		public static function null2error($var = null) {
			$backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
			$itself = current($backtrace);
			$caller = end($backtrace);
			$parameter = self :: parameter($var, $itself, $caller);
			if (is_array($parameter)) {
				if (!is_null($parameter[0])) { //get parameter value
					csl_error :: cast((isset ($caller['class']) ? $caller['class'] . '::' : '') . $caller['function'] . '(): Expects parameter ' . ($var +1) . ' to be NULL, ' . strtolower(gettype($parameter[0])) . ' given', E_USER_WARNING, 2);
				} else {
					return false;
				}
			}
			return true;
		}
		/** Check the numeric type.
		 * @access - public function
		 * @param - integer $var (number id)
		 * @return - boolean
		 * @usage - csl_func_arg::numeric2error($var);
		 */
		public static function numeric2error($var = null) {
			$backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
			$itself = current($backtrace);
			$caller = end($backtrace);
			$parameter = self :: parameter($var, $itself, $caller);
			if (is_array($parameter)) {
				if (!is_numeric($parameter[0])) { //get parameter value
					csl_error :: cast((isset ($caller['class']) ? $caller['class'] . '::' : '') . $caller['function'] . '(): Expects parameter ' . ($var +1) . ' to be numeric, ' . strtolower(gettype($parameter[0])) . ' given', E_USER_WARNING, 2);
				} else {
					return false;
				}
			}
			return true;
		}
		/** Check the object type.
		 * @access - public function
		 * @param - integer $var (number id)
		 * @return - boolean
		 * @usage - csl_func_arg::object2error($var);
		 */
		public static function object2error($var = null) {
			$backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
			$itself = current($backtrace);
			$caller = end($backtrace);
			$parameter = self :: parameter($var, $itself, $caller);
			if (is_array($parameter)) {
				if (!is_object($parameter[0])) { //get parameter value
					csl_error :: cast((isset ($caller['class']) ? $caller['class'] . '::' : '') . $caller['function'] . '(): Expects parameter ' . ($var +1) . ' to be object, ' . strtolower(gettype($parameter[0])) . ' given', E_USER_WARNING, 2);
				} else {
					return false;
				}
			}
			return true;
		}
		/** Check the resource type.
		 * @access - public function
		 * @param - integer $var (number id)
		 * @return - boolean
		 * @usage - csl_func_arg::resource2error($var);
		 */
		public static function resource2error($var = null) {
			$backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
			$itself = current($backtrace);
			$caller = end($backtrace);
			$parameter = self :: parameter($var, $itself, $caller);
			if (is_array($parameter)) {
				if (!is_resource($parameter[0])) { //get parameter value
					csl_error :: cast((isset ($caller['class']) ? $caller['class'] . '::' : '') . $caller['function'] . '(): Expects parameter ' . ($var +1) . ' to be resource, ' . strtolower(gettype($parameter[0])) . ' given', E_USER_WARNING, 2);
				} else {
					return false;
				}
			}
			return true;
		}
		/** Check the string type.
		 * @access - public function
		 * @param - integer $var (number id)
		 * @return - boolean
		 * @usage - csl_func_arg::string2error($var);
		 */
		public static function string2error($var = null) {
			$backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
			$itself = current($backtrace);
			$caller = end($backtrace);
			$parameter = self :: parameter($var, $itself, $caller);
			if (is_array($parameter)) {
				if (!is_string($parameter[0])) { //get parameter value
					csl_error :: cast((isset ($caller['class']) ? $caller['class'] . '::' : '') . $caller['function'] . '(): Expects parameter ' . ($var +1) . ' to be string, ' . strtolower(gettype($parameter[0])) . ' given', E_USER_WARNING, 2);
				} else {
					return false;
				}
			}
			return true;
		}
	}
}
?>