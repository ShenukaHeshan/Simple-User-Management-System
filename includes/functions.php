<?php 
	function verify_query($result_set){

		global $connection;

		if (!$result_set) {
			die("database query faild: " . mysqli_error($connection));
		}
	}

	function check_req_fields($req_fields){
		//checking requireds fields
		$errors = array();
		foreach ($req_fields as $fields) {
			if (empty(trim($_POST[$fields]))) {
			$errors[] = $fields . ' is required';
			}
		}
		return $errors;
	}


	function check_max_len($max_len_fields){
		//checking max length
		$errors = array();
		foreach ($max_len_fields as $fields => $max_len) {
		if (strlen(trim($_POST[$fields])) > $max_len) {
			$errors[] = $fields . ' must be less than ' . $max_len . ' characters';
		}
	}
		return $errors;
	}

	function display_errors($errors){
		//display form errors
		echo '<div class="errmsg">';
				echo '<b>There were error(s) on your form.</b><br>';
				foreach ($errors as $error) {
					$error = ucfirst(str_replace("_", " ", $error));
					echo ("- ".$error.".<br>");
				}
				echo '</div><br>';
	}


 ?>