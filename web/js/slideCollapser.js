$(document).ready(function() {
    /** VARIABLES **/
    var sidebarCollapsed = false;
    var sliderActive = false;

    /* FUNCTIONS */
    var collapseSidebar = function (speed, easing, target) {
        var totalWidth = $('.jumbojumba').find('.row').width(),
            totalMinusBtn = totalWidth - totalWidth*0.05,
            contentWidth = 0, sidebarWidth = 0;

        if (sidebarCollapsed) {
            contentWidth = '66.6%';
            sidebarWidth = 'initial';
        } else {
            contentWidth = totalMinusBtn;
        }

        // run simultaneously 3 effects
        $('.main-content').animate({ width: contentWidth }, {
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

                sliderActive = false;
            }
        });
        $('.sidebar').animate({ width: sidebarWidth }, {
            duration: speed,
            easing: easing,
            queue: false,
            complete: function () {
                if (sidebarCollapsed) {
                    sidebarCollapsed = false;
                } else {
                    sidebarCollapsed = true;
                }
            }
        });
        $('.sidebar').toggle({
            duration: speed,
            easing: easing,
            queue: false,
            complete: function () {
                fireEvent($(this), 'shown');
            }
        });
    };

    var switchHover = function () {
        $('.sidebar').animate({opacity: 0.3}, 'fast');
    };

    var switchLeave = function () {
        $('.sidebar').animate({opacity: 1}, 'fast');
    };

    function fireEvent(target, type) {
        if ($(target).length > 0) {
            var shownEvent = $.Event(type);

            $(target).trigger(shownEvent);
        }
    }

    /** EVENTS **/
    $('.collapse-slider-switch').hover(switchHover, switchLeave);

    $('.collapse-slider-switch').click(function() {
        $(this).blur();

        if (sliderActive) {
            return;
        }
        sliderActive = true;

        collapseSidebar('slow', 'swing', this);
    });

    $('.sidebar').on('show shown', function (event) {
        switchLeave();
    });
});