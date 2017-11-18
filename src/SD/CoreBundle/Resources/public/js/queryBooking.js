var periodType = document.getElementById('sd_corebundle_queryBooking_periodType');
var beginningDate = document.getElementById('sd_corebundle_queryBooking_beginningDate');
var endDate = document.getElementById('sd_corebundle_queryBooking_endDate');

periodType.addEventListener('change', function() {
	var periodTypeValue = periodType.options[periodType.selectedIndex].value;
	if (periodTypeValue == "BETWEEN" || periodTypeValue == "AFTER") {
		beginningDate.disabled = false;
	} else {
		beginningDate.value = "";
		beginningDate.disabled = true;
	}

	if (periodTypeValue == "BETWEEN" || periodTypeValue == "BEFORE") {
		endDate.disabled = false;
	} else {
		endDate.value = "";
		endDate.disabled = true;
	}
});
