<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if(empty($arResult))
    return;
?>

<ul class="section-vertical">
    <li>
        <a href="javascript:void(0)" class="showsection"><i class="fa fa-bars"></i><span><?=GetMessage("MENU")?></span></a>
        <div class="catalog-section-list" style="display:none;">
            <div class="catalog-section">
                <div class="catalog-section-title" style="margin:0px 0px 2px 0px;">
                    <a href="/catalog/"><?=GetMessage("MENU_CATALOG")?></a>
                </div>
            </div>
            <?php foreach ($arResult as $menuItem) : ?>
                <?if(1 == $menuItem['DEPTH_LEVEL'] && ! $menuItem['SELECTED']) {?>
                    <div class="catalog-section">
                            <div class="catalog-section-title" style="margin:0px 0px 2px 0px;">
                                <a href="<?=$menuItem['LINK']?>"><?=$menuItem["TEXT"]?></a>
                            </div>
                    </div>
                <?}?>
            <?php endforeach; ?>

            </div>
            </li>
        </ul>


<script type="text/javascript">
    //<![CDATA[
    $(function() {
        $('.showsection').click(function() {
            var clickitem = $(this);
            if(clickitem.parent('li').hasClass('')) {
                clickitem.parent('li').addClass('active');
            } else {
                clickitem.parent('li').removeClass('active');
            }

            if($('.showsubmenu').parent('li').hasClass('active')) {
                $('.showsubmenu').parent('li').removeClass('active');
                $('.showsubmenu').parent('li').find('ul.submenu').css({'display':'none'});
            }

            if($('.showcontacts').parent('li').hasClass('active')) {
                $('.showcontacts').parent('li').removeClass('active');
                $('.header_4').css({'display':'none'});
            }

            if($('.showsearch').parent('li').hasClass('active')) {
                $('.showsearch').parent('li').removeClass('active');
                $('.header_2').css({'display':'none'});
                $('div.title-search-result').css({'display':'none'});
            }

            clickitem.parent('li').find('.catalog-section-list').slideToggle();
        });
        $('.showsectionchild').click(function() {
            var clickitem = $(this);
            if(clickitem.parent('div').hasClass('active')) {
                clickitem.parent('div').removeClass('active');
            } else {
                clickitem.parent('div').addClass('active');
            }
            clickitem.parent('div').parent('div').find('.catalog-section-childs').slideToggle();
        });
    });
    //]]>
</script>