<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$frame = $this->createFrame("pvp-checkcode-block")->begin();?>
<?php \Bitrix\Main\UI\Extension::load("ui.forms");?>
<div class="pvp-checklog-block">
    <div class="action-block">
        <div class="dates">
            <form method="POST">
                <input type="hidden" name="action" value="filter">
                <span class="label-text">с</span>
                <input class="date-input" type="text" value="<?=$arResult['DATE_FROM']?>" name="date_from" onclick="BX.calendar({node: this, field: this, bTime: false});">
                <span class="label-text">по</span>
                <input class="date-input" type="text" value="<?=$arResult['DATE_TO']?>" name="date_to" onclick="BX.calendar({node: this, field: this, bTime: false});">
                <button class="send-button" type="submit">Применить</button>
            </form>
        </div>
        <div class="export">
            <form method="POST">
                <input type="hidden" name="action" value="export-excel">
                <input type="hidden" name="date_from" value="<?=$arResult['DATE_FROM']?>">
                <input type="hidden" name="date_to" value="<?=$arResult['DATE_TO']?>">
                <button class="export-button" type="submit">Выгрузить в Excel</button>
            </form>
        </div>
    </div>
    <?php if (! empty($arResult['RESULT'])) : ?>
        <div class="result">
            <div class="row header">
                <div class="column date">Дата</div>
                <div class="column order">Заказ</div>
                <div class="column status">Статус</div>
            </div>
            <?php foreach ($arResult['RESULT'] as $row) : ?>
                <div class="row">
                    <div class="column date"><?=$row['DATE_CREATE']->format('d.m.Y H:i:s')?></div>
                    <div class="column order"><?=$row['ORDER_ID']?></div>
                    <div class="column status"><?=$row['RESULT'] ? '+' : '-'?></div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?$frame->end();?>