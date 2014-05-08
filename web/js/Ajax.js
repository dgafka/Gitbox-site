var Ajax = function(dataType) {

    this.successCallBack = {};

    this.sendAjax = function(url) {
        $.ajax({
            url: url,
            context: this,
            type: "GET",
            dataType: typeof dataType === "undefined" ? 'json' : dataType,
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