<?php session_start(); ?>
<?php require_once('includes/connection.php'); ?>
<?php require_once('includes/functions.php'); ?>
<?php 
    // checking if a user is logged in
	if (!isset($_SESSION['first_name'])) {
		header('location: index.php');
	}

	$user_list = '';
	$search ='';

	//getting the list of users
	if (isset($_GET['search'])) {
		$search = mysqli_real_escape_string($connection, $_GET['search']);
		$query = "SELECT * FROM user WHERE (first_name LIKE '%{$search}%' OR last_name LIKE '%{$search}%' OR email LIKE '%{$search}%') AND is_deleted=0 ORDER BY first_name";
	}else{
		$query = "SELECT * FROM user WHERE is_deleted=0 ORDER BY first_name";
	}

	$users = mysqli_query($connection, $query);
	verify_query($users);
		//get users one by one
		while ($user = mysqli_fetch_assoc($users)) {
			$user_list .= "<tr>";
			$user_list .= "<td>{$user['first_name']}</td>";
			$user_list .= "<td>{$user['last_name']}</td>";
			$user_list .= "<td>{$user['last_login']}</td>";
			$user_list .= "<td><a href=\"modify-user.php?user_id={$user['id']}\">Eidt</a></td>";
			$user_list .= "<td><a href=\"delete-user.php?user_id={$user['id']}\" 
			onclick=\"return confirm('Are you sure?');\">Delete</a></td>";
			$user_list .= "</tr>";
		}

 ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>users</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
	<header>
		<div class="appname">User Management System</div>
		<div class="loggedin">Welcome <?php echo $_SESSION['first_name']; ?> <a href="logout.php">Log Out</a> </div>
	</header>

	<main>
		<h1>Users <span> <a href="add-user.php">+ Add new user</a> | <a href="users.php">Refresh</a></span></h1>

		<div class="search">
			<form action="users.php" method="get">
				<p>
					<input type="text" name="search" id="" placeholder="First Name, Last Name or Email" value="<?php echo $search; ?>" autofocus required>
				</p>
			</form>
		</div>

		<table class="masterlist">
			<tr>
				<th>First Name</th>
				<th>Last Name</th>
				<th>Last Login</th>
				<th>Edit</th>
				<th>Delete</th>
			</tr>

			<?php echo $user_list; ?>

		</table>

	</main>
</body>
</html>