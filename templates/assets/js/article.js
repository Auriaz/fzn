import '../css/themes/article.scss';
import '../css/file_manager.scss';

// import Vue from 'vue';
import $ from 'jquery';
import 'bootstrap';
// import './components/Editor';
// import PhotoManagerApp from './vue/file_manager/PhotoManagerApp.vue';

// Vue.component('file-manager-app', PhotoManagerApp);

// const app_manager = new Vue({
//     el: '#file_manager'
// });

import Sidebar from './components/Sidebar';
import StickyHeader from './components/StickyHeader';
// import PreventDelete from './components/PreventDelete';
// import Edit from './components/Edit';

var sidebar = new Sidebar();
var stickyHeader = new StickyHeader('.content-section__article--control');
// var preventDelete = new PreventDelete();
// var edit = new Edit();
// $(document).ready(function () {
//     $('#article_form_content').summernote();
// });
