var UserManagment = function(){

    this.pagination = function(event) {
        var ajax = new Ajax('html');
        ajax.setSuccessCallback(function(results){
            var managmentDiv = $('#userManagment');
            managmentDiv.empty();
            managmentDiv.append(results);
        });

        var url = $(this).attr('link_target');

        ajax.sendAjax(url);
        event.preventDefault();
    }

    this.sendAction = function(event) {
        var ajax = new Ajax();
        ajax.setSuccessCallback(function(results){
            $('<div></div>').appendTo('body')
                .html('<div><h6>'+ results.answer +'</h6></div>')
                .dialog({
                    modal: true,
                    title: 'Informacja',
                    zIndex: 10000,
                    autoOpen: true,
                    width: 'auto',
                    resizable: false,
                    buttons: {
                        Yes: function () {
                            $(this).dialog("close");
                        }
                    },
                    close: function (event, ui) {
                        $(this).remove();
                    }
                });
        });

        var url = $(this).attr('link_target');
        var message = $(this).attr('message');

        $('<div></div>').appendTo('body')
            .html('<div><h6>'+ message +'</h6></div>')
            .dialog({
                modal: true,
                title: 'Potwierdzenie',
                zIndex: 10000,
                autoOpen: true,
                width: 'auto',
                resizable: false,
                buttons: {
                    Yes: function () {
                        $(this).dialog("close");
                        ajax.sendAjax(url);
                    },
                    No: function () {
                        $(this).dialog("close");
                    }
                },
                close: function (event, ui) {
                    $(this).remove();
                }
            });
        event.preventDefault();
    }

    this.initalization = function() {

        //binding listeners
        $('.pagination a').on('click', this.pagination);
        $('#accordion button').on('click', this.sendAction);
    }


    this.initalization();
}

$(document).ready(function(){
    var userManagment = new UserManagment();


})