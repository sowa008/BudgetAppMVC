<?php

namespace App\Models;
use \Core\View;
use \App\Auth;

use PDO;

/**
 * IncomeCategories model
 *
 * PHP version 7.0
 */
class ShowBalance extends \Core\Model
{
	public $errors = [];
	public $time = [];
	
  /**
   * Class constructor
   *
   * @param array $data  Initial property values
   *
   * @return void
   */
  public function __construct($data = [])
  {
    foreach ($data as $key => $value) {
      $this->$key = $value;
	  };
  }

	public function setUserID ()
	{
		if (isset($_SESSION['user_id'])) {
				return $userID = $_SESSION['user_id'];
			} else {
				return '';
			}
	}
	
	public function getIncomesGenerally($beginOfPeriod, $endOfPeriod) 
	{

		$userID = $this->setUserID();
		
		$sql = "SELECT i.date_of_income, ic.name, i.income_comment, i.amount FROM incomes AS i, incomes_category_assigned_to_users AS ic WHERE i.user_id='$userID' AND ic.user_id='$userID' AND ic.id=i.income_category_assigned_to_user_id AND i.date_of_income BETWEEN '$beginOfPeriod' AND '$endOfPeriod' ORDER BY i.date_of_income";

		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();

		$incomesGenerally = $stmt->fetchAll();
		
				
		//var_dump($userID);
		//var_dump($incomesGenerally);
		//exit();

		return $incomesGenerally;
	}
	
	public function getSumOfIncomes($beginOfPeriod, $endOfPeriod) 
	{
		$userID = $this->setUserID();

		$sql = "SELECT SUM(amount) FROM incomes WHERE user_id='$userID' AND date_of_income BETWEEN '$beginOfPeriod' AND '$endOfPeriod' LIMIT 1";

		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();

		$sum = $stmt->fetchColumn();
		return $sum;
	}
	
	public function getSumOfIncomesCustomPeriod()
	{
		$userID = $this->setUserID();
		
		$beginOfPeriod = isset($_SESSION['begin']) ? $_SESSION['begin'] : "another";
		$endOfPeriod = isset($_SESSION['end']) ? $_SESSION['end'] : "another";
		
		/*if (isset($_SESSION['user_id'])){
			$beginOfPeriod = $_SESSION['begin'];
			$endOfPeriod = $_SESSION['end'];
		} else {
			$beginOfPeriod = isset($_SESSION['begin']);
			$endOfPeriod = isset($_SESSION['end']);	
		}*/

		$sql = "SELECT SUM(amount) FROM incomes WHERE user_id='$userID' AND date_of_income BETWEEN '$beginOfPeriod' AND '$endOfPeriod' LIMIT 1";

		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();

		$sum = $stmt->fetchColumn();
		return $sum;
	}
	
	public function getSumOfExpenses($beginOfPeriod, $endOfPeriod) 
	{
		$userID = $this->setUserID();

		$sql = "SELECT SUM(amount) FROM expenses WHERE user_id='$userID' AND date_of_expense BETWEEN '$beginOfPeriod' AND '$endOfPeriod' LIMIT 1";

		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();

		$sum = $stmt->fetchColumn();
		return $sum;
	}
	
	public function getSumOfExpensesCustomPeriod() 
	{
		$userID = $this->setUserID();
		
		$beginOfPeriod = isset($_SESSION['begin']) ? $_SESSION['begin'] : "another";
		$endOfPeriod = isset($_SESSION['end']) ? $_SESSION['end'] : "another";
		
	/*	if (isset($_SESSION['user_id'])){
			$beginOfPeriod = $_SESSION['begin'];
			$endOfPeriod = $_SESSION['end'];
		} else {
			$beginOfPeriod = isset($_SESSION['begin']);
			$endOfPeriod = isset($_SESSION['end']);	
		}*/

		$sql = "SELECT SUM(amount) FROM expenses WHERE user_id='$userID' AND date_of_expense BETWEEN '$beginOfPeriod' AND '$endOfPeriod' LIMIT 1";

		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();

		$sum = $stmt->fetchColumn();
		return $sum;
	}
		
	public function getExpensesGenerally($beginOfPeriod, $endOfPeriod) 
	{

		$userID = $this->setUserID();

		$sql = "SELECT e.date_of_expense, e.amount, ec.name, p.name , e.expense_comment FROM expenses AS e, expenses_category_assigned_to_users AS ec, payment_methods_assigned_to_users AS p WHERE e.user_id='$userID'  AND ec.user_id='$userID'  AND p.user_id='$userID'  AND e.user_id=ec.user_id AND e.user_id=p.user_id AND ec.id=e.expense_category_assigned_to_user_id AND p.id=e.payment_method_assigned_to_user_id AND e.date_of_expense BETWEEN '$beginOfPeriod' AND '$endOfPeriod' ORDER BY e.date_of_expense";


		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();

		$expensesGenerally = $stmt->fetchAll();

		return $expensesGenerally;
	}
	
	public function getExpancesForGoogleChart($beginOfPeriod, $endOfPeriod) 
	{
		$userID = $this->setUserID();
		
		$sql = "SELECT ec.name AS name, SUM(e.amount) AS sum
					FROM expenses AS e, expenses_category_assigned_to_users AS ec
					WHERE e.user_id='$userID' AND ec.user_id='$userID'
					AND e.user_id=ec.user_id 
					AND ec.id=e.expense_category_assigned_to_user_id
					AND e.date_of_expense BETWEEN '$beginOfPeriod' AND '$endOfPeriod'
					GROUP BY expense_category_assigned_to_user_id
					ORDER BY SUM(e.amount) DESC";

			$stmt=$db->prepare($sql);
			$stmt->execute();
			$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
			
	return $result;		
	}

  public static function takeMonthName()
  {
	$current_month = date('m');
	$current_month_name = date("F", strtotime(date('mm')));
	return $current_month_name;
  }
  
  public static function takeLastMonthName()
  {
		$current_month = date('m');
		
		if ($current_month == 1){
			$last_month_name = "December";
		}
		else {
		$last_month = $current_month-1;
		$last_month_name = date("F", mktime(0, 0, 0, $last_month, 10));
		}
		
		return $last_month_name;
  }
  
   
	
}