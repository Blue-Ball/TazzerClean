$(document).ready(function() {
	init();
	
	$("form#add_data_form").on("submit", function(event) {
		var form = $(this).get(0);
		if (!form.checkValidity()) {
			event.preventDefault();
			event.stopPropagation();
		}
		form.classList.add('was-validated');
	});

});

function init() {
	
}