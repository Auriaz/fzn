import $ from 'jquery';

class OpenModel {
    constructor(click, handle) {
        this.open = $(`#${click}`);
        this.close = $(`#close_modal_${handle}`);
        this.modal = $(`#${handle}`);
        this.nameHandle = handle;
        this.events();
    }

    events() {
        this.open.click(this.toggleMenu.bind(this));
        this.close.click(this.toggleMenu.bind(this));
    }

    toggleMenu() {
        this.modal.toggleClass(`modal-active-${this.nameHandle}`);
    }
}

export default OpenModel;