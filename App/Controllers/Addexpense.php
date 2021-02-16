<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Models\User;
use \App\Models\ExpenseCategories;

/**
 * Addincome controller
 *
 * PHP version 7.0
 */
class Addexpense extends Authenticated
{

    /**
     * Show addincome
     *
     * @return void
     */
    public function addexpenseAction()
    {       
		$income = new ExpenseCategories($_POST);
		$income->saveExpense($_SESSION['user_id']);
		View::renderTemplate('Contents/addexpense_success.html');
		//$name=$_POST['income_option'];
		//$category_id = IncomeCategories::takeIncomeCategoryID($name);
		//var_dump($category_id);
    }
}
