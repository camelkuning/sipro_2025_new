document.addEventListener("DOMContentLoaded", function () {
    fetch('/chart-pembagian')
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            const ctx = document.getElementById('pieChart').getContext('2d');

            // Hitung total supaya bisa tampilkan persentase sesuai data
            const values = Object.values(data);
            const total = values.reduce((a, b) => a + b, 0);

            const pieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: Object.keys(data),
                    datasets: [{
                        data: values,
                        backgroundColor: [
                            '#FF6384',
                            '#36A2EB',
                            '#FFCE56',
                            '#4BC0C0'
                        ],
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                // Tambahkan % di legend
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    if (data.labels.length && data.datasets.length) {
                                        return data.labels.map((label, i) => {
                                            const value = data.datasets[0].data[i];
                                            const percentage = ((value / total) * 100).toFixed(1);
                                            return {
                                                text: label + ' - ' + percentage + '%',
                                                fillStyle: data.datasets[0].backgroundColor[i],
                                                strokeStyle: data.datasets[0].backgroundColor[i],
                                                index: i
                                            };
                                        });
                                    }
                                    return [];
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed;
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return percentage + '%'; // Hanya tampilkan persentase, tanpa nilai angka
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Persentase Pembagian Pengeluaran Keuangan'
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error fetching data:', error));
});
