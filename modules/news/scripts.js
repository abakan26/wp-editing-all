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
            syncNews(0, sites,
                function (data, domain) {
                    self.results.push({
                        site_domain: domain,
                        update: data.log.update.length,
                        insert: data.log.insert.length,
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

    function syncNews(next, sites, done, callback) {
        if (next < sites.length) {
            let domain = sites[next].domain;
            let formData = new FormData()
            formData.append('action', 'wea-sync-news')
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
                    syncNews(next + 1, sites, done, callback)
                })
        } else {
            callback()
        }
    }

    ko.applyBindings(new SubdomainSelect());
})(jQuery);