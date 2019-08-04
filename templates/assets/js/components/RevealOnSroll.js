import $ from 'jquery';
import waypoints from 'waypoints/lib/noframework.waypoints';


class RevealOnSroll {
    constructor(offset) {
        this.itemsToReveal = $('.zoom');
        this.offsetPercetage = offset;
        this.hideInitially();
        this.createWaypoints();
    }

    hideInitially() {

        this.itemsToReveal.addClass("reveal-item");
    }

    createWaypoints() {
        var that = this;
        this.itemsToReveal.each(function () {
            var currentItem = this;
            new Waypoint({
                element: currentItem,
                handler: function () {
                    $(currentItem).addClass("reveal-item--is-visible");
                },
                offset: that.offsetPercetage
            });
        });
    }
}

export default RevealOnSroll;