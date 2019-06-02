$(document).ready(function () {
    $('.algolia-autocomplete').each( function(){
        var autocompleteUrl = $(this).data('autocomplete-url');
        var name = $(this).data('autocomplete-name');

        $(this).autocomplete({ hint: false }, [
            {
                source: function (query, cb) {
                    $.ajax({
                        url: autocompleteUrl+'?query='+query
                    }).then(function(data) {
                        cb(data.users)
                    })
                },
                displayKey: 'email',
                debounce: 500
            }
        ]);
    });
});