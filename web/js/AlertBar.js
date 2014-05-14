/**
 *
 * @param msg
 * @param type
 * @param timeout
 * @constructor
 */
function AlertBar(msg, type, timeout) {
    /* variables */
    this.msg = msg;
    this.type = type;
    this.timeout = timeout || null;
    this.uid = '_' + Math.random().toString(36).substr(2, 9);

    /* methods */
    this.render = function (renderOver) {
        var alertBox = $('.flash-messages');

        var alertBuild =
            '<div id="' + this.uid + '"class="alert alert-' + this.type + ' alert-dismissable alert-message">' +
                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                this.msg +
            '</div>';

        if (renderOver) {
            $(alertBox).html(alertBuild);
        } else {
            $(alertBox).append(alertBuild);
        }

        if (this.timeout > 600) {
            var alert = $(alertBox).find('#' + this.uid);

            $(alert).delay(this.timeout).fadeOut('slow', function () {
                $(this).remove();
            });
        }
    };

    this.clearBox = function () {
        var alertBox = $('.flash-messages');

        $(alertBox).html('');
    };
}