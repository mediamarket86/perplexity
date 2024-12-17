BX.ready(function() {
    pvpPropertySearch = new PvpPropertySearch();
    pvpPropertySearch.init();
});

class PvpPropertySearch {
    init() {
        let body = $('body');

        body.on('click', '.pvp-search-property .search-block .search-result-wrap .results-close .results-close__link', function (event) {
            event.stopPropagation();
            event.preventDefault();

            $('.pvp-search-property .search-block').hide();
        });

        body.on('input', '.pvp-search-property .search-form .search-form__input', BX.proxy(this.find, this));

        //enter keystroke on card
        body.on('keydown', '.pvp-search-property .search-form .search-form__input', BX.proxy(this.goToCardAction, this));

        // arrow keystroke on card
        body.on('keydown', '.pvp-search-property .search-result .catalog-item-cards .catalog-item-card.fastOrderActive', BX.proxy(this.arrowAction, this));
        //enter keystroke in active block
        body.on('keydown', '.pvp-search-property .search-result .catalog-item-cards .catalog-item-card.fastOrderActive input.quantity', BX.proxy(this.addToCart, this));
    }

    addToCart(event) {
        if (13 == event.keyCode) {
            event.preventDefault();
            event.stopPropagation();

            $('.pvp-search-property .search-result .catalog-item-cards .catalog-item-card.fastOrderActive').find('button.btn_buy').click();
        }
    }

    goToCardAction(event) {
        let searchInput = $('.pvp-search-property .search-form .search-form__input');
        let cardBlock = $('.pvp-search-property .search-block .search-result .catalog-item-cards');

        if (4 > searchInput.val().length || 13 != event.keyCode || 0 == cardBlock.children().length) {
            return;
        }

        event.stopPropagation();
        event.preventDefault();

        let firstCard = cardBlock.children()[0];

        this.selectBlock(firstCard);
    }
    arrowAction(event) {
        let keyCodes = [37, 38, 39, 40]; //arrow key codes
        if (! keyCodes.includes(event.keyCode)) {
            return;
        }
        event.preventDefault();
        event.stopPropagation();

        let elm = event.currentTarget;

        switch (event.keyCode) {
            case 37:
                let prevElm = $(elm).prev();

                if (prevElm.length) {
                    this.selectBlock(prevElm[0]);
                }
                break;
            case 38:
                this.upDownArrowAction(elm, 'up');
                break;
            case 39:
                let nextElm = $(elm).next();

                if (nextElm.length) {
                    this.selectBlock(nextElm[0]);
                }
                break;
            case 40:
                this.upDownArrowAction(elm, 'down');
                break;
        }
    }

    upDownArrowAction(block, direction) {
        let cards = $('.pvp-search-property .catalog-item-cards .catalog-item-card');
        let position = $.inArray(block, cards);
        let newPosition = -1;

        if ('down' == direction) {
            newPosition = position + 4;
        } else {
            newPosition = position - 4;
        }

        if (newPosition > -1 && cards.length > newPosition) {
            this.selectBlock(cards[newPosition]);
        }
    }

    selectBlock(block) {
        $('.pvp-search-property .catalog-item-cards .catalog-item-card.fastOrderActive').removeClass('fastOrderActive');
        $(block).addClass('fastOrderActive');
        $(block).find('.add2basket_form .quantity').focus();
        block.scrollIntoView(true);
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