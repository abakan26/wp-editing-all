(function ($) {
    function getSites() {
        return window.wpEditingAll.sites;
    }

    function SubdomainSelect() {
        let self = this
        self.domain = ko.observable('');
        self.loadingAttr = ko.observable(false);
        self.loadingStatus = ko.observable(false);
        self.attributes = ko.observableArray([])
        self.results = ko.observableArray([])
        self.domain.subscribe(function (newValue) {
            self.attributes([])
            if (newValue === '') return;
            self.loadingAttr(true)
            fetchAttr(newValue)
                .done(function (response) {
                    self.attributes(response.data)
                })
                .always(function () {
                    self.loadingAttr(false)
                })
        })
        self.weaSynchronize = function () {
            self.loadingStatus(true)
            self.results.removeAll()
            let sites = getSites().filter(site => site.domain !== self.domain());
            syncAttributes(0, sites, self.attributes(),
                function (domain) {
                    self.results.push({
                        site_domain: domain,
                        url: `https://${domain}/wp-admin/edit.php?post_type=product&page=product_attributes`
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

    function fetchAttr(domain) {
        let formData = new FormData()
        formData.append('action', 'wea-fetch-attributes')
        return $.ajax({
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
    }

    function syncAttributes(next, sites, attributes, done, callback) {
        if (next < sites.length) {
            let domain = sites[next].domain;
            let formData = new FormData()
            formData.append('action', 'wea-sync-attributes')
            formData.append('attributes', JSON.stringify(attributes))
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
                    done(domain)
                })
                .fail(function () {
                    console.log('Произошла ошибка')
                })
                .always(function () {
                    syncAttributes(next + 1, sites, attributes, done, callback)
                })
        } else {
            callback()
        }
    }

    ko.applyBindings(new SubdomainSelect());
})(jQuery);