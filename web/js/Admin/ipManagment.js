var ipManagmentClass = function() {

    this.add = function() {
        var url  = $('#addIp').attr('url');
        var dt   = new Date();
        var date = 'Pojawi się po utworzenu.';
        var id   = dt.getHours() + dt.getMinutes() + dt.getSeconds();
        $('#ip-content').prepend(
            '<tr>'              +
                '<td class="date">' + date + '</td>'    +
                '<td class="ip"><input type="text" class="form-control" placeholder="IP"></td>' +
                '<td><button class="btn btn-default" id="'+ id + '-delete' +'">Usuń</button>  <button class="btn btn-primary" url="' + url + '" id="'+ id + '-save' +'">Zapisz</button></td>' +
        '</tr>'
        );


        this.addListener($('#' + id + '-delete'), 'delete');
        this.addListener($('#' + id + '-save'), 'save');
    }.bind(this),

    this.save = function() {
        var ip   = $(this).parent().parent().children('.ip').children('input').val();
        var date = $(this).parent().parent().children('.date').html();

        if(ip.length < 9) {
            alert('Podaj prawidłowe ip');
        }else {
            var ajax = new Ajax();
            ajax.setSuccessCallback(function(results) {
                if(typeof results.error === "undefined") {
                    var html = results.data;
                    $('#ip-content').append(html);
                    return;
                }

                alert('Podano błędne ip');
            })
            ajax.sendAjax($(this).attr('url'), {"ip": ip, "date": date});

        }
    },

//        Usuwanie wpisu
    this.delete = function() {
        alert('deletion');
    },

//        Obsługa paginacji
    this.pagination = function() {

    }

//  Funkcja wysłana do callbacka ajaxa
    this.ajaxCallBack = function() {

    }

//    Dodanie listenerów
    this.addListener = function(dom, type) {
        if(type == 'save') {
            dom.on('click', this.save);

        }else if(type == 'delete') {
            dom.on('click', this.delete);
        }
    }

}


$(document).ready(function() {

    var ipManagment = new ipManagmentClass();
    //settings
    $('#addIp').on('click', ipManagment.add);

})
