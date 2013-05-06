google.setOnLoadCallback(function() {

	$('#username').bind('blur', function (e) {
		
		$.getJSON('/ws/username',
		  {username: $('#username').val()},
		  function(data) {
			if (data == "TRUE") {
  		      $("#available").text("This username is available!");
			} else {
			  $("#available").text("This username is not available!");
			}
		  }
		);
		
	});

});
