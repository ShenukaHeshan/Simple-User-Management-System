<?php session_start(); ?>
<?php require_once('includes/connection.php'); ?>
<?php require_once('includes/functions.php'); ?>

<?php 

	    // checking if a user is logged in
	if (!isset($_SESSION['first_name'])) {
		header('location: index.php');
	}

	 $errors = array();
	 $user_id = '';
	 $first_name ='';
	 $last_name ='';
	 $email ='';
	 $password ='';

	 if (isset($_GET['user_id'])) {
	 	// getting user information
	 	$user_id = mysqli_real_escape_string($connection, $_GET['user_id']);
	 	$query = "SELECT * FROM user WHERE id = {$user_id} LIMIT 1";

	 	$result_set = mysqli_query($connection, $query);

	 	if ($result_set) {
	 		if (mysqli_num_rows($result_set) == 1) {
	 			// user found
	 			$result = mysqli_fetch_assoc($result_set);
	 				 $first_name =$result['first_name'];
	 				 $last_name =$result['last_name'];
					 $email =$result['email'];

	 		}else{
	 			// user not found
	 			header('location: users.php?err=user_not_found');
	 		}
	 	}
	 	else{
	 		//query unsuccessful.
	 		header('location: users.php?err=query_field');
	 	}
	 	

	 }


if (isset($_POST['submit'])) {

	$user_id =$_POST['user_id'];
	$first_name =$_POST['first_name'];
	$last_name =$_POST['last_name'];
	$email =$_POST['email'];
	

	// checking required fields
	$req_fields = array('user_id', 'first_name', 'last_name', 'email');
	$errors = array_merge($errors, check_req_fields($req_fields));


	//checking max length
	$max_len_fields = array('first_name' => 50, 'last_name'=> 100, 'email'=> 100);
	$errors = array_merge($errors, check_max_len($max_len_fields));

	
	//checking if email address already exixts
	$email = mysqli_real_escape_string($connection, $_POST['email']);
	$query = "SELECT * FROM user WHERE email = '{$email}' AND id != {$user_id} LIMIT 1";

	$result_set = mysqli_query($connection, $query);

	if ($result_set) {
		if(mysqli_num_rows($result_set) == 1){
			$errors[] = 'email address already exists';
		}
	}

	if (empty($errors)) {
		// no errors found, adding new record
		$first_name = mysqli_real_escape_string($connection, $_POST['first_name']);
		$last_name = mysqli_real_escape_string($connection, $_POST['last_name']);
		// email is already sanitized
		$hashed_password = sha1($password);

		$query = "UPDATE user SET ";
		$query .= "first_name ='{$first_name}', ";
		$query .= "last_name ='{$last_name}', ";
		$query .= "email ='{$email}'";
		$query .= "WHERE id = {$user_id} LIMIT 1";


		$result = mysqli_query($connection, $query);

		if ($result) {
			// Query successful, redirecting to users page
			header('location: users.php?user_modified=true');
		}else{
			$errors[] = 'field to modify user';
		}
	}

}

 ?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>View/Modify User</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
	<header>
		<div class="appname">User Management System</div>
		<div class="loggedin">Welcome <?php echo $_SESSION['first_name']; ?> <a href="logout.php">Log Out</a> </div>
	</header>

	<main>
		<h1>View / Modify user<span> <a href="users.php">< back to user view</a></span></h1>

		<?php 
			if (!empty($errors)) {
				display_errors($errors);
			}
		?>

		<form action="modify-user.php" method="post" class="userform">

			<input type="hidden" name="user_id" value="<?php echo $user_id ?>">

			<p>
				<label>First Name:</label>
				<input type="text" name="first_name" <?php echo 'value="' . $first_name . '"'; ?>>
			</p>

			<p>
				<label>Last Name:</label>
				<input type="text" name="last_name" <?php echo 'value="' . $last_name . '"'; ?>>
			</p>

			<p>
				<label>Email Address:</label>
				<input type="email" name="email" <?php echo 'value="' . $email . '"'; ?>>
			</p>

			<p>
				<label>Password:</label>
				<span>******</span> | <a href="change-password.php?user_id=<?php echo $user_id; ?>">Change Password</a>
			</p>

			<p>
				<label>&nbsp;</label>
				<button type="submit" name="submit">Save</button>
			</p>
		</form>

	</main>
</body>
</html>