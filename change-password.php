<?php 
include('./classes/DB.php');
include('./classes/Login.php');

$tokenIsValid = False;

if (Login::isLoggedIn()) {
	
	if(isset($_POST['changepassword'])){

		$oldpassword = $_POST['oldpassword'];
		$newpassword = $_POST['newpassword'];
		$newpasswordrepeat = $_POST['newpasswordrepeat'];
		$userid = Login::isLoggedIn();

		if(password_verify($oldpassword, DB::query('SELECT password FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['password'])){

			if ($newpassword == $newpasswordrepeat) {

				if (strlen($newpassword) >= 6 && strlen($newpassword) <= 60) {
					DB::query('UPDATE users SET password=:newpassword WHERE id=:userid', array(':newpassword'=>password_hash($newpassword, PASSWORD_BCRYPT), 'userid'=>$userid));

					echo 'Password Change Successfully!';
				} 
				
			} else{
				echo "Password does not match";
			}

		} else{
			echo "Incorrect Old Password";
		}
	}

	} else{

		if (isset($_GET['token'])) {
		$token = $_GET['token'];

		if (DB::query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($token)))) {

			$userid = DB::query('SELECT user_id FROM password_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];

			$tokenIsValid = True;

			if(isset($_POST['changepassword'])) {

			$newpassword = $_POST['newpassword'];
			$newpasswordrepeat = $_POST['newpasswordrepeat'];

				if ($newpassword == $newpasswordrepeat) {

					if (strlen($newpassword) >= 6 && strlen($newpassword) <= 60) {
						DB::query('UPDATE users SET password=:newpassword WHERE id=:userid', array(':newpassword'=>password_hash($newpassword, PASSWORD_BCRYPT), 'userid'=>$userid));

						echo 'Password Change Successfully!';
					} 
					
				} else{
					echo "Password does not match";
				}
		}

		} else{
			die('Token Invalid');
			}

		} else{

		die('Not Logged In');
		}
    
}

?>

<h1>Change your password</h1>

<form action="<?php if (!$tokenIsValid) { echo 'change-password.php'; } else { echo 'change-password.php?token='.$token.''; } ?>" method="POST">
	
	<?php 
		if (!$tokenIsValid) {
			echo '<input type="password" name="oldpassword" value="" placeholder="Current Password">';
		}
	?>
	</p>
	<input type="password" name="newpassword" value="" placeholder="New Password">
	</p>
	<input type="password" name="newpasswordrepeat" value="" placeholder="Repeat Password">
	</p>
	<input type="submit" name="changepassword" value="Change Password">
	
</form>