import trumbowyg from 'trumbowyg/dist/trumbowyg.min';
import 'trumbowyg/dist/plugins/fontsize/trumbowyg.fontsize.min';
// import 'trumbowyg/dist/plugins/base64/trumbowyg.base64.min';
import 'trumbowyg/dist/plugins/colors/trumbowyg.colors.min';
import 'trumbowyg/dist/plugins/emoji/trumbowyg.emoji.min';
import 'trumbowyg/dist/plugins/fontfamily/trumbowyg.fontfamily.min';
// import 'trumbowyg/dist/plugins/insertaudio/trumbowyg.insertaudio.min';
import 'trumbowyg/dist/plugins/lineheight/trumbowyg.lineheight.min';
import 'trumbowyg/dist/plugins/table/trumbowyg.table.min';
// import 'trumbowyg/dist/plugins/template/trumbowyg.template.min';
import 'trumbowyg/dist/plugins/upload/trumbowyg.upload.min';
import './plugins/trumbowyg.myplugin';

const icons = require('trumbowyg/dist/ui/icons.svg');
import $ from 'jquery';
import axios from 'axios';

// const icons = require("./../../images/icon/icons.svg");

$.trumbowyg.svgPath = icons;
$('.editor').trumbowyg({
    tagsToRemove: ['script', 'link'],
    removeformatPasted: true,
    hideButtonTexts: true,
    urlProtocol: true,
    minimalLinks: true,
    btnsDef: {
        // Create a new dropdown
        image: {
            dropdown: ['insertImage', 'upload', 'openModal'],
            ico: 'insertImage'
        },
        list: {
            dropdown: ['unorderedList', 'orderedList'],
            ico: 'unorderedList'
        },
        alignText: {
            dropdown: ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
            ico: 'justifyLeft'
        },

    },
    btns: [
        // ['base64'], // uploads photo
        // ['template'],
        ['viewHTML'],
        ['undo', 'redo'], // Only supported in Blink browsers
        ['fontfamily'],
        ['formatting'],
        ['table'],
        ['strong', 'em', 'del'], 
        ['fontsize'],
        ['lineheight'],
        ['superscript', 'subscript'],
        ['foreColor', 'backColor'],
        ['link'],
        ['image'],
        ['alignText'],
        ['list'],
        ['horizontalRule'],
        ['removeformat'],
        ['emoji'],
        // ['insertaudio'],
        ['fullscreen'], 
        ['myplugin'],

    ],
    plugins: {
        fontsize: {
            sizeList: [
                '12px',
                '14px',
                '16px'
            ]
        },
        templates: [
            {
                name: 'Template 1',
                html: '<p>I am a template!</p>'
            },
            {
                name: 'Template 2',
                html: '<p>I am a different template!</p>'
            }
        ],
        upload: {
            serverPath: '/api/files',
            fileFieldName: 'file',
        }
    }
});

// var img = $('img#an-img');
// // Open a modal box
// var $modal = $('.editor').trumbowyg('openModalInsert', {
//     title: 'A title for modal box',
//     fields: {
//         url: {
//             value: img.attr('src')
//         },
//         alt: {
//             label: 'Alt',
//             name: 'alt',
//             value: img.attr('alt'),
//             type: 'text',
//             attributes: {}
//         },
//         example: {
//             // Missing label is replaced by the key of this object (here 'example')
//             // Missing name is the key
//             // When value is missing, value = ''
//             // When type is missing, 'text' is assumed. You can use all the input field types,
//             //   plus checkbox and radio (select and textarea are not supported)
//             // When attributes is missing, {} is used. Attributes are added as attributes to
//             //   the input element.
//             // For radio and checkbox fields, you will need to use attributes if you want it
//             //   to be checked by default.
//         }
//     },
//     // Callback is called when user confirms
//     callback: function (values) {
//         img.attr('src', values['url']);
//         img.attr('alt', values['alt']);

//         return true; // Return true if you have finished with this modal box
//         // If you do not return anything, you must manage the closing of the modal box yourself
//     }
// });

// // Listen clicks on modal box buttons
// $modal.on('tbwconfirm', function (e) {
//     // Save data
//     $(".editor").trumbowyg('closeModal');
// });
// $modal.on('tbwcancel', function (e) {
//     $('.editor').trumbowyg('closeModal');
// });