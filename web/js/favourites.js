$(document).ready(function() {
    $('.fav-remove-btn').on('click', function (event) {
        event.preventDefault(); // same as `return false`
        event.stopPropagation();

        favRequest($(this).attr('data-link'), $(this));
    });

    function favRequest(url, target) {
        $.ajax({
            type: 'POST',
            url: url,
            success: function(data) {
                var status = data.status;

                var content = $(target).closest('.content-item');

                if (status == 'removed') {
                    $(content).css('opacity', '0.5');
                    $(target)
                        .removeClass('btn-danger')
                        .addClass('btn-primary')
                        .addClass('fav-revert-btn');
                    $(target).children().first()
                        .removeClass('glyphicon-remove')
                        .addClass('glyphicon-ok');
                } else if (status == 'added') {
                    $(content).css('opacity', '1');
                    $(target)
                        .addClass('btn-danger')
                        .removeClass('btn-primary')
                        .removeClass('fav-revert-btn');
                    $(target).children().first()
                        .addClass('glyphicon-remove')
                        .removeClass('glyphicon-ok');
                }

                // alert bar, niepotrzebny :)
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