import $ from 'jquery';

class Alert {
    constructor(_msg) {
        this.alertBox = $('#alert-box');    
        this.add(_msg);
    }
    
    add(_msg) {
        //sprawdzenie ile jest alertów w 'div.alert-box' oraz nadanie indexu 'i' dla '.alert.i'
        var i = this.alertBox.children(".alert").length;
      //Dodanie alerta do 'div.alert-box'
        this.alertBox.append(`<span class='alert ${i} alert-${_msg.alert}'><i class="info-circle"></i> ${_msg.msg}</span>`);
        //Przekazanie indexu 'i'
        this.disableTimeout(i);
    }
    //Funkcja wyłaczjąca alert 'span.i' po upływie założonego czasu.
    // Czas musi być idenntyczny jak w animacji 'row'.
    disableTimeout(i) {
        var timer = setTimeout(()=>{
            this.alertBox.children(`.${i}`).remove();
       }, 4000);

       timer = clearTimeout();
    }
}

/* Funkcja która jest uchwytem dla alertów 'alert',
 z przesyłaną wiadomościa w tablicą '_msg'.
 '_msg.alert' - rodzaj wyświetlanego alerta(danger, warning, info lub succes),
 '_msg.msg' - wiadomość alerta
 */

function catchAlert(_msg) { 
    return new Alert(_msg);
}

export default catchAlert;