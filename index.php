<?php require_once('includes/connection.php'); ?>
<?php require_once('includes/functions.php'); ?>
<?php session_start(); ?>
<?php 
	 //check for form submission
	if (isset($_POST['submit'])) {

		$error = array(); 

		// check if user name and password has been entered
		// chech email

		if (!isset($_POST['email']) || strlen(trim($_POST['email'])) < 1) {
			$error[] = 'username is missing or invalid';
		}

		//check password
		if (!isset($_POST['password']) || strlen(trim($_POST['password'])) < 1) {
			$error[] = 'password is missing or invalid';
		}

		//check if there are any errors in the form
		if (empty($error)) { 
			$email = mysqli_real_escape_string($connection, $_POST['email']);
			$password = mysqli_real_escape_string($connection, $_POST['password']);
			$hashed_password = sha1($password);

			// database Query
			$query = "SELECT * FROM user
			WHERE email = '{$email}'
			AND password = '{$hashed_password}'
			LIMIT 1";

			$result_set = mysqli_query($connection, $query);

			//query succesful
			verify_query($result_set);

				if (mysqli_num_rows($result_set) == 1) {
					//if valid user found
					$user = mysqli_fetch_assoc($result_set);
					$_SESSION['user_id'] = $user['id'];
					$_SESSION['first_name'] = $user['first_name'];

					//update last login
					$query ="UPDATE user SET last_login =now()";
					$query.="where id = {$_SESSION['user_id']} LIMIT 1";

					$result_set = mysqli_query($connection, $query);

					verify_query($result_set);

					// redirect to users.php page
					header('location: users.php');
				}else{
					$error[] = 'invalid username or password';
				}
		}

	}
 ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
	<div class="login">
		<form action="index.php" method="post">
			<fieldset>
				<legend>
					<h1>Log In</h1>
				</legend>
				
				<?php 
				if (isset($error) && !empty($error)) {
					echo '<p class="error">Invalid user name or password</p>';
				} ?>

				<?php 
				if (isset($_GET['logout'])) {
					echo '<p class="info">Log out succesfully</p>';
				} ?>

				<p>
					<label for="">User Name:</label>
					<input type="text" name="email" id="" placeholder="Email Address">
				</p>
				<p>
					<label for="">Password:</label>
					<input type="password" name="password" id="" placeholder="Password">
				</p>
				<p>
					<button type="submit" name="submit">Log In</button>
				</p>
			</fieldset>
		</form>
	</div>
</body>
</html>

<?php mysqli_close($connection);?>