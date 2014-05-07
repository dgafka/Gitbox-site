$(document).ready(function () {
    /* Tooltips-y `Feel soo dipsy~` */
    $('.option-left').tooltip();

    /* Post remove modal - wypełniany treścią po kliknięciu na button `Usuń` */
    $(function () {
        var modal = $('#postRemoveModal');

        var modalTitle = $(modal).find('.modal-title');
        var modalBody = $(modal).find('.modal-body');
        var confirmBtn = $(modal).find('.modal-footer .btn-remove-confirm');

        $('.btn-post-remove').click(function () {
            var removalLink = $(this).attr('data-link');
            var postTitle = $(this).closest('.post-in-the-box').find('.post-title').first().html();

            $(modalTitle).html('Usuń wpis <b>' + postTitle + '</b>');
            $(modalBody).html(
                '<p>Czy na pewno chcesz usunąć wpis <b>' + postTitle + '</b></p>' +
                '<p>Usunięte zostaną również komentarze, oceny oraz ogólna zawartość wpisu.</p>'
            );
            $(confirmBtn).attr('href', removalLink);

            $('#postRemoveModal').modal('show');
        });

        $('#postRemoveModal').on('hidden.bs.modal', function () {
            $(modalTitle).empty();
            $(modalBody).empty();
            $(confirmBtn).attr('href', '#');
        });
    });
});