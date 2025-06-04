document.addEventListener("DOMContentLoaded", function () {
    fetch('/chart-data')
        .then(response => response.json())
        .then(data => {
            const namaBulan = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            const bulan = data.map(item => namaBulan[item.bulan - 1]);
            const totalKredit = data.map(item => item.total_kredit);
            const totalDebit = data.map(item => item.total_debit);

            const ctx = document.getElementById('chartKeuangan').getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: bulan,
                    datasets: [
                        {
                            label: 'Pemasukan (Kredit)',
                            data: totalKredit,
                            fill: false,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 3,
                            tension: 0.4,
                            pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                            pointRadius: 5
                        },
                        {
                            label: 'Pengeluaran (Debit)',
                            data: totalDebit,
                            fill: false,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 3,
                            tension: 0.4,
                            pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                            pointRadius: 5
                        }
                    ]
                },
                options: {
                    responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                                tooltip: {
            mode: 'index',
            intersect: false,
            callbacks: {
                label: function(context) {
                    let label = context.dataset.label || '';
                    let value = context.parsed.y || 0;

                    // Format ke Rupiah
                    const formattedValue = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(value);

                    return `${label}: ${formattedValue}`;
                }
            }
        }
    },
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
