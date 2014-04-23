$(document).ready(function(){
    $(function(){
        $('.post-editor').editable({minHeight: 300, inlineMode: false})
        $('.post-editor').editable("setHTML", "<div><p>Wprowadź treść nowego wpisu...</p></div>");
    });
});