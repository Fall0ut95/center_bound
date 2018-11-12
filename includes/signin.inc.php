<?php
if (isset($_POST['signin-submit'])) {

	require 'dbh.inc.php'; // This is the file that connects us to the DB

	$uid = $_POST['username'];
	$pwd = $_POST['password'];

	if (empty($uid) || empty($pwd)) {
		header("Location: ../index.php?error=emptyfields"); // If the user leaves a field blank they are returned to the home page
		exit();
	}else{
		$sql = "SELECT * FROM users WHERE uidUsers=?"; // Gets the UN of the user
		$stmt= mysqli_stmt_init($conn);

		if (!mysqli_stmt_prepare($stmt, $sql)) {
			header("Location: ../index.php?error=sqlerror");
			exit();
		}else{
			mysqli_stmt_bind_param($stmt, "ss", $uid, $uid); // "ss" is for two string inputs and makes sure UN is same
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt); // The info we got from the DB is stored in result

			if ($row = mysqli_fetch_assoc($result)) {
				$pwdCheck = password_verify($pwd, $row['pwdUsers']); // Boolean that checks if the password user entered is same as in DB

				if ($pwdCheck == false) {
					header("Location: ../index.php?error=wrongpwd"); // if passwords dont match
				}else if($pwdCheck == true){
					session_start();
					$_SESSION['userId'] = $row['idUsers']; // Stores the key of the user from the DB
					$_SESSION['userUid'] = $row['uidUsers']; // Stores the UN of the user from the DB

					header("Location: ../index.php?login=success");
					exit();
				}else{
					header("Location: ../index.php?error=wrongpwd"); // if somehow it is neither true or false 
				}
			}else{
				header("Location: ../index.php?error=nouser");
				exit();
			}
		}
	}

}else{

	header("Location: ../index.php");
	exit();

}