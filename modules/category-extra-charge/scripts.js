(function ($) {
    let ViewModel = function (initial) {
        let that = this
        this.isFirst = ko.observable(true);
        this.productCat = ko.observable("")
        this.extra = ko.observable(0)
        this.notice = ko.observable('')
        this.errors = ko.observable('')
        this.productCatIsValid = ko.pureComputed(function () {
            return that.productCat() !== "";
        })
        this.extraIsValid = ko.pureComputed(function () {
            return that.extra() >= 0;
        })
        this.loading = ko.observable(false)
        this.registerClick = function (formElement) {
            if (that.isFirst()) that.isFirst(false);
            that.notice('')
            that.errors('')
            if (!that.productCatIsValid() || !that.extraIsValid()) {

            } else {
                this.loading(true)
                let formData = new FormData(formElement)
                formData.append('action', 'product_cat_extra')
                backend(formData)
                    .done(function (data) {
                        that.notice(data.notice)
                    })
                    .fail(function (jqXHR, textStatus) {
                        that.errors("Request failed: " + textStatus);
                    })
                    .always(function () {
                        that.loading(false)
                    });
            }

        };
        this.loadingStatus = ko.pureComputed(function () {
            return that.loading() ? "wea-loading" : "";
        })
    };
    ko.applyBindings(new ViewModel(""));

    function backend(formData) {
        return $.ajax({
            url: window.ajaxurl,
            type: 'post',
            contentType: false,
            processData: false,
            data: formData,
            async: true,
            cache: false,
            dataType: 'json',
            enctype: 'multipart/form-data',
        })
    }
})(jQuery);
