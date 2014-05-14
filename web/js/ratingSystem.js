$(document).ready(function() {

    function voteRequest(url, target) {
        var postContainer = $(target).closest('.post-in-the-box');

        $(postContainer).find('.btn-vote-up, .btn-vote-down').attr('disabled', true);

        $.ajax({
            type: 'POST',
            url: url,
            success: function(data) {
                fireVoteEvent($('.sidebar'));

                $(postContainer).find('.btn-vote-up, .btn-vote-down').removeAttr('disabled');

                $(postContainer).find('.score-up').html(data.votesUp);
                $(postContainer).find('.score-down').html(data.votesDown);

                voteAnimation($(postContainer).find('.score-up'), $(postContainer).find('.btn-vote-up'), 'slow', 'swing');
                voteAnimation($(postContainer).find('.score-down'), $(postContainer).find('.btn-vote-down'), 'slow', 'linear');

                // smiley watches YOU
                var smiley = $(postContainer).find('.smiley-see-you');
                $(smiley).removeClass('fa-meh-o');

                if ($(target).hasClass('btn-vote-up')) {
                    smiley.addClass('fa-smile-o');
                } else {
                    smiley.addClass('fa-frown-o');
                }

                // alert bar
                var msg = data.msg;
                var type = data.success == true ? 'success' : 'warning';

                if (type != 'success') {
                    var alert = new AlertBar(data.msg, type, 10000);
                    alert.render();
                }

                // obsługa `ciasteczek` znajduje się w wywoływanej akcji (GitboxCoreBundle:Rating:vote)
            },
            error: function() {
                var msg = 'Wystąpił nieoczekiwany błąd! Odśwież stronę i spróbuj ponownie.';
                var type = 'danger';

                var alert = new AlertBar(msg, type);
                alert.render();
            }
        });
    };

    function voteAnimation(showTarget, hideTarget, speed, easing) {
        $(hideTarget).fadeOut({
            duration: speed,
            queue: true
        });

        $(showTarget).slideDown({
            duration: speed,
            queue: true
        });
    }

    function fireVoteEvent(target) {
        if ($(target).length > 0) {
            var voteEvent = $.Event('vote');

            $(target).trigger(voteEvent);
        }
    }

    $('.btn-vote-up, .btn-vote-down').on('click', function (event) {
        event.preventDefault(); // same as `return false`
        event.stopPropagation();

        voteRequest($(this).attr('href'), $(this));
    });

});