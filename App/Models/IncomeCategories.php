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
class IncomeCategories extends \Core\Model
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
   * @return object IncomeCategories object
   */
/**  public static function takeDefaultCategories()
  {
	  	$sql = 'SELECT id, name FROM incomes_category_default';
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		$stmt->execute();
		return $stmt->fetchAll();
  }*/
  
    public static function takeIncomeUserCategories()
  {
	   $incomecategories = new IncomeCategories();
		$userID=$incomecategories->setUserID();
	  
	  	$sql = "SELECT id, name FROM incomes_category_assigned_to_users WHERE user_id='$userID'";
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		$stmt->execute();
	
		return $stmt->fetchAll();
  }
  
    /**
   * Find the ID of default income category by given name
   *
   * @return object/int
   */
  public static function takeIncomeCategoryID($name, $user_id)
  {
		$sql = 'SELECT id FROM incomes_category_assigned_to_users WHERE name = :name AND user_id = :user_id';
		
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
	  $sql = 'SELECT COUNT(id) FROM incomes_category_default';
	  $db = static::getDB();
	  $stmt = $db->prepare($sql);
	  $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
 	  $stmt->execute();
	 return $stmt->fetchColumn();
  } */
  
  public function saveDefaultCategoriesToUserAccount($user_id)
  {
	  //wywołaj funkcję takeDefaultCategories();
	  //zapisz te kategorie do drugiej bazy "incomes_category_assigned_to_users" w formacie id user_id name
  }
  
  public function saveIncome($user_id)
  {
				$name=$_POST['income_option'];
				
				$category_id = IncomeCategories::takeIncomeCategoryID($name, $user_id);

	  			$sql = 'INSERT INTO incomes (id, user_id, income_category_assigned_to_user_id, amount, date_of_income, income_comment)
						VALUES (NULL, :user_id, :category_id, :amount, :date_of_income, :income_comment)';

				$db = static::getDB();
				$stmt = $db->prepare($sql);

				$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
				$stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
				$stmt->bindValue(':amount', $this->income, PDO::PARAM_STR);
				$stmt->bindValue(':date_of_income', $this->date, PDO::PARAM_STR);
				$stmt->bindValue(':income_comment', $this->comment, PDO::PARAM_STR);
				
				return $stmt->execute();			
  }
	
}