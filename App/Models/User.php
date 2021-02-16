<?php

namespace App\Models;
use \Core\View;
use \App\Flash;
use \App\Token;
use \App\Mail;
use \App\Auth;

use PDO;

/**
 * User model
 *
 * PHP version 7.0
 */
class User extends \Core\Model
{
	
	/**
	* Error messages
	*
	* @var array
	*/
	public $errors = [];
	
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
  
  public function getLastUserID()
  {
	$sql = "SELECT id FROM users ORDER BY id DESC LIMIT 1";
	$db = static::getDB();
	$stmt = $db->prepare($sql);	
	$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
	$stmt->execute();		
	return $stmt->fetchColumn();
  }
  
  public function saveIncomesCategories()
  {
	$user_ID =  $this->getLastUserID();
	$sql = "INSERT INTO incomes_category_assigned_to_users (id, user_id, name)
			VALUES (NULL, '$user_ID', 'Salary'), (NULL, '$user_ID', 'Interest'), (NULL, '$user_ID', 'Allegro'), (NULL, '$user_ID', 'Another')";
	$db = static::getDB();
	$stmt = $db->prepare($sql);
	return $stmt->execute();
  }
  
   public function saveExpensesCategories()
  {
	$user_ID =  $this->getLastUserID();
		$sql = "INSERT INTO expenses_category_assigned_to_users (id, user_id, name)
			VALUES (NULL, '$user_ID', 'Transport'), (NULL, '$user_ID', 'Books'), (NULL, '$user_ID', 'Food'), (NULL, '$user_ID', 'Apartments'), (NULL, '$user_ID', 'Telecommunication'), (NULL, '$user_ID', 'Health'), (NULL, '$user_ID', 'Clothes'), (NULL, '$user_ID', 'Hygiene'), (NULL, '$user_ID', 'Kids'), (NULL, '$user_ID', 'Recreation'), (NULL, '$user_ID', 'Trip'), (NULL, '$user_ID', 'Savings'), (NULL, '$user_ID', 'For retirement'), (NULL, '$user_ID', 'Debt Repayment'), (NULL, '$user_ID', 'Gift'), (NULL, '$user_ID', 'Another')";
	$db = static::getDB();
	$stmt1 = $db->prepare($sql);
	return $stmt1->execute();
  }
  
	public function savePaymentMethods()
  {
	$user_ID =  $this->getLastUserID();
	$sql = "INSERT INTO payment_methods_assigned_to_users (id, user_id, name)
			VALUES (NULL, '$user_ID', 'Cash'), (NULL, '$user_ID', 'Debit Card'), (NULL, '$user_ID', 'Credit Card')";
	$db = static::getDB();
	$stmt2 = $db->prepare($sql);
	return $stmt2->execute();
  }

  /**
   * Save the user model with the current property values
   *
   * @return boolean True if the user was saved, false otherwise
   */
  public function save()
  {
	  $this->validate();
	  
	  if (empty($this->errors)) {
				
				$password_hash = password_hash($this->register_password1, PASSWORD_DEFAULT);
				
				$token = new Token();
				$hashed_token = $token->getHash();
				$this->activation_token = $token->getValue();

				$sql = 'INSERT INTO users (id, username, password, email, activation_hash)
						VALUES (NULL, :name, :password_hash, :email, :activation_hash)';

				$db = static::getDB();
				$stmt = $db->prepare($sql);

				$stmt->bindValue(':name', $this->username, PDO::PARAM_STR);
				$stmt->bindValue(':email', $this->register_email, PDO::PARAM_STR);
				$stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
				$stmt->bindValue(':activation_hash', $hashed_token, PDO::PARAM_STR);

				return $stmt->execute();
	  }
	  
	  return false;
  }

	/**
	* Validate current property values, adding validation error messages to the errors array property
	*
	* @return void
	*/
	public function validate()
	{
		//Name
		if ($this->username == '') {
				$this->errors[] = 'Name is required';
		}
		
		//E-mail address
		if (filter_var($this->register_email, FILTER_VALIDATE_EMAIL) === false){
				$this->errors[] = 'Invalid e-mail';
		}
		
		if ($this->emailExists($this->register_email)){
				$this->errors[] = 'E-mail already taken';
		}
		
		$this->validatePassword();
	}
	
	public function validateWithoutEmail()
	{
		//Name
		if ($this->username == '') {
				$this->errors[] = 'Name is required';
		}
		
		//E-mail address
		//if (filter_var($this->register_email, FILTER_VALIDATE_EMAIL) === false){
		//		$this->errors[] = 'Invalid e-mail';
		//}
				
		$this->validatePassword();
	}
	
	public function validatePassword()
	{
		if (isset($this->register_password1)) {
			if ($this->register_password1 != $this->register_password2) {
					$this->errors[] = 'Password must match confirmation';
			}
			
			if (strlen($this->register_password1) < 6) {
					$this->errors[] = 'Please enter at least 6 characters';
			}
			
			if (preg_match('/.*[a-z]+.*/i', $this->register_password1) == 0) {
					$this->errors[] = 'Password needs at least one letter';
			}
			
			if (preg_match('/.*\d+.*/i', $this->register_password1) == 0) {
					$this->errors[] = 'Password needs at least one number';
			}
		}
	}

	/**
	* See if a user record already exists with the specified email
	* 
	* @param string $email email address to search for
	*
	* @return boolean True if a record already exists with the specified email, false otherwise
	*/
	protected function emailExists($email)
	{
		return static::findByEmail($email) !== false;
	}
	
	/**
	* Find a user model by email address
	*
	* @param string $email email address to search for
	*
	* @return mixed User object if found, false otherwise
	*/
	public static function findByEmail($email)
	{
		$sql = 'SELECT * FROM users WHERE email = :email';
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':email', $email, PDO::PARAM_STR);
		
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		
		$stmt->execute();
		
		return $stmt->fetch();
	}
	
	/**
	* Authenticate a user by email and password.
	*
	* @param string $email email address
	* @param string $password password
	*
	*@return mixed The user object or false if authentication fails
	*/
	public static function authenticate($email, $password)
	{
		$user = static::findByEmail($email);
		
		if ($user){		
		$password_hash = $user->password;
		} else {
			Flash::addMessage('Login unsuccessful, please try again', Flash::WARNING);
			//View::renderTemplate('Home/index.html');
			unset($_SESSION['flash_notifications']);
		}
		
        if ($user && $user->is_active) {
            if (password_verify($password, $password_hash)) {
                return $user;
            }
        }
		
		return false;
	}
	
	/**
	* Find a user model by ID
	*
	* @param string $id The user ID
	* 
	* @return mixed User object found, false otherwise 
	*/
	public static function findByID($id)
	{
		$sql = 'SELECT * FROM users WHERE id = :id';
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		
		$stmt->execute();
		
		return $stmt->fetch();
	}
	
	/**
	* Remember the login by inserting a new unique token into the remembered_logins table
	* for this user record
	*
	* @return boolean  True if the login was remembered successfully, false otherwise
	*/
	public function rememberLogin()
	{
		$token = new Token();
		$hashed_token = $token->getHash();
		$this->remember_token = $token->getValue();
		
		$this->expiry_timestamp = time() + 60 * 60 * 24 * 30;    //30 days from now
		
		$sql = 'INSERT INTO logins_remembered (token_hash, user_id, expires_at) VALUES (:token_hash, :user_id, :expires_at)';
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		
		$stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
		$stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
		$stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $this->expiry_timestamp), PDO::PARAM_STR);
		
		return $stmt->execute();
	}
	
	/**
	* Send password reset instruction to the user specified
	*
	* @param string $email  The email address
	*
	* @return void
	*/
	public static function sendPasswordReset($email)
	{
		$user = static::findByEmail($email);
		
		if ($user){
			
				if($user->startPasswordReset()){
					
						$user->sendPasswordResetEmail();
				}
		}	
	}
	
	/**
	* Start the password reset process by generating a new token and expiry
	*
	* @return void
	*/
	protected function startPasswordReset()
	{
		$token = new Token();
		$hashed_token = $token->getHash();
		$this->password_reset_token = $token->getValue();
		
		$expiry_timestamp = time() + 60 * 60 * 2; 		//2 hours from now
		
		$sql = 'UPDATE users
					SET password_reset_hash = :token_hash,
					password_reset_expires_at = :expires_at
					WHERE id = :id';
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		
		$stmt ->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
		$stmt ->bindValue('expires_at', date('Y-m-d H:i:s', $expiry_timestamp), PDO::PARAM_STR);
		$stmt ->bindValue(':id', $this->id, PDO::PARAM_INT);
		
		return $stmt->execute();
	}
		/**
		* Send password reset instruction in an email to the user
		*
		* @return void
		*/
		protected function sendPasswordResetEmail()
		{
			$url = 'http://' . $_SERVER['HTTP_HOST'] . '/password/reset/' . $this->password_reset_token;
			
			$text = View::getTemplate('Password/reset_email.txt', ['url' => $url]);
			$html = View::getTemplate('Password/reset_email.html', ['url' => $url]);
			
			Mail::send($this->email,$this->username, 'Password reset', $text, $html);
		}
		
		/**
		* Find a user model by password reset token and expiry
		*
		* @param string $token Password reset token sent to user
		*
		* @return mixed User object if found and the token hasn't expired, null otherwise
		*/
		public static function findByPasswordReset($token)
		{
			$token = new Token($token);
			$hashed_token = $token->getHash();
			
			$sql = 'SELECT * FROM users
						WHERE password_reset_hash = :token_hash';
			
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			
			$stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
			
			$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
			
			$stmt->execute();
			
			$user = $stmt->fetch();	

			if($user) {
				
				//Check password reset token hasn't expired
				if (strtotime($user->password_reset_expires_at) > time()) {
					return $user;
				}
			} 			
		}
	
	/**
	* Reset the password
	*
	* @param string $password The new password
	*
	* @return boolean  True if the password was updated successfully, false otherwise
	*/
	public function resetPassword($password, $password2)
	{
		$this->register_password1 = $password;
		$this->register_password2 = $password2;
		
		$this->validatePassword();
		
		if (empty($this->errors)) {
			
			$password_hash = password_hash($this->register_password1, PASSWORD_DEFAULT);
			
			$sql = 'UPDATE users
						SET password = :password_hash,
								password_reset_hash = NULL,
								password_reset_expires_at = NULL
						WHERE id = :id';
			
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			
			$stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
			$stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
			
			return $stmt->execute();
		}
		
		return false;
		
	}
	
		/**
		* Send an e-mail to the user containing activation link
		*
		* @return void
		*/
		public function sendActivationEmail()
		{
			$url = 'http://' . $_SERVER['HTTP_HOST'] . '/register/activate/' . $this->activation_token;
			
			$text = View::getTemplate('Register/activation_email.txt', ['url' => $url]);
			$html = View::getTemplate('Register/activation_email.html', ['url' => $url]);
			
			Mail::send($this->register_email,$this->username, 'Account activation', $text, $html);
		}
		
		/**
		* Activate the user account with the specified activation token
		*
		* @param string $value Activation token from the URL
		*
		* @return void
		*/
		public static function activate($value)
		{
			$token = new Token($value);
			$hashed_token = $token->getHash();
			
			$sql = 'UPDATE users
						 SET is_active = 1,
								 activation_hash = null
						 WHERE activation_hash = :hashed_token';
			
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			
			$stmt->bindValue(':hashed_token', $hashed_token, PDO::PARAM_STR);
			
			$stmt->execute();
		}
		
		/**
		* Update the user's profile
		*
		* @param array $data Data from the edit profile form
		*
		* @return boolean True if the data was updated, false otherwise
		*/
		public function updateContents($data)
		{
			$this->username = $data['username'];
			//$this->register_email = $data['register_email'];
			
			//Only validate and update the password if a value provided
			if ($data['register_password1'] != ''){
				$this->register_password1 = $data['register_password1'];
				$this->register_password2 = $data['register_password2'];
			}
			
			//Jesli pobrany email to ten sam email co był to waliduj bez maila, w przeciwnym razie waliduj normalnie

			//$user = Auth::getUser();

			$this->validateWithoutEmail();

			
			if (empty($this->errors)) {
				
				$sql = 'UPDATE users
							SET username = :name';
									
									//Add email if it's set
									
							//if (isset($this->register_email)){
							//$sql .= ', email = :email';
							//}
									
									//Add password if it's set
							if (isset($this->register_password1)){
							$sql .= ', password = :password_hash';
							}
							
							$sql .= "\nWHERE id = :id";
							
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			
			$stmt->bindValue(':name', $this->username, PDO::PARAM_STR);
			
			//Add email if it's set
			//if (isset($this->register_email)){
			//$stmt->bindValue(':email', $this->register_email, PDO::PARAM_STR);
			//}
			
			$stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
			
			//Add password if it's set
			if (isset($this->register_password1)){
				$password_hash = password_hash($this->register_password1, PASSWORD_DEFAULT);
				$stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
			}
			
			return $stmt->execute();
			}
			return false;
			
		}
}