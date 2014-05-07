$(document).ready(function() {

    function sidebarUpdate(score, total) {
        var scoreBadge = $('.sidebar').find('.badge-score');

        if (score > 0) {
            $(scoreBadge).removeClass('badge-lightpink');
            $(scoreBadge).addClass('badge-lightgreen');
        } else if (score < 0) {
            $(scoreBadge).removeClass('badge-lightgreen');
            $(scoreBadge).addClass('badge-lightpink');
        } else {
            $(scoreBadge).removeClass('badge-lightpink');
            $(scoreBadge).removeClass('badge-lightgreen');
        }

        $(scoreBadge).html(
            score + '&nbsp;<span class="inner-badge-sub">(' + total + ')</span>'
        );
    }

    // animation function
    $('.sidebar').on('vote', function (event) {
        var url = $('.sidebar').find('.sidebar-update').attr('data-link');

        $.ajax({
            type: 'POST',
            url: url,
            success: function(data) {
                sidebarUpdate(data.ratingScore, data.ratingQuantity);
            },
            error: function() {
                // TODO: updateAction - exception test
            }
        });
    });

});