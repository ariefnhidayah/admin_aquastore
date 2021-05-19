var datatable_data = {}
$(function() {
    const table = $('#table').DataTable({
        stateSave: ($('#table').data('state-save')) ? $('#table').data('state-save') : true,
        "processing": true,
        "serverSide": true,
        "paging":   true,
        "ordering": true,
        "info":     false,
        "searching": true,
        "destroy": true,
        "responsive": true,
        ajax: {
            url: $('#table').attr('data-url'),
            type: 'post',
            data: function (d) {
                d.additional_data = datatable_data;
                return d;
            }
        },
        autoWidth: false,
        columnDefs: [
            {
                orderable: false,
                targets: 'no-sort'
            },
            {
                className: 'text-center',
                targets: 'text-center'
            },
            {
                className: 'text-right',
                targets: 'text-right'
            }
        ],
        order: $('th.default-sort').length? [[$('th.default-sort').index(), $('th.default-sort').attr('data-sort')]]:false,
        dom: '<"datatable-header"fBl><"datatable-scroll"t><"datatable-footer"ip>',
    });

    table.on('click', '.delete', function(e) {
        const url = $(this).attr('href')
        e.preventDefault()
        Swal.fire({
            title: 'Apakah ingin dihapus?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Iya, Hapus!',
            cancelButtonText: 'Tidak!'
          }).then((result) => {
            if(result.value) {
                $.ajax({
                    url: url,
                    success: function (data) {
                        data = JSON.parse(data);
                        table.ajax.reload();
                        Swal.fire(
                            'Berhasil!',
                            data.message,
                            'success'
                        )
                    }
                });
            }
          })
    })
})