<?php
defined('BASEPATH') OR exit ('No direct script access allowed');
if (!class_exists('csl_time')) {
	/**
	 * @about - time-related functions.
	 */
	class csl_time {
		private static $DateTime;
		/** Get the date range of the number of working days and weekend days, if YYYY beyond calculation range 1 ~ 32767 returns false on failure.
		 * @access - public function
		 * @param - string $firstDate (YYYY-MM-DD)
		 * @param - string $secondDate (YYYY-MM-DD)
		 * @param - boolean $type (date type `true` is weekday or `false` is weekend) : Default false
		 * @return - integer|boolean
		 * @usage - csl_time::part_days($firstDate,$secondDate,$type)
		 */
		public static function part_days($firstDate = null, $secondDate = null, $type = false) {
			if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0) && !csl_func_arg :: string2error(1) && !csl_func_arg :: bool2error(2)) {
				if (!csl_inspect :: is_date($firstDate)) {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): The parameter 1 should be date YYYY-MM-DD', E_USER_WARNING, 1);
				}
				elseif (!csl_inspect :: is_date($secondDate)) {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): The parameter 2 should be date YYYY-MM-DD', E_USER_WARNING, 1);
				} else {
					$max = ($firstDate > $secondDate ? $firstDate : $secondDate);
					$min = ($firstDate > $secondDate ? $secondDate : $firstDate);
					$startReduce = $endAdd = 0;
					$startWeekday = self :: date2week($min);
					$startReduce = ($startWeekday == 7) ? 1 : 0;
					$endWeekday = self :: date2week($max);
					in_array($endWeekday, array (
						6,
						7
					)) && $endAdd = ($endWeekday == 7) ? 2 : 1;
					$allDays = ((self :: datetime2sec($max . ' 00:00:00') - self :: datetime2sec($min . ' 00:00:00')) / 86400) + 1;
					$weekEndDays = floor(($allDays + $startWeekday -1 - $endWeekday) / 7) * 2 - $startReduce + $endAdd;
					if ($type) {
						$workdayDays = $allDays - $weekEndDays;
						return $workdayDays;
					}
					return $weekEndDays;
				}
			}
			return false;
		}
		/** Check the now datetime within limits range, if YYYY beyond calculation range 1 ~ 32767 returns false on failure.
		 * @access - public function
		 * @param - string $nowDatetime (YYYY-MM-DD hh:ii:ss)
		 * @param - string $firstDatetime (YYYY-MM-DD hh:ii:ss)
		 * @param - string $secondDatetime (YYYY-MM-DD hh:ii:ss)
		 * @return - boolean
		 * @usage - csl_time::in_range($nowDatetime,$firstDatetime,$secondDatetime)
		 */
		public static function in_range($nowDatetime = null, $firstDatetime = null, $secondDatetime = null) {
			if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0) && !csl_func_arg :: string2error(1) && !csl_func_arg :: string2error(2)) {
				if (!csl_inspect :: is_datetime($nowDatetime)) {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): The parameter 1 should be datetime YYYY-MM-DD hh:ii:ss', E_USER_WARNING, 1);
				}
				elseif (!csl_inspect :: is_datetime($firstDatetime)) {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): The parameter 2 should be datetime YYYY-MM-DD hh:ii:ss', E_USER_WARNING, 1);
				}
				elseif (!csl_inspect :: is_datetime($secondDatetime)) {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): The parameter 3 should be datetime YYYY-MM-DD hh:ii:ss', E_USER_WARNING, 1);
				} else {
					$max = ($firstDatetime > $secondDatetime ? $firstDatetime : $secondDatetime);
					$min = ($firstDatetime > $secondDatetime ? $secondDatetime : $firstDatetime);
					return ($nowDatetime >= $min && $nowDatetime <= $max ? true : false);
				}
			}
			return false;
		}
		/** Get date day (1 ~ 7 : monday ~ sunday) of the week, if YYYY beyond calculation range 1 ~ 32767 returns false on failure.
		 * @access - public function
		 * @param - string $date (YYYY-MM-DD)
		 * @return - integer|boolean
		 * @usage - csl_time::date2week($date)
		 */
		public static function date2week($date = null) {
			if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0)) {
				if (!csl_inspect :: is_date($date)) {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): The parameter 1 should be date YYYY-MM-DD', E_USER_WARNING, 1);
				} else {
					$year = (int) self :: sub_date($date, 'y');
					$month = (int) self :: sub_date($date, 'm');
					$day = (int) self :: sub_date($date, 'd');
					if ($month == 1 || $month == 2) {
						$month += 12;
						$year -= 1;
					}
					//Calculate
					$y = $year % 100;
					$c = floor($year / 100);
					$m = $month;
					$d = $day;
					$w = $y + floor($y / 4) + floor($c / 4) - (2 * $c) + floor((26 * ($m +1)) / 10) + $d + ($date <= '1582-10-04' ? 2 : -1);
					$w = ($w % 7 + 7) % 7;
					return ($w > 0 ? $w : 7);
				}
			}
			return false;
		}
		/** Calculation date range list, if YYYY beyond calculation range 1 ~ 32767 returns false on failure.
		 * @access - public function
		 * @param - string $firstDate (YYYY-MM-DD)
		 * @param - string $secondDate (YYYY-MM-DD)
		 * @return - array|boolean
		 * @usage - csl_time::date_range($firstDate,$secondDate)
		 */
		public static function date_range($firstDate = null, $secondDate = null) {
			if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0) && !csl_func_arg :: string2error(1)) {
				if (!csl_inspect :: is_date($firstDate)) {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): The parameter 1 should be date YYYY-MM-DD', E_USER_WARNING, 1);
				}
				elseif (!csl_inspect :: is_date($secondDate)) {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): The parameter 2 should be date YYYY-MM-DD', E_USER_WARNING, 1);
				} else {
					$dateList = array ();
					$firstDate .= ' 00:00:00';
					$secondDate .= ' 00:00:00';
					$startDate = ($firstDate < $secondDate ? $firstDate : $secondDate);
					$endDate = ($firstDate > $secondDate ? $firstDate : $secondDate);
					$push = true;
					while ($push) {
						$dateList[] = self :: sub_datetime($startDate, 'date');
						if ($startDate != $endDate) {
							$startDate = self :: jump_datetime($startDate, 86400); //1 day
						} else {
							$push = false;
						}
					}
					return $dateList;
				}
			}
			return false;
		}
		/** Set the script default timezone by timezone id.
		 * @access - public function
		 * @param - string $timezoneId (timezone id)
		 * @return - boolean
		 * @usage - csl_time::set_timezone($timezoneId)
		 */
		public static function set_timezone($timezoneId = null) {
			if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0)) {
				if (function_exists('date_default_timezone_set')) {
					if ($timezoneId) {
						$errorLevel = error_reporting();
						if (ini_set('error_reporting', 0) !== false) { //avoid showing error message
							$result = date_default_timezone_set($timezoneId);
							ini_set('error_reporting', $errorLevel);
						} else {
							$result = @ date_default_timezone_set($timezoneId); //avoid showing error message
						}
						return ($result === false ? false : true);
					}
				} else {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Call to undefined date_default_timezone_set()', E_USER_ERROR, 1);
				}
			}
			return false;
		}
		/** Return part info of date, if YYYY beyond calculation range 1 ~ 32767 returns false on failure.
		 * @access - public function
		 * @param - string $date (YYYY-MM-DD)
		 * @param - string $index (index y,m,d)
		 * @return - string|boolean
		 * @usage - csl_time::sub_date($date,$index)
		 */
		public static function sub_date($date = null, $index = null) {
			if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0) && !csl_func_arg :: string2error(1)) {
				if (!csl_inspect :: is_date($date)) {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): The parameter 1 should be date YYYY-MM-DD', E_USER_WARNING, 1);
				} else {
					$date = explode('-', $date);
					$index = strtolower($index);
					switch ($index) {
						case 'y' :
							return $date[0];
						case 'm' :
							return $date[1];
						case 'd' :
							return $date[2];
						default :
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid index specified', E_USER_WARNING, 1);
							return false;
					}
				}
			}
			return false;
		}
		/** Return part info of time.
		 * @access - public function
		 * @param - string $time (hh:ii:ss)
		 * @param - string $index (index h,i,s,12h)
		 * @return - string|boolean
		 * @usage - csl_time::sub_time($time,$index)
		 */
		public static function sub_time($time = null, $index = null) {
			if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0) && !csl_func_arg :: string2error(1)) {
				if (!csl_inspect :: is_time($time)) {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): The parameter 1 should be time hh:ii:ss', E_USER_WARNING, 1);
				} else {
					$time = explode(':', $time);
					$index = strtolower($index);
					switch ($index) {
						case 'h' :
							return $time[0];
						case 'i' :
							return $time[1];
						case 's' :
							return $time[2];
						case '12h' :
							$msg = (23 - (int) $time[0] > 11 ? 'AM ' : 'PM ');
							$hour = ((int) $time[0] % 12);
							$hour = ($hour != 0 ? $hour : 12);
							$msg .= ($hour < 10 ? '0' . $hour : $hour);
							$msg .= ':' . $time[1] . ':' . $time[2];
							return $msg;
						default :
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid index specified', E_USER_WARNING, 1);
							return false;
					}
				}
			}
			return false;
		}
		/** Return part info of datetime, if YYYY beyond calculation range 1 ~ 32767 returns false on failure.
		 * @access - public function
		 * @param - string $datetime (YYYY-MM-DD hh:ii:ss)
		 * @param - string $index (index y,m,d,h,i,s,date,24h,12h)
		 * @return - string|boolean
		 * @usage - csl_time::sub_datetime($datetime,$index)
		 */
		public static function sub_datetime($datetime = null, $index = null) {
			if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0) && !csl_func_arg :: string2error(1)) {
				if (!csl_inspect :: is_datetime($datetime)) {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): The parameter 1 should be datetime YYYY-MM-DD hh:ii:ss', E_USER_WARNING, 1);
				} else {
					$datetime = explode(' ', $datetime);
					$index = strtolower($index);
					switch ($index) {
						case 'y' :
							return self :: sub_date($datetime[0], $index);
						case 'm' :
							return self :: sub_date($datetime[0], $index);
						case 'd' :
							return self :: sub_date($datetime[0], $index);
						case 'h' :
							return self :: sub_time($datetime[1], $index);
						case 'i' :
							return self :: sub_time($datetime[1], $index);
						case 's' :
							return self :: sub_time($datetime[1], $index);
						case 'date' :
							return $datetime[0];
						case '24h' :
							return $datetime[1];
						case '12h' :
							return self :: sub_time($datetime[1], $index);
						default :
							csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid index specified', E_USER_WARNING, 1);
							return false;
					}
				}
			}
			return false;
		}
		/** Get system microtime.
		 * @access - public function
		 * @return - double|boolean
		 * @usage - csl_time::get_microtime();
		 */
		public static function get_microtime() {
			if (!csl_func_arg :: delimit2error()) {
				list ($usec, $sec) = explode(' ', microtime());
				return (double) ($sec . '.' . str_replace('0.', '', $usec));
			}
			return false;
		}
		/** Get system date.
		 * @access - public function
		 * @param - string $type (type `host` or `gmt`) : Default gmt
		 * @return - string|boolean
		 * @usage - csl_time::get_date($type);
		 */
		public static function get_date($type = 'gmt') {
			if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0)) {
				$type = strtolower($type);
				if ($type != 'host' && $type != 'gmt') {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid type specified', E_USER_WARNING, 1);
				} else {
					return ($type == 'host' ? date('Y-m-d') : gmdate('Y-m-d'));
				}
			}
			return false;
		}
		/** Get system time.
		 * @access - public function
		 * @param - string $type (type `host` or `gmt`) : Default gmt
		 * @return - string|boolean
		 * @usage - csl_time::get_time($type);
		 */
		public static function get_time($type = 'gmt') {
			if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0)) {
				$type = strtolower($type);
				if ($type != 'host' && $type != 'gmt') {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid type specified', E_USER_WARNING, 1);
				} else {
					return ($type == 'host' ? date('H:i:s') : gmdate('H:i:s'));
				}
			}
			return false;
		}
		/** Get system datetime.
		 * @access - public function
		 * @param - string $type (type `host` or `gmt`) : Default gmt
		 * @return - string|boolean
		 * @usage - csl_time::get_datetime($type);
		 */
		public static function get_datetime($type = 'gmt') {
			if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0)) {
				$type = strtolower($type);
				if ($type != 'host' && $type != 'gmt') {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid type specified', E_USER_WARNING, 1);
				} else {
					return ($type == 'host' ? date('Y-m-d H:i:s') : gmdate('Y-m-d H:i:s'));
				}
			}
			return false;
		}
		/** Jump change datetime, if YYYY beyond calculation range 1 ~ 32767 returns false on failure.
		 * @access - public function
		 * @param - string $datetime (YYYY-MM-DD hh:ii:ss)
		 * @param - integer $offsetSec (offset sec number -2147483647 ~ 2147483647)
		 * @return - string|boolean
		 * @usage - csl_time::jump_datetime($datetime,$offsetSec);
		 */
		public static function jump_datetime($datetime = null, $offsetSec = null) {
			if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0) && !csl_func_arg :: int2error(1)) {
				if (!csl_inspect :: is_datetime($datetime)) {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): The parameter 1 should be datetime YYYY-MM-DD hh:ii:ss', E_USER_WARNING, 1);
				} else {
					if (is_null(self :: $DateTime)) {
						self :: $DateTime = new DateTime();
					}
					self :: $DateTime->setDate(self :: sub_datetime($datetime, 'y'), self :: sub_datetime($datetime, 'm'), self :: sub_datetime($datetime, 'd'));
					self :: $DateTime->setTime(self :: sub_datetime($datetime, 'h'), self :: sub_datetime($datetime, 'i'), self :: sub_datetime($datetime, 's'));
					$datetime = self :: $DateTime->modify(($offsetSec < 0 ? '-' : '+') . abs($offsetSec) . ' sec')->format('Y-m-d H:i:s');
					return (csl_inspect :: is_datetime($datetime) ? $datetime : false);
				}
			}
			return false;
		}
		/** Datetime conversion total number of seconds, if YYYY beyond calculation range 1 ~ 32767 returns false on failure.
		 * @access - public function
		 * @param - string $datetime (YYYY-MM-DD hh:ii:ss)
		 * @return - double|boolean
		 * @usage - csl_time::datetime2sec($datetime);
		 */
		public static function datetime2sec($datetime = null) {
			if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0)) {
				if (!csl_inspect :: is_datetime($datetime)) {
					csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): The parameter 1 should be datetime YYYY-MM-DD hh:ii:ss', E_USER_WARNING, 1);
				} else {
					$datetimeArray = explode(' ', $datetime);
					$dateArray = explode('-', $datetimeArray[0]);
					$timeArray = explode(':', $datetimeArray[1]);
					$setY = (double) $dateArray[0];
					$setMn = (double) $dateArray[1] - 1;
					$yearSecs = ((($setY -1) * 365) + floor(($setY -1) / 4)) * 86400;
					$daySecs = (double) $dateArray[2] * 86400;
					$timeSecs = (((double) $timeArray[0]) * 3600) + (((double) $timeArray[1]) * 60) + (double) $timeArray[2];
					$mDay = 0;
					for ($j = 1; $j <= $setMn; $j++) {
						$mDay = $mDay + (double) date('t', mktime(0, 0, 0, $j, 1, $setY));
					}
					$monthSecs = ($mDay -1) * 86400;
					return (double) ($yearSecs + $monthSecs + $daySecs + $timeSecs);
				}
			}
			return false;
		}
	}
}
?>