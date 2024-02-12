(function ($) {
    class ServicesLinker {
        constructor($) {
            this.ajax_url = $;
        }

        linkerAttributeToSearchTerms(sku, terms, successCallback, errorCallback) {
            $.ajax({
                url: this.ajax_url,
                method: "POST",
                dataType: "json",
                data: {
                    "action": 'alst_attribute_linker_search_terms_ajax',
                    "sku": sku,
                    "terms": terms,
                },
                success: function (response) {
                    successCallback(response);
                },
                error: function (xhr, status, error) {
                    errorCallback(error);
                }
            });
        }
    }

    window.ServicesLinker = ServicesLinker;
})(jQuery);
