$(document).ready(function(){
    // load checkbox component
    $('.checkbox').checkbox();

    // load select component
    $('.selectpicker').selectpicker({
        size: 8
    });

    // reveal password
    $(".reveal").mousedown(function() {
        $(".password-input").replaceWith($('.password-input').clone().attr('type', 'text'));
    })
    .mouseup(function() {
        $(".password-input").replaceWith($('.password-input').clone().attr('type', 'password'));
    })
    .mouseout(function() {
        $(".password-input").replaceWith($('.password-input').clone().attr('type', 'password'));
    });

    // scroll to top
    $(window).scroll(function () {
        if ($(this).scrollTop() > 50) {
            $('#back-to-top').fadeIn();
        } else {
            $('#back-to-top').fadeOut();
        }
    });
    // scroll body to 0px on click
    $('#back-to-top').click(function () {
        $('#back-to-top').tooltip('hide');
        $('body,html').animate({
            scrollTop: 0
        }, 800);
        return false;
    });

    $('#back-to-top').tooltip('show');
});



