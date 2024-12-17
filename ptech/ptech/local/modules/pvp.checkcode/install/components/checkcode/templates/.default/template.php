<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$frame = $this->createFrame("pvp-checkcode-block")->begin();?>
<div class="pvp-checkcode-block">
    <form method="POST">
        <div class="order-id">
            Заказ # <?=$arResult['ORDER_ID']?>
            <input type="hidden" name="order_id" value="<?=$arResult['ORDER_ID']?>">
        </div>
        <div class="code">
            <input class="code__input <?=empty($arResult['RESULT']['CLASS']) ? '' : $arResult['RESULT']['CLASS'] ?>" type="text" maxlength="4" name="code" value="<?=$arResult['CODE']?>" placeholder="Введите код из SMS" required>
        </div>
        <div class="send">
            <button class="send__button" type="submit">Проверить</button>
        </div>
    </form>
    <?php if ($arResult['RESULT']) : ?>
        <div class="result <?=empty($arResult['RESULT']['CLASS']) ? '' : $arResult['RESULT']['CLASS'] ?>">
            <div class="result-icon"></div>
            <div class="result-message">
                <?=$arResult['RESULT']['MESSAGE']?>
            </div>
        </div>
    <?php endif; ?>
</div>
<?$frame->end();?>