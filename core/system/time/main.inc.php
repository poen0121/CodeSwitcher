<?php
defined('BASEPATH') OR exit ('No direct script access allowed');
if (!class_exists('csl_time')) {
	/**
	 * @about - time-related functions.
	 */
	class csl_time {
		/** Error handler.
		 * @access - private function
		 * @param - integer $errno (error number)
		 * @param - string $message (error message)
		 * @return - boolean|null
		 * @usage - set_error_handler(__CLASS__.'::ErrorHandler');
		 */
		private static function ErrorHandler($errno = null, $message = null) {
			if (!(error_reporting() & $errno)) {
				// This error code is not included in error_reporting
				return;
			}
			//replace message target function
			$caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
			$caller = end($caller);
			$message = __CLASS__ . '::' . $caller['function'] . '(): ' . $message;
			//echo message
			csl_error :: cast($message, $errno, 3);
			/* Don't execute PHP internal error handler */
			return true;
		}
		/** Get the date range of the number of working days and weekend days.
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
					in_array($endWeekday, array (6,7)) && $endAdd = ($endWeekday == 7) ? 2 : 1;
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
		/** Check the now datetime within limits range.
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
		/** Get date day (1 ~ 7 : monday ~ sunday) of the week.
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
					$w = $y +floor($y / 4) + floor($c / 4) - (2 * $c) + floor((26 * ($m +1)) / 10) + $d + ($date <= '1582-10-04' ? 2 : -1);
					$w = ($w % 7 + 7) % 7;
					return ($w > 0 ? $w : 7);
				}
			}
			return false;
		}
		/** Calculation date range list.
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
		/** Set the script host time zone by GMT.
		 * @access - public function
		 * @param - integer $GMT (offset hours -12 ~ 14)
		 * @return - boolean
		 * @usage - csl_time::set_timezone($GMT)
		 */
		public static function set_timezone($GMT = null) {
			if (!csl_func_arg :: delimit2error() && !csl_func_arg :: int2error(0)) {
				set_error_handler(__CLASS__ . '::ErrorHandler');
				$result = date_default_timezone_set('Etc/GMT' . ($GMT > 0 ? '-' : '+') . abs($GMT));
				restore_error_handler();
				return $result;
			}
			return false;
		}
		/** Return part info of date.
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
		/** Return part info of datetime.
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
					$datetime = ($offsetSec < 0 ? self :: deduct_datetime($datetime, $offsetSec) : $datetime);
					$datetime = ($offsetSec > 0 ? self :: add_datetime($datetime, $offsetSec) : $datetime);
					return $datetime;
				}
			}
			return false;
		}
		/** Additional time, if YYYY beyond calculation range 1 ~ 32767 returns false on failure.
		 * @access - private function
		 * @param - string $datetime (YYYY-MM-DD hh:ii:ss)
		 * @param - integer $offsetSec (offset sec number 0 ~ 2147483647)
		 * @return - string|boolean
		 * @usage - self::add_datetime($datetime,$offsetSec);
		 */
		private static function add_datetime($datetime = null, $offsetSec = null) {
			$month = (int) self :: sub_datetime($datetime, 'm');
			$year = (int) self :: sub_datetime($datetime, 'y');
			$daysInMonth = array (1 => 31,2 => 28,3 => 31,4 => 30,5 => 31,6 => 30,7 => 31,8 => 31,9 => 30,10 => 31,11 => 30,12 => 31);
			if ($month == 2 && checkdate($month, 29, $year)) {
				$daysInMonth[2] = 29;
			}
			$ms = abs($offsetSec);
			$ns = ((int) self :: sub_datetime($datetime, 's') + $ms); //total sec
			$ds = ($ns % 60);
			$S = ($ds < 10 ? '0' . $ds : $ds);
			$rm = floor($ns / 60);
			$rm = (int) self :: sub_datetime($datetime, 'i') + $rm; //total minute
			$dm = ($rm % 60); //need minute
			$M = ($dm < 10 ? '0' . $dm : $dm);
			$rh = floor($rm / 60);
			$nh = (int) self :: sub_datetime($datetime, 'h') + $rh; //total hour
			$dh = ($nh % 24);
			$H = ($dh < 10 ? '0' . $dh : $dh);
			$nd = floor($nh / 24);
			//----------------------------------------
			$dd = ((int) self :: sub_datetime($datetime, 'd')) + $nd; //total day
			if ($dd <= $daysInMonth[$month]) {
				$D = ($dd < 10 ? '0' . $dd : $dd);
				$Mn = self :: sub_datetime($datetime, 'm');
				$Y = self :: sub_datetime($datetime, 'y');
			} else {
				$setY = (int) self :: sub_datetime($datetime, 'y');
				$setMn = (int) self :: sub_datetime($datetime, 'm');
				$nowDay = $dd;
				while ($nowDay > $daysInMonth[$setMn]) {
					if ($setMn == 2 && checkdate($setMn, 29, $setY)) {
						$daysInMonth[2] = 29;
					} else {
						$daysInMonth[2] = 28;
					}
					$nowDay = $nowDay - $daysInMonth[$setMn];
					$setMn = ($setMn +1);
					$setY = ($setMn > 12 ? ($setY +1) : $setY);
					$setMn = ($setMn > 12 ? 1 : $setMn);
					if ($nowDay <= $daysInMonth[$setMn]) {
						$Y = $setY;
						$Mn = ($setMn < 10 ? '0' . $setMn : $setMn);
						$D = ($nowDay < 10 ? '0' . $nowDay : $nowDay);
					}
				}
			}
			$datetime = $Y . '-' . $Mn . '-' . $D . ' ' . $H . ':' . $M . ':' . $S;
			return (csl_inspect :: is_datetime($datetime) ? $datetime : false);
		}
		/** Deduct time, if YYYY beyond calculation range 1 ~ 32767 returns false on failure.
		 * @access - private function
		 * @param - string $datetime (YYYY-MM-DD hh:ii:ss)
		 * @param - integer $offsetSec (offset sec number 0 ~ 2147483647)
		 * @return - string|boolean
		 * @usage - self::deduct_datetime($datetime,$offsetSec);
		 */
		private static function deduct_datetime($datetime = null, $offsetSec = null) {
			$month = (int) self :: sub_datetime($datetime, 'm');
			$year = (int) self :: sub_datetime($datetime, 'y');
			$daysInMonth = array (1 => 31,2 => 28,3 => 31,4 => 30,5 => 31,6 => 30,7 => 31,8 => 31,9 => 30,10 => 31,11 => 30,12 => 31);
			if ($month == 2 && checkdate($month, 29, $year)) {
				$daysInMonth[2] = 29;
			}
			$ms = abs($offsetSec);
			$ns = ($ms % 60); //need sec
			$ds = (int) self :: sub_datetime($datetime, 's') - $ns;
			$S = ($ds < 0 ? ((60 + $ds) < 10 ? '0' . (60 + $ds) : (60 + $ds)) : ($ds < 10 ? '0' . $ds : $ds));
			$rm = floor($ms / 60);
			$nm = ($rm % 60); //need minute
			$dm = ((int) self :: sub_datetime($datetime, 'i')) - $nm - ($ds < 0 ? 1 : 0);
			$M = ($dm < 0 ? ((60 + $dm) < 10 ? '0' . (60 + $dm) : (60 + $dm)) : ($dm < 10 ? '0' . $dm : $dm));
			$rh = floor($rm / 60);
			$nh = ($rh % 24); //need hour
			$dh = ((int) self :: sub_datetime($datetime, 'h')) - $nh - ($dm < 0 ? 1 : 0);
			$H = ($dh < 0 ? ((24 + $dh) < 10 ? '0' . (24 + $dh) : (24 + $dh)) : ($dh < 10 ? '0' . $dh : $dh));
			$nd = ($dh < 0 ? (1 + floor($rh / 24)) : floor($rh / 24)); //need day
			if ($nd == 0) {
				$D = self :: sub_datetime($datetime, 'd');
				$Mn = self :: sub_datetime($datetime, 'm');
				$Y = self :: sub_datetime($datetime, 'y');
			} else {
				$setY = (int) self :: sub_datetime($datetime, 'y');
				$setMn = (int) self :: sub_datetime($datetime, 'm');
				$nowDay = (int) self :: sub_datetime($datetime, 'd');
				while ($nd > 0) {
					if ($nowDay > 0) {
						$nowDay = $nowDay -1;
						$nd = $nd -1;
					}
					if ($nowDay < 1) {
						$setY = (($setMn -1) < 1 ? ($setY -1) : $setY);
						$setMn = (($setMn -1) < 1 ? 12 : ($setMn -1));
						if ($setMn == 2 && checkdate($setMn, 29, $setY)) {
							$daysInMonth[2] = 29;
						} else {
							$daysInMonth[2] = 28;
						}
						$nowDay = $daysInMonth[$setMn];
						if ($nd > 0) {
							$nowDay = $nowDay -1;
							$nd = $nd -1;
						}
					}
					if ($nd == 0) {
						$Y = $setY;
						$Mn = ($setMn < 10 ? '0' . $setMn : $setMn);
						$D = ($nowDay < 10 ? '0' . $nowDay : $nowDay);
					}
				}
			}
			$datetime = (isset ($Y) ? $Y : '0000') . '-' . (isset ($Mn) ? $Mn : '00') . '-' . (isset ($D) ? $D : '00') . ' ' . $H . ':' . $M . ':' . $S;
			return (csl_inspect :: is_datetime($datetime) ? $datetime : false);
		}
		/** Datetime conversion total number of seconds.
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