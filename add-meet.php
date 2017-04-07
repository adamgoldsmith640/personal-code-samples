<?php
	session_start();
	ob_start();
	include "functions.php";
	
	if( !isset($_SESSION["accessLevel"]) || $_SESSION["accessLevel"] < 1 ) {
		header( "location: login.php" );
		exit;
	}
	else if( $_SESSION["accessLevel"] < 10 ) {
	    header( "location: register-student.php" );
	    exit;
	}
	
	if( isset($_POST['exportFiles']) ) {
		$errorMsg = exportToFiles();
	}
?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Azle EasyMeet - Add a Meet</title>
	
	<!--needed for datepicker-->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
	<script src="jquery.min.js"></script>
	<script src="jquery-1.12.4.js"></script>
	<script src="jquery-ui.js"></script>
	
	<link rel="stylesheet" href="style.css">
	<script src="script.js"></script>
	<script>
		$( function() {
			$( "#datepicker" ).datepicker( {
				dateFormat: "mm-dd-yy",
				altFormat: "yy-mm-dd",
				altField: "#altField"
			});
		});
	</script>
</head>



<body>
<div id="wrapper">
	
	<?php
		insertNav();
		
			$errorMsg = "";
		if( isset($_POST["deleteMeet"]) ) {
			$errorMsg = deleteMeet();
		}
		else if( isset($_POST['submit']) ) {
			$errorMsg = addMeet();
		}
		
		//pulldown options for meets
		$_SESSION['meets'] = getMeets();
		$meets = "";
		for( $i=0; $i<countItemsInArray( $_SESSION['meets'] ); $i++ ) {
			$meets .= "<option value='" . $_SESSION['meets'][$i]['meetID'] . "'>" . $_SESSION['meets'][$i]['description'] . "</option>";
		}
	?>
	
	<div>
		<h1>Add Meet</h1>
		<p id="error"></p>
		<p id="success"></p>
		
		<p>Please enter the date, location, and a brief description.</p>
		
		<form onsubmit="return( validateMeet() )" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<table align="center"><tr>
				<td><label>Meet Description</label></td>
				<td><input type="text" name="meetDescription" id="meetDescription" required></td>
			</tr><tr>
				<td><label>Date</label></td>
				<td><input type="text" name="datepicker" id="datepicker" readonly="readonly" required=""></td>
			</tr><tr>
				<td><label>Location</label></td>
				<td><input type="text" id="location" name="location" required> <br/></td>
			</tr><tr colspan=2>
				<td><label>Grade Range</label></td>
			</tr><tr>
				<td>
					<label><input type="radio" name="gradeRange" id="gradeRange" value="5-8" required>5-8 </label><br/>
				</td>
				<td>
					<label><input type="radio" name="gradeRange" id="gradeRange" value="9-12">9-12 </label><br/>
				</td>
			</tr></table>
			<br/>
			<button type="reset">Clear Fields</button>
			<button type="submit" name="submit">Add Meet</button>
			<input type="hidden" name="altField" id="altField">
		</form>
	</div>
	<br/><br/>
	
	
	<div>
		Meet Management
		<form method="post" id="controlPanel" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<select id="selectMeet" name="selectMeet" required>
				<option value="">select a meet</option>
				<?php
					echo $meets;
				?>
			</select>
			<button type="submit" id="exportFiles" name="exportFiles">Export Files</button>
			<button type="submit" name="deleteMeet" id="deleteMeet">Delete Meet</button>
			<input type="hidden" name="deleteMe" id="deleteMe">
		</form>
	</div>
	
</div>
</body>
</html>


<?php
	if( isset($_GET['f']) ) {
		switch( $_GET['f'] ) {
			//SUCCESS		messages to be customized on a per page basis
			case 0:	echo "<script>document.getElementById('success').innerHTML = 'Meet created successfully.';</script>"; break;
			case 1:	echo "<script>document.getElementById('success').innerHTML = 'Meet deleted.';</script>"; break;
			
			//ADD-MEET.PHP
			case 15:	echo "<script>document.getElementById('error').innerHTML = 'Error creating meet.';</script>"; break;
			case 16:	echo "<script>document.getElementById('error').innerHTML = 'Error deleting meet.';</script>"; break;
			
			//GENERAL ERRORS
			case 55:	echo "<script>document.getElementById('error').innerHTML = 'Database query error.';</script>"; break;
			case 56:	echo "<script>document.getElementById('error').innerHTML = 'Input provided is dirty. Please remove any unnecessary punctuation.';</script>"; break;
			default:	echo "<script>document.getElementById('error').innerHTML = 'Unspecified error.';</script>";
		}
	}
	ob_end_flush();