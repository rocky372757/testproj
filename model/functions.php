<?php
	//Connection information for finding and connecting to the MySQL server
	$dsn = 'mysql:host=localhost;dbname=login';
	$dbuser = 'root';
	$dbpass = '';


	$db = new PDO($dsn, $dbuser, $dbpass);
	
	
	function notEmptyAccount($username, $password, $email, $fname, $lname)
	{
		//Not every function deals with DB connections
		//This function checks for empty variables and is used for form validation
		//returns true if non of the variables (parameters) are empty or returns false if one or more of them are empty
		if(empty($username) || empty($password) || empty($email) || empty($fname) || empty($lname)){
			return false;
		}
		return true;
		
	}
	
	function addAccount($username, $password, $email, $fname, $lname)
	{
		//Inserts a new account in the accounts table
		global $db;

		$query = 'INSERT INTO accounts (username, password, fname, lname, email) VALUES (:username, :password, :fname, :lname, :email)';

		$statement = $db->prepare($query);

		$statement->bindValue(':username', $username);
		$statement->bindValue(':password', $password);
		$statement->bindValue(':fname', $fname);
		$statement->bindValue(':lname', $lname);
		$statement->bindValue(':email', $email);

		$statement->execute();

		$statement->closeCursor();


	}
	function getAccount($id)
	{
		//Finds and returns a single account based on the 'id' column 
		global $db;
		
		$query = 'SELECT * FROM accounts WHERE id = :id';
		$statement = $db-> prepare($query);
		$statement->bindValue(':id', $id);
		$statement->execute();

		$account = $statement->fetch();
		$statement->closeCursor();

		return $account;
		
	}
	function checkUsername($username)
	{
		//Checks if a username submitted for registration is available
		//Usernames must be unique
		//Function returns true if the username is available and false if it is not
		global $db;

		$queryUser = 'SELECT id FROM accounts WHERE username = :username';
		$statement = $db->prepare($queryUser);

		$statement->bindValue(':username', $username);
		$statement->execute();

		$userAccount = $statement->fetch();
		$statement->closeCursor();
		
		if($userAccount == NULL)
		return true;
		
		return false;

	}
	function processLogin($username, $password)
	{
		//This function receives the username and password entered by the user in the Login form
		//It queries the accounts table using the username to see if a match was found
		//Since the username field is set as unique in the DB, only a single record will match
		//Or no records will match if the username does not exist in the table
		//If a record is found, then the supplied password will be compared to the password stored in the database and returned with the query results
		//The function will return the matching account if the login is correct
		//It will return NULL if the login was incorrect
		global $db;

		$queryUser = 'SELECT * FROM accounts WHERE username = :username';
		$statement = $db->prepare($queryUser);

		$statement->bindValue(':username', $username);
		$statement->execute();

		$userAccount = $statement->fetch();
		$statement->closeCursor();
		
		if($userAccount != NULL)
		{
		if($password == $userAccount['password'])
		
				return $userAccount; //Login successful
			else
				return NULL;
		
			}
			else
				return NULL;
		
	}
?>