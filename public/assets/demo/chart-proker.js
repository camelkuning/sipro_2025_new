document.addEventListener("DOMContentLoaded", function () {
    fetch('/chart-data') // pastikan route ini tersedia
        .then(response => response.json())
        .then(data => {
            const bulan = data.map(item => 'Bulan ' + item.bulan);
            const total = data.map(item => item.total);

            const ctx = document.getElementById('chartKeuangan').getContext('2d');

            new Chart(ctx, {
                type: 'line', // UBAH DARI 'bar' KE 'line'
                data: {
                    labels: bulan,
                    datasets: [{
                        label: 'Total Pemasukan per Bulan',
                        data: total,
                        fill: true,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        tension: 0.3, // biar garisnya agak melengkung
                        pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Gagal ambil data chart:', error);
        });
});
