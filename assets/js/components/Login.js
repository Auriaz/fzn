import $ from 'jquery';
import axios from 'axios';
import qs from 'qs';
import catchAlert from './Alert';


class Login {
  constructor() {
    this.form = $('.form-login form');
    this.data = [];
    this.events();
  }

  events() {
    var that = this;

    this.form.submit(function (e) {
      e.preventDefault();
         
      $("#form-login input").each(function () {
        that.checkField($(this));    
      });
      that.setField(); 
    });
  }

  setField() {
    var data = this.data;
    
    axios({
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      data: qs.stringify(data),
      url: '/login',
    })
      .then(res => res.data)
      .then(data => {
        // console.log(data.message);
      // przesyła wiadomości do alerta
        catchAlert(data.message);
        this.validedField(data.message.alert);
      })
      .catch(err => console.log(err));
  }
  
  checkField(field) {
    if ($(field).prop('required') == true) {      
      if ($(field).prop('type') == 'text') {
        if ($(field).val()) {
          this.data.login = $(field).val();
        } 
      }

      if ($(field).prop('type') == 'password') {
        if ($(field).val()) {  
          this.data.pass = $(field).val();
        } 
      }
    }
  }
  
  validedField(res) {  
    if (res == 'success') {
      setTimeout(() => location.reload(), 500);
    } else if (res == "warning") {
      $('#login').css({ 'color': 'green', 'border-bottom': '2px solid green' });
      $('#login').next('label').css("color", "green");
      $('#pass').css({'color':'red', 'border-bottom': '2px solid red'}); 
      $('#pass').next('label').css("color", "red"); 
    } else {
      $('#login').css({ 'color': 'red', 'border-bottom': '2px solid red' });
      $('#login').next('label').css("color", "red");
      $('#pass').css({ 'color': 'red', 'border-bottom': '2px solid red' });
      $('#pass').next('label').css("color", "red");
    } 
  }
}

export default Login;