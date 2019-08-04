import $ from 'jquery';

class AgreeTerms {
    constructor() {
        this.agreeTerms = $('#user_registration_form_agreeTerms');
        this.button = $('#user_registration_modal_agreeTerms_submit');

        this.agreeTerms.click(this.notAccepted.bind(this));
        this.button.click(this.accepted.bind(this));
    }

    notAccepted() {
        this.agreeTerms.prop('checked', false);
    }

    accepted() {
        $('#user_registration_modal_agreeTerms').modal('hide');
        this.agreeTerms.prop('checked', true);
    }
}

export default AgreeTerms;