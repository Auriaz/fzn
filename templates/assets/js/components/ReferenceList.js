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
    
    const $referenceList = $('.reference-list');
    if ($referenceList[0]) {
        var referenceList = new ReferenceList($('.reference-list'));
        initializeDropzone(referenceList);
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

// todo - use Webpack Encore so ES6 syntax is transpiled to ES5
class ReferenceList {
    constructor($element) {
        this.$element = $element;
        this.sortable = Sortable.create(this.$element[0], {
            handle: '.drag-handle',
            animation: 150,
            onEnd: () => {
                $.ajax({
                    url: this.$element.data('url') + '/reorder',
                    method: 'POST',
                    data: JSON.stringify(this.sortable.toArray())
                });
            }
        });

        this.idArticle = this.$element.data('id');

        this.references = [];

        this.render();

        this.$element.on('click', '.reference-delete', (event) => {
            this.handleReferenceDelete(event);
        });

        this.$element.on('blur', '.reference-edit-filename', (event) => {
            this.handleReferenceEditFilname(event);
        });

        $.ajax({
            url: this.$element.data('url')
        }).then(data => {
            this.references = data;
            this.render();
        })
    }

    addReference(reference) {
        this.references.push(reference);
        this.render();
    }

    handleReferenceDelete(event) {
        const $li = $(event.currentTarget).closest('.list-group-item');
        const id = $li.data('id');
   
        $li.addClass('disabled');

        $.ajax({
            url: `/admin/article/${this.idArticle}/references/${id}`,
            method: 'DELETE'
        }).then(() => {
            this.references = this.references.filter(reference => {
                return reference.id !== id;
            });
            this.render();
        });
    }

    handleReferenceEditFilname(event) {
        const $li = $(event.currentTarget).closest('.list-group-item');
        const id = $li.data('id');
        const reference = this.references.find(reference => {
            return reference.id === id;
        });

        reference.originalFilename = $(event.currentTarget).val();

        $.ajax({
            url: '/admin/article/references/' + id,
            method: 'PUT',
            data: JSON.stringify(reference)
        });
    }

    render() {
        const itemsHtml = this.references.map(reference => {
            return `
                <li class="list-group-item d-flex justify-content-between align-items-center mb-2" data-id="${reference.id}">
                    <span class="drag-handle fas fa-bars mr-3"></span>

                    <span class="d-flex flex-column justify-content-center align-items-center">
                        <input type="text" value="${reference.originalFilename}" class="form-control reference-edit-filename">

                        <img class="img-thumbnail handle-image" src="${reference.url}" alt="${reference.originalFilename}" data-src="${reference.location}">

                        <span class="d-flex justify-content-around align-items-center mb-2">
                            <a href="/admin/article/references/${reference.id}/download" class="btn btn-link btn-sm" title="Pobierz"/><i class="fas fa-download fa-lg"></i></a>
                   
                            <button class="reference-delete btn btn-link btn-sm" title="Usuń"><i class="fas fa-trash fa-lg" data-id="${reference.id}"></i></button>
                        </span>
                    </span>
                </li>
            `
        });
        this.$element.html(itemsHtml.join(''));
    }
}
/**
 * 
 * @param {ReferenceList} referenceList
 */
function initializeDropzone(referenceList) {
    var formElement = document.querySelector('.reference-dropzone');

    if (!formElement) {
        return;
    }

    var dropzone = new Dropzone(formElement, {
        paramName: 'file',
        init: function() {
            this.on('success', function (file, data) {
                referenceList.addReference(data);
            });

            this.on('error', function (file, data) {
                if(data.detail) {
                    this.emit('error', file, data.detail);
                }
            });
        }
    });
}