$(window).load(function () {
    var parser = document.createElement('a');
    parser.href = document.URL;
    var prefixUriMatch = parser.pathname.match(/(\/env\/[0-9]+)/);
    var prefixUri = prefixUriMatch ? prefixUriMatch[1] : '';

    $.getJSON(prefixUri + '/servers/fact-names', function (data) {
        $.each(data.facts, function (index, val) {
            $('#fact').append('<option>' + val + '</option>');
        });
    });
});
