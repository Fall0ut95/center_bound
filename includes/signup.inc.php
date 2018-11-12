<?php
if (isset($_POST['signup-submit'])) {
	
	require 'dbh.inc.php';

	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$confirmPassword = $_POST['confirm_password'];

	if (empty($username) || empty($email) || empty($password) || empty($confirmPassword) ) {
		header("Location: ../index.php?error=emptyfields&username=".$username."&mail=".$email);
		exit(); // If there was a mistake here nothing will execute following this code. 
	}else if(!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)){
		header("Location: ../index.php?error=invalidmailusername");
		exit();
	}else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		header("Location: ../index.php?error=invalidmail&username=".$username);
		exit();
	}else if(!preg_match("/^[a-zA-Z0-9]*$/", $username)){
		header("Location: ../index.php?error=invalidusername&mail=".$email);
		exit();
	}else if($password !== $confirmPassword){
		header("Location: ../index.php?error=passwordcheck&username=".$username."&mail=".$email);
		exit();
	}else{
		$sql = "SELECT uidUsers FROM users WHERE uidUsers=?";
		$stmt = mysqli_stmt_init($conn);

		if (!mysqli_stmt_prepare($stmt, $sql)) {
			header("Location: ../index.php?error=sqlerror");
			exit();
		}else{
			mysqli_stmt_bind_param($stmt, "s", $username); // The s is for string because the UN is a string
			mysqli_stmt_execute($stmt); // Executes the information within the DB
			mysqli_stmt_store_result($stmt); // Takes the variable that the user entered and stores it in the $stmt variable
			$resultCheck = mysqli_stmt_num_rows($stmt); // Check how many times the name came up in the DB for that specific UN

			if ($resultCheck > 0) {
				header("Location: ../index.php?error=usertaken&mail=".$email); // If that UN is taken we throw an error
				exit();
			}else{
				$sql = "INSERT INTO users (uidUsers, emailUsers, pwdUsers) VALUES (?, ?, ?)";
				$stmt = mysqli_stmt_init($conn); 

				if (!mysqli_stmt_prepare($stmt, $sql)) {
					header("Location: ../index.php?error=sqlerror");
					exit();
				}else{
					$hashedPwd = password_hash($password, PASSWORD_DEFAULT);

					mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPwd);
					mysqli_stmt_execute($stmt);
					header("Location: ../index.php?signup=success");
					exit();
				}
			}
		}
	}

	mysqli_stmt_close($stmt);
	mysqli_close($conn); // Closes the DB (not necessary in mysqli but it saves resources)

}else{

	header("Location: ../index.php");
	exit();
	
}