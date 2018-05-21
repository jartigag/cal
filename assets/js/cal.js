/*
DOCUMENTACIÓN: https://fullcalendar.io/docs
*/

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
			url: './events_fullcalendar/json_events.php',
			color: 'light_blue',
			textColor: 'black'
		}
	});
});
