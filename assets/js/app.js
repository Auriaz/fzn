/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
import '../css/app.scss';
import getNiceMessage from './components/get_nice_message';
import $ from 'jquery';
import 'bootstrap';
import Dropdown from "./components/Dropdown";
import StickyHeader from './components/StickyHeader';
import RevealOnSroll from './components/RevealOnSroll';
import Login from "./components/Login";
import MobileMenu from "./components/MobileMenu";
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');


const dropdown = new Dropdown();
const mobileMenu = new MobileMenu();
const login = new Login();
const sticky = new StickyHeader();

new RevealOnSroll("100%");

//  uncomment to support legacy code
// global.$ = $;


console.log(getNiceMessage(5));

// $('.dropdown-toggle').dropdown();
// $('.custom-file-input').on('change', function (event) {
//     var inputFile = event.currentTarget;
//     $(inputFile).parent()
//         .find('.custom-file-label')
//         .html(inputFile.files[0].name);
// });