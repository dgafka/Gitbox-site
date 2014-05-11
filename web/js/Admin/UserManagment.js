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
        var mainFunction = arguments.callee;
        $(this).button('loading');

        var ajax = new Ajax('json');
        var _button = $(this);
        ajax.setSuccessCallback2(mainFunction);
        ajax.setSuccessCallback(function(results){
            var content = '#' + $(this).parent().attr('id');
            var header = '#' + $(this).parent().attr('id').replace('content', 'header');
            content = $(content);
            header  = $(header);

            content.remove();
            if(!(typeof results.content === "undefined")) {
                header.after(results.content);
            }
            header.remove();
            $( "#accordion" ).accordion( "destroy");
            $( "#accordion" ).accordion();

        }.bind(this));

        var url = $(this).attr('link_target');
        var message = $(this).attr('message');

        $('<div></div>').appendTo('body')
            .html('<div><h6>'+ message +'</h6></div>')
            .dialog({
                modal: true,
                title: 'Potwierdzenie',
                zIndex: 10000,
                class: "dialog-confirm",
                autoOpen: true,
                width: 'auto',
                resizable: false,
                buttons: {
                    Tak: function () {
                        $(this).remove();
                        ajax.sendAjax(url);
                        _button.button('reset');
                    },
                    Nie: function () {
                        $(this).remove();
                        _button.button('reset');
                    }
                },
                close: function (event, ui) {
                    $(this).remove();
                    _button.button('reset');
                }
            });


    }

    this.initalization = function() {

        //binding listeners
        $('#paginationUser a').on('click', this.pagination);
        this.sendAction.prototype.scopeHere = this;
        $('#accordion button').on('click', this.sendAction);
    }


    this.initalization();
}

$(document).ready(function(){
    var userManagment = new UserManagment();
})