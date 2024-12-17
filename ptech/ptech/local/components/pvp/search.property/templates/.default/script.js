class PvpPropertySearch {
    init() {
        let body = $('body');

        body.on('click', '.pvp-search-property .search-block .search-result-wrap .results-close .results-close__link', function (event) {
            event.stopPropagation();
            event.preventDefault();

            $('.pvp-search-property .search-block').hide();
        });

        body.on('input', '.pvp-search-property .search-form .search-form__input', pvpPropertySearch.find);
    }

    find(event) {
        let elm = event.currentTarget;

        if (4 > elm.value.length) {
            $('.pvp-search-property .search-block').hide();
            return;
        }

        $.post(pvpPropertySearchParams.callbackUrl,
            {
                'q': elm.value,
                'params': pvpPropertySearchParams.componentParams
            },
            function(data) {
                $('.pvp-search-property .search-block .search-result').html(data);
                $('.pvp-search-property .search-block').show();
            },
            'html'
        );
    }
}

BX.ready(function() {
    window.pvpPropertySearch = new PvpPropertySearch();
    pvpPropertySearch.init();
});