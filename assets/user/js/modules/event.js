$(function() {
    $('#btn_min').on('click', function(e) {
        let quantity = $('#quantity').val()
        quantity--
        if (quantity <= 0) {
            quantity = 0
            $(this).attr('disabled', true)
            $('#btn_checkout').attr('disabled', true)
        } else {
            $(this).removeAttr('disabled')
            $('#btn_checkout').removeAttr('disabled')
        }
        $('#quantity').val(quantity)
    })
    $('#btn_plus').on('click', function(e) {
        let quantity = $('#quantity').val()
        quantity++
        $('#quantity').val(quantity)
        $('#btn_min').removeAttr('disabled')
        $('#btn_checkout').removeAttr('disabled')
    })
})

function changeQty(e) {
    let value = $(e).val()
    if (value == '') {
        $(e).val(0)
        $('#btn_min').attr('disabled', true)
    } else {
        $(e).val(Number(value))
    }
    if (value == 0) {
        $('#btn_min').attr('disabled', true)
        $('#btn_checkout').attr('disabled', true)
    } else {
        $('#btn_min').removeAttr('disabled')
        $('#btn_checkout').removeAttr('disabled')
    }
}

function checkout() {
    const quantity = $('#quantity').val()
    const selectTicket = $('input[name=select_ticket]:checked').val()
    if (selectTicket == undefined) {
        Swal.fire({
            title: 'Terjadi suatu kesalahan!',
            type: 'error',
            text: 'Anda harus memilih tiket terlebih dahulu!'
        })
    } else if (quantity == 0) {
        Swal.fire({
            title: 'Terjadi suatu kesalahan!',
            type: 'error',
            text: 'Jumlah tiket tidak boleh kosong!'
        })
    } else {
        $.ajax({
            url: site_url + 'event/check_stock',
            method: 'POST',
            data: {
                id: selectTicket,
                quantity: quantity
            },
            success: function(data) {
                data = JSON.parse(data)
                if (data.status == 'success') {
                    $('#selectPaymentModal').modal('show')
                    $('#harga span').html(data.harga)
                    $('#jumlah_tiket span').html(quantity)
                    $('#pajak span').html(data.pajak)
                    $('#total_bayar span').html(data.total_bayar)
                    $('#subtotal span').html(data.subtotal)
                } else {
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

function submitPayment() {
    const quantity = $('#quantity').val()
    const selectTicket = $('input[name=select_ticket]:checked').val()
    const selectPayment = $('select[name=select_payment]').children('option:selected').val()
    if (selectPayment == '') {
        Swal.fire({
            title: 'Terjadi suatu kesalahan!',
            type: 'error',
            text: 'Pilih metode pembayaran terlebih dahulu!'
        })
    } else {
        $.ajax({
            url: site_url + 'event/checkout',
            method: 'POST',
            data: {
                tiket: selectTicket,
                pembayaran: selectPayment,
                quantity: quantity
            },
            success: function(data) {
                data = JSON.parse(data)
                if (data.status == 'success') {
                    window.location.href = site_url + 'order/detail?id=' + data.no_pesanan
                } else {
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