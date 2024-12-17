class WhereBuyPage
{
    speed = 400;

    init() {
        let body = $('body');

        body.on(
            'click',
            '.partner-address-list .org-block .store-list .store .address .address-map-link',
            BX.proxy(this.showOnMapBtnClick, this)
        );

        body.on(
            'click',
            '.partner-address-list .org-block .short-desc .stores-count .store-expand-btn',
            BX.proxy(this.storeExpandBtnClick, this)
        );
    }

    showOnMapBtnClick(event) {
        event.stopPropagation();
        event.preventDefault();

        let elm = event.currentTarget;
        document.getElementById("partnerAddressMap").scrollIntoView(false);
        partnerMap.map.setCenter([elm.dataset.latitude, elm.dataset.longitude], 15);
    }

    storeExpandBtnClick(event) {
        event.stopPropagation();
        event.preventDefault();

        let elm = event.currentTarget;
        let sectionId = elm.dataset.section;

        if (this.getExpandArrow(sectionId).hasClass('up')) {
            window.whereBuyPage.hide(sectionId)
        } else {
            window.whereBuyPage.show(sectionId)
        }
    }

    hide(sectionId) {
        let storeList = $('#store-list-' + sectionId);

        $(storeList).slideUp(this.speed);
        this.getExpandArrow(sectionId).removeClass('up');
    }

    show(sectionId) {
        let storeList = $('#store-list-' + sectionId);

        $('.partner-address-list .org-block .store-list').slideUp(this.speed);
        $('.partner-address-list .org-block .short-desc .stores-count .store-expand-btn .arrow').removeClass('up');
        $(storeList).slideDown(this.speed);
        this.getExpandArrow(sectionId).addClass('up');
    }

    getExpandArrow(sectionId) {
        return $('#store-expand-btn-' + sectionId + ' .arrow');
    }

    goToStoreList(sectionId) {
        let storeBlock = $('#store-list-' + sectionId).parent()

        if (0 >= storeBlock.length) {
            return;
        }

        storeBlock[0].scrollIntoView(true);

        this.show(sectionId);
    }
}

BX.ready(function () {
    window.whereBuyPage = new WhereBuyPage();
    whereBuyPage.init();
});