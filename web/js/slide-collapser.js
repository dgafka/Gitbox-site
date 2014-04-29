$(document).ready(function() {
    var collapseSidebar = function(speed, easing, callback) {
        var totalWidth = $('.jumbojumba').find('.row').width();
        var totalMinusBtn = totalWidth - totalWidth*0.05;
        var contentWidth = $('.main-content').width();

        var width = 0;

        if (contentWidth <= totalWidth*(2/3)) {
            width = totalMinusBtn;
        } else {
            width = '66.6%';
        }

        // run simultaneously 2 effects
        $('.main-content').animate({
            width: width }, speed, easing, callback
        );
        $('.sidebar').toggle(speed, easing);
    };

    $('.collapse-slider-switch').hover(function() {
        // mouseover
        var totalWidth = $('.jumbojumba').find('.row').width();

        $(this).animate({paddingLeft: totalWidth*0.005, paddingRight: totalWidth*0.005}, 300, 'linear');
    }, function() {
        // mouseout
        var totalWidth = $('.jumbojumba').find('.row').width();

        $(this).animate({paddingLeft: totalWidth*0.001, paddingRight: totalWidth*0.001}, 300, 'linear');
    })
//    $('.collapse-slider-switch').

    $('.collapse-slider-switch').click(function() {
        var clicked = typeof clicked == 'undefined' ? false : true;

        if (!clicked) {
            collapseSidebar('slow', 'linear');
        }

        delete clicked;
    });
});