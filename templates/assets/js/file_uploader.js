import Vue from 'vue';
import PhotoManagerUploader from './vue/file_manager/PhotoManagerUploader';
import 'bootstrap';

// import '../css/file_manager.scss';

Vue.component('file-uploader-app', PhotoManagerUploader);

const app_uploader = new Vue({
    el: '#file_uploader'
});

