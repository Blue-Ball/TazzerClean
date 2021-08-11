$(document).ready(function() {
	init();
	
	$("form#edit_faq").on("submit", function(event) {
		var form = $(this).get(0);
		if (!form.checkValidity()) {
			event.preventDefault();
			event.stopPropagation();
		}
		form.classList.add('was-validated');
	});

});

function init() {
	$("select[name='category'] option[value='"+datalist['category']+"']").attr("selected", "selected");
}