//input  -> jQuery input reference
//submit -> jQuery submit button reference
//url    -> with following syntax /Gitbox-site/web/app_dev.php/search/user/*  , where * is inputed value
//resultAppendCallback -> Should recive at the beginning object from ajax and should return value which will be passed to an option
//submitCallback -> Should recive at the beginning value from option, which was picked and do some final action
//urlTarget optional, if you want to redirect user somewhere. You will get it as second argument when calling submitCallback
var Search = function(select, submit, url, resultAppendCallback, submitCallback, urlTarget) {

    this.select  = select;
    this.submit  = submit;
    this.url     = url;
    this.ajax    = new Ajax();
    this.resultCallback = resultAppendCallback;
    this.submitCallback = submitCallback;
    this.urlTarget      = urlTarget;

    //* is replaced with passed value
    this.sendAsynchronize = function(searchedValue) {
        var url   = this.url.replace('*', searchedValue);
        if($.trim(searchedValue) != '' && searchedValue.length > 1) {
            this.ajax.sendAjax(url);
        }
    }

    //Get actual passed value to the input
    this.getPassedValue = function() {
        return this.select.parent().children('.custom-combobox').children('input').val();
    }

    //On input change do following
    this.onInputChange = function(event) {
        this.sendAsynchronize(this.getPassedValue());
    }

    this.configure = function() {
        this.select.combobox({
            delay: 500,
            minLength: 1
        });

        this.ajax.setSuccessCallback(this.resultCallback);

        //bind listeners
        this.select.parent().children('.custom-combobox').children('input').on( 'keyup', this.onInputChange.bind(this));
        this.submit.on('click', function(event) {
            event.preventDefault();
            var value = this.select.val();
            return this.submitCallback(value, this.urlTarget);
        }.bind(this))
    }

    this.configure();
}