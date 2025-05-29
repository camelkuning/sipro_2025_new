document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    if (calendarEl) {
        if (typeof FullCalendar !== 'undefined') {
            
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: typeof eventsData !== 'undefined' ? eventsData : [],

                eventClick: function (info) {
                    console.log('Event diklik:', info.event);
                    var judul = info.event.extendedProps.nama_program_kerja;
                    var mulai = info.event.start.toLocaleDateString();
                    var selesai = info.event.end ? info.event.end.toLocaleDateString() : mulai;

                    // Masukkan ke modal
                    var isi = `
                        <strong>Nama Program Kerja:</strong><br>${judul}<br><br>
                        <strong>Tanggal Mulai:</strong> ${mulai}<br>
                        <strong>Tanggal Selesai:</strong> ${selesai}
                    `;
                    document.getElementById('isiDetailProker').innerHTML = isi;

                    // Tampilkan modal
                    var modal = new bootstrap.Modal(document.getElementById('modalDetailProker'));
                    modal.show();
                },

                eventMouseEnter: function (info) {
                    info.el.style.cursor = 'pointer';
                }
            });

            calendar.render();
        } else {
            console.error('Library FullCalendar tidak dimuat dengan benar');
        }
    } else {
        console.error('Element dengan id "calendar" tidak ditemukan');
    }
});
