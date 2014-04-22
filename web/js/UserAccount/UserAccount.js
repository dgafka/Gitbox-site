/**
 * Main user account class, contains all must have stuff for User account
 */
var userAccount = {

    registerVerification : function(username, email, password, confirmPassword) {
        var message = '<ul class="list-unstyled">';

        if(username == '') {
            message += '<li>Fill username.</li>';
        }
        if(email == '') {
            message += '<li>Fill email.</li>';
        }else {
            /** @TODO Napisać regex, który sprawdzi poprawność adresu email */
        }
        if(password == '') {
            message += '<li>Fill password. </li>';
        }
        if(confirmPassword == '') {
            message += '<li>Fill confirm password. </li>';
        }

        if(password.length < 8) {
            message += '<li>Password must be atleast 8 characters. </li>';
        }else if(password != confirmPassword) {
            message += '<li>Passwords must be the same. </li>';
        }

        message += '</ul>';

        return message;
    }

}


/**
    actions for user account
*/

$(function() {


    //Submit for register
    $('#submitRegister').on('click', function(event) {
        var formArray = new Array();
        $('form[role="form"] > div > input').each(function (index, element) {
            formArray.push(element.value);
        })

        var message = userAccount.registerVerification(formArray[0], formArray[1], formArray[2], formArray[3]);
        if(message == '<ul class="list-unstyled"></ul>') {
            return true;
        }

        var summary = $('.summary');
        summary.empty();
        summary.append(message);

        event.preventDefault();
    });
})