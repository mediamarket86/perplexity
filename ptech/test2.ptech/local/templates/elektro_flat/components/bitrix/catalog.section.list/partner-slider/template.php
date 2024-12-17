<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arResult */
$this->setFrameMode(true);

if(count($arResult["SECTIONS"]) < 1)
	return;
?>
<div class="partner-slider-wrap pt-slider">
    <div class="homepage-header">
        <h2>Партнеры по всей России</h2>

        <div class="more-data">
            <a href="/kupit/" class="more-data__link">смотреть все</a>
        </div>
    </div>

    <div class="partner-slider">
        <?php foreach($arResult["SECTIONS"] as $arSection) : ?>
            <?php if (! empty($arSection['PICTURE']['SRC'])): ?>
                <a class="slider-link" href="/kupit/?partnerId=<?=$arSection['ID']?>" title="<?=$arSection['NAME']?>">
                    <img class="slider-link__img" src="<?=$arSection['PICTURE']['SRC']?>">
                </a>
            <?php endif; ?>
        <?php endforeach;?>
    </div>
    <script>
        BX.ready(function () {
            $('.partner-slider').slick({
                slidesToShow: 3,
                slidesToScroll: 1,
                infinite: false,
                variableWidth: true,
                prevArrow: '<a href="javascript:void(0)" class="button back"></a>',
                nextArrow: '<a href="javascript:void(0)" class="button forward"></a>',
                responsive: [
                    {
                        breakpoint: 787,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 580,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        });
    </script>
</div>
