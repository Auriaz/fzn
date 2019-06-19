import $ from 'jquery';

class Sidebar {
    constructor() {
        this.sideBarDashboard = $("#sidebar__dashboard");
        // this.sideBarArticle = $("#sidebar__article");
        this.menuIcon = $(".sidebar__menu-icon");
        // this.contentText = $(".content-section--article");
        this.contentDashboard = $(".dashboard-section");
        this.textNameDashboard = $(".sidebar__list--dashboard-text-name");
        this.events();
    }

    events() {
        this.menuIcon.click(this.toggleMenu.bind(this));
    }

    toggleMenu() {
        // this.sideBarArticle.toggleClass("sidebar--is-unseen__article");
        this.sideBarDashboard.toggleClass("sidebar--is-unseen__dashboard");
        // this.contentText.toggleClass("dashboard-section--is-large");
        this.contentDashboard.toggleClass("dashboard-section--is-mid-large");
        this.textNameDashboard.toggleClass("sidebar__list--dashboard-text-name_empty");
        this.menuIcon.toggleClass("sidebar__menu-icon--close-x");
    }
}

export default Sidebar;