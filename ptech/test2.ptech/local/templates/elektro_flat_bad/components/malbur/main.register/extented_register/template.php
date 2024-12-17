<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();


?>



<?if($USER->IsAuthorized()):?>

<p><?echo GetMessage("MAIN_REGISTER_AUTH")?></p>

<?else:?>
	<div class="register_user">
		<?
		if (count($arResult["ERRORS"]) > 0):
			foreach ($arResult["ERRORS"] as $key => $error)
				if (intval($key) == 0 && $key !== 0)
					$arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;".GetMessage("REGISTER_FIELD_".$key)."&quot;", $error);

			ShowError(implode("<br />", $arResult["ERRORS"]));

		elseif($arResult["USE_EMAIL_CONFIRMATION"] === "Y"):
			?>
			<p><?echo GetMessage("REGISTER_EMAIL_WILL_BE_SENT")?></p>
		<?endif?>
		<div class="us_dan">Данные пользователя</div>
		<form method="post" action="<?=POST_FORM_ACTION_URI?>">
			
			
			<input type="hidden" name="UF_IP" value="<?=$_SERVER['HTTP_X_REAL_IP']?>">
			<input type="hidden" name="REGISTER[LOGIN]" value="<?=$arResult["VALUES"]['LOGIN']?>">
			<?if($arResult["BACKURL"] <> ''):?>
				<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
			<?endif;?>
			<div class="user_field">
				<div class="labform">e-mail: <b>*</b></div>
				<input size="30" type="text" name="REGISTER[<?='EMAIL'?>]" value="<?=$arResult["VALUES"]['EMAIL']?>" autocomplete="off"  />
				<div class="inptext">e-mail должен быть реально существующим! На этот адрес будет отправлено письмо о подтверждении регистрации.</div>
			</div>
			<div class="user_field">
				<div class="labform">Телефон: <b>*</b></div>
				<input size="30" type="text" name="REGISTER[<?='PERSONAL_PHONE'?>]" value="<?=$_REQUEST["REGISTER"]['PERSONAL_PHONE']?>" autocomplete="off"  />
			</div>
			<div class="user_field">
				<div class="labform">Пароль: <b>*</b></div>
				<input size="30" type="password" name="REGISTER[<?='PASSWORD'?>]" value="<?=$arResult["VALUES"]['PASSWORD']?>" autocomplete="off"  />
			</div>
			<div class="user_field">
				<div class="labform">Повторите пароль: <b>*</b></div>
				<input size="30" type="password" name="REGISTER[<?='CONFIRM_PASSWORD'?>]" value="<?=$arResult["VALUES"]['CONFIRM_PASSWORD']?>" autocomplete="off" />
			</div>

			<div class="separate"></div>

			<div class="infocomp2">Информация о компании</div>
			<div class="user_field">
				<div class="labform">Название: <b>*</b></div>
				<input type="text" name="COMPANY_FIELDS[COMPANY_NAME]" value="<?=$_REQUEST['COMPANY_FIELDS']['COMPANY_NAME']?>"/>
			</div>
			<div class="user_field">
				<div class="labform">Город: <b>*</b></div>
				<input type="text" name="COMPANY_FIELDS[COMPANY_CITY]" value="<?=$_REQUEST['COMPANY_FIELDS']['COMPANY_CITY']?>"/>
			</div>
			<div class="user_field">
				<div class="labform">ИНН организации: <b>*</b></div>
				<input type="text" name="COMPANY_FIELDS[COMPANY_INN]" value="<?=$_REQUEST['COMPANY_FIELDS']['COMPANY_INN']?>"/>
			</div>
			<div class="user_field">
				<div class="labform">Контактное лицо: <b>*</b></div>
				<input type="text" name="COMPANY_FIELDS[COMPANY_PERSON]" value="<?=$_REQUEST['COMPANY_FIELDS']['COMPANY_PERSON']?>"/>
			</div>

			<div class="separate"></div>

			<div class="user_field wd1">
				<div class="labform">Являетесь ли Вы <br /> клиентом <br /> «Политех-Инструмент»?: <b>*</b></div>
				<select name="ADD_NEW_EXTENDED_USER" id="select_client">
					<option value="Y">нет </option>
					<option value="N" <?=$_REQUEST['ADD_NEW_EXTENDED_USER'] == 'N' ? 'selected="true"':''?>>да</option>
				</select>
				<div class="inptext">Если Да, - карточку клиента  <br />заполнять не нужно</div>
			</div>
			<div class="clear"></div>
			<div class="separate"></div>

			<div id="kart_klient">
				<div class="infocomp2">Карточка клиента</div>
	
				<div class="user_field wd2">
					<div class="labform">Зарегистрирован как: </div>
					<select name="PROFILE_TYPE" id="typ_sobstv">
						<option value="1">Юридическое лицо </option>
						<option value="2">Индивидуальный предприниматель</option>
					</select>
				</div>
	
				<div id="type_sobst1">
					<div class="infocomp mrleft">Данные об организации</div>
					<div class="user_field width2">
						<div class="labform">Орг. форма: </div>
						<input type="text" name="CLIENT_CARD[ORG_FORM]" value="<?=$_REQUEST['CLIENT_CARD']['ORG_FORM']?>" />
					</div>
					<div class="user_field width2">
						<div class="labform">Наименование:</div>
						<input type="text" name="CLIENT_CARD[ORG_NAME]" value="<?=$_REQUEST['CLIENT_CARD']['ORG_NAME']?>" />
					</div>
					<div class="user_field width2">
						<div class="labform">Город:</div>
						<input type="text" name="CLIENT_CARD[ORG_CITY]" value="<?=$_REQUEST['CLIENT_CARD']['ORG_CITY']?>" />
					</div>
				</div>
				<div id="type_sobst2" style="display: none; padding: 10px 0 0 0">
					<div class="user_field width2">
						<div class="labform">Фамилия: </div>
						<input type="text" name="CLIENT_CARD[IP_LAST_NAME]" value="<?=$_REQUEST['CLIENT_CARD']['IP_LAST_NAME']?>" />
					</div>
					<div class="user_field width2">
						<div class="labform">Имя: </div>
						<input type="text" name="CLIENT_CARD[IP_NAME]" value="<?=$_REQUEST['CLIENT_CARD']['IP_NAME']?>" />
					</div>
					<div class="user_field width2">
						<div class="labform">Отчество: </div>
						<input type="text" name="CLIENT_CARD[IP_MIDDLE_NAME]" value="<?=$_REQUEST['CLIENT_CARD']['IP_MIDDLE_NAME']?>" />
					</div>
					<div class="user_field width2">
						<div class="labform">Город: </div>
							<input type="text" name="CLIENT_CARD[IP_CITY]" value="<?=$_REQUEST['CLIENT_CARD']['IP_CITY']?>" />
					</div>
				</div>
	
				<table class="regadd">
					<tr class="thead">
						<td></td>
						<td><p>Юридический адрес (ЕГРЮЛ)</p>Для ИП - адрес прописки <br /> (Плательщик)</td>
						<td><p>Почтовый адрес</p> (Если отличается от юридического)</td>
						<td><p>Фактический адрес</p> (Грузополучатель)</td>
					</tr>
					<tr>
						<td>Индекс</td>
						<td><input name="CLIENT_CARD[UR_INDEX]" value="<?=$_REQUEST['CLIENT_CARD']['UR_INDEX']?>" type="text"/></td>
						<td><input name="CLIENT_CARD[POST_INDEX]" value="<?=$_REQUEST['CLIENT_CARD']['POST_INDEX']?>" type="text"/></td>
						<td><input name="CLIENT_CARD[REAL_INDEX]" value="<?=$_REQUEST['CLIENT_CARD']['REAL_INDEX']?>" type="text"/></td>
					</tr>
					<tr>
						<td>Регион</td>
						<td><input name="CLIENT_CARD[UR_REGION]" value="<?=$_REQUEST['CLIENT_CARD']['UR_REGION']?>" type="text"/></td>
						<td><input name="CLIENT_CARD[POST_REGION]" value="<?=$_REQUEST['CLIENT_CARD']['POST_REGION']?>" type="text"/></td>
						<td><input name="CLIENT_CARD[REAL_REGION]" value="<?=$_REQUEST['CLIENT_CARD']['REAL_REGION']?>" type="text"/></td>
					</tr>
					<tr>
						<td>Район</td>
						<td><input name="CLIENT_CARD[UR_DISTRICT]" value="<?=$_REQUEST['CLIENT_CARD']['UR_DISTRICT']?>" type="text"/></td>
						<td><input name="CLIENT_CARD[POST_DISTRICT]" value="<?=$_REQUEST['CLIENT_CARD']['POST_DISTRICT']?>" type="text"/></td>
						<td><input name="CLIENT_CARD[REAL_DISTRICT]" value="<?=$_REQUEST['CLIENT_CARD']['REAL_DISTRICT']?>" type="text"/></td>
					</tr>
					<tr>
						<td>Город</td>
						<td><input name="CLIENT_CARD[UR_CITY]" value="<?=$_REQUEST['CLIENT_CARD']['UR_CITY']?>" type="text"/></td>
						<td><input name="CLIENT_CARD[POST_CITY]" value="<?=$_REQUEST['CLIENT_CARD']['POST_CITY']?>" type="text"/></td>
						<td><input name="CLIENT_CARD[REAL_CITY]" value="<?=$_REQUEST['CLIENT_CARD']['REAL_CITY']?>" type="text"/></td>
					</tr>
					<tr>
						<td>Улица</td>
						<td><input name="CLIENT_CARD[UR_STREET]" value="<?=$_REQUEST['CLIENT_CARD']['UR_STREET']?>" type="text"/></td>
						<td><input name="CLIENT_CARD[POST_STREET]" value="<?=$_REQUEST['CLIENT_CARD']['POST_STREET']?>" type="text"/></td>
						<td><input name="CLIENT_CARD[REAL_STREET]" value="<?=$_REQUEST['CLIENT_CARD']['REAL_STREET']?>" type="text"/></td>
					</tr>
					<tr>
						<td>Дом</td>
						<td><input name="CLIENT_CARD[UR_HOUSE]" value="<?=$_REQUEST['CLIENT_CARD']['UR_HOUSE']?>" type="text"/></td>
						<td><input name="CLIENT_CARD[POST_HOUSE]" value="<?=$_REQUEST['CLIENT_CARD']['POST_HOUSE']?>" type="text"/></td>
						<td><input name="CLIENT_CARD[REAL_HOUSE]" value="<?=$_REQUEST['CLIENT_CARD']['REAL_HOUSE']?>" type="text"/></td>
					</tr>
					<tr>
						<td>Корпус</td>
						<td><input name="CLIENT_CARD[UR_CORPUS]" value="<?=$_REQUEST['CLIENT_CARD']['UR_CORPUS']?>" type="text"/></td>
						<td><input name="CLIENT_CARD[POST_CORPUS]" value="<?=$_REQUEST['CLIENT_CARD']['POST_CORPUS']?>" type="text"/></td>
						<td><input name="CLIENT_CARD[REAL_CORPUS]" value="<?=$_REQUEST['CLIENT_CARD']['REAL_CORPUS']?>" type="text"/></td>
					</tr>
					<tr>
						<td>Квартира</td>
						<td><input name="CLIENT_CARD[UR_ROOM]" value="<?=$_REQUEST['CLIENT_CARD']['UR_ROOM']?>" type="text"/></td>
						<td><input name="CLIENT_CARD[POST_ROOM]" value="<?=$_REQUEST['CLIENT_CARD']['POST_ROOM']?>" type="text"/></td>
						<td><input name="CLIENT_CARD[REAL_ROOM]" value="<?=$_REQUEST['CLIENT_CARD']['REAL_ROOM']?>" type="text"/></td>
					</tr>
				</table>
	
				<div class="infocomp mrleft2">Контактные данные руководителя</div>
				<div class="user_field width3">
					<div class="labform">Должность:</div>
					<input name="HEAD_CONTACTS[STATUS]" value="<?=$_REQUEST['HEAD_CONTACTS']['STATUS']?>" type="text"/>
				</div>
				<div class="user_field width3">
					<div class="labform">Фамилия:</div>
					<input name="HEAD_CONTACTS[LAST_NAME]" value="<?=$_REQUEST['HEAD_CONTACTS']['LAST_NAME']?>" type="text"/>
				</div>
				<div class="user_field width3">
					<div class="labform">Имя:</div>
					<input name="HEAD_CONTACTS[NAME]" value="<?=$_REQUEST['HEAD_CONTACTS']['NAME']?>" type="text"/>
				</div>
				<div class="user_field width3">
					<div class="labform">Отчество:</div>
					<input name="HEAD_CONTACTS[MIDDLE_NAME]" value="<?=$_REQUEST['HEAD_CONTACTS']['MIDDLE_NAME']?>" type="text"/>
				</div>
				<div class="user_field width4">
					<div class="labform">Мобильный телефон:</div>
					<span>код (без 8)</span>
					<input name="HEAD_CONTACTS[MOBILE_CODE]" value="<?=$_REQUEST['HEAD_CONTACTS']['MOBILE_CODE']?>" type="text"/>
					<span>номер</span>
					<input name="HEAD_CONTACTS[MOBILE_PHONE]" value="<?=$_REQUEST['HEAD_CONTACTS']['MOBILE_PHONE']?>" type="text" class="wdd4"/>
				</div>
				<div class="user_field width4">
					<div class="labform">Дополнительный:</div>
					<span>код (без 8)</span>
					<input type="text" name="HEAD_CONTACTS[DOP_MOBILE_CODE]" value="<?=$_REQUEST['HEAD_CONTACTS']['DOP_MOBILE_CODE']?>" />
					<span>номер</span>
					<input name="HEAD_CONTACTS[DOP_MOBILE_PHONE]" value="<?=$_REQUEST['HEAD_CONTACTS']['DOP_MOBILE_PHONE']?>" type="text" class="wdd4"/>
				</div>
				<div class="user_field width4">
					<div class="labform">Рабочий телефон:</div>
					<span>код (без 8)</span>
					<input type="text" name="HEAD_CONTACTS[WORK_CODE]" value="<?=$_REQUEST['HEAD_CONTACTS']['WORK_CODE']?>" />
					<span>номер</span>
					<input type="text" name="HEAD_CONTACTS[WORK_PHONE]" value="<?=$_REQUEST['HEAD_CONTACTS']['WORK_PHONE']?>" class="wdd4"/>
				</div>
				<div class="user_field width4">
					<div class="labform">Дополнительный:</div>
					<span>код (без 8)</span>
					<input type="text" name="HEAD_CONTACTS[DOP_WORK_CODE]" value="<?=$_REQUEST['HEAD_CONTACTS']['DOP_WORK_CODE']?>" />
					<span>номер</span>
					<input type="text" name="HEAD_CONTACTS[DOP_WORK_PHONE]" value="<?=$_REQUEST['HEAD_CONTACTS']['DOP_WORK_PHONE']?>" class="wdd4"/>
				</div>
				<div class="separate"></div>
			</div>
			<div class="user_field width5">
				<div class="labform">Введите символы с картинки: <b>*</b></div>
				<input id="captchaSid" type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
				<img id="captchaImg" src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
				<input type="text" name="captcha_word" maxlength="50" value=""/>
				<div class="clear"></div>
				<a id="reloadCaptcha" href="#">Обновить</a>
			</div>
			<input type="submit" name="register_submit_button" value="<?=GetMessage("AUTH_REGISTER")?>" />
			<div class="redcaps"><b>*</b> - поля обязательные для заполнения</div>
		</form>
	</div>

<?endif;?>