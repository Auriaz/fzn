import tinymce from "tinymce";
import "tinymce/themes/modern/theme";
import "tinymce/themes/mobile/theme";

import "tinymce/plugins/image"
import "tinymce/plugins/imagetools"
import "tinymce/plugins/advlist"
import "tinymce/plugins/autolink"
import "tinymce/plugins/lists"
import "tinymce/plugins/link"
import "tinymce/plugins/charmap"
import "tinymce/plugins/print"
import "tinymce/plugins/anchor"
import "tinymce/plugins/searchreplace"
import "tinymce/plugins/visualblocks"
import "tinymce/plugins/code"
import "tinymce/plugins/fullscreen"
import "tinymce/plugins/fullpage"
import "tinymce/plugins/insertdatetime"
import "tinymce/plugins/media"
import "tinymce/plugins/table"
import "tinymce/plugins/contextmenu"
import "tinymce/plugins/paste"
import "tinymce/plugins/wordcount"
import "tinymce/plugins/textcolor"
import "tinymce/plugins/textpattern"
import "tinymce/plugins/colorpicker"
import "tinymce/plugins/pagebreak"
import "tinymce/plugins/help"




import axios from "axios";
import $ from 'jquery';

require("../../../../node_modules/tinymce/skins/lightgray/content.min.css");
require("../../../../node_modules/tinymce/skins/lightgray/skin.min.css");



$(document).ready(function () {
    const form = $('#tinymce_editor');

    if(form[0]) {
        var editor = new Editor($('#tinymce_editor'));
    }
});

class Editor {
    constructor(el) {
        this.name = el.attr('data-name');
        this.id = el.attr('data-post-id');

        this.referenceList = $('.reference-list');

        this.uploadUrl = `/admin/${this.name}/${this.id}/references`;

        this.tinymce = tinymce.init({
            selector: '.editor',
            plugins: [
                "advlist autolink lists link image charmap print preview anchor textcolor",
                "searchreplace visualblocks code fullscreen fullpage colorpicker pagebreak",
                "insertdatetime media table contextmenu paste help imagetools wordcount textpattern",
            ],
            toolbar: '| insertfile undo redo | styleselect | bold italic strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | insert | bullist numlist outdent indent | removeformat | help fullscreen',

            automatic_uploads: true,
            images_upload_url: this.uploadUrl,
            file_picker_types: 'image',
            file_picker_callback: function (cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');


                input.onchange = function () {
                    var file = this.files[0];
                    // console.log(this.files[0]);
                    var reader = new FileReader();
                    reader.onload = function () {
                        var name = file.name.split(".").shift();
                        var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];
                        var blobInfo = blobCache.create(name, file, base64);
                        blobCache.add(blobInfo);

                        cb(blobInfo.blobUri(), { title: name });
                    };
                    reader.readAsDataURL(file);
                };

                input.click();
            },
        });
    }
}

