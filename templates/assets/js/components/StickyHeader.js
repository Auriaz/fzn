import $ from "jquery";
// import Waypoint from 'Waypoint';

/*  #### Przyklejanie nawigacji  ####  */
class StickyHeader {
  constructor(element = null) {
    this.Nav = $(".nav");
    this.Logo = $(".logo");
    this.Icon = $(".icon-nav");
    this.headerTriggerElement = $("#container");
    this.headerLinks = $('.home-sction');
    this.additionalElement = $(`${element}`);
    this.createHeaderWaypoint();
  }

  addSticky() {
    this.Nav.addClass("nav-sticky");
    this.Logo.addClass("logo-sticky");
    this.Icon.addClass("icon-nav-sticky");
    this.additionalElement.addClass('add-sticky');
  }

  removeSticky() {
    this.Nav.removeClass("nav-sticky");
    this.Logo.removeClass("logo-sticky");
    this.Icon.removeClass("icon-nav-sticky");
    this.additionalElement.removeClass('add-sticky');
  }

  createHeaderWaypoint() {
    var that = this;
    new Waypoint({
      element: this.headerTriggerElement[0],
      handler: function(direction) {
        if (direction == "down") {
          that.addSticky();
        } else {
          that.removeSticky();
        }
      }
    });
  }
}

export default StickyHeader;