import Vue from 'vue';
import PhotoManagerApp from './vue/file_manager/PhotoManagerApp.vue';
import PhotoManagerUploader from './vue/file_manager/PhotoManagerUploader';
import PhotoReference from './vue/PhotoReference/PhotoReferenceApp.vue';
import StickyHeader from './components/StickyHeader';



import 'bootstrap';
import '../css/file_manager.scss';

Vue.component('file-manager-app', PhotoManagerApp);
Vue.component('file-uploader-app', PhotoManagerUploader);
Vue.component('photo-reference-app', PhotoReference);


const app_manager = new Vue({
    el: '#file_manager'
});
$(document).ready(function () {
    $('#uploader_show').click(function () {
        $('.photo-manager__uploader').toggle("slow")
    })
})

var sticky_search = new StickyHeader('.photo-manager__search-field');
