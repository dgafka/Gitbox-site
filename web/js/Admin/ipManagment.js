var ipManagmentClass = function() {

    this.add = function() {
        var dt   = new Date();
        var date = dt.getFullYear() + "-" + dt.getMonth() + 1 + "-" + dt.getDate() + " " + dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
        var id   = dt.getHours() + dt.getMinutes() + dt.getSeconds();
        $('#ip-content').append(
            '<tr>'              +
                '<td>' + date + '</td>'    +
                '<td><input type="text" class="form-control" placeholder="IP"></td>' +
                '<td><button class="btn btn-default" id="'+ id + '-delete' +'">Usuń</button>  <button class="btn btn-primary" id="'+ id + '-save' +'">Zapisz</button></td>' +
        '</tr>'
        );


        this.addListener($('#' + id + '-delete'), 'delete');
        this.addListener($('#' + id + '-save'), 'save');
    }.bind(this),

    this.save = function() {
        alert('save');
    },

    this.delete = function() {
        alert('deletion');
    },

    this.pagination = function() {

    }

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
