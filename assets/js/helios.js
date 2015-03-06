$(function() {


	if($('#dataTable td.Norm').length > 0){
		$('#dataTable thead tr').append('<th>combine</th>');
		$('#dataTable tbody tr').append('<td></td>')
	
		$.each($('#dataTable td.Norm'), function( index, element ) {
			Norm = $(element).text()
			if( Norm != ''){
				RowItems = $(element).parent().find('td') ;
				Count = $(RowItems).first().text();
				Icon = ( Count != '' ? 'glyphicons-inbox-out' : 'glyphicons-inbox-in' );
				Action = ( Count != '' ? 'DEL' : 'ADD' )
				Button = ( Count != '' ? 'btn-warning' : 'btn-success' )
				$(RowItems).last().html('<a href="#" class="btn '+Button+'" norm="'+Norm+'" action="'+Action+'" ><i style="color:black;" class="glyphicons '+Icon+'"></i></a>')
			}
		});
		
		$('#dataTable tbody tr [href=#]').click(function(event) {
			event.preventDefault();
			
			var PostData = {
				Action : $(this).attr( "action" ) ,
				Norm   : $(this).attr('norm') ,
			} ;
			
			$.ajax({
				url: 'http://wwsv05/HELiOS/XmlDuplicates.php',
				type: 'POST',
				data: PostData,
				dataType: 'text',
				timeout:5000,
				success: function (data) {
					if(data === "OK"){
						location.reload();
					}
				},
				error: function(xhr, textStatus, errorThrown){
					console.log(xhr.responseText);
				}
			});

		});
		
		
	}

	
	/*$('[href=#]').click(function(event) {
		event.preventDefault();
		var element = $(this);
		var PostData = {
			Action   : 'BomOneToMany' ,
			Data : $(this).attr('data') ,
		} ;
			
    $.ajax({
      url: 'AjaxPost.php',
      type: 'POST',
			data: PostData,
      dataType: 'text',
      timeout:5000,
      success: function (data) {
        if(data === "OK"){
					$(element).parent().parent().hide(800, function() {
						// Use arguments.callee so we don't need a named function
						$(this).remove();
					})
				}
      },
      error: function(xhr, textStatus, errorThrown){
        console.log(xhr.responseText);
      }
    }); 
		
		
	});
	*/

});