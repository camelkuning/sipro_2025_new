window.addEventListener('DOMContentLoaded', event => {
    // Simple-DataTables
    // https://github.com/fiduswriter/Simple-DataTables/wiki

    const datatablesSimple = document.getElementById('datatablesSimple');
    if (datatablesSimple) {
        const dataTable = new simpleDatatables.DataTable(datatablesSimple, {
            perPage: 10,
            perPageSelect: [5, 10, 20, 50],
            searchable: true,
            fixedHeight: true,
            sortable: true,
            labels: {
                placeholder: "Search",
                noRows: "No records found",
                info: false
            },
            columns: [
                { select: 0, sortable: false }, // ID
                { select: 1, sortable: false }, // Tanggal
                { select: 2, sortable: false }, // Tipe
                { select: 3, sortable: false }, // Jumlah
                { select: 4, sortable: false },  // Keterangan
                { select: 5, sortable: false },  //  saldo awal
                { select: 6, sortable: false },  //  saldo akhir
                // { select: 7, sortable: false }, //  aksi
                // { select: 8, sortable: false },  //  aksi
                // { select: 9, sortable: false },  //  aksi
                // { select: 10, sortable: false },  //  aksi
                // { select: 11, sortable: false },  //  aksi
                // { select: 12, sortable: false },  //  aksi
                // { select: 13, sortable: false },  //  aksi
                // { select: 14, sortable: false },  //  aksi
                // { select: 15, sortable: false },  //  aksi
                // { select: 16, sortable: false },  //  aksi
                // { select: 17, sortable: false },  //  aksi
                // { select: 18, sortable: false },  //  aksi

            ]
        });
    
        // Filter berdasarkan tipe (debit/kredit)
        const filterTipe = document.getElementById('filter-tipe');
        
        filterTipe.addEventListener('change', function() {
            const selectedTipe = filterTipe.value;
            if (selectedTipe) {
                dataTable.search(selectedTipe);  // Filter berdasarkan 'debit' atau 'kredit'
            } else {
                dataTable.search('');  // Reset filter jika tidak ada pilihan
            }
        });
    }
});
