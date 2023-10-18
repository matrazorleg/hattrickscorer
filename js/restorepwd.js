//javascript di funzioni utili
function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function checkRestore()
{
	jQuery("#success_box").css("display","none");
	
	//ricavo i parametri
	var username = jQuery("#register_username").val();
	var email = jQuery("#register_email").val();
	
	if(username == "" || email == "") //campi vuoti...
	{
		jQuery("#error_box").empty();
		jQuery("#error_box").append("Empty fields");
		jQuery("#error_box").css("display","block");
		return false
	}
	else
	{
		if(!validateEmail(email)) //email non valida...
		{
			jQuery("#error_box").empty();
			jQuery("#error_box").append("Email not valid");
			jQuery("#error_box").css("display","block");
			return false;
		}
		else
		{
			//check tramite servizio di username e password, se ci sono manda email, restituisce ack.
			jQuery("#error_box").empty();
			jQuery("#error_box").css("display","none");
			
			jQuery.get( "services/resetPassword.php?username="+username+"&email="+email, function( output ) {
				console.log(output);
				if(output == "OK")
				{
					jQuery("#success_box").empty();
					jQuery("#success_box").append("Check your email account to view the new password.");
					jQuery("#success_box").css("display","block");
				}
				else
				{
					if(output == "1.0" || output == "3.0")
					{
						jQuery("#error_box").empty();
						jQuery("#error_box").append("Invalid username or email address.");
						jQuery("#error_box").css("display","block");
					}
					if(output == "2.0")
					{
						jQuery("#error_box").empty();
						jQuery("#error_box").append("We are sorry. The system can't restore your password now. Come back later!");
						jQuery("#error_box").css("display","block");
					}
				}
			
			});
			
			
		}
	}
	
}