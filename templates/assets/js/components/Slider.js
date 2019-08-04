import $ from "jquery";

class Slider {
    
    constructor() {
        this.slider = $('#slider');
        
        if (typeof this.slider.html() != "undefined"){
            this.sliderImages = $('.slide');
            this.arrowLeft = $('#arrow-left');
            this.arrowRight = $('#arrow-right');
            this.words = $('.words');

            this.current = 0;
            this.maxDelay = 0;
            this.maxDuration = 0;
            this.timer = 0;
            this.timer2 = 0;
            this.bufor = 0;

            this.events();
            this.startSlide();  
        } 
    }

    animate() {
        var that = this;

        this.timer2 = setTimeout(function () {

            for (let i = 0; i < that.words.length; i++) {

                let word = that.words[i];
                let duration = (Math.floor(Math.random() * 50) * 0.1);
                let delay = (Math.floor(Math.random() * 100) * 0.01);
                let blur = (Math.floor(Math.random() * 10));

                that.maxDelay = Math.max(delay, that.maxDelay);
                that.maxDuration = Math.max(duration, that.maxDuration);

                word.style.filter = `blur(${blur}px)`;
                word.style.transition = `all ${duration}s ease-in ${delay}s`;
                that.words.addClass('animate');
                $('.slide-content__quotation--author').addClass('slide-content__quotation--author__animate');             
            }
        }, 500);

        this.changeSlide();
    }

    // Clear all images
    rest() {
        this.maxDelay = 0;
        this.maxDuration = 0;

        for (let i = 0; i < this.sliderImages.length; i++) {
            this.sliderImages[i].style.display = 'none';
        }

        this.words.removeClass("animate");

        clearTimeout(this.timer);
        clearTimeout(this.timer2);
    }

    // Init slider
    startSlide() {
        this.rest();

        if (typeof this.sliderImages[this.current] !== 'undefined') {
            this.sliderImages[this.current].style.display = 'block';
        }

        this.animate();
    }

    // Show prev 
    slideLeft() {
        this.rest();      
        this.current--;
        this.sliderImages[this.current].style.display = 'block';
        this.animate();
    }

    slideRight() {  
        this.rest();      
        this.current++;
        this.sliderImages[this.current].style.display = 'block';
        this.animate();
    }

    events() {
        this.arrowLeft.click(this.leftClickedArrow.bind(this));
        this.arrowRight.click(this.rightClickedArrow.bind(this));
    }

    // Left arrow click
    leftClickedArrow() {
        if (this.current === 0) {
            this.current = this.sliderImages.length;
        }

        this.slideLeft();
    }

    // Right arrow click
    rightClickedArrow() {
        if (this.current === this.sliderImages.length - 1) {
            this.current = -1;
        }

        this.slideRight();
    }

    changeSlide() {
        var that = this;
        
        this.timer = setTimeout(()=> {
            that.rightClickedArrow();
        }, 20000);   
    }
}

// function slideChangeConroller (container, content, width) {
//     $(`${container}`).css("transform", `translateX(${$(this).index()} * -${width}px)`);
//     $(`${content}`).removeClass("selected");
//     $(this).addClass("selected");
// }

export default Slider;