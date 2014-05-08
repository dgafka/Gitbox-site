$(document).ready(function(){

    //Configure autocompleter for global search
    var initalizeGlobalSearch = function() {
        var select       = $('#selectGlobalSearch');
        var button       = select.parent().children('button');
        var urlSource    = button.attr('data');
        var urlTarget    = button.attr('target');
        //function for handling results
        var resultsCallback = function(results) {
            if(!$.isEmptyObject(results)) {
                select.empty();
                results.forEach(function(object){
                    select.append('<option value="' + object.login + '">' + object.login + '</option>');
                })
            }
        }
        //function for handling submit
        var submitCallback = function(pickedOption, urlTarget) {
            var url   = window.location.origin + urlTarget.replace('*', pickedOption);
            window.location = url;
        }
        new Search(select, button, urlSource, resultsCallback, submitCallback, urlTarget)
    }

    //Run all configuration for search
    var initalize = function() {
        initalizeGlobalSearch();
    }();

})