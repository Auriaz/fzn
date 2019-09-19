import '../css/themes/article.scss';
import $ from 'jquery';
import 'bootstrap';
// import './components/Editor';

import Sidebar from './components/Sidebar';
import StickyHeader from './components/StickyHeader';
// import PreventDelete from './components/PreventDelete';
// import Edit from './components/Edit';

var sidebar = new Sidebar();
var stickyHeader = new StickyHeader('.content-section__article--control');
// var preventDelete = new PreventDelete();
// var edit = new Edit();