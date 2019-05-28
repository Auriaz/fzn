$(document).ready(function () {
    $('.js-like-article').on('click', function (e) {
        e.preventDefault();
        var $link = $(e.currentTarget);
        $link.toggleClass('far').toggleClass('fas');
        $.ajax({
            method: 'POST',
            url: $link.attr('href')
        }).done(function (data) {
            $('.js-like-article-count').html(data.hearts);
        })
    });
});