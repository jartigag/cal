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
		defaultView: 'listYear',
		nowIndicator: true,
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'agendaWeek,agendaDay,month,listYear'
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
