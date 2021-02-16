<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Models\User;
use \App\Models\IncomeCategories;

/**
 * Addincome controller
 *
 * PHP version 7.0
 */
class Addincome extends Authenticated
{

    /**
     * Show addincome
     *
     * @return void
     */
    public function addincomeAction()
    {       
		$income = new IncomeCategories($_POST);
		$income->saveIncome($_SESSION['user_id']);
		View::renderTemplate('Contents/addincome_success.html');
		//$name=$_POST['income_option'];
		//$category_id = IncomeCategories::takeIncomeCategoryID($name);
		//var_dump($category_id);
    }
}
