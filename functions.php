<?php

//adds nav bar to page dependent on account access level
function insertNav() {
	$navBar = '<nav><ul>';
	if( isset($_SESSION) ) {
		if( $_SESSION['accessLevel'] >= 10 ) {
			$navBar .= '<li><a href="add-meet.php">Add Meet</a></li>
			<li><a href="add-school.php">New School</a></li>
			<li><a href="admin-new-account.php">New Coach Account</a></li>';
		}
		if( $_SESSION['accessLevel'] >= 1 ) {
			$navBar .= '<li><a href="register-student.php">Register New Student</a></li>
			<li><a href="modify-student.php">Modify Existing Student</a></li>
			<li><a href="add-to-team.php">Add Student to Meet</a></li>
			<li><a href="account-management.php">Account Management</a></li>
			<li><a href="logout.php">Log Out</a></li>';
		}
	}
	$navBar .= '</ul></nav>';
	echo $navBar;
}


//updates account information for fields with user input (dependent on current password entry)
function updateAccount() {
	$userID = $_SESSION['userID'];
	$result = queryDB( 0, "SELECT passHash, salt, userID FROM `accounts` WHERE userID = '$userID'" );
	$salt = $result[0]['salt'];
	$passHash = hashMe( $_POST['oldPassword'], $salt );
	//print_r( $result );
	if( ($result[0]['passHash'] == $passHash) ) {
		$ID = $result[0]['userID'];
		$somethingChanged = false;
		
		$newData = sanitize( $_POST['firstName'] );
		if( !empty($newData) ) {
			queryDB( 1, "UPDATE accounts SET firstName='$newData' WHERE userID='$ID'" );
			$somethingChanged = true;
		}
		
		$newData = sanitize( $_POST['lastName'] );
		if( !empty($newData) ) {
			queryDB( 1, "UPDATE accounts SET lastName='$newData' WHERE userID='$ID'" );
			$somethingChanged = true;
		}
		
		$newData = sanitize( $_POST['emailAddress'] );
		if( !empty($newData) ) {
			queryDB( 1, "UPDATE accounts SET email='$newData' WHERE userID='$ID'" );
			$somethingChanged = true;
		}
		
		$newData = sanitize( $_POST['username'] );
		if( !empty($newData) ) {
			queryDB( 1, "UPDATE accounts SET username='$newData' WHERE userID='$ID'" );
			$somethingChanged = true;
		}
		
		$newData = sanitize( $_POST['newPassword1'] );
		if( !empty($newData) ) {
			updatePass( $_POST['newPassword1'], $userID );
			$somethingChanged = true;
		}
		
		if( $somethingChanged ) header( "location: account-management.php?f=0" );
		else header( "location: account-management.php" );
	}
	else header( "location: account-management.php?f=50" );
}

