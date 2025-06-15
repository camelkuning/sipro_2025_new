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
                            label: 'Pemasukan (Total Kredit/bulan)',
                            data: totalKredit,
                            fill: false,
                            backgroundColor: '#1E90FF',
                            borderColor: '#1E90FF',
                            borderWidth: 3,
                            tension: 0.4,
                            pointBackgroundColor: '#1E90FF',
                            pointRadius: 5
                        },
                        
                        {
                            label: 'Pengeluaran (Total Debit/bulan)',
                            data: totalDebit,
                            fill: false,
                            backgroundColor: '#FFA500',
                            borderColor: '#FFA500',
                            borderWidth: 3,
                            tension: 0.4,
                            pointBackgroundColor: '#FFA500',
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
