$(function() {

    $('submitRegister').submit(function(event) {
        var formArray = new Array();
        $('form[role="form"] > div > input').each(function (index, element) {
            formArray.push(element);
        })

        var message = userAccount.registerVerification(formArray[0], formArray[1], formArray[2], formArray[3]);
        if(message == '') {
            return true;
        }

        event.preventDefault();
        $('.summary').append(message);
    });
})