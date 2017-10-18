
document.getElementById("sd_corebundle_userfile_email").focus();

var accountType = document.getElementById('sd_corebundle_userfile_accountType');
var uniqueName = document.getElementById('sd_corebundle_userfile_uniqueName');

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
