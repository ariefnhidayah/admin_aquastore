var datatable_data = {};
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
        autoWidth: false,
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

    $('#province').on('change', function() {
        const value = $(this).val()
        $.ajax({
            url: site_url + 'seller/get_city',
            method: 'post',
            type: 'json',
            data: {
                province: value
            },
            success: function(data) {
                data = JSON.parse(data)
                data = data.data
                let html = '<option value="">Pilih Kota / Kabupaten</option>';
                for (const item of data) {
                    html += '<option value="'+item.id+'">'+item.type+' '+item.name+'</option>'
                }

                $('#city').html(html)
                $('#district').html('<option value="">Pilih Kecamatan</option>')
            }
        })
    })

    $('#city').on('change', function() {
        const value = $(this).val()
        $.ajax({
            url: site_url + 'seller/get_district',
            method: 'post',
            type: 'json',
            data: {
                city: value
            },
            success: function(data) {
                data = JSON.parse(data)
                data = data.data
                let html = '<option value="">Pilih Kecamatan</option>';
                for (const item of data) {
                    html += '<option value="'+item.id+'">'+item.name+'</option>'
                }

                $('#district').html(html)
            }
        })
    })
})