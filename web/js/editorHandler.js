$(document).ready(function(){
    $(function(){
        $('.post-editor').editable({minHeight: 300, inlineMode: false, placeholder: 'Wprowadź treść nowego wpisu...'})
//        $('.post-editor').editable("setHTML", "<p>Wprowadź treść nowego wpisu...</p>");
    });
});