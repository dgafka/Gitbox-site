$(document).ready(function() {
    /** VARIABLES **/
    var sliderActive = false;

    /* FUNCTIONS */
    var collapseSidebar = function (speed, easing, target) {
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
        $('.main-content').animate({ width: width }, {
            duration: speed,
            easing: easing,
            queue: false,
            complete: function () {
                var cell = $(target).find('.collapse-slider-cell');

                if (cell.html() === '»') {
                    cell.html('«');
                } else {
                    cell.html('»');
                }
            }
        });
        $('.sidebar').toggle({
            duration: speed,
            easing: easing,
            queue: false,
            complete: function () {
                sliderActive = false;

                if ($('.collapse-slider-switch').is(':hover')) {
                    switchLeave();
                }
            }
        });
    };

    var switchHover = function () {
        $('.sidebar').animate({opacity: 0.3}, 'fast');
    };

    var switchLeave = function () {
        $('.sidebar').animate({opacity: 1}, 'fast');
    };

    /** EVENTS **/
    $('.collapse-slider-switch').hover(switchHover, switchLeave);

    $('.collapse-slider-switch').click(function() {
        $(this).blur();

        if (sliderActive) {
            return;
        }
        sliderActive = true;

        collapseSidebar('slow', 'linear', this);
    });
});