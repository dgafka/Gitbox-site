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
                '<td><button class="btn btn-default delete" id="'+ id + '-delete' +'">Usuń</button>  <button class="btn btn-primary save" url="' + url + '" id="'+ id + '-save' +'">Zapisz</button></td>' +
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
                    var result = results.data;
                    //przypisanie wyników do rekordu
                    $(this).parent().parent().children('.date').html(result.createDate);
                    $(this).parent().parent().children('.ip').html(result.ip)
                    $(this).parent().children('.delete').attr('url', results.url);
                    $(this).remove();

                    return;
                }

                alert('Podano błędne ip');
            }.bind(this))
            ajax.sendAjax($(this).attr('url'), {"ip": ip, "date": date});
        }
    },

//        Usuwanie wpisu
    this.delete = function() {
        if(!(typeof $(this).attr('url') === "undefined")) {
            var ajax = new Ajax();
            ajax.sendAjax($(this).attr('url'));
        }

        $(this).parent().parent().remove();
    },

//        Obsługa paginacji
    this.pagination = function(event) {
        var ajax = new Ajax('html');
        ajax.setSuccessCallback(function(results){
            var managmentDiv = $('#ipManagment');
            managmentDiv.empty();
            managmentDiv.append(results);
        });

        var url = $(this).attr('link_target');

        ajax.sendAjax(url);
        event.preventDefault();
    }

//    Dodanie listenerów
    this.addListener = function(dom, type) {
        if(type == 'save') {
            dom.on('click', this.save);

        }else if(type == 'delete') {
            dom.on('click', this.delete);
        }
    }

    this.initalize = function() {

        //binding listeners
        $('#paginationIP a').on('click', this.pagination);
        $('#ip-content button').each(function(index, button) {
            this.addListener($(button), 'delete');
        }.bind(this))
    }

    this.initalize();
}


$(document).ready(function() {

    var ipManagment = new ipManagmentClass();
    //settings
    $('#addIp').on('click', ipManagment.add);

})
