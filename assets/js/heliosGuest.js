$(function() {

	function executeCopy(text) {
			var input = document.createElement('textarea');
			document.body.appendChild(input);
			input.value = text;
			input.focus();
			input.select();
			document.execCommand('Copy');
			input.remove();
	}


	$('font#SzaClipBoard').click(function(event) {
		event.preventDefault();



	});

});