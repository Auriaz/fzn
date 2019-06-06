class showImageFile {
    constructor(element) {
        this.element = element;

        this.element.click((e) => {
            showImage(e);
        });
    }

    showImage(e) {
        alert('ok');
        console.log(e);
    }
}