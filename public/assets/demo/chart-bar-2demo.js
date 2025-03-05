// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';

// Bar Chart Example
var ctx = document.getElementById("myBarChart2");
var labels = JSON.parse(document.getElementById("chartLabels2").value);
var konveksional = JSON.parse(document.getElementById("chartKonveksional").value);
var inkonveksional = JSON.parse(document.getElementById("chartInkonveksional").value);

var myBarChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [
            {
                label: "Konvensional",
                backgroundColor: "rgba(2,117,216,1)",
                borderColor: "rgba(2,117,216,1)",
                data: konveksional,
            },
            {
                label: "Inkonvensional",
                backgroundColor: "rgb(0, 255, 76)",
                borderColor: "rgb(0, 255, 76)",
                data: inkonveksional,
            }
        ]
    },
    options: {
        tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                    let value = tooltipItem.yLabel;
                    let formattedValue = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
                    return `${data.datasets[tooltipItem.datasetIndex].label}: ${formattedValue}`;
                }
            }
        },
        scales: {
            xAxes: [{
                gridLines: { display: false },
                ticks: { autoSkip: false }
            }],
            yAxes: [{
                ticks: {
                    min: 0,
                    callback: function(value) {
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                    }
                },
                gridLines: { display: true }
            }],
        },
        legend: { display: true }
    }
});

