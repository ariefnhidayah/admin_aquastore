var datatable_data = {}
$(function () {
    const table = $('#table').DataTable({
        stateSave: ($('#table').data('state-save')) ? $('#table').data('state-save') : true,
        "processing": true,
        "serverSide": true,
        "paging": true,
        "ordering": true,
        "info": false,
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
        order: $('th.default-sort').length ? [[$('th.default-sort').index(), $('th.default-sort').attr('data-sort')]] : false,
        dom: '<"datatable-header"fBl><"datatable-scroll"t><"datatable-footer"ip>',
    })

    $('#delete').on('click', function () {
        const noPesanan = $(this).data('no_pesanan')
        Swal.fire({
            title: 'Yakin dihapus?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Iya',
            cancelButtonText: 'Tidak!'
        }).then(result => {
            if (result.value) {
                $.ajax({
                    url: site_url + '/order/delete',
                    data: {
                        no_pesanan: noPesanan
                    },
                    method: 'POST',
                    success: function (data) {
                        data = JSON.parse(data)
                        if (data.status == 'success') {
                            Swal.fire({
                                title: 'Berhasil',
                                text: data.message,
                                type: 'success',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                window.location.href = site_url + '/order/'
                            })
                        } else {
                            Swal.fire({
                                title: 'Terjadi suatu kesalahan',
                                text: data.message,
                                type: 'error',
                                confirmButtonText: 'Ok'
                            })
                        }
                    }
                })
            }
        })
    })

    $('#accept_order').on('click', function () {
        const noPesanan = $(this).data('no_pesanan')
        const idUser = $(this).data('id_user')
        Swal.fire({
            title: 'Yakin diterima?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Iya',
            cancelButtonText: 'Tidak!'
        }).then((result) => {
            if (result.value) {
                $('#accept_order').html('Loading...')
                $('#accept_order').attr('disabled', true)
                $.ajax({
                    url: site_url + '/order/accepted',
                    data: {
                        no_pesanan: noPesanan,
                        id_user: idUser
                    },
                    method: 'POST',
                    success: function (data) {
                        data = JSON.parse(data)
                        if (data.status == 'success') {
                            Swal.fire({
                                title: 'Berhasil',
                                text: data.message,
                                type: 'success',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                window.location.href = site_url + '/order/'
                            })
                        } else {
                            $('#accept_order').html('Terima')
                            $('#accept_order').removeAttr('disabled')
                        }
                    }
                })
            }
        })
    })

    $('#reject_order').on('click', function () {
        const noPesanan = $(this).data('no_pesanan')
        const idUser = $(this).data('id_user')
        // Swal.fire({
        //     title: 'Yakin ditolak?',
        //     type: 'warning',
        //     showCancelButton: true,
        //     confirmButtonColor: '#3085d6',
        //     cancelButtonColor: '#d33',
        //     confirmButtonText: 'Iya',
        //     cancelButtonText: 'Tidak!'
        // }).then((result) => {
        //     if (result.value) {
        //         $.ajax({
        //             url: site_url + '/order/rejected',
        //             data: {
        //                 no_pesanan: noPesanan,
        //                 id_user: idUser
        //             },
        //             method: 'POST',
        //             success: function (data) {
        //                 data = JSON.parse(data)
        //                 console.log(data)
        //                 if (data.status == 'success') {
        //                     Swal.fire({
        //                         title: 'Berhasil',
        //                         text: data.message,
        //                         type: 'success',
        //                         confirmButtonText: 'Ok'
        //                     }).then((result) => {
        //                         window.location.href = site_url + '/order/'
        //                     })
        //                 } else {
        //                     Swal.fire(
        //                         'Gagal!',
        //                         data.message,
        //                         'error'
        //                     )
        //                 }
        //             }
        //         })
        //     }
        // })
        Swal.fire({
            title: 'Masukkan alasan penolakan',
            input: 'text',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Iya',
            cancelButtonText: 'Tidak!',
            inputValidator: (value) => {
                if (!value) {
                    return 'Alasan harus diisi!'
                }
            }
        }).then(result => {
            if (result.value) {
                $.ajax({
                    url: site_url + '/order/rejected',
                    data: {
                        no_pesanan: noPesanan,
                        id_user: idUser,
                        keterangan: result.value,
                    },
                    method: 'POST',
                    success: function (data) {
                        data = JSON.parse(data)
                        console.log(data)
                        if (data.status == 'success') {
                            Swal.fire({
                                title: 'Berhasil',
                                text: data.message,
                                type: 'success',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                window.location.href = site_url + '/order/'
                            })
                        } else {
                            Swal.fire(
                                'Gagal!',
                                data.message,
                                'error'
                            )
                        }
                    }
                })
            }
        })
    })
})