/*TODO:
estudiar documentación!! https://fullcalendar.io/docs

EJEMPLOS:
[x] click en evento:
	https://fullcalendar.io/docs/eventClick-demo
	(https://fullcalendar.io/docs/eventClick)

[ ] añadir eventos externos:
	https://fullcalendar.io/releases/fullcalendar/3.9.0/demos/external-dragging.html
	(https://fullcalendar.io/docs/editable)

*/

$(document).ready(function() {

	/* initialize the external events
		-----------------------------------------------------------------*/

	$('#external-events .fc-event').each(function() {

	  // store data so the calendar knows to render an event upon drop
	  $(this).data('event', {
		title: $.trim($(this).text()), // use the element's text as the event title
		stick: true // maintain when user navigates (see docs on the renderEvent method)
	  });

	  // make the event draggable using jQuery UI
	  $(this).draggable({
		zIndex: 999,
		revert: true,      // will cause the event to go back to its
		revertDuration: 0  //  original position after the drag
	  });

	});    

	$('#calendar').fullCalendar({
		aspectRatio: 1.6,
		defaultView: 'agendaWeek',
		nowIndicator: true,
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'agendaWeek,agendaDay,month,listYear'
		},
		events: {
			url: './json_events.php',
			color: 'yellow',
			textColor: 'black'
		},
		editable: true,
		droppable: true,
		eventDrop: function(event, delta, revertFunc) {

			//var date = event.start.getMonth()+1 + "/" + event.start.getDate() + "/" + event.start.getFullYear();
			//alert(date);
			//TODO: actualizar evento
			alert(event.title + " se va a mover a " + event.start.format());
			if (!confirm("Confirma o cancela este cambio:")) {
			  revertFunc();
			}
			$.ajax({
				url: './post_event.php',
				data: ({
					className: event.className,
					delta: event.dayDelta,
					newDate: event.date,
					newTitle: event.title
				}),
				type: "POST",
				success: function (data) {
					alert('se ha llamado a success()!\nTODO: programar post_event.php');
					//$('#calendar').empty();
					//loadCalendar(); //TODO: cargar calendario
				},
				error: function (xhr, status, error) {
					alert("fail");
				}
			});
		}
	})
});
