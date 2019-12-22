import $ from 'jquery';
import Dropzone from 'dropzone';
import Sortable from 'sortablejs';

import 'dropzone/dist/dropzone.css';


Dropzone.autoDiscover = false;


$(document).ready(function () {
    const $autoComplete = $('.article-form_algolia-autocomplete');
    if (!$autoComplete.is(':disabled')) {
        // Start loading animation
        import('./algoliaAutocomplete').then((autocomplete) => {
            // Stop loading animation
            autocomplete.default($autoComplete, 'users', 'email');
        });
    }
    
    var $locationSelect = $('.article-form-location');
    var $specificLocationTarget = $('.specific-location-target');

    $locationSelect.on('change', function (e) {
        $.ajax({
            url: $locationSelect.data('specific-location-url'),
            data: {
                location: $locationSelect.val()
            },
            success: function (html) {
                if (!html) {
                    $specificLocationTarget.find('select').remove();
                    $specificLocationTarget.addClass('d-none');
                    return;
                }
                // Replace the current field and show
                $specificLocationTarget
                    .html(html)
                    .removeClass('d-none')
            }
        });
    });
});

