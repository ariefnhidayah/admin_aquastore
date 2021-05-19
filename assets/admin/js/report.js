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
                d.filter = get_form_data($('#filter-form'))
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

    $('#filter').on('click', function () {
        table.ajax.reload();
    });

    $('#filter-by').on('change', function () {
        var a = $(this).val();
        var now = new Date();
        $('input[name=from]').datepicker('destroy');
        $('input[name=to]').datepicker('destroy');
        $('input[name=from]').attr('readonly', 'readonly');
        $('input[name=to]').attr('readonly', 'readonly');
        if (a == 'today') { //hari ini
            $('input[name=from]').val(formatDate(now));
            $('input[name=to]').val(formatDate(now));
        } else if (a == 'yesterday') { //kemarin
            var date = new Date();
            date.setDate(now.getDate() - 1);
            $('input[name=from]').val(formatDate(date));
            $('input[name=to]').val(formatDate(date));
        } else if (a == 'this month') { //bulan ini
            var date1 = new Date(now.getFullYear(), now.getMonth(), 1);
            var date2 = new Date(now.getFullYear(), now.getMonth() + 1, 0);
            $('input[name=from]').val(formatDate(date1));
            $('input[name=to]').val(formatDate(date2));
        } else if (a == 'last month') { //bulan lalu
            var date1 = new Date(now.getFullYear(), now.getMonth() - 1, 1);
            var date2 = new Date(now.getFullYear(), now.getMonth(), 0);
            $('input[name=from]').val(formatDate(date1));
            $('input[name=to]').val(formatDate(date2));
        } else if (a == 'this year') { //tahun ini
            var date1 = new Date(now.getFullYear(), 0, 1);
            var date2 = new Date(now.getFullYear(), 12, 0);
            $('input[name=from]').val(formatDate(date1));
            $('input[name=to]').val(formatDate(date2));
        } else if (a == 99) {
            $('input[name=from]').datepicker({ dateFormat: 'yy-mm-dd' });
            $('input[name=from]').removeAttr('readonly');
            $('input[name=to]').datepicker({ dateFormat: 'yy-mm-dd' });
            $('input[name=to]').removeAttr('readonly');
        }
    });

    $('#export').on('click', function () {
        form_data = get_form_data($('#filter-form'));
        window.open(site_url + '/report/export?from='+form_data.from+'&to='+form_data.to, '_blank');
    });
})

function formatDate(date) {
    var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

    if (month.length < 2)
        month = '0' + month;
    if (day.length < 2)
        day = '0' + day;

    return [year, month, day].join('-');
}

function get_form_data($form) {
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};

    $.map(unindexed_array, function (n, i) {
        indexed_array[n['name']] = n['value'];
    });

    return indexed_array;
}