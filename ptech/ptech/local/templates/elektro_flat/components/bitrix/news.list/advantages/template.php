<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if(count($arResult["ITEMS"]) < 1)
	return;?>
<style>
	.box-download {
	display: flex;
	gap: 20px;
	align-items: start;
	justify-content: space-between;
	margin-top: 10px;
    box-sizing: border-box;
    border-radius: 20px;
	padding: 15px 20px 10px;
		border: 2px solid transparent;
  background: linear-gradient(rgb(255, 255, 255), rgb(255, 255, 255) 0px) padding-box padding-box, linear-gradient(-45deg, #c71848, #fff) border-box border-box;
  animation: 6s rotate ease infinite;
    background-size: 200% 200%;
}
	.app-link {
		width: 30%;
	}
   .app-link img {
		width: 100%;
		max-height: 45px;
	}


@keyframes rotate {
    0% {
        background-position: 0% 50%
    }

    50% {
        background-position: 100% 50%
    }

    to {
        background-position: 0% 50%
    }
}

	@media screen and (max-width: 532px) {
		.box-download {
			flex-wrap: wrap;
	}

</style>
<div class="box-download">
	<p style="font-size:16px; margin-top:0%; color: #575b71; margin-bottom: 0; width: 100%"><b>Установите мобильное приложение<br />Политех-Инструмент уже сейчас!</b>
	</p>
	<div style="display: flex; gap: 30px; width: 100%">
		<a class="app-link" href="https://play.google.com/store/apps/details?id=ru.ptech.ptechmobile" target="_blank"><img src="/upload/medialibrary/qr/buttonGOOGLEPLAY.svg"></a> 
		<a class="app-link" href="https://apps.apple.com/ru/app/политех-инструмент/id6448510804?l=en-GB" target="_blank"><img  src="/upload/medialibrary/qr/buttonAPPSTORE.svg"></a>
		<a class="app-link" href="https://www.rustore.ru/catalog/app/ru.ptech.ptechmobile" target="_blank"><img  src="/upload/medialibrary/qr/buttonRUSTORE.svg"></a>
	</div>
</div>

<div class="advantages">
	<?foreach($arResult["ITEMS"] as $arItem):?>
		<div class="advantages-item">
			<div class="advantages-item-icon-wrap">
				<div class="advantages-item-icon">
                    <?php if (isset($arItem['PREVIEW_PICTURE']['SRC'])) : ?>
                    <img class="advantages-item-icon__img" src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>">
                    <?php endif; ?>
				</div>
			</div>
			<div class="advantages-item-text">
				<?=$arItem['NAME']?>
			</div>
		</div>
	<?endforeach;?>
</div>