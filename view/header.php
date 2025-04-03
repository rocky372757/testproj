<!DOCTYPE html>
<html>
<head>
    <title><?php echo $pagetitle ?></title>
   
</head>

<body>
<header>
	<nav>
		<a href="login_register.php">Home</a> |
		<?php if($loggedin) 
		{ ?>
			<a href="login_register.php?action=account">My Account</a> | <a href="login_register.php?action=logout">Logout</a>
		<?php 
		} 
		else 
		{ ?>
			<a href="login_register.php?action=loginform">Login</a> | <a href="login_register.php?action=register">Register</a>
		<?php 
		} 
		?>	
	</nav>
</header>
	