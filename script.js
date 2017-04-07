$( document ).ready( function() {
	//updates a hidden form field so student ID is included in POST
	$( "#modifyStudent" ).click( function() {
		var studentDisplayID = students[$( "#selectStudent" ).val()][0];
		$( "#modifyMe" ).val( studentDisplayID );
	});
	
	
	//updates a hidden form field so student ID is included in POST
	$( "#deleteStudent" ).click( function() {
		var name = students[$( "#selectStudent" ).val()][3];
		var studentDisplayID = students[$( "#selectStudent" ).val()][0];
		var answer = confirm( "Are you sure you wish to delete " + name + "?" );
		if( answer ) {
			$( "#modifyMe" ).val( studentDisplayID );
		}
	});
});


//checks that first password is valid, and that the second matches; otherwise, turns field background red
function validateNewPass() {
	var validated = true;
	
	if( document.getElementById("newPassword1") ) {
		document.getElementById( "newPassword1" ).style.backgroundColor = "white";
		elementValidated = validatePassword( document.getElementById("newPassword1").value );
		if( !elementValidated ) {
			document.getElementById( "error" ).innerHTML += "Error: New password contains a space or disallowed symbol. Allowed symbols include: - ' . _<br/>";
			document.getElementById( "newPassword1" ).style.backgroundColor = "red";
			validated = false;
		}
	}
	
	if( document.getElementById("newPassword2") ) {
		document.getElementById( "newPassword2" ).style.backgroundColor = "white";
		var pass1 = document.getElementById("newPassword1").value;
		var pass2 = document.getElementById("newPassword2").value;
		
		if( pass1 != pass2 ) {
			document.getElementById( "error" ).innerHTML += "Error: New password fields must match.<br/>";
			document.getElementById( "newPassword2" ).style.backgroundColor = "red";
			validated = false;
		}
	}
	
	return validated;
}

