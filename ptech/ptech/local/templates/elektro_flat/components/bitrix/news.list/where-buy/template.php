<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<div id="partnerAddressMap" style="width: 100%; height: 400px"></div>
<script>
	const partnerMap = {map: {}};

    ymaps.ready(init);

	function init(){
		partnerMap.map = new ymaps.Map("partnerAddressMap", {
			center: [55.37552376, 37.78764989],
			zoom: 9
		});

		objectManager = new ymaps.ObjectManager({
			clusterize: true,
			gridSize: 32,
			clusterDisableClickZoom: true
		});

		objectManager.objects.options.set('preset', 'islands#redDotIcon');
		objectManager.clusters.options.set('preset', 'islands#redClusterIcons');
		partnerMap.map.geoObjects.add(objectManager);

		objectManager.add({
			"type": "FeatureCollection",
			"features": [
				<?php foreach ($arResult['ITEMS'] as $item) : ?>
				{"type": "Feature", "id": <?=$item['ID']?>, "geometry": {"type": "Point", "coordinates": [<?=$item['JS']['LATITUDE']?>, <?=$item['JS']['LONGITUDE']?>]}, "properties": <?=\CUtil::PhpToJSObject($item['JS']['PROP'])?>},
				<?php endforeach;?>
			]
		});

		// При клике на метке изменяем цвет ее иконки на желтый.
		function onObjectEvent (e) {
			var objectId = e.get('objectId');
            let geoObject =  objectManager.objects.getById(objectId);
			// Если событие произошло на метке, изменяем цвет ее иконки.
			if (geoObject.geometry.type === 'Point') {
				console.log(objectManager.objects.getById(objectId));
			}
		}

// Назначаем обработчик событий на коллекцию объектов менеджера.
		objectManager.objects.events.add(['click'], onObjectEvent);
	}
</script>

<div class="partner-address-list">
	<?php foreach ($arResult['SECTIONS'] as $section) : ?>
		<div class="org-block">
			<div class="short-desc">
				<div class="title">
					<span class="partner-logo">
						<?php if (isset($section['IMG']['SRC'])) :?>
							<img class="partner-logo__img" src="<?=$section['IMG']['SRC']?>">
						<?php endif; ?>
					</span>

					<h2><?=$section['NAME']?></h2>
				</div>
				<div class="stores-count">
					<button class="store-expand-btn" id="store-expand-btn-<?=$section['ID']?>" data-section="<?=$section['ID']?>">
						<span class="title"><?=count($section['ITEMS'])?> магазин<?=NumberWordEndingsEx(count($section['ITEMS']))?></span>
						<span class="arrow down"></span>
					</button>
				</div>
			</div>
			<div class="store-list" id="store-list-<?=$section['ID']?>">
                <div class="store header">
                    <span class="address row">
                        Адрес
                    </span>
                    <span class="contacts row">
                        Контакты
                    </span>
                    <span class="info row">
                        Режим работы
                    </span>
                </div>
				<?php foreach ($section['ITEMS'] as $item) :?>
					<div class="store" id="store-<?=$item['ID']?>">
						<div class="address row">
							<?=$item['NAME']?>
							<a href="#" class="address-map-link" data-longitude="<?=$item['JS']['LONGITUDE']?>" data-latitude="<?=$item['JS']['LATITUDE']?>">Показать на карте</a>
						</div>
						<div class="contacts row">
							<?php if (! empty($item['PROPERTIES']['PHONE']['VALUE'])) : ?>
								<div>Телефон: <b><?=$item['PROPERTIES']['PHONE']['VALUE']?></b></div>
							<?php endif; ?>
							<?php if (! empty($item['PROPERTIES']['EMAIL']['VALUE'])) : ?>
								<div>Email: <b><?=$item['PROPERTIES']['EMAIL']['VALUE']?></b></div>
							<?php endif; ?>
						</div>
						<div class="info row">
							<?=$item['PREVIEW_TEXT']?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endforeach; ?>
</div>