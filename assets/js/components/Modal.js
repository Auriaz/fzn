import $ from 'jquery';

/* 
* index - this is the id/class of the button,
* modal - this is the id of the modal,
* title - this it's modal title, 
* button - this is the id/class of the button in the modal, ????
* action - how to do it after pressing the button, ????
* methods - An array of methods such as:
*   [
*       0 => bool - if true then e.preventDefault() is included ,
*         
*   ]     
*/

class Modal {
    constructor(index = null, modal = null, button = null, action = null, methods = []) {
        this.index = index;
        this.modal = $(modal);
        this.button = $(button);
        this.action = action;
       
        if(methods) {
            this.methods = methods;
        }
        
        this.index.click(e => {
            this.events(e);
        });
    }

    events(e) {
        if(this.methods[0] == true ) {
            e.preventDefault();
        }

        if(this.modal != null) {
            this.modal.modal('hide');
        }

        this.button.click(this.actions.bind(this));
    }

    actions() {
        this.action;

    }
}

export default Modal;