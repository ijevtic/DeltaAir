$(document).on('click', '.number-spinner button', function () {    
	var btn = $(this),
		oldValue = btn.closest('.number-spinner').find('input').val().trim(),
		newVal = 0;
	
	if (btn.attr('data-dir') == 'up') {
        if(oldValue == '') newVal = 1;
		else newVal = parseInt(oldValue) + 1;
	} else {
		if (oldValue > 0) {
            if(oldValue == '') newVal = 0;
			else newVal = parseInt(oldValue) - 1;
		} else {
			newVal = 0;
		}
	}
	btn.closest('.number-spinner').find('input').val(newVal);
});