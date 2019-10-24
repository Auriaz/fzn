import '../css/themes/dashboard.scss';
// import $ from 'jquery';
// import './vendors/trumbowyg/Trumbowyg';
import './components/ReferenceList';
import './vendors/TinyMCE';

import 'bootstrap';

import Sidebar from './components/Sidebar';
import StickyHeader from './components/StickyHeader';
import PreventDelete from './components/PreventDelete';
import Edit from './components/Edit';

var sidebar = new Sidebar();
var stickyHeader = new StickyHeader('.dashboard-section__menu-icon');
var stickyHeader = new StickyHeader('.dashboard-section__search');
var stickyHeader = new StickyHeader('.table-sticky');
var preventDelete = new PreventDelete();
var edit = new Edit();
