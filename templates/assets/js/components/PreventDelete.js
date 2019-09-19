import $ from 'jquery';
import axios from 'axios';
// import catchAlert from './Alert';

class PreventDelete {
	constructor() {
	  	this.delete = $(".delete");
	  	this.events();
	}

	events() {
	  	this.delete.click(function(e) {
            if (confirm('Czy jesteś pewien, że chcesz usunąć ?')) {
                const url = $(this).data("url");
                axios({
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    url:url,
                })
                    .then(res => location.reload())
                    .catch(err => console.log(err));
            }
        });
	}

}
export default PreventDelete;