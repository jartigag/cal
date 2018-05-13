/*TODO:
estudiar documentación!! https://fullcalendar.io/docs

EJEMPLOS:
- click en evento:
  https://fullcalendar.io/docs/eventClick-demo
  (https://fullcalendar.io/docs/eventClick)

- eventos editables mediante arrastre:
  https://fullcalendar.io/docs/event-dragging-resizing-demo
  (https://fullcalendar.io/docs/editable)

*/

$(document).ready(function() {

	$('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,basicWeek,basicDay'
		},
		/*editable: true,
		eventDrop: function(event, delta) {
            alert(event.title + ' was moved ' + delta + ' days\n' +
                '(should probably update your database)');
        },*/
		events: {
			url: './json_events.php',
			color: 'yellow',
			textColor: 'black'
		}
	})
});
