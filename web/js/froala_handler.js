$(document).ready(function(){
    $(function(){
        $('.post-editor').editable({minHeight: 300, inlineMode: false, imageUploadURL: 'http://localhost:3000/upload_image'})
        $('.post-editor').editable("setHTML", "<div><p>Wprowadź treść nowego wpisu...</p></div>");
    });
});