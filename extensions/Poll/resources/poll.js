$("#btn001").click(
	function() {
		var feedback = $("#inp001").val();
		var clear = $("#chk001").is(':checked');
		$.get(
			mw.util.wikiScript(),
			{
				action: 'ajax',
				rs: 'Poll::submitVote',
				rsargs: [feedback, clear]
			}
		).done(
			function(data) {
				data=JSON.parse(data);
				$("#dat001").html(data.time);
				$("#myTBODY").prepend("<tr><td>"+data.feedback+"</td><td>"+data.time+"</td></tr>");
				//$("#myTBODY").prepend("<tr><td>Booyeah</td><td>TIME</td></tr>");
				
				console.log(data);
			}
			
		);
	}
);


