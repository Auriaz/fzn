import Vue from 'vue';
import PhotoManagerApp from './vue/file_manager/PhotoManagerApp.vue';
import StickyHeader from './components/StickyHeader';
import 'bootstrap';
import '../css/file_manager.scss';

Vue.component('file-manager-app', PhotoManagerApp);

const app_manager = new Vue({
    el: '#file_manager'
})

$(document).ready(function () {
    $('#uploader_show').click(function () {
        $('.photo-manager__uploader').toggle("slow")
    })
})

var sticky_search = new StickyHeader('.photo-manager__search-field');
// var sticky_uploader = new StickyHeader('.photo-manager__uploader');