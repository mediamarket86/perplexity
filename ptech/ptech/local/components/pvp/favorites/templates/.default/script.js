class PvpFavorites {
    init() {
        let body = $('body');

        body.on('click', '.pvp-favorite-block a.favorite-button__add', $.proxy(this.add, this));
        body.on('click', '.pvp-favorite-block a.favorite-button__exist', $.proxy(this.delete, this));
        body.on('click', '.pvp-favorite-block.favorite-list .mass-action.select-action-list .clear-all a.clear-all__link', $.proxy(this.deleteAll, this));

        body.on('click', '.pvp-favorite-block.favorite-list .sort-filter .section-filter .list-header .list-header__link', function(elm) {
            elm = elm.currentTarget;
            var sectionList = $('.pvp-favorite-block.favorite-list .sort-filter .section-filter .section-list');

            if ($(elm).hasClass('active')) {
                $(elm).removeClass('active');
                $(sectionList).slideUp();
            } else {
                $(elm).addClass('active');
                $(sectionList).slideDown();
            }
        });

        //section filter checkbox
        body.on('change', '.pvp-favorite-block.favorite-list .section-list-item .controls__checkbox', function (elm) {
            pvpFavorites.setLabelState(elm.currentTarget);

            pvpFavorites.refreshFavoritesList();
        });

        //favorites items checkbox
        body.on('change', '.pvp-favorite-block.favorite-list .catalog-item-info .mass-action.controls .controls__checkbox', function (elm) {
            elm = elm.currentTarget;
            pvpFavorites.setLabelState(elm);

            var selectAllCheckbox = $('.pvp-favorite-block.favorite-list .mass-action.select-action-list .select-all.controls .controls__checkbox')[0];
            selectAllCheckbox.checked = false;
            pvpFavorites.setLabelState(selectAllCheckbox);

            pvpFavorites.setSelectedCount();
        });

        //Select all checkbox
        body.on('change', '.pvp-favorite-block.favorite-list .mass-action.select-action-list .select-all.controls .controls__checkbox', function(elm) {
            elm = elm.currentTarget;
            pvpFavorites.setLabelState(elm);

            $('.pvp-favorite-block.favorite-list .favorite-items .catalog-item-info .mass-action .controls__checkbox').each(function(index, catItem) {
                catItem.checked = elm.checked;
                pvpFavorites.setLabelState(catItem);
            });

            pvpFavorites.setSelectedCount();
        });

        //add selected button
        body.on('click',  '.pvp-favorite-block.favorite-list .mass-action .add-selected .add-selected__link', function() {
            $('.pvp-favorite-block.favorite-list .favorite-items .catalog-item-info .mass-action .controls__checkbox').each(function(index, catItem) {
                if (catItem.checked) {
                    $('#' + catItem.dataset.bxItemId + '_btn_buy').click();
                }
            });
        });




        let observer = new MutationObserver(this.loadButtons);
        let elm = document.querySelector('body');
        let config = {
            childList: true, // наблюдать за непосредственными детьми
            subtree: true, // и более глубокими потомками
        };

        observer.observe(elm, config);
        this.loadButtons();
    }

    loadButtons() {
        let data = {};
        data.action = 'getExistsIdFromList';
        data.params = [];
        $('.pvp-favorite-block .favorite.loading.placeholder').each(function (index, elm) {
            let productId = elm.parentNode.dataset.productId;

            if (productId) {
                $(elm).removeClass('placeholder');
                data.params[index] = productId;
            }
        });

        if (data.params.length) {
            pvpFavorites.ajaxCall(data, pvpFavorites.setExistButtons);
        }
    }

    add(event) {
        event.preventDefault();
        event.stopPropagation();

        let productId = event.currentTarget.parentNode.dataset.productId;
        pvpFavorites.setLoading(productId);

        let  data = {};
        data.action = 'add';
        data.params = [productId];

        pvpFavorites.ajaxCall(data, pvpFavorites.setExistButtons);
    }

    delete(event) {
        event.preventDefault();
        event.stopPropagation();

        let productId = event.currentTarget.parentNode.dataset.productId;
        pvpFavorites.setLoading(productId);

        let  data = {'action': 'delete', 'params': [productId]};

        let callback = pvpFavorites.setAddButtons;

        if ('FAVORITE' === pvpFavoritesParams.mode) {
            callback = function() {
                document.location.reload()
            };
        }

        pvpFavorites.ajaxCall(data, callback);

    }

    deleteAll(event) {
        event.preventDefault();
        event.stopPropagation();

        let  data = {'action': 'deleteAll', 'params': []};

        pvpFavorites.ajaxCall(data, function() {
            document.location.reload()
        });
    }

    setQuantity() {
        let  data = {};
        data.action = 'getCount';
        data.params = [];

        pvpFavorites.ajaxCall(data, function (data) {
            $('.pvp-favorites-quantity-value').html(data);
        });
    }

    setExistButtons(data) {
        data.forEach(pvpFavorites.switchButtonToExist);
        pvpFavorites.hideAllLoadings();
        pvpFavorites.setQuantity();
    }

    setAddButtons(data) {
        data.forEach(pvpFavorites.switchButtonToAdd);
        pvpFavorites.hideAllLoadings();
        pvpFavorites.setQuantity();
    }

    switchButtonToExist(productId) {
        let button = $('#pvp-favorites-' + productId + ' .favorite-button');
        button.removeClass('favorite-button__add');
        button.addClass('favorite-button__exist');
    }

    switchButtonToAdd(productId) {
        let button = $('#pvp-favorites-' + productId + ' .favorite-button');

        button.removeClass('favorite-button__exist');
        button.addClass('favorite-button__add');
    }

    hideAllLoadings() {
        $('.pvp-favorite-block .favorite.loading').hide();
        $('.pvp-favorite-block .favorite-button').css('display', 'flex');
    }

    setLoading(productId) {
        $('#pvp-favorites-' + productId + '.pvp-favorite-block .favorite-button').hide();
        $('#pvp-favorites-' + productId + '.pvp-favorite-block .favorite.loading').show();
    }

    refreshFavoritesList() {
        let  data = {};
        data.action = 'filterList';
        data.mode = 'html';
        data.params = [];

        $('.pvp-favorite-block.favorite-list .favorite-items').html('');
        $('.pvp-favorite-block.favorite-list .catalog-loading').show();
        $('.pvp-favorite-block.favorite-list .section-list-item .controls__checkbox').each(function(index, elm) {
            if (elm.checked) {
                data.params.push(elm.dataset.sectionId);
            }
        })

        pvpFavorites.ajaxCall(data, function (data) {
            $('.pvp-favorite-block.favorite-list .catalog-loading').hide();
            $('.pvp-favorite-block.favorite-list .favorite-items').html(data);
        });
    }

    setSelectedCount() {
        var count = 0;
        $('.pvp-favorite-block.favorite-list .favorite-items .catalog-item-info .mass-action .controls__checkbox').each(function(index, elm) {
            if (elm.checked) {
                count++;
            }
        });

        $('.pvp-favorite-block.favorite-list .favorite-items .mass-action.select-action-list .selected-count').html(count);
    }

    setLabelState(checkbox) {
        var label = $('.pvp-favorite-block.favorite-list .controls .controls__label[for="' + checkbox.id + '"]');
        if (checkbox.checked) {
            $(label).addClass('active');
        } else {
            $(label).removeClass('active');
        }
    }

    ajaxCall(data, callback) {
        if (! ('mode' in data)) {
            data.mode = 'json';
        }

        // //fast fix bug wait until needed
        // if ('undefined' === typeof pvpFavoritesParams) {
        //     console.log('params not loaded');
        //     let waitLoad = setTimeout(pvpFavorites.ajaxCall.bind(null, data, callback), 200);
        //
        //     return;
        // }

        data.componentParams = pvpFavoritesParams.componentParams;

        $.post(pvpFavoritesParams.callbackUrl,
            {'data': JSON.stringify(data)},
            callback,
            data.mode
        );
    }
}

BX.ready(function() {
    if ('undefined' === typeof window.pvpFavorites) {
        window.pvpFavorites = new PvpFavorites();
        window.pvpFavorites.init();
    }
});