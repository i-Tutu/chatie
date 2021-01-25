<?php 
include('./classes/DB.php');
include('./classes/Login.php');

$username = "";

if (isset($_GET['username'])) {
	if (DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))) {
		
		$username = DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['username'];

	} else{
		die('User not found');
	}
}

?>

<h1><?php echo $username; ?>'s Profile</h1>