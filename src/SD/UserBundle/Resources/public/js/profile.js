
document.getElementById("fos_user_profile_form_lastName").focus();

var accountType = document.getElementById('fos_user_profile_form_accountType');
var uniqueName = document.getElementById('fos_user_profile_form_uniqueName');

var accountTypeValue = accountType.options[accountType.selectedIndex].value;

if (accountTypeValue == "INDIVIDUAL") {
	uniqueName.disabled = true;
} else {
	uniqueName.disabled = false;
}

accountType.addEventListener('change', function() {
	var accountTypeValue = accountType.options[accountType.selectedIndex].value;
	// alert(accountType.value);
	// alert(uniqueName.value);
	if (accountTypeValue == "INDIVIDUAL") {
		uniqueName.value = "";
		uniqueName.disabled = true;
	} else {
		uniqueName.disabled = false;
	}
});
