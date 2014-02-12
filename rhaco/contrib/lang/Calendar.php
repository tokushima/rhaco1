<?php
Rhaco::import("lang.DateUtil");
/**
 * 万年カレンダー
 * 
 * 一ヶ月を一クラスで扱う
 * 
 * @author Kentaro YABE
 * @license New BSD License
 * @copyright Copyright 2008- rhaco project. All rights reserved.
 */
class Calendar
{
	var $year;
	var $month;
	var $weeks = array();
	var $startOfWeek=0;
	var $days = array(31,28,31,30,31,30,31,31,30,31,30,31);
	
	/**
	 * コンストラクタ
	 *
	 * @param integer $year 年
	 * @param integer $month 月
	 * @param integer $startOfWeek 0:日曜 - 6:土曜
	 * @return Calendar
	 */
	function Calendar($year,$month,$startOfWeek=0){
		$this->__init__($year,$month);
	}
	function __init__($year,$month,$startOfWeek=0){
		$this->year = $year;
		if($this->isLeapYear()){
			$this->days[1]++;
		}
		$this->month = $month;
		$this->startOfWeek = $startOfWeek;
		
		$week = (DateUtil::weekday(sprintf("%04d%02d%02d",$this->year,$this->month,1)) == $this->startOfWeek) ? 0 : 1;
		for($i=1;$i<=$this->days[$this->month-1];$i++){
			$wday = DateUtil::weekday(sprintf("%04d%02d%02d",$this->year,$this->month,$i));
			if($wday==$this->startOfWeek) $week++;
			$this->weeks[$week][$i] = $wday;
		}
	}
	
	/**
	 * 年を取得
	 *
	 * @return integer
	 */
	function getYear(){
		/*** #pass */
		return $this->year;
	}
	
	/**
	 * 月を取得
	 *
	 * @return integer
	 */
	function getMonth(){
		/*** #pass */
		return $this->month;
	}
	
	/**
	 * 閏年かどうか
	 *
	 * @return boolean
	 */
	function isLeapYear(){
		/***
		 * $cal = new Calendar(2005,1);
		 * eq(false,$cal->isLeapYear());
		 * 
		 * $cal = new Calendar(2000,1);
		 * eq(true,$cal->isLeapYear());
		 * 
		 * $cal = new Calendar(1900,1);
		 * eq(false,$cal->isLeapYear());
		 */
		return ($this->year % 4 == 0) && (($this->year % 400 == 0) || ($this->year % 100 != 0));
	}
	
	/**
	 * 次月のカレンダーを取得
	 *
	 * @return Calendar
	 */
	function getNextMonth(){
		/***
		 * $cal = new Calendar(2008,11);
		 * $cal2 = $cal->getNextMonth();
		 * eq(2008,$cal2->getYear());
		 * eq(12,$cal2->getMonth());
		 * 
		 * $cal3 = $cal2->getNextMonth();
		 * eq(2009,$cal3->getYear());
		 * eq(1,$cal3->getMonth());
		 */
		$year = ($this->month==12) ? $this->year+1 : $this->year;
		$month = ($this->month==12) ? 1 : $this->month+1;
		return new Calendar($year,$month,$this->startOfWeek);
	}
	
	/**
	 * 前月のカレンダーを取得
	 *
	 * @return Calendar
	 */
	function getPrevMonth(){
		/***
		 * $cal = new Calendar(2008,2);
		 * $cal2 = $cal->getPrevMonth();
		 * eq(2008,$cal2->getYear());
		 * eq(1,$cal2->getMonth());
		 * 
		 * $cal3 = $cal2->getPrevMonth();
		 * eq(2007,$cal3->getYear());
		 * eq(12,$cal3->getMonth());
		 */
		$year = ($this->month==1) ? $this->year-1 : $this->year;
		$month = ($this->month==1) ? 12 : $this->month-1;
		return new Calendar($year,$month,$this->startOfWeek);
	}
	
	/**
	 * 日付と曜日の一覧を取得
	 *
	 * @return hash 
	 */
	function getDays(){
		/***
		 * $cal = new Calendar(2000,2);
		 * $days = $cal->getDays();
		 * eq(29,count($days));
		 * eq(2,$days[29]);
		 */
		$result = array();
		foreach($this->weeks as $week){
			$result += $week;
		}
		return $result;
	}
	
	/**
	 * 週の情報を取得
	 *
	 * @param integer $weekNo 取得する週 / 0:全て
	 * @param boolean $includeOtherMonth 前後の月の情報を含む
	 * @return array
	 */
	function getWeek($weekNo=0,$includeOtherMonth=false){
		/***
		 * $cal = new Calendar(2008,8);
		 * $weeks = $cal->getWeek(0);
		 * eq(6,count($weeks));
		 * eq(2,count($weeks[1]));
		 * eq(1,count($weeks[6]));
		 * 
		 * $weeks = $cal->getWeek(0,true);
		 * eq(6,count($weeks));
		 * eq(7,count($weeks[1]));
		 * eq(7,count($weeks[6]));
		 * 
		 * $week = $cal->getWeek(3);
		 * eq(7,count($week));
		 * eq(0,$week[10]);
		 * eq(6,$week[16]);
		 * 
		 * $week = $cal->getWeek(1);
		 * eq(2,count($week));
		 * 
		 * $week = $cal->getWeek(6);
		 * eq(1,count($week));
		 * 
		 * $week = $cal->getWeek(1,true);
		 * eq(7,count($week));
		 * 
		 * $week = $cal->getWeek(6,true);
		 * eq(7,count($week));
		 */
		$weekNo = $weekNo;
		if($weekNo==0){
			if(!$includeOtherMonth){
				return $this->weeks;
			}else{
				$weeks = $this->weeks;
				if(count($weeks[1])!=7){
					$cal = $this->getPrevMonth();
					$getLastWeek = $cal->getLastWeek();
					$weeks[1] = $getLastWeek + $weeks[1];
				}
				$getLast = count($weeks);
				if(count($weeks[$getLast])!=7){
					$cal = $this->getNextMonth();
					$weeks[$getLast] += $cal->getFirstWeek();
				}
				return $weeks;
			}
		}else{
			$week = isset($this->weeks[$weekNo]) ? $this->weeks[$weekNo] : false;
			if($week && $includeOtherMonth && count($week)!=7){
				if($weekNo==1){
					$cal = $this->getPrevMonth();
					$week = $week + $cal->getLastWeek();
				}else if($weekNo==$this->getWeekCount()){
					$cal = $this->getNextMonth();
					$week += $cal->getFirstWeek();
				}
			}
			return $week;
		}
	}
	
	/**
	 * 最初の週を取得
	 *
	 * @param boolean $includeOtherMonth 前後の月の情報を含む
	 * @return hash
	 */
	function getFirstWeek($includeOtherMonth=false){
		/***
		 * $cal = new Calendar(2008,8);
		 * $week = $cal->getFirstWeek();
		 * eq(2,count($week));
		 * 
		 * $week = $cal->getFirstWeek(true);
		 * eq(7,count($week));
		 */
		return $this->getWeek(1,$includeOtherMonth);
	}
	
	/**
	 * 最後の週を取得
	 *
	 * @param boolean $includeOtherMonth 前後の月の情報を含む
	 * @return hash
	 */
	function getLastWeek($includeOtherMonth=false){
		/***
		 * $cal = new Calendar(2008,8);
		 * $week = $cal->getLastWeek();
		 * eq(1,count($week));
		 * 
		 * $week = $cal->getLastWeek(true);
		 * eq(7,count($week));
		 */
		return $this->getWeek($this->getWeekCount(),$includeOtherMonth);
	}
	
	/**
	 * 存在する週の数を返す
	 * 
	 * @return integer
	 */
	function getWeekCount(){
		/***
		 * $cal = new Calendar(2008,12);
		 * eq(5,$cal->getWeekCount());
		 */
		return count($this->weeks);
	}
}
?>