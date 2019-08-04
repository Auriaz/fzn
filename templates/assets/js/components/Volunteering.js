import $ from 'jquery';

class Volunteering {
    constructor() {
        this.volunteering = $('.home-volunteering__text-volunteer');
        this.text = $('.home-volunteering__text');
        this.count = $('.grid-volunteering').children("div").length;
           
        this.events();
    }

    events() {
        var i = 0;
        var word = "wolontariat";
        var append = "";
        if(this.count) {

            // var color = ['red', 'teal', 'green', 'fuchsia', 'lime', 'aqua', 'purple', 'blue', 'maroon', 'navy', 'yellow']
            append += word.charAt(i);
            if (typeof this.text[i] !== 'undefined' || this.volunteering ) {
                this.text[i].style.display = 'block';
                $('.first-letter').css('color', 'white');
                this.volunteering.html(append).css('color', 'white');
            }
             
            setInterval(()=>{
                this.rest();
                i++;
    
                if (i == this.count) {
                    i = 0;
                }
    
                append += word.charAt(i);
    
                $('.first-letter').css('color', 'white');
                this.volunteering.html(append).css('color', 'white');
                this.text[i].style.display = 'block';
                if(word == append) {       
                    append= "";
                }
            }, 7000);       
        }
    }

    rest() {
        for (let i = 0; i < this.count; i++) {
            this.text[i].style.display = 'none';
        }
    }

}
export default Volunteering;

