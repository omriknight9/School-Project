
loginMailValidality();
loginPasswordValidality();

function loginMailValidality() {
	var loginEmail = document.querySelector("#login_email");
	
	if (login_email.value.length === 0) {
		loginEmail.setCustomValidity("You MUST enter a valid email");
	} else {
		loginEmail.setCustomValidity("");
	}
}

function loginPasswordValidality() {
	var loginPassword = document.querySelector("#login_password");
	
	if (login_password.value.length === 0) {
		loginPassword.setCustomValidity("You MUST enter a valid password");
	} else {
		loginPassword.setCustomValidity("");
	}
}