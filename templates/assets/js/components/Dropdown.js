import $ from "jquery";

class Dropdown {
    constructor() {
        this.dropdown = $('.dropdown');
        this.menu = $('.dropdown-menu');
        
        this.events();
    }

    events() {
        this.dropdown.click(function() { 
            $(this)
                .find("a")
                .toggleClass("dropdown-change-color");
            
            $(this)
                .find(".dropdown-menu")
                .toggleClass("dropdown-is-visible");
                
            $(this)
                .find(".dropdown-menu-sidebar")
                .toggleClass("dropdown-is-visible-sidebar");
            
            $(this)
                .find(".fa-caret-right")
                .toggleClass("fa-rotate-90");
            });
        }
    }
export default Dropdown;