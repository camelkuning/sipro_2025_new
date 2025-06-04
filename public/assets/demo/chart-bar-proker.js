document.addEventListener("DOMContentLoaded", function () {
    fetch('/chart-data-proker')
        .then(response => response.json())
        .then(data => {
            const namaProgram = data.map(item => item.nama_program_kerja);
            const anggaran = data.map(item => item.anggaran_digunakan);

            const ctx = document.getElementById('chartProkerAnggaran').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: namaProgram,
                    datasets: [
                        {
                            label: 'Anggaran Digunakan',
                            data: anggaran,
                            backgroundColor: '#27ae60',
                            borderColor: '#1e8449',
                            borderWidth: 2,
                            borderRadius: 8
                        }
                    ]
                },
                options: {
                    indexAxis: 'x', // 👉 ini bikin horizontal bar
                    responsive: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ` + new Intl.NumberFormat('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR',
                                        minimumFractionDigits: 0
                                    }).format(context.parsed.y);
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Grafik Anggaran Digunakan per Program Kerja'
                        }
                    },
                    scales: {
    x: {
        beginAtZero: true,
        title: {
            display: true,
            text: 'Nama Program Kerja',
            font: {
                size: 16, // Ukuran font sumbu X
                weight: 'bold' // Opsional, bisa dihilangkan
            }
        }
    },
    y: {
        title: {
            display: true,
            text: 'Anggaran (Rp)',
            font: {
                size: 16, // Ukuran font sumbu Y
                weight: 'bold' // Opsional, bisa dihilangkan
            }
        }
    }
}

                }
            });
        })
        .catch(error => {
            console.error('Gagal mengambil data chart proker:', error);
        });
});
