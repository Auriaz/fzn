import $ from 'jquery';

class MobileMenu {
    constructor() {
        this.menuIcon = $(".btn-x");
        this.menuContent = $(".btn-x_content");
        this.events();
    }

    events() {
        this.menuIcon.click(this.toggleTheMenu.bind(this));
    }

    toggleTheMenu() {
        this.menuContent.toggleClass("btn-x_content-is-visible");
        this.menuIcon.toggleClass("btn-x-close");
    }
}

export default MobileMenu;