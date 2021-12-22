(function ($) {
    function getSites() {
        return window.wpEditingAll.sites.filter(site => site.domain !== window.wpEditingAll.mainSite.domain);
    }

    function SubdomainSelect() {
        let self = this
        let sites = getSites();
        self.loadingStatus = ko.observable(false);
        self.results = ko.observableArray([])
        self.weaSynchronize = function () {
            self.loadingStatus(true)
            self.results.removeAll()
            backend(0, sites,
                function (data, domain) {
                    self.results.push({
                        site_domain: domain,
                    })
                },
                function () {
                    self.loadingStatus(false)
                }
            )
        }
        self.loadingClass = ko.pureComputed(function () {
            return self.loadingStatus() ? "wea-loading" : "";
        })
    }

    function backend(next, sites, done, callback) {
        if (next < sites.length) {
            let domain = sites[next].domain;
            let formData = new FormData()
            formData.append('action', 'wea-update-price')
            $.ajax({
                url: `https://${domain}${window.ajaxurl}`,
                type: 'post',
                contentType: false,
                processData: false,
                data: formData,
                async: true,
                cache: false,
                dataType: 'json',
                enctype: 'multipart/form-data',
            })
                .done(function (response) {
                    done(response, domain)
                })
                .fail(function () {
                    console.log('Произошла ошибка')
                })
                .always(function () {
                    backend(next + 1, sites, done, callback)
                })
        } else {
            callback()
        }
    }

    ko.applyBindings(new SubdomainSelect());
})(jQuery);