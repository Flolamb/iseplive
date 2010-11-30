<?php

/**
 * Helpers for date with internationalization
 */

class Date {
	
	/**
	 * Returns a date easily readable in human language
	 *
	 * @param int $time	Timestamp
	 * @return string
	 */
	public static function easy($time){
		$date = date('j', $time);
		
		// If it's today, date in hour, minutes, seconds
		if($date == date('j'))
			return __('DATE_RECENT', array(
				'interval'	=> self::interval(time() - $time, 1)
			));
		
		// It it's yesterday
		if($date == date('j', time()-86400))
			return __('DATE_YESTERDAY', array(
				'hour'		=> date('H', $time),
				'min'		=> date('i', $time)
			));
		
		// Normal date otherwise
		return self::dateHour($time);
	}
	
	/**
	 * Returns a date with hour in human language
	 *
	 * @param int $time	Timestamp
	 * @return string
	 */
	public static function dateHour($time){
		$year = date('Y', $time);
		return __($year == date('Y') ? 'DATE_FULL' : 'DATE_FULL_WITH_YEAR', array(
			'day'		=> self::getDay($time),
			'date'		=> self::getDate($time),
			'month'		=> self::getMonth($time),
			'year'		=> $year,
			'hour'		=> date('H', $time),
			'min'		=> date('i', $time)
		));
	}
	
	/**
	 * Returns a date in human language
	 *
	 * @param int $time	Timestamp
	 * @return string
	 */
	public static function dateMonth($time){
		$year = date('Y', $time);
		return __($year == date('Y') ? 'DATE_MONTH' : 'DATE_MONTH_WITH_YEAR', array(
			'day'		=> self::getDay($time),
			'date'		=> self::getDate($time),
			'month'		=> self::getMonth($time),
			'year'		=> $year
		));
	}
	
	
	/**
	 * Returns an interval of two dates in human language
	 *
	 * @param int $time1	Timestamp of the starting date
	 * @param int $time2	Timestamp of the ending date
	 * @return string
	 */
	public static function event($time1, $time2){
		if($time1 > $time2){
			$time_ = $time1;
			$time1 = $time2;
			$time2 = $time_;
			unset($time_);
		}
		if($time2 - $time1 < 24*3600){
			return __('DATE_EVENT_TYPE1', array(
				'day'		=> self::getDay($time1),
				'date'		=> self::getDate($time1),
				'month'		=> self::getMonth($time1),
				'year'		=> date('Y', $time1),
				'hour1'		=> date('H', $time1),
				'min1'		=> date('i', $time1),
				'hour2'		=> date('H', $time2),
				'min2'		=> date('i', $time2)
			));
		}else{
			return __('DATE_EVENT_TYPE2', array(
				'day1'		=> self::getDay($time1),
				'date1'		=> self::getDate($time1),
				'month1'	=> self::getMonth($time1),
				'year1'		=> date('Y', $time1),
				'hour1'		=> date('H', $time1),
				'min1'		=> date('i', $time1),
				'day2'		=> self::getDay($time2),
				'date2'		=> self::getDate($time2),
				'month2'	=> self::getMonth($time2),
				'year2'		=> date('Y', $time2),
				'hour2'		=> date('H', $time2),
				'min2'		=> date('i', $time2)
			));
		}
	}
	
	
	/**
	 * Returns an interval in human language
	 *
	 * @param int $seconds	Number of seconds in the interval
	 * @return string
	 */
	public static function interval($seconds, $accuracy=1){
		$interval = array();
		// Years
		if($seconds > 31536000){
			$years = floor($seconds/31536000);
			$interval[] = $years.' '.($years==1 ? __('DATE_YEAR') : __('DATE_YEARS'));
			$seconds = $seconds%31536000;
			$accuracy--;
		}
		// Months
		if($seconds > 2592000 && $accuracy != 0){
			$mois = floor($seconds/2592000);
			$interval[] = $mois.' '.($mois==1 ? __('DATE_MONTH') : __('DATE_MONTHS'));
			$seconds = $seconds%2592000;
			$accuracy--;
		}
		// Days
		if($seconds > 86400 && $accuracy != 0){
			$days = floor($seconds/86400);
			$interval[] = $days.' '.($days==1 ? __('DATE_DAY') : __('DATE_DAYS'));
			$seconds = $seconds%86400;
			$accuracy--;
		}
		// Hours
		if($seconds > 3600 && $accuracy != 0){
			$hours = floor($seconds/3600);
			$interval[] = $hours.' '.($hours==1 ? __('DATE_HOUR') : __('DATE_HOURS'));
			$seconds = $seconds%3600;
			$accuracy--;
		}
		// Minutes
		if($seconds > 60 && $accuracy != 0){
			$minutes = floor($seconds/60);
			$interval[] = $minutes.' '.($minutes==1 ? __('DATE_MINUTE') : __('DATE_MINUTES'));
			$seconds = $seconds%60;
			$accuracy--;
		}
		// Seconds
		if(($seconds > 0 || count($interval) == 0) && $accuracy != 0){
			$interval[] = $seconds.' '.($seconds==1 ? __('DATE_SECOND') : __('DATE_SECONDS'));
		}
		// Last separator (e.g: ", and")
		if(count($interval) > 1){
			$interval[count($interval)-2] .= __('DATE_INTERVAL_SEPARATOR_LAST').' ' . $interval[count($interval)-1];
			array_pop($interval);
		}
		$interval = implode(__('DATE_INTERVAL_SEPARATOR').' ', $interval);
		return $interval;
	}
	
	
	
	/**
	 * Returns the day of the month in human language
	 *
	 * @param int $time	Timestamp
	 * @return string
	 */
	public static function getDate($time){
		$date = date('j', $time);
		if($date == 1)
			return $date.__('DATE_NUMBER_SUFFIX_ONE');
		switch($date % 10){
			case 1:
				return $date.__('DATE_NUMBER_SUFFIX_FIRST');
			case 2:
				return $date.__('DATE_NUMBER_SUFFIX_SECOND');
			case 3:
				return $date.__('DATE_NUMBER_SUFFIX_THIRD');
			default:
				return $date.__('DATE_NUMBER_SUFFIX_DEFAULT');
		}
	}
	
	/**
	 * Returns the day of the week in human language
	 *
	 * @param int $time	Timestamp
	 * @return string
	 */
	public static function getDay($time){
		return self::getDayByNum((int) date('w', $time));
	}
	
	/**
	 * Returns the day of the week in human language
	 *
	 * @param int $day	Number of the day
	 * @return string
	 */
	public static function getDayByNum($day){
		$days = __('DAYS');
		return $days[$day];
	}
	
	/**
	 * Returns the month in human language
	 *
	 * @param int $time	Timestamp
	 * @return string
	 */
	public static function getMonth($time){
		return self::getMonthByNum((int) date('n', $time));
	}
	
	/**
	 * Returns the month in human language
	 *
	 * @param int $month	Number of the month
	 * @return string
	 */
	public static function getMonthByNum($month){
		$months = __('MONTHS');
		return $months[$month-1];
	}
	
}
