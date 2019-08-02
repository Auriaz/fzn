import $ from 'jquery';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
// import trumbowyg from 'trumbowyg';


// $('#animal_add_form_description').trumbowyg();
ClassicEditor
    .create(document.querySelector('#animal_add_form_description'))
    .then(editor => {
        console.log(editor);
    })
    .catch(error => {
        console.error(error);
    });