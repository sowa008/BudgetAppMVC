<?php

namespace App\Models;

use \App\Models\ShowBalance;
use \Core\View;
use \App\Auth;
use \App\Controllers\Balance;


class DateModel extends \Core\Model
{	
	
	public static function getFirstDayOfThisMonth()
	{
		$begin = date('Y-m'.'-01');
		return $begin;
	}
	
	public static function getLastDayOfThisMonth()
	{
		$today = date("Y-m-d");
		$end = date("Y-m-t", strtotime($today));
		return $end;
	}
	
	public static function getFirstDayOfLastMonth()
	{
		$begin = date("Y-m-d", mktime(0, 0, 0, date("m")-1, 1));
		return $begin;
	}
	
	public static function getLastDayOfLastMonth()
	{
		$begin = DateModel::getFirstDayOfLastMonth();
		$end = date("Y-m-t", strtotime($begin));
		return $end;
	}

	public static function getFirstDayOfCurrentYear()
	{	
		$beginYear = date('l',strtotime(date('Y-01-01')));
		return $beginYear;
	}
	
	public static function takeCurrentYear()
	{
		$year = date('Y');
		return $year;
	}
	
	public static function getLastDayOfCurrentYear()
	{	
		$endYear = date('Y-m-d', strtotime('12/31'));
		return $endYear;
	}
	
	/*public static function getFirstDayOfCustomPeriod()
	{
			$begin = isset($_SESSION['begin']);
			return $begin;
	}
	
	public static function getLastDayOfCustomPeriod()
	{
			$end = isset($_SESSION['end']);
			return $end;
	}*/
	
}