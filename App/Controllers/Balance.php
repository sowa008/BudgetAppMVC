<?php

namespace App\Controllers;

use \App\Models\ShowBalance;
use \Core\View;
use \App\Auth;
use \App\Models\DateModel;

class Balance extends Authenticated 
{	
	
	public function showbalanceAction() 
	{
		
		$beginOfPeriod = DateModel::getFirstDayOfThisMonth();
		$endOfPeriod = DateModel::getLastDayOfThisMonth();
		
		$balance = new ShowBalance();
		
		$arg['incomesGenerally'] = $balance->getIncomesGenerally($beginOfPeriod, $endOfPeriod);
		$arg['pieChartIncomeData'] = $balance->getIncomesGenerally($beginOfPeriod, $endOfPeriod);
		
		$arg['expensesGenerally'] = $balance->getExpensesGenerally($beginOfPeriod, $endOfPeriod);
		$arg['pieChartExpenseData'] = $balance->getExpensesGenerally($beginOfPeriod, $endOfPeriod);
		
		View::renderTemplate('Contents/showbalance.html', $arg);
	}
	
	public function lastmonthAction()
	{
		$beginOfPeriod = DateModel::getFirstDayOfLastMonth();
		$endOfPeriod = DateModel::getLastDayOfLastMonth();
		
		$balance = new ShowBalance();
		
		$arg['incomesGenerally'] = $balance->getIncomesGenerally($beginOfPeriod, $endOfPeriod);
		$arg['pieChartData'] = $balance->getIncomesGenerally($beginOfPeriod, $endOfPeriod);
		
		$arg['expensesGenerally'] = $balance->getExpensesGenerally($beginOfPeriod, $endOfPeriod);
		$arg['pieChartData'] = $balance->getExpensesGenerally($beginOfPeriod, $endOfPeriod);
		
		View::renderTemplate('Contents/lastmonth.html', $arg);
		
	}
	
	public function currentyearAction()
	{
		$beginOfPeriod = DateModel::getFirstDayOfCurrentYear();
		$endOfPeriod = DateModel::getLastDayOfCurrentYear();
		
		$balance = new ShowBalance();
		
		$arg['incomesGenerally'] = $balance->getIncomesGenerally($beginOfPeriod, $endOfPeriod);
		$arg['pieChartData'] = $balance->getIncomesGenerally($beginOfPeriod, $endOfPeriod);
		
		$arg['expensesGenerally'] = $balance->getExpensesGenerally($beginOfPeriod, $endOfPeriod);
		$arg['pieChartData'] = $balance->getExpensesGenerally($beginOfPeriod, $endOfPeriod);
		
		View::renderTemplate('Contents/currentyear.html', $arg);
		
	}
	
	public function custombalanceAction() 
	{
		View::renderTemplate('Contents/choosedates.html');
			
	}

	public function customAction() 
	{
		$beginOfPeriod = $_POST['begin'];
		$_SESSION['begin'] = $_POST['begin'];
		//$beginOfPeriod = isset($_SESSION['begin']);

		$endOfPeriod = $_POST['end'];
		$_SESSION['end'] = $_POST['end'];
		//$endOfPeriod = isset($_SESSION['end']);

		$balance = new ShowBalance();
		
		$arg['incomesGenerally'] = $balance->getIncomesGenerally($beginOfPeriod, $endOfPeriod);
		$arg['pieChartIncomeData'] = $balance->getIncomesGenerally($beginOfPeriod, $endOfPeriod);
		
		$arg['expensesGenerally'] = $balance->getExpensesGenerally($beginOfPeriod, $endOfPeriod);
		$arg['pieChartExpenseData'] = $balance->getExpensesGenerally($beginOfPeriod, $endOfPeriod);
		
		View::renderTemplate('Contents/custombalance.html', $arg);
		
	}
	
}