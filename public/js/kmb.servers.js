$(window).load(function () {
    var parser = document.createElement('a');
    parser.href = document.URL;
    var prefixUriMatch = parser.pathname.match(/(\/env\/[0-9]+)/);
    var prefixUri = prefixUriMatch ? prefixUriMatch[1] : '';

    $.getJSON(prefixUri + '/servers/fact-names', function (data) {
        $.each(data.facts, function (index, val) {
            $('#fact').append('<option>' + val + '</option>');
        });
        $('#fact').trigger("chosen:updated");
    });

    var serversTable = $('#servers').dataTable($.extend({}, DATATABLES_DEFAULT_SETTING, {
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": window.location,
            "data": function (data) {
                data.factName = $('#fact').val();
                data.factValue = $('#value').val();
            },
            "error": function (cause) {
                console.log('Could not get servers list : ' + cause.statusText);
                $('#servers_processing').hide();
            }
        },
        "order": [
            [ 1, "asc" ]
        ],
        "columns": [
            { "orderable": false },
            { "orderSequence": [ "asc", "desc" ] },
            { "orderable": false },
            { "orderable": false },
            { "orderable": false },
            { "orderable": false },
            { "orderable": false },
            { "orderable": false },
            { "orderable": false },
            null
        ]
    }));

    $('#select-all-nodes').click(function () {
        $('#servers input.select-node').prop('checked', $(this).prop('checked'));
    });

    $('#fact-filter-submit').click(function () {
        if ($('#fact').val() == 'default') {
            $('#fact').closest('.form-group')
                .addClass('has-error')
                .delay(2000).queue(function () {
                    $(this).removeClass("has-error").dequeue();
                });
            return false;
        }
        serversTable.fnDraw();
        return false;
    });

    $('#fact-filter-reset').click(function () {
        $('#fact').val('').trigger('chosen:updated');
        $('#value').val('');
        serversTable.fnDraw();
        return false;
    });

    $('#facts').dataTable($.extend({}, DATATABLES_DEFAULT_SETTING, {
        "sAjaxSource": document.URL.replace(/(\/show)?(\?.*)?$/gm, '') + '/facts'
    }));
});
