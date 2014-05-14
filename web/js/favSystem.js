$(document).ready(function() {
    $('.fav-btn').on('click', function (event) {
        event.preventDefault(); // same as `return false`
        event.stopPropagation();

        favRequest($(this).attr('href'), $(this));
    });

    function favRequest(url, target) {
        $.ajax({
            type: 'POST',
            url: url,
            success: function(data) {
                var status = data.status;

                if (status == 'added') {
                    $(target).children().first().addClass('fav-star-active');
                } else if (status == 'removed') {
                    $(target).children().first().removeClass('fav-star-active');
                }
            },
            error: function() {}
        });
    };
});