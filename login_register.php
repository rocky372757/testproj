<?php
//Start or re-start the session 
session_start(); 

//Check if this client is authenticated (logged in)
if(isset($_SESSION['loggedin']))
{//The $_SESSION array is only assigned values after successful 
//authentication, so $_SESSION['loggedin'] will only be set if the user 
//if the user is already logged in
//Assign the data stored in the $_SESSION array to local variables
	$loggedin = $_SESSION['loggedin'];
	$accountid = $_SESSION['accountid'];
	$userDisplay = $_SESSION['userDisplay'];
	$username = $_SESSION['username'];
	$email = $_SESSION['email'];
}
else
{//User is not authenticated (not logged in)
	$loggedin = FALSE;
}
require("model/functions.php");
//Get the user 'action'
//Check the POST array for a value with the name 'action' (form submission)
$action = filter_input(INPUT_POST, 'action');
//If NULL - nothing found in POST array - check the GET array (URL submission)
if($action == NULL)
	$action = filter_input(INPUT_GET, 'action');

if($action == "register")
{//user has clicked the 'register' link
	//No data to capture
	//No processing to be done
	//set the variables for the 'view' file
	$pagetitle="Registration Form";
	$error="";
	$username = "";
	$email="";
	$fname = "";
	$lname="";
	//include the view files
	include("view/header.php");
	include("view/registerform.php");		
}
elseif($action == "Submit Registration")
{//User has submitted the registration form
	//capture data from registration form
	$username = filter_input(INPUT_POST, 'username');
	$password = filter_input(INPUT_POST, 'password');
	$email= filter_input(INPUT_POST, 'email');
	$fname =  filter_input(INPUT_POST, 'fname');
	$lname= filter_input(INPUT_POST, 'lname');
	
	//Check for empty fields by calling a the function
	if(notEmptyAccount($username, $password, $email, $fname, $lname))
	{//function returned true, so all fields are completed
		
		//check if username is available by calling the function
		if(checkUsername($username))
		{//function returned true, so the username is available
			//enter new account by calling the function
			addAccount($username, $password, $email, $fname, $lname);
			$pagetitle="Registration Complete";
			include("view/header.php");
			include("view/registrationdone.php");
		}
		else
		{
			//username is taken, show form again
			$error = "<p>Username is not available. Please select another.</p>";
			$pagetitle="Registration Form";
			include("view/header.php");
			include("view/registerform.php");
		}
	}
	else
	{//Some fields were left empty
		$pagetitle="Registration Form";
		$error="<p>Please complete all fields</p>";
		include("view/header.php");
		include("view/registerform.php");		
	}
	
}
elseif($action == "loginform")
{//User clicked the 'login' link
	//No data to capture
	//No processing to be done
	//set the variables for the 'view' file
	$pagetitle="Login Form";
	$username = "";
	$error="";
	//include the view files
	include("view/header.php");
	include("view/loginform.php");		
}	
elseif($action == "Login")
{//User has submitted the login form
	//Capture the submitted data
	$username = filter_input(INPUT_POST, 'username');
	$password = filter_input(INPUT_POST, 'password');
	
	//Call the processLogin function, send the username and password as parameters
	$account = processLogin($username, $password);
	//The function will return the matching account if the login is correct
	//It will return NULL if the login was incorrect
	if($account != NULL)
	{//Login was successful, user account was returned
		//Set the $_SESSION array values
		//This is the only time when values are assigned to the $_SESSION array
		$_SESSION['loggedin'] = true;
		$_SESSION['username'] = $account['username'];
		$_SESSION['accountid']= $account['id'];
		$_SESSION['email'] = $account['email'];
		$_SESSION['userDisplay'] = $account['fname']." ".$account['lname'];
		//login is done, redirect to default view
		header("Location:login_register.php");			
	}
	else
	{//Login was not successful
		//set the variables for the 'view' file
		$error = "<p>Incorrect login, try again</p>";
		$pagetitle="Login Form";
		//include the 'view' files, showing the login form again
		include("view/header.php");
		include("view/loginform.php");	
	}	
}
elseif($action == "logout" && $loggedin)
{//This 'action' is only available if logged in
	$_SESSION = array();  //clear the $_SESSION array
	session_destroy();	
	header("Location:login_register.php");
}
elseif($action == "account" && $loggedin)
{//This 'action' is only available if logged in
	//user want to view their account details
	//Write the controller file code require
	//as well as any new view file(s) or functions needed
	$pagetitle = "My Account";
	$_SESSION['username'] = $username;
	$_SESSION['email'] = $email;
	$_SESSION['userDisplay'] = $userDisplay;
	include("view/header.php");
	include("view/account.php");
}
else
{ 
	//No data was sent with the request for login_register.php
	$pagetitle = "Login and Register Sample Application";
	include("view/header.php");  //header view file
	include("view/home.php");  //article list view file
	echo "Hello this is an addtion";
}
?>







