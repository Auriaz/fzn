import '../css/themes/dashboard.scss';
import $ from 'jquery';
import 'bootstrap';

import Sidebar from './components/Sidebar';
import StickyHeader from './components/StickyHeader';

var sidebar = new Sidebar();
var stickyHeader = new StickyHeader('.dashboard-section__menu-icon');