<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

/**
 * Signup controller
 *
 * PHP version 7.0
 */
class Register extends \Core\Controller
{
    /**
     * Show the signup page
     *
     * @return void
     */
    public function registerAction()
    {
		if (isset($_SESSION['user_id'])){
		View::renderTemplate('Contents/main.html');
		} else
        View::renderTemplate('Register/register.html');
    }

    /**
     * Sign up a new user
     *
     * @return void
     */
	 
    public function createAction()
    {
		$user = new User($_POST);
		
		if ($user->save()){
			
			$user->sendActivationEmail();

			$this->redirect('/register/welcome');
		
		} else {
			
			 View::renderTemplate('Register/register.html', [
			 'user' => $user
			 ]);
			 
		}
    }
	
	/**
	* Show the register welcome page
	*
	*@return void
	*/
	public function welcomeAction()
	{
		View::renderTemplate('Register/welcome.html');
	}
	
	/**
	* Activate a new account
	*
	* @return void
	*/
	public function activateAction()
	{
		User::activate($this->route_params['token']);
		
		$user = new User($_POST);
		$user->saveIncomesCategories();
		$user->saveExpensesCategories();
		$user->savePaymentMethods();
		
		$this->redirect('/register/activated');
	}
	
	/**
	* Show the activation success page
	*
	* @return void
	*/
	public function activatedAction()
	{
		View::renderTemplate('Register/welcome_activated.html');
	}
}
