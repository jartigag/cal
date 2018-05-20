var container = document.getElementById('chart-area');
var data = {
    categories: ['Browser'],
    series: [
        {
            name: 'Halterofilia',
            data: 46.02
        },
        {
            name: 'Endurance',
            data: 20.47
        },
        {
            name: 'Senderismo',
            data: 17.71
        },
        {
            name: 'Gimnasticos',
            data: 5.45
        },
        {
            name: 'Padel',
            data: 3.10
        },
        {
            name: 'Etc',
            data: 7.25
        }
    ]
};
var options = {
    chart: {
        width: 660,
        height: 560,
        title: 'Clases tlmGym mas populares'
    },
    tooltip: {
        suffix: '%'
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

tui.chart.pieChart(container, data, options);*/