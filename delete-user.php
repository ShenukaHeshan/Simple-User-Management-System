<?php session_start(); ?>
<?php require_once('includes/connection.php'); ?>
<?php require_once('includes/functions.php'); ?>

<?php 

	    // checking if a user is logged in
	if (!isset($_SESSION['first_name'])) {
		header('location: index.php');
	}

	 if (isset($_GET['user_id'])) {
	 	// getting user information
	 	$user_id = mysqli_real_escape_string($connection, $_GET['user_id']);

	 	if ($user_id == $_SESSION['user_id']) {
	 		// should not delete current user
	 		header('location: users.php');
	 	}else{

	 		//deleting user
	 		$query = "UPDATE user SET is_deleted = 1 WHERE id = {$user_id} LIMIT 1";
	 		$result = mysqli_query($connection, $query);
	 		if ($result) {
	 			// user deleted
	 			header('location: users.php?msg=user_deleted');
	 		} else {
	 			header('location: users.php?err=delete_faild');
	 		}
	 		
	 	}

	 }else{
	 	header('location: users.php');
	 }
?>