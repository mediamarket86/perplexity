<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$frame = $this->createFrame("pvp-setlements-block")->begin();?>
<div class="pvp-setlements-block">
    <div class="profile-list">
        <form id="pvp-setlements-set-profile" method="post">
            <select id="pvp-setlements-profile-select" name="profileId">
                <?php foreach ($arResult['PROFILES'] as $profile) : ?>
                    <option value="<?=$profile['ID']?>" <?=($profile['ID'] == $arResult['PROFILE_ID']) ? ' selected': ''?>>
                        <?=$profile['NAME']?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
<script>
    BX.ready(function() {
        $('.pvp-setlements-block #pvp-setlements-profile-select').change(function () {
            $('#pvp-setlements-set-profile').submit();
        })
    });
</script>

    <?php
        $usedPercent = 100;
        if ((int)$arResult['PRODUCT_LIMIT'] && (int)$arResult['CURRENT_DEBT']) {
            $usedPercent = (int)$arResult['CURRENT_DEBT'] / ((int)$arResult['PRODUCT_LIMIT'] / 100);
        }
    ?>
    <div class="limit-remaining-graph setlements-block">
        <div class="graph-line">
            <div class="limit-used" style="width: <?=$usedPercent?>%"><?=(int)$arResult['CURRENT_DEBT']?> руб.</div>
        </div>
        <div class="limits">
            <span class="limit-value">0 руб.</span>
            <span class="limit-value"><?=$arResult['PRODUCT_LIMIT']?> руб.</span>
        </div>
    </div>

    <div class="setlements-info">
        <div class="setlements-block info-block product-limit">
            <span class="block-header">Общий товарный лимит</span>
            <span class="block-value"><?=$arResult['PRODUCT_LIMIT']?> руб.</span>
        </div>

        <div class="setlements-block info-block product-limit">
            <span class="block-header">Текущий долг</span>
            <span class="block-value"><?=$arResult['CURRENT_DEBT']?> руб.</span>
        </div>

        <div class="setlements-block info-block product-limit">
            <span class="block-header">Остаток товарного лимита</span>
            <span class="block-value"><?=$arResult['REMAINING_LIMIT']?> руб.</span>
        </div>

        <div class="setlements-block info-block product-limit">
            <span class="block-header">Просроченный долг</span>
            <span class="block-value"><?=$arResult['OVERDUE_DEBT']?> руб.</span>
        </div>
    </div>
</div>
<?$frame->end();?>