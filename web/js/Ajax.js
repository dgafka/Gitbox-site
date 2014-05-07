var Ajax = function() {

    this.successCallBack = {};

    this.sendAjax = function(url) {
        $.ajax({
            url: url,
            context: this,
            type: "GET",
            dataType: "json",
            success: function(data) {
                this.successCallBack(data);
            },
            error: function(data) {
                this.results = {};
            }
        });
    }

    this.setSuccessCallback = function(successCallback) {
       this.successCallBack = successCallback;
    }
}