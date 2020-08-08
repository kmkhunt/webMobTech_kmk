
function emailInvalid(s)
{
	if(!(s.match(/^[\w]+([_|\.-][\w]{1,})*@[\w]{1,}([_|-|\.-][\w]{1,})*\.([a-z]{2,4})$/i)) ){
		return false;
	}
	else{
		return true;
	}
}

function check_single_file_required(obj)
{
	//alert(obj.type);
	if(obj.type == "file")
	{	
		var file_hdn_id = "old_"+obj.id;
		file_hdn_obj_val = document.getElementById(file_hdn_id);
		
		if(obj.value=="" && (file_hdn_obj_val == null || file_hdn_obj_val == ""))
		{
			return true;
		}else{
			return false;
		}
	}
	else
	{
		return false;
	}
}

function check_radio_or_checkbox_required(obj) 
{
	if(obj.type == "checkbox" || obj.type == "radio")
	{	
		if(obj.type == "checkbox")
		{
			var r = document.getElementsByClassName("chk_"+obj.id);
	
			//alert(r);
		}else if(obj.type == "radio"){
			var r = document.getElementsByName(obj.name);
		}
		
		var c = -1
		for(var i=0; i < r.length; i++){
			if(obj.id == "active"){
			}
		   if(r[i].checked) {
			  c = i; 
		   }
		}
			if (c == -1)
			{
				return true;
			}
			else
			{
				return false;
			}
	}
	else
	{
			return false;
	}
}

function onlyNumber(event)
{
	var key = window.event ? event.keyCode : event.which;
	if (event.keyCode == 8 || event.keyCode == 46
	|| event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 9) {
	return true;
	}
	else if ( key < 48 || key > 57 ) {
	return false;
	}
	else return true;
	return true;
}


function UserValidation(){
	var is_form_have_to_submit = true;

	var obj_first_name = $("#first_name");
	var obj_last_name = $("#last_name");
	var obj_email = $("#email");
	var obj_password = $("#password");
	var obj_password_confirmation = $("#password_confirmation");
	var obj_gender = $("#gender");
	var obj_telephone = $("#telephone");
	var obj_profile_picutre = $("#profile_picutre");
	var obj_update_id = $("#update_id");
	
	var obj_first_name_derror_msg = $("#first_name_derror_msg");
	var obj_last_name_derror_msg = $("#last_name_derror_msg");
	var obj_email_derror_msg = $("#email_derror_msg");
	var obj_password_derror_msg = $("#password_derror_msg");
	var obj_password_confirmation_derror_msg = $("#password_confirmation_derror_msg");
	var obj_gender_derror_msg = $("#gender_derror_msg");
	var obj_telephone_derror_msg = $("#telephone_derror_msg");
	var obj_profile_picutre_derror_msg = $("#profile_picutre_derror_msg");
	
	
	obj_first_name_derror_msg.html("");
	obj_last_name_derror_msg.html("");
	obj_email_derror_msg.html("");
	obj_password_derror_msg.html("");
	obj_password_confirmation_derror_msg.html("");
	obj_gender_derror_msg.html("");
	obj_telephone_derror_msg.html("");
	obj_profile_picutre_derror_msg.html("");
	
	if(obj_first_name.val() == ""){
		obj_first_name_derror_msg.html("Please Enter First Name");
		is_form_have_to_submit = false;
	}

	if(obj_last_name.val() == ""){
		obj_last_name_derror_msg.html("Please Enter Last Name");
		is_form_have_to_submit = false;
	}

	if(obj_email.val() == ""){
		obj_email_derror_msg.html("Please Enter Email");
		is_form_have_to_submit = false;
	}

	if(!emailInvalid(obj_email.val()) && obj_email.val() != ""){
		obj_email_derror_msg.html("Please Enter Valid Email");
		is_form_have_to_submit = false;
	}

	if(obj_update_id.val() == ""){
		if(obj_password.val() == ""){
			obj_password_derror_msg.html("Please Enter Password");
			is_form_have_to_submit = false;
		}

		if(obj_password_confirmation.val() == ""){
			obj_password_confirmation_derror_msg.html("Plese Enter Confirm Password");
			is_form_have_to_submit = false;
		}

		if(obj_password.val() != obj_password_confirmation.val()){
			obj_password_derror_msg.html("Password and Confirm passoword should be matched");
			is_form_have_to_submit = false;
		}

		if(obj_password.val().length < 5 && obj_password.val() != ""){
			obj_password_derror_msg.html("Password must be minimum of 5 characters");
			is_form_have_to_submit = false;
		}
	}

	if(check_radio_or_checkbox_required(obj_gender.get(0))){
		obj_gender_derror_msg.html("Please Select Gender");
		is_form_have_to_submit = false;
	}

	if(obj_telephone.val() == ""){
		obj_telephone_derror_msg.html("Please Enter Telephone");
		is_form_have_to_submit = false;
	}

	if(obj_telephone.val().length < 10 && obj_telephone.val() != ""){
		obj_telephone_derror_msg.html("Telephone must be minimum of 10 characters");
		is_form_have_to_submit = false;
	}

	if(obj_telephone.val().length > 10 && obj_telephone.val() != ""){
		obj_telephone_derror_msg.html("Telephone must be maximum of 10 characters");
		is_form_have_to_submit = false;
	}

	if(check_single_file_required(obj_profile_picutre.get(0))){
		obj_profile_picutre_derror_msg.html("Please Select Profile Picutre");
		is_form_have_to_submit = false;
	}

	return is_form_have_to_submit;
}
