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

                // alert bar
                var msg = data.msg;
                var type = data.success == true ? 'success' : 'warning';

                var alert = new AlertBar(data.msg, type, 3000);
                alert.render();
            },
            error: function() {
                var msg = 'Wystąpił nieoczekiwany błąd! Odśwież stronę i spróbuj ponownie.';
                var type = 'danger';

                var alert = new AlertBar(msg, type);
                alert.render();
            }
        });
    };
});