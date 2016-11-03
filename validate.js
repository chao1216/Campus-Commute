/*
Chao Lin
Maggie Koella
Liz Zuniga
*/

function validateFirstname(field){
	return (field == "") ? "First Name was not entered. \n" : ""
}

function validateLastname(field){
	return (field == "") ? "Last Name was not entered. \n" : ""
}

function validatePhone(field){
	if(field == ""){
		return "Phone number was not entered. \n"
	}else if(!/^\d{3}-\d{3}-\d{4}$/.test(field)){
		return "Incorrect phone number format --> 000-000-0000. \n"
	}
	return ""
}

function validateEmail(field){
	if(field==""){
		return "No Email was entered. \n"
	}else if(!((field.indexOf(".")>0) &&
				(field.indexOf("@")>0)) ||
				/[^a-zA-Z0-9.@_-]/.test(field)){
	    return "The Email address is invalid"
	}
	return ""
}

function validateCity(field){
	if(field==""){
		return "City was not entered. \n"
	}else if(/\d/.test(field)){
		return "City contain invalid value. \n"
	}
	return ""
}

function validateState(field){
	if(field==""){
		return "State was not entered. \n"
	}else if(/\d/.test(field)){
		return "State contain invalid value. \n"
	}
	return ""
}

function validateCampus(field){
	if(field==""){
		return "Campus was not entered. \n"
	}
	return ""
}

function validateDeparture(field){
	if(field==""){
		return "Departure date not selected. \n"
	}else if(/[^\d{4}-\d{2}-\d{2}]/.test(field)){
		return "Incorrect departure date format ---> yyyy-mm-dd. \n"
	}
	return ""
}

function validateGasQ(field){
	if(field==""){
	   return "Question not answered. \n"
    }
    return ""
}

function validateDriverQ(field){
	if(field==""){
		return "Question not answered. \n"
	}
	return ""
}

function validateCarSickQ(field){
	if(field==""){
		return "Question not answered. \n"
	}
	return ""
}

function validateSmokeQ(field){
	if(field==""){
		return "Question not answered. \n"
	}
	return ""
}

function validatePetQ(field){
	if(field==""){
		return "Question not answered. \n"
	}return ""
}
