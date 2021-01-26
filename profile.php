<?php 
include('./classes/DB.php');
include('./classes/Login.php');

$username = "";
$isFollowing = False;

if (isset($_GET['username'])) {
	if (DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))) {
		
		$username = DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['username'];
		$userid = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['id'];
		$followerid = Login::isLoggedIn(); 

		if (isset($_POST['follow'])) {

			if (!DB::query('SELECT follower_id FROM followers WHERE user_id=:userid', array(':userid'=>$userid))){

				DB::query('INSERT INTO followers VALUES (\'\', :userid, :followerid)', array(':userid'=>$userid, ':followerid'=>$followerid));
			} else{
				echo 'Already following';
			}

			$isFollowing = True;
		}

		if (isset($_POST['unfollow'])) {

			if (DB::query('SELECT follower_id FROM followers WHERE user_id=:userid', array(':userid'=>$userid))){

				DB::query('DELETE FROM followers WHERE user_id=:userid AND follower_id=:followingid', array(':userid'=>$userid, ':followerid'=>$followerid));
			}

			$isFollowing = False;
		}

		if (DB::query('SELECT follower_id FROM followers WHERE user_id=:userid', array(':userid'=>$userid))){

				//echo 'Already following';
				$isFollowing = True;

	} else{
		die('User not found');
	}
}

?>

<h1><?php echo $username; ?>'s Profile</h1>
<form action="profile.php?username=<?php echo $username; ?>" method="POST">
	<?php 
	if ($isFollowing) {
		echo '<input type="submit" name="unfollow" value="UnFollow">';
	}
	else{
		echo '<input type="submit" name="follow" value="Follow">';
	}
	?>
	
</form>