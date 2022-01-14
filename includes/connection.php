<?php 

	$dbhost = 'localhost';
	$dbuser = 'root';
	$dbpass = '';
	$dbname = 'userdb';

	$connection = mysqli_connect('localhost','root','','userdb');

	if (mysqli_connect_errno()) {
		die('database connection faild. ' . mysqli_connect_error());
	}
 ?>