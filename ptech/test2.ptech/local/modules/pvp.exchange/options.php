<?
$module_id = "pvp.exchange";
$POST_RIGHT = $APPLICATION->GetGroupRight($module_id);
if($POST_RIGHT>="R") :
    IncludeModuleLangFile(__FILE__);

    $cipherMethods = [];
    foreach (openssl_get_cipher_methods() as $method) {
        $cipherMethods[$method] = $method;
    }

    $arAllOptions = array(
        array("private_key", GetMessage("PVP_EXCHANGE_OPT_PRIVATE_KEY"), array("textarea", 45, 90)),
        array("public_key", GetMessage("PVP_EXCHANGE_OPT_PUBLIC_KEY"), array("textarea", 15, 90)),
        array("access_ttl", GetMessage("PVP_EXCHANGE_OPT_ACCESS_TTL"), array("text", 50)),
        array("refresh_ttl", GetMessage("PVP_EXCHANGE_OPT_REFRESH_TTL"), array("text", 50)),
        array("cipher_alg", GetMessage("PVP_EXCHANGE_OPT_CHIPHER_ALG"), array("selectbox", $cipherMethods)),
    );
    $aTabs = array(
        array("DIV" => "edit1", "TAB" => GetMessage("PVP_EXCHANGE_TAB_JWT_SETTINGS"), "ICON" => "pvp_excahnge_jwt_settings", "TITLE" => GetMessage("PVP_EXCHANGE_TAB_JWT_TITLE_SET")),
        array("DIV" => "edit2", "TAB" => GetMessage("PVP_EXCHANGE_OPT_ACCESS"), "ICON" => "pvp_excahnge_settings", "TITLE" => GetMessage("MAIN_TAB_TITLE_RIGHTS")),
    );
    $tabControl = new CAdminTabControl("tabControl", $aTabs);

    if($REQUEST_METHOD=="POST" && $Update.$Apply.$RestoreDefaults <> '' && $POST_RIGHT=="W" && check_bitrix_sessid())
    {
        if($RestoreDefaults <> '')
        {
            COption::RemoveOption($module_id);
            $z = CGroup::GetList("id", "asc", array("ACTIVE" => "Y", "ADMIN" => "N"));
            while($zr = $z->Fetch())
                $APPLICATION->DelGroupRight($module_id, array($zr["ID"]));
        }
        else
        {
            foreach($arAllOptions as $arOption)
            {
                $name = $arOption[0];
                if($arOption[2][0]=="srlz-list")
                {
                    $val = ${$name};
                    TrimArr($val);
                    sort($val);
                    $val = serialize($val);
                }
                else if($arOption[2][0]=="text-list")
                {
                    $val = "";
                    $valCount = count(${$name});
                    for($j=0; $j<$valCount; $j++)
                    {
                        if(trim(${$name}[$j]) <> '')
                            $val .= ($val <> ""? ",":"").trim(${$name}[$j]);
                    }
                }
                else
                    $val=${$name};
                if($arOption[2][0] == "checkbox" && $val <> "Y")
                    $val="N";

                COption::SetOptionString($module_id, $name, $val);
            }
        }

        \Bitrix\Sender\Runtime\Job::actualizeAll();

        $Update = $Update.$Apply;
        ob_start();
        require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");
        ob_end_clean();

        if($_REQUEST["back_url_settings"] <> '')
        {
            if(($Apply <> '') || ($RestoreDefaults <> ''))
                LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($module_id)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($_REQUEST["back_url_settings"])."&".$tabControl->ActiveTabParam());
            else
                LocalRedirect($_REQUEST["back_url_settings"]);
        }
        else
        {
            LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($module_id)."&lang=".urlencode(LANGUAGE_ID)."&".$tabControl->ActiveTabParam());
        }
    }

    ?>
    <form method="post" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=urlencode($module_id)?>&amp;lang=<?=LANGUAGE_ID?>">
        <?
        $tabControl->Begin();
        $tabControl->BeginNextTab();

        foreach($arAllOptions as $Option):
            $type = $Option[2];
            $val = COption::GetOptionString($module_id, $Option[0]);
            ?>
            <tr>
                <td width="30%" <?if($type[0]=="textarea" || $type[0]=="text-list") echo 'class="adm-detail-valign-top"'?>>
                    <label for="<?echo htmlspecialcharsbx($Option[0])?>"><?echo $Option[1]?></label>
                <td width="70%">
                    <?
                    if($type[0]=="checkbox"):
                        ?><input type="checkbox" name="<?echo htmlspecialcharsbx($Option[0])?>" id="<?echo htmlspecialcharsbx($Option[0])?>" value="Y"<?if($val=="Y")echo" checked";?>><?
                    elseif($type[0]=="text"):
                        ?><input type="text" size="<?echo $type[1]?>" maxlength="255" value="<?echo htmlspecialcharsbx($val)?>" name="<?echo htmlspecialcharsbx($Option[0])?>"><?
                    elseif($type[0]=="textarea"):
                        ?><textarea rows="<?echo $type[1]?>" cols="<?echo $type[2]?>" name="<?echo htmlspecialcharsbx($Option[0])?>"><?echo htmlspecialcharsbx($val)?></textarea><?
                    elseif($type[0]=="text-list" || $type[0]=="srlz-list"):
                        if ($type[0]=="srlz-list")
                        {
                            $aVal = !empty($val) ? unserialize($val, ['allowed_classes' => false]) : '';
                        }
                        else
                        {
                            $aVal = explode(",", $val);
                        }
                        $aVal = is_array($aVal) ? $aVal : [];

                        sort($aVal);
                        $aValCount = count($aVal);
                        for($j=0; $j<$aValCount; $j++):
                            ?><input type="text" size="<?echo $type[2]?>" value="<?echo htmlspecialcharsbx($aVal[$j])?>" name="<?echo htmlspecialcharsbx($Option[0])."[]"?>"><br><?
                        endfor;
                        for($j=0; $j<$type[1]; $j++):
                            ?><input type="text" size="<?echo $type[2]?>" value="" name="<?echo htmlspecialcharsbx($Option[0])."[]"?>"><br><?
                        endfor;
                    elseif($type[0]=="selectbox"):
                        $arr = $type[1];
                        $arr_keys = array_keys($arr);
                        $alertWarning = '';
                        if(in_array($Option[0], array('auto_method', 'reiterate_method')) && !CheckVersion(SM_VERSION, '15.0.9'))
                            $alertWarning = 'onchange="if(this.value==\'cron\')alert(\''.GetMessage('opt_sender_cron_support').SM_VERSION.'.\');"';
                        ?><select name="<?echo htmlspecialcharsbx($Option[0])?>" <?=$alertWarning?>><?
                        $arr_keys_count = count($arr_keys);
                        for($j=0; $j<$arr_keys_count; $j++):
                            ?><option value="<?echo $arr_keys[$j]?>"<?if($val==$arr_keys[$j])echo" selected"?>><?echo htmlspecialcharsbx($arr[$arr_keys[$j]])?></option><?
                        endfor;
                        ?></select><?
                    endif;
                    ?></td>
            </tr>
        <?endforeach?>
        <?$tabControl->BeginNextTab();?>
        <?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");?>
        <?$tabControl->Buttons();?>
        <input <?if ($POST_RIGHT<"W") echo "disabled" ?> type="submit" name="Update" value="<?=GetMessage("MAIN_SAVE")?>" title="<?=GetMessage("MAIN_OPT_SAVE_TITLE")?>" class="adm-btn-save">
        <?if($_REQUEST["back_url_settings"] <> ''):?>
            <input <?if ($POST_RIGHT<"W") echo "disabled" ?> type="button" name="Cancel" value="<?=GetMessage("MAIN_OPT_CANCEL")?>" title="<?=GetMessage("MAIN_OPT_CANCEL_TITLE")?>" onclick="window.location='<?echo htmlspecialcharsbx(CUtil::addslashes($_REQUEST["back_url_settings"]))?>'">
            <input type="hidden" name="back_url_settings" value="<?=htmlspecialcharsbx($_REQUEST["back_url_settings"])?>">
        <?endif?>
        <input <?if ($POST_RIGHT<"W") echo "disabled" ?> type="submit" name="RestoreDefaults" title="<?echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="return confirm('<?echo AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>')" value="<?echo GetMessage("PVP_EXCHANGE_BTN_RESTORE")?>">
        <?=bitrix_sessid_post();?>
        <?$tabControl->End();?>
    </form>
<?endif;?>
