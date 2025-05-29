document.addEventListener("DOMContentLoaded", function () {
    fetch('/chart-data') // pastikan route ini tersedia
        .then(response => response.json())
        .then(data => {
            const bulan = data.map(item => 'Bulan ' + item.bulan);
            const total = data.map(item => item.total);

            const ctx = document.getElementById('chartKeuangan').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: bulan,
                    datasets: [{
                        label: 'Total Pemasukan per Bulan',
                        data: total,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Gagal ambil data chart:', error);
        });
});
