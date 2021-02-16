<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Models\User;

/**
 * Items controller (example)
 *
 * PHP version 7.0
 */
class Contents extends Authenticated
{
    /**
     * Items index
     *
     * @return void
     */
    public function mainAction()
    {       
		View::renderTemplate('Contents/main.html');
    }
	
	/**
	*
	* Add a new content
	* 
	* @return void
	*/
	public function addexpenseAction()
	{
		View::renderTemplate('Contents/addexpense.html');
	}
	
	public function addincomeAction()
	{
		View::renderTemplate('Contents/addincome.html');
	}
	
	public function settingsAction()
	{
		View::renderTemplate('Contents/settings.html', [
		'user' => Auth::getUser()
		]);
	}
	
	public function showbalanceAction()
	{
		$this->redirect('/balance/showbalance');
	}
	
	public function updateAction()
	{
		$user = Auth::getUser();
		
		if ($user->updateContents($_POST)) {
			Flash::addMessage('Changes saved');
			$this->redirect('/contents/settings');
		}
		else {
		View::renderTemplate('Contents/settings.html', [
		'user' => $user
		]);
		}
	}
	
	public function choosebalanceAction()
	{
		if(isset($_POST['optionTime'])){
				$value = $_POST['optionTime'];
	
			switch($value)
			{
			case '1' : {
				$this->redirect('/balance/showbalance');
				}
			break;
			case '2' : {
				$this->redirect('/balance/lastmonth');
				}
			break;
			case '3' : {
				$this->redirect('/balance/currentyear');
				}
			break;
			case '4' : {
				$this->redirect('/balance/custombalance');
				}
			break;
			}
		}
	}
	
}
