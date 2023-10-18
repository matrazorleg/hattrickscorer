//javascript di funzioni utili
function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function validateRegistration()
{
	jQuery("#success_box").css("display","none");
	
	//ricavo i parametri
	var username = jQuery("#register_username").val();
	var email = jQuery("#register_email").val();
	var password = jQuery("#register_password").val();
	var password2 = jQuery("#register_re_password").val();
		
	if(username == "" || email == "" || password == "" || password2 == "") //campi vuoti...
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
			if(password != password2) //password non coincidenti...
			{
				jQuery("#error_box").empty();
				jQuery("#error_box").append("Different passwords");
				jQuery("#error_box").css("display","block");
				return false;
			}
			else
			{
				if(password.length < 8) //lunghezza della password non sufficiente...
				{
					jQuery("#error_box").empty();
					jQuery("#error_box").append("Password minimum length is 8 characters");
					jQuery("#error_box").css("display","block");
					return false;
				}
				else //verifico il captcha
				{
					jQuery("#error_box").empty();
					jQuery("#error_box").css("display","none");
					return true;
				}
			}
		}
	}
	
}