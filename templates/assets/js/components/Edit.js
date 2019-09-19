import $ from 'jquery';
import axios from 'axios'
import { encode } from 'querystring';
import { Array } from 'core-js';

class Edit {
    constructor() {
        this.edit = $(".edit");
        this.modal = $(".edit-user-modal");
        this.data =[];

        this.events();
    }

    events() {
        var that = this;
        this.edit.click(function(e) {
            that.getData($(this).data('url'));
        });
    }

    getData(url) {
        axios
            .get(url)
            .then(res => {
                // let json = Array(res.data);
                // console.log(json);
                this.data = JSON.parse(res.data);
                this.addData(res.data);
            })
            .catch(err => console.log(err));
    }

    addData(data) {
        console.log(this.data);
        this.modal.find('.modal-body').append(`
            <p><strong>Email</strong>: ${this.data['email']} </p>
        `);
    }

}

export default Edit;



