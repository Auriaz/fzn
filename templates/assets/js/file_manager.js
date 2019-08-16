import Vue from 'vue';
import FileManagerApp from './vue/file_manager/FileManagerApp';
import 'bootstrap';

Vue.component('file-manager-app', FileManagerApp);

const app = new Vue({
    el: '#file_manager'
});