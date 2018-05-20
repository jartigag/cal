/*
DOCUMENTACIÓN: https://fullcalendar.io/docs

EJEMPLOS:
[x] click en evento:
	https://fullcalendar.io/docs/eventClick-demo
	(https://fullcalendar.io/docs/eventClick)

[ ] añadir eventos externos:
	https://fullcalendar.io/releases/fullcalendar/3.9.0/demos/external-dragging.html
	(https://fullcalendar.io/docs/event-dragging-resizing)
	(https://fullcalendar.io/docs/eventReceive)

*/

$(document).ready(function() {

	$('#external-events .fc-event').each(function() {

	  // almacena data para renderizar el evento cuando se arrastre
	  $(this).data('event', {
		title: 'mi evento' //TODO: $(this).textContent // event title = element title
	  });

	  // hace el evento draggable con jQuery UI
	  $(this).draggable({
		revert: true,
		revertDuration: 0
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
			url: './events_fullcalendar/json_events.php',
			color: 'yellow',
			textColor: 'black'
		},
		editable: true,
		droppable: true,
		drop: function(date, revertFunc) {
			/*TODO: cómo usar event?
			var start = $.fullCalendar.formatDate(event.start, "YYYY-MM-DD HH:mm:ss");
			var end = $.fullCalendar.formatDate(event.start, "YYYY-MM-DD HH:mm:ss"); //TODO: falla event.end
			if (!confirm('La clase\n"'+event.title+'"\nse va a crear en \n'+start)) {
				revertFunc(); //TODO: reverFunc no está definida
			}
			$.ajax({
				url: './events_fullcalendar/add_event.php',
				data: 'title='+ event.title +'&datetime_start='+ start +'&datetime_end='+ end +'&id='+ event.id,
				type: "POST",
				success: function(data) {
					//TODO: recargar calendario
				},
				error: function() {
					alert("error al crear la clase");
				}
			});*/
		},
		eventDrop: function(event, revertFunc) {
			var start = $.fullCalendar.formatDate(event.start, "YYYY-MM-DD HH:mm:ss");
			var end = $.fullCalendar.formatDate(event.start, "YYYY-MM-DD HH:mm:ss"); //TODO: falla event.end
			if (!confirm('La clase\n"'+event.title+'"\nse va a mover a \n'+start)) {
				revertFunc(); //TODO: reverFunc no está definida
			}
			$.ajax({
				url: './events_fullcalendar/update_event.php',
				data: 'title='+ event.title +'&datetime_start='+ start +'&datetime_end='+ end +'&id='+ event.id,
				type: "POST",
				success: function(data) {
					//TODO: recargar calendario
				},
				error: function() {
					alert("error al actualizar la clase");
				}
			});
		},
		//TODO eventResize:
		eventResize: function(event, revertFunc) {
			var start = $.fullCalendar.formatDate(event.start, "YYYY-MM-DD HH:mm:ss");
			var end = $.fullCalendar.formatDate(event.start, "YYYY-MM-DD HH:mm:ss"); //TODO: falla event.end
			if (!confirm('Se va a cambiar el tiempo que dura la clase\n"'+event.title+'"')) {
				revertFunc(); //TODO: reverFunc no está definida
			}
			$.ajax({
				url: './events_fullcalendar/update_event.php',
				data: 'title='+ event.title+'&datetime_start='+ start +'&datetime_end='+ end +'&id='+ event.id ,
				type: "POST",
				success: function(json) {
					//TODO: recargar calendario
				},
				error: function() {
					alert("error al actualizar la clase");
				}
			});
		}
	});
});
