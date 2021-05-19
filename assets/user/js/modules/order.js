function upload() {
    const file = $('#bukti_pembayaran').prop('files')[0]
    const noPesanan = $('#no_pesanan').val()
    let formData = new FormData()
    if (file == undefined) {
        Swal.fire({
            title: 'Terjadi suatu kesalahan!',
            type: 'error',
            text: 'Tidak ada gambar!'
        })
    } else {
        $('#submitButton').html('Loading...')
        $('#submitButton').attr('disabled', true)
        formData.append('file', file)
        formData.append('no_pesanan', noPesanan)
        $.ajax({
            url: site_url + 'order/upload_bukti_bayar',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            type: 'POST',
            success: function (data) {
                data = JSON.parse(data)

                if (data.status == 'success') {
                    window.location.reload()
                } else {
                    $('#submitButton').html('Submit')
                    $('#submitButton').removeAttr('disabled')
                    Swal.fire({
                        title: 'Terjadi suatu kesalahan!',
                        type: 'error',
                        text: data.message
                    })
                }
            }
        })
    }
}

function resendConfirm() {
    const file = $('#bukti_keterangan').prop('files')[0]
    const noPesanan = $('#no_pesanan_resend_confirm').val()
    let formData = new FormData()
    if (file == undefined) {
        Swal.fire({
            title: 'Terjadi suatu kesalahan!',
            type: 'error',
            text: 'Tidak ada gambar!'
        })
    } else {
        $('#submitButtonResendConfirm').html('Loading...')
        $('#submitButtonResendConfirm').attr('disabled', true)
        formData.append('file', file)
        formData.append('no_pesanan', noPesanan)
        $.ajax({
            url: site_url + 'order/resend_confirmation',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            type: 'POST',
            success: function (data) {
                data = JSON.parse(data)

                if (data.status == 'success') {
                    window.location.reload()
                } else {
                    $('#submitButtonResendConfirm').html('Submit')
                    $('#submitButtonResendConfirm').removeAttr('disabled')
                    Swal.fire({
                        title: 'Terjadi suatu kesalahan!',
                        type: 'error',
                        text: data.message
                    })
                }
            }
        })
    }
}