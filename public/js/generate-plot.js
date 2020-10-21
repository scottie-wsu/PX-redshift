var ctx = document.getElementById('myChart');
Chart.defaults.global.legend.display = false;

var gType = null;
if (graphType == "bar-chart")
    gType = "bar";
else if (graphType == "line-chart")
    gType = "line";
else
    gType = "pie"

    if (gType != "pie") {
    var myChart = new Chart(ctx, {
        type: gType,
        data: {
            labels: graphLabels,
            datasets: [{
                label: graphYLabel,
                data: graphData,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: graphYLabel,
                        lineHeight: 1,
                        fontSize: 16,
                        fontStyle: 'oblique',
                        fontFamily: 'Arial'
                    },
                    ticks: {
                        beginAtZero: true
                    }
                }],
                xAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: graphXLabel,
                        fontStyle: 'oblique',
                        lineHeight: 1,
                        fontSize: 16,
                        fontFamily: 'Arial'
                    },
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
            title: {
                display: true,
                text: graphTitle
            }
        }
    });
}
else {
    var myChart = new Chart(ctx, {
        type: gType,
        data: {
            labels: graphLabels,
            datasets: [{
                label: graphYLabel,
                data: graphData,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            title: {
                display: true,
                text: graphTitle
            }
        }
    });
}