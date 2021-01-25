<?php

include('classes/DB.php');

if (isset($_POST['createaccount'])) {
	
	$username = $_POST['username'];
	$password = $_POST['password'];
	$email = $_POST['email'];

	if (!DB::query('SELECT username FROM users WHERE username=:username', array('username'=>$username))){

		if(strlen($username) >= 3 && strlen($username) <= 33){

			if (preg_match('/[a-zA-Z0-9]+/', $username)) {

				if (strlen($password) >= 6 && strlen($password) <= 60) {

					if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

						if (!DB::query('SELECT email FROM users WHERE email=:email', array(':email'=>$email))) {
		
								DB::query('INSERT INTO users VALUES (\'\', :username, :password, :email)', array(':username'=>$username, ':password'=>password_hash($password, PASSWORD_BCRYPT), ':email'=>$email));
								echo "Success!";
					} 
						else{
							echo 'Email in Use';
					}
				}
					else{
						echo 'Invalid Email';
					}
				}
				else{
					echo 'Invalid Password';
				}
			}
			else{
				echo 'Invalid Username';
			}
		}
		else{
			echo 'Invalid Username';
		}
	}
	else{
		echo 'User already exists!';
	}
}
?>


<h1>Register</h1>
<form action="create-account.php" method="POST">
	<input type="text" name="username" value="" placeholder="Username
	"><p />
	<input type="password" name="password" value="" placeholder="Password"><p />
	<input type="email" name="email" value="" placeholder="Email"><p />
	<input type="submit" name="createaccount" value="Create Account"><p />
</form>