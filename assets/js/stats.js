$.ajax({
	url: "consultastat.php",

	success: (function(stat) {
		datos = stat;
		console.log(datos);
		var halt = 0;
		var end = 0;
		var send = 0;
		var gim = 0;
		for (var i = 0; i < datos.length; i++) {
			if (datos[i][0] == "Halterofilia") {
				halt = parseInt(datos[i][1]);
			}

			if (datos[i][0] == "Endurance") {
				end = parseInt(datos[i][1]);
			}

			if (datos[i][0] == "Senderismo") {
				send = parseInt(datos[i][1]);
			}

			if (datos[i][0] == "Gimnasia") {
				gim = parseInt(datos[i][1]);
			}

		}

		var container = document.getElementById('chart-area');
		var data = {
			categories: ['Clase:'],
			series: [{
					name: 'Halterofilia',
					data: halt
				},
				{
					name: 'Endurance',
					data: end
				},
				{
					name: 'Senderismo',
					data: send
				},
				{
					name: 'Gimnasia',
					data: gim
				}

			]
		};
		var options = {
			chart: {
				width: 660,
				height: 560,
				title: 'Clases más populares'
			},
			tooltip: {
				suffix: 'inscripciones'
			}
		};
		var theme = {
			series: {
				colors: [
					'#83b14e', '#458a3f', '#295ba0', '#2a4175', '#289399',
					'#289399', '#617178', '#8a9a9a', '#516f7d', '#dddddd'
				]
			}
		};

		// For apply theme

		// tui.chart.registerTheme('myTheme', theme);
		// options.theme = 'myTheme';

		tui.chart.pieChart(container, data, options);

	})

})