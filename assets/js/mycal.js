/*
DOCUMENTACIÓN: https://fullcalendar.io/docs

EJEMPLOS:
[x] click en evento:
	https://fullcalendar.io/docs/eventClick-demo
	(https://fullcalendar.io/docs/eventClick)

[ ] modificar eventos:
	https://fullcalendar.io/releases/fullcalendar/3.9.0/demos/external-dragging.html
	(https://fullcalendar.io/docs/event-dragging-resizing)
	(https://fullcalendar.io/docs/eventDrop)

*/

var userId = getUrlVars()["user_id"];

$(document).ready(function() {

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
			url: './events_fullcalendar/json_editable_events.php?user_id='+userId,
			color: 'yellow',
			textColor: 'black'
		},
		editable: true,
		droppable: true,
		eventDrop: function(event, revertFunc) {
			var start = $.fullCalendar.formatDate(event.start, "YYYY-MM-DD HH:mm:ss");
			if (!confirm('La clase\n"'+event.title+'"\nse va a mover a \n'+start)) {
				revertFunc(); //TODOc: reverFunc no está definida
			}
			$.ajax({
				url: './events_fullcalendar/update_event.php',
				data: 'title='+ event.title +'&datetime_start='+ start +'&id='+ event.id,
				type: "POST",
				success: function(data) {
					//TODOc: recargar calendario
				},
				error: function() {
					alert("error al actualizar la clase");
				}
			});
		},
		//TODOc eventResize:
		eventResize: function(event, revertFunc) {
			var start = $.fullCalendar.formatDate(event.start, "YYYY-MM-DD HH:mm:ss");
			var end = $.fullCalendar.formatDate(event.start, "YYYY-MM-DD HH:mm:ss"); //TODOc: falla event.end
			if (!confirm('Se va a cambiar el tiempo que dura la clase\n"'+event.title+'"')) {
				revertFunc(); //TODOc: reverFunc no está definida
			}
			$.ajax({
				url: './events_fullcalendar/update_event.php',
				data: 'title='+ event.title+'&datetime_start='+ start +'&datetime_end='+ end +'&id='+ event.id ,
				type: "POST",
				success: function(json) {
					//TODOc: recargar calendario
				},
				error: function() {
					alert("error al actualizar la clase");
				}
			});
		}
	});
});

function getUrlVars() {
	var vars = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
	vars[key] = value;
	});
	return vars;
}