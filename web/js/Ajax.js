var Ajax = function(dataType, async) {

    this.successCallBack = {};
    this.successCallBack2;

    this.sendAjax = function(url, data) {
        $.ajax({
            url: url,
            context: this,
            async: typeof async === "undefined" ? true : async,
            type: "GET",
            dataType: typeof dataType === "undefined" ? 'json' : dataType,
            data: typeof data === "undefined" ? {} : data,
            success: function(data) {
                this.successCallBack(data);
                if(!(typeof this.successCallBack2 === "undefined")) {
                    $('#accordion button').unbind('click');
                    $('#accordion button').on('click', this.successCallBack2);
                }
            },
            error: function(data) {
                this.results = {};
            }
        });
    }

    this.setSuccessCallback = function(successCallback) {
       this.successCallBack = successCallback;
    }

    this.setSuccessCallback2 = function(successCallback) {
        this.successCallBack2 = successCallback;
    }
}