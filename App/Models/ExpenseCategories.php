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
class ExpenseCategories extends \Core\Model
{
	
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

  /**
   * Get all the default income categories from database
   *
   * @return object ExpenseCategories object
   */
  /*public static function takeDefaultCategories()
  {
	  	$sql = 'SELECT id, name FROM expenses_category_default';
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		$stmt->execute();
		return $stmt->fetchAll();
  }*/
  
    public static function takeExpenseUserCategories()
  {
	   $expensecategories = new ExpenseCategories();
	  $userID=$expensecategories->setUserID();
	  
	  	$sql = "SELECT id, name FROM expenses_category_assigned_to_users WHERE user_id='$userID'";
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		$stmt->execute();
		return $stmt->fetchAll();
  }
  
      /**
   * Find the ID of default expense category by given name
   *
   * @return object/int
   */
  public static function takeExpenseCategoryID($name, $user_id)
  {
		$sql = 'SELECT id FROM expenses_category_assigned_to_users WHERE name = :name AND user_id = :user_id';
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':name', $name, PDO::PARAM_STR);
		$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
		
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		
		$stmt->execute();
		
		return $stmt->fetchColumn();
  }
  
  /*public static function takeDefaultPaymentMethods()
  {
	  	$sql = 'SELECT id, name FROM payment_methods_default';
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		$stmt->execute();
		return $stmt->fetchAll();
  }*/
  
  public static function takeUserPaymentMethods() //na końcu to 3
  {
	   $paymentmethods = new ExpenseCategories();
	  $userID=$paymentmethods->setUserID();
	  
	  	$sql = "SELECT id, name FROM payment_methods_assigned_to_users WHERE user_id='$userID'";
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		$stmt->execute();
		return $stmt->fetchAll();
  }
  
   public static function takeUserPaymentMethodID($name, $user_id)
  {
		$sql = 'SELECT id FROM payment_methods_assigned_to_users WHERE name = :name AND user_id = :user_id';
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':name', $name, PDO::PARAM_STR);
		$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
		
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		
		$stmt->execute();
		
		return $stmt->fetchColumn();
  }
  
  /**public static function countRecords()
  {
	  $sql = 'SELECT COUNT(id) FROM expenses_category_default';
	  $db = static::getDB();
	  $stmt = $db->prepare($sql);
	  $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
 	  $stmt->execute();
	 return $stmt->fetchColumn();
  }*/
  
  public function saveExpense($user_id)
  {
				$category_name=$_POST['expense_option'];
				$payment_name=$_POST['payment_option'];
				$category_id = ExpenseCategories::takeExpenseCategoryID($category_name, $user_id);
				$payment_id = ExpenseCategories::takeUserPaymentMethodID($payment_name, $user_id);

	  			$sql = 'INSERT INTO expenses (id, user_id, expense_category_assigned_to_user_id, payment_method_assigned_to_user_id, amount, date_of_expense, expense_comment)
						VALUES (NULL, :user_id, :category_id, :payment_id, :amount, :date_of_income, :income_comment)';

				$db = static::getDB();
				$stmt = $db->prepare($sql);

				$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
				$stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
				$stmt->bindValue(':payment_id', $payment_id, PDO::PARAM_INT);
				$stmt->bindValue(':amount', $this->expense, PDO::PARAM_STR);
				$stmt->bindValue(':date_of_income', $this->date, PDO::PARAM_STR);
				$stmt->bindValue(':income_comment', $this->comment, PDO::PARAM_STR);
				
				return $stmt->execute();
  }
	
}