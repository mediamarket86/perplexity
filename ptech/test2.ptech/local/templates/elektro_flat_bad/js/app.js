$(document).ready(function () {
	
	//форма "изменить пароль" system.auth.changepasswd (по ссылке из письма)
	$('#btn_change_pswd').click(function(){
		var f = $(this).closest('form');
		var d = f.serializeObject();
		if ( (d.USER_PASSWORD.length>=6) && (d.USER_CONFIRM_PASSWORD.length>=6 ) ) {
			f.submit();
		} else {
			$('#show_preduprezhdenie').show();
		}
		return false;
	});

	//форма "забыли пароль"
	$('#btn_send_forgot').click(function(){
		var f = $(this).closest('form');
		var d = f.serializeObject();
		if ( (d.USER_EMAIL.length>0) || (d.USER_LOGIN.length>0 ) ) {
			/*
			$('#show_preduprezhdenie').hide();
			alert('На ваш емайл выслан пароль');
			window.location.href = '/personal/';
			*/
			f.submit();
		} else {
			$('#show_preduprezhdenie').show();
		}
		return false;
	});

	//подписка в футере - ввод буквы в поле - разрешение кнпоки отправки
	$('.footer .subsc input[name="footer_email"]').keyup(function(){
		var e = $(this).val();
		$('#footer_subs_ok').hide();
		btn = $('#footer_subscribe');
		if (e.length>0) {
			btn.removeClass('grey-btn');
			btn.addClass('orange-btn');
			btn.attr('disabled', false);
		} else {
			btn.removeClass('orange-btn');
			btn.addClass('grey-btn');
			btn.attr('disabled', true);
		}
	});
	
	//подписка в футере - клик по кнопке отправбки
	$('#footer_subscribe').click(function(){
		var email = $('.footer .subsc input[name="footer_email"]').val();
		$('#footer_subs_ok').hide();
		$.ajax({
			type: 'POST',
			url: '/ajax/subscribe_to_email.php',
			data: {'DO_SUBSCRIBE':1, 'EMAIL':email },
			dataType: 'json',
			success: function (data) {
				console.log(data);
				if (data.result == 'OK') {
					//alert(data.msg);
					$('.footer .subsc input[name="footer_email"]').val('');
					$('#footer_subs_ok').css('color','green');
				} else {
					$('#footer_subs_ok').css('color','red');
					//alert(data.msg);
				}
				$('#footer_subs_ok').text(data.msg);
				$('#footer_subs_ok').show();
				var scroll = $('#footer_subs_ok').offset().top;
				$('html,body').stop().animate({ scrollTop: scroll}, 100);
			}
		});
		return false;
	});


	//обновление каптчи при регистрации
	$('.reg_upd_captcha').click(function(){
		$.ajax({
			type: 'POST',
			url: '/ajax/reg_upd_captcha.php',
			data: {'RELOAD_CAPTCHA':1},
			dataType: 'json',
			success: function (data) {
				console.log(data);
				if (data.result == 'OK') {
					//новая captcha 
					$('#reg_upd_capt input[name="captcha_word"]').val('');
					$('#reg_upd_capt input[name="captcha_sid"]').val(data.captcha.new_captcha_code);
					$('#reg_upd_capt img.captcha_img').attr('src', data.captcha.new_img);
				} else {
					alert(data.msg);
				}
			}
		});
		return false;
	});
    

	//добавление отзыва с карточки товара
	$('#add_review').submit(function () {
		var dat = $(this).serializeObject(); //функция из плагина 
		console.log(dat);
		//return false;
		$.ajax({
			type: 'POST',
			url: '/ajax/add_review.php',
			data: dat,
			dataType: 'json',
			success: function (data) {
				console.log(data);
				if (data.result == 'OK') {
					alert('Ваш отзыв добавлен и подлежит модерации.')
				} else {
					alert(data.mes);
				}
				//новая captcha 
				$('#add_review input[name="captcha_word"]').val('');
				$('#add_review input[name="captcha_code"]').val(data.captcha.new_captcha_code);
				$('#add_review img.captcha_img').attr('src', data.captcha.new_img);
				if (data.result == 'OK') {
					window.location.reload();
				}
			}
		});
		return false;
	});

	$('#captcha_refresh').click(function () {
		//return false;
		$.ajax({
			type: 'POST',
			url: '/ajax/add_review.php',
			data: {'RELOAD_CAPTCHA': 1},
			dataType: 'json',
			success: function (data) {
				console.log(data);
				//новая captcha 
				$('#add_review input[name="captcha_word"]').val('');
				$('#add_review input[name="captcha_code"]').val(data.captcha.new_captcha_code);
				$('#add_review img.captcha_img').attr('src', data.captcha.new_img);
			}
		});
		return false;
	});


	// btn "buy 1 click" c карточки товара (1 товар)
	$('.buytoclick.fancybox').fancybox({
		padding: "0",
		helpers: {
			overlay: {
				locked: false
			}
		},
		afterLoad: function (current, previous) {
			console.log($(this));
		}
	});

	$(".tool_pic a").click(function(){
		$("#tocl1").click();
//		return false
	});


	$('.fancybox').fancybox({
		padding: "0",
		helpers: {
			overlay: {
				locked: false
			}
		}
	});


	$('.fancybox_foto').fancybox({
		padding: 20,
		margin: 20,
		wrapCSS: 'superfixnah',
		fitToView	: true,
		autoResize   : true,
		aspectRatio: true,
		helpers: {
			overlay: {
				locked: false
			}
		}
	});


	
	$(".root-item > a, .root-item-selected > a").click(function(){
		$(this).parent().children("ul.root-item").slideToggle();
		return false;
	})



	
	
	//с карточки товара "купить 1клик"
	//$("#prod_order input").blur(function () {
	$("#prod_order input").on('keyup', function () {
		var fio = $("#p_fio").val();
		var phone = $("#p_phone").val();
		var email = $("#p_email").val();
		if (fio != "" && (phone != "" || email != ""))
			$("#p_send").attr("disabled", false)
		else
			$("#p_send").attr("disabled", true)
	})
	$("#prod_order form").submit(function () {
		var num = $('#QUANTITY').val();
		var id = $('#TOV_ID').val();
		$.ajax({
			type: 'POST',
			url: '/ajax/one_click_handler.php',
			data: {
				'is_ajax': 1,
				'product_id': id,
				'num': num,
				'fio': $("#p_fio").val(),
				'email': $("#p_email").val(),
				'phone': $("#p_phone").val()
			},
			dataType: 'json',
			success: function (data) {
				console.log(data);
				if (data.result == 'OK') {
					UpdateTopBottomCartInfo(data.basket.price_formated, data.basket.num);
					//это из футера
					$('#overlay').fadeIn(200, function () {
						$('.added-basket.buy-1-click')
								.css('display', 'block')
								.animate({opacity: 1}, 200);
					});

				} else {
					alert('Возникла ошибка: ' + data.mes);
				}
			}
		});
		$.fancybox.close();
		return false;
	});
	$("#prod_order a.close").click(function () {
		$.fancybox.close();
		return false;
	});




	//с формы обратного звонка
	$('#call_order input[type="text"]').on('keyup', function () {
		var dat = $(this).closest('form').serializeObject(); //функция из плагина
		var btn = $('#call_order_send');
		if (dat.fio != "" && ( dat.phone != ""  || dat.email != "") ) {
			btn.attr('disabled', false);
		} else {
			btn.attr('disabled', true);
		}
	});
	$("#call_order form").submit(function () {
		var dat = $(this).closest('form').serializeObject(); //функция из плагина
		dat.is_ajax=1;
		$.ajax({
			type: 'POST',
			url: '/ajax/zakaz_callback.php',
			data: dat,
			dataType: 'json',
			success: function (data) {
				console.log(data);
				if (data.result == 'OK') {
					alert(data.mes);
				} else {
					alert('Возникла ошибка: ' + data.mes);
				}
			}
		});
		$.fancybox.close();
		return false;
	});


	//из списк по кнопке "ЗАКАЗАТЬ"
	$('a[href=#zakaz_no_price]').click(function(){
		//при клике по ссылке открытия формы
		var pi = $(this).data('id');
		$('input[name="z_product_id"]').val(pi);
		console.log(pi);
	});
	
	//$("#zakaz_no_price input").blur(function () {
	$("#zakaz_no_price input").on('keyup', function () {
		var fio = $("#z_fio").val();
		var phone = $("#z_phone").val();
		var email = $("#z_email").val();
		if (fio != "" && (phone != "" || email != ""))
			$("#z_send").attr("disabled", false)
		else
			$("#z_send").attr("disabled", true)
	})
	$("#zakaz_no_price form").submit(function () {
		var id =$('input[name="z_product_id"]').val();
		console.log(id);
		$.ajax({
			type: 'POST',
			url: '/ajax/zakaz_no_price.php',
			data: {
				'is_ajax': 1,
				'product_id': id,
				'fio': $("#z_fio").val(),
				'email': $("#z_email").val(),
				'phone': $("#z_phone").val()
			},
			dataType: 'json',
			success: function (data) {
				console.log(data);
				if (data.result == 'OK') {
					//UpdateTopBottomCartInfo(data.basket.price_formated, data.basket.num);
					alert(data.mes);
				} else {
					alert('Возникла ошибка: ' + data.mes);
				}
			}
		});
		$.fancybox.close();
		return false;
	});
	$("#zakaz_no_price a.close, #call_order a.close").click(function () {
		$.fancybox.close();
		return false;
	});






	$("#buy-one-click input").keyup(function () {
		var dat = $(this).closest('form').serializeObject(); //функция из плагина
		if (dat.fio != "" && (dat.phone != "" || dat.email != ""))
			$("#basket_buy_one_click").attr("disabled", false)
		else
			$("#basket_buy_one_click").attr("disabled", true)
	})

	//заказ в 1 клик из корзины (все товары)
	$('#basket_buy_one_click').click(function () {
		var dat = $(this).closest('form').serializeObject(); //функция из плагина
		dat.is_ajax = 1;
		$.ajax({
			type: 'POST',
			url: '/ajax/basket_all_one_click.php',
			data: dat,
			dataType: 'json',
			success: function (data) {
				console.log(data);
				if (data.result == 'OK') {
					$('#order_id').html(data.order.id);
					$('#order_num').html(data.order.num + ' шт.');
					$('#order_price').html(data.order.price + ' руб.');
					$('.popup').hide();
					$('#overlay').fadeIn(400,
							function () {
								$('#thank')
										.css('display', 'block')
										.animate({opacity: 1}, 200);
							});
				} else {
					alert('Возникла ошибка: ' + data.mes);
				}
			}
		});
		return false;
	});

	$('#thank_close').click(function () {
		//window.location.href='/personal/order/make/?ORDER_ID='+$('#order_id').html();
		window.location.href = '/catalog/';
	});



	// В переменной input — текстовое поле
	$('#QUANTITY').keyup(function () {
		this.value = this.value.replace(/\D/g, "");
	});

	//с карточки товара клик по кнопке "купить"
	$('#tocart_from_kard').click(function (e) {
		e.preventDefault();
		var num = $('#QUANTITY').val();
		var id = $('#TOV_ID').val();
		add_to_basket(id, num);
		return false;

	});

	//со списков section_list
	$('.tocart.from_fav').on('click', function (e) {
		e.preventDefault();
		var num = 1;
		var id = $(this).data('id');
		add_to_basket(id, num);
		return false;
	});


	function add_to_basket(id, num, v) {
		//показ формы добавления товара в корзину с вводом и - + товаров для добавления в корзину
		
		$.ajax({
			type: 'POST',
			url: '/ajax/add_to_basket.php',
			data: {'is_ajax': 1, 'tov_id': id, 'quantity': num},
			dataType: 'json',
			success: function (data) {
				console.log(data);
				if (data.result == 'OK') {
					UpdateTopBottomCartInfo(data.basket.price_formated, data.basket.num);
					//console.log(window.location.pathname);
					if (window.location.pathname!='/personal/cart/') {
						update_pre_basket_modal_win(data.tovar);
						if (!$('.added-basket.basket').is(":visible")) {
							//это из футера
							$('#overlay').fadeIn(200, function () {
								// $('.added-basket.from_card')
								$('.added-basket.basket')
									.css('display', 'block')
									.animate({opacity: 1}, 200);
							});
						}
					}
				} else {
					alert('Возникла ошибка: ' + data.mes);
				}
			}
		});
	}
	
	
	 $('.set-count .plus').click(function(){
		add_to_basket($('#pre_basket').data('id'), 1);
	 });
	 $('.set-count .minus').click(function(){
		if($(this).parent().find('input').val() <= 1) return;
		add_to_basket($('#pre_basket').data('id'), -1);
	 });
	
	
	function update_pre_basket_modal_win(data) {
		var price = data.discount_price != ""? '<s style="color:red;">'+data.price+'</s><br>'+data.discount_price:data.price;
		$('#pre_basket').data('id',data.id);
		$('#pre_basket img').attr('src', data.image.SRC);
		$('#pre_basket .name').html(data.name );
		$('#pre_basket .index').text(data.kod);
		$('#pre_basket .min_order').text(data.min_buy);
		$('#pre_basket .in_upakovka').text(data.upakovka);
		$('#pre_basket .pre_price').html(price);
		$('#pre_basket input[name="count"]').val(data.num);
		$('#pre_basket .pre_cost').text(data.cost);
	}
	

	//обновление информации о товарах в корзине 
	function UpdateTopBottomCartInfo(price_formated, new_quantity) {
		console.log(price_formated, new_quantity);
		//инфо о корзине снизу
		$('.basketb .summ').text(price_formated);
		$('.basketb a b').text(new_quantity);
		if (!$('.basketb').hasClass('active'))
			$('.basketb').addClass('active');
		//инфо о корзине сверху    
		$('.head_cart .csumm').text(price_formated);
		$('.head_cart .cuqant').text(new_quantity);
		if (!$('.head_cart a').hasClass('active'))
			$('.head_cart a').addClass('active');
		if (!$('.head_cart .cuqant').hasClass('active'))
			$('.head_cart .cuqant').addClass('active');
	}


	var last_smart_search = 'page';
	//$('.basket .smart-search .result').height(0);
	//$('.smart-search .result').height(0);
	$('.smart-search input').focus(function () {
		$('.smart-search ul.result').show();
	});
	$(".smart-search ul.result").bind("clickoutside", function (event) {
		if (!$(".smart-search input").is(":focus"))
			$(this).hide();
	});
	$('.smart-search input').focus(function () {
		var scroll = $(this).closest('.smart-search').offset().top;
		//$('html,body').stop().animate({ scrollTop: scroll}, 500);
		//$('.smart-search .result').height(0);
	});

	//поиск по части названия
	$('.smart-search input').keyup(function () {
		var  itm = $(this).closest('.smart-search');
		var word = $(this).val();
		if (word.length > 0) {
			$.ajax({
				type: 'POST',
				url: '/ajax/search_by_word.php',
				data: {'search_word': word, 'action': 'search'},
				dataType: 'json',
				success: function (data) {
					//console.log(data);
					if (data.result = 'OK') {
						var h = data.items_count * 25;
						$('.smart-search ul.result li').off('click');
						if (data.items_count > 0) {
							//обновление списка
							$(itm).find('ul.result').html(data.items_html);
							if (h > 300)
								h = 300;
							//навешивание обработчиков
							//определение страницы где действо
							//var from = $('.smart-search').data('page');
							var from = $(itm).data('page');
							if (from == 'search') {
																
								//поиск вверху
								$('.smart-search ul.result li').on('click', function () {
									var name = $(this).data('capt');
									name=name.replace(/<.*?>/g, "");

									$(itm).find('input').val(name);
									$(itm).closest('form').submit();
									//$(itm).find('ul.result').height(0); //.hide();
								});
							} else if (from == 'cart') {
								
								//корзина
								//$('.smart-search ul.result li').on('click', function () {
								$(itm).find('ul.result li').on('click', function () {
									var id = $(this).data('id');
									//console.log('id='+id);
									//добавление товара в корзину
									add_to_basket(id, 1);
									window.location.reload();
								});
							} else {
								//сравнение
								$('.smart-search ul.result li').on('click', function () {
									var id = $(this).data('id');
									//console.log('id='+id);
									//добавление товара в список сравнения
									$.get("/ajax/list_compare.php",
											{'action': 'ADD_TO_COMPARE_LIST', 'id': id},
									function (data) {
										//console.log(data);
										window.location.href = '/catalog/compare/';  //reload();
									}
									);
								});
							}
						}
						//$(itm).find('.result').height(h);
						$(itm).find('.result').show();
						//$('.basket .smart-search .result').height(h);

					} else {
						$('.smart-search ul.result').html('<li data-id="0">Возникла ошибка</li>');
						alert('Возникла ошибка');
					}
				}
			});
		}

	});



	//добавить/удалить в сравнение
	$('.tocompare').click(function () {
		var id = $(this).data('id');
		var action = '';
		elm = $(this);
		if (elm.hasClass('active')) {
			action = 'DELETE_FROM_COMPARE_LIST';
		} else {
			action = 'ADD_TO_COMPARE_LIST';
		}

		var AddedGoodId = id;
		$.get("/ajax/list_compare.php",
				{'action': action, 'id': AddedGoodId},
		function (data) {
			//console.log(data);
			if ($(elm).hasClass('active')) {
				$(elm).removeClass('active').text('к сравнению');
				var str = window.location.pathname;
				//если на странице закладок, то удалить этот элемент
				if (str.indexOf('/compare') >= 0) {
					window.location.reload();
				}
			} else {
				$(elm).addClass('active').text('в сравнении');
			}

			$("#compare_list_count").replaceWith(data);
		}
		);
		return false;
	});


	//добавить/удалить в закладки    
	$('.tofavor').click(function () {
		var id = $(this).data('id');
		var action = '';
		elm = $(this);
		if ($(this).hasClass('active')) {
			action = 'remove';
		} else {
			action = 'add';
		}
		$.ajax({
			type: 'POST',
			url: '/ajax/favorites.php',
			data: {'id': id, 'action': action},
			dataType: 'json',
			success: function (data) {
				if (data.result = 'OK') {
					if ($(elm).hasClass('active')) {
						$(elm).removeClass('active').text('в закладки');
						var str = window.location.pathname;
						//если на странице закладок, то удалить этот элемент
						if (str.indexOf('/favorites') >= 0) {
							//console.log('aaa');
							window.location.reload();
							//$(elm).closest('.prod_item').hide(1000).remove();
						}
					} else {
						$(elm).addClass('active').text('в закладках');
					}
					//отображение кол-ва снизу
					$('.favor_count').text(data.num_favorites);
					if (data.num_favorites <= 0) {
						if ($('.favor').hasClass('active'))
							$('.favor').removeClass('active');
					} else {
						if (!$('.favor').hasClass('active'))
							$('.favor').addClass('active');
					}

				} else {
					alert('Возникла ошибка');
				}
			}
		});
		return false;
	});




	$('.tab-links > span, .tab-links .item .link').click(function () {
		var index = $(this).index(),
				tabs = $(this).parents('.tbl').find('.tab');

		if ($(this).hasClass('link')) {
			index = $(this).parent().index();
			$(this).parents('.tab-links').find('.item').removeClass('active');
			$(this).parent().addClass('active');
		} else {
			$(this).parent().find('span').removeClass('active');
			$(this).addClass('active');
		}


		tabs.removeClass('active');
		tabs.eq(index).addClass('active');
	});


	$('.reg-entity .jq-selectbox__dropdown ul li').click(function () {
		var index = $(this).index();
		if (index == 1) {
			$('.reg-entity .tab-first').removeClass('active');
			$('.reg-entity .tab-second').addClass('active');
		} else {
			$('.reg-entity .tab-first').addClass('active');
			$('.reg-entity .tab-second').removeClass('active');
		}
	});

	$('.personal .ord-desk .btn, .personal .ord-desk .preview a, .personal .ord-desk .preview .col-2, .personal .ord-desk .full-desc .title').click(function (e) {
		e.preventDefault();
		if ($(this).parents('.ord-desk').hasClass('is-close')) {
			$(this).parents('.ord-desk').removeClass('is-close');
			$(this).parents('.ord-desk').addClass('is-open');
		} else {
			$(this).parents('.ord-desk').addClass('is-close');
			$(this).parents('.ord-desk').removeClass('is-open');
		}
	});

	$('.sec-tab-links span').click(function () {
		var index = $(this).index(),
				tabs = $(this).parent().parent().find('.sec-tab');
		var prn = $(this).parent();
		console.log(prn);
		if ($(prn).hasClass('pers-orders')) {
			//если это вкладка фильтр по статусу заказов в ЛК
			if ($(this).hasClass('active'))
				return false;
			$('input[name="ORDERS_FILTER"]').val(index + 1);
			$(this).closest('form').submit();
			return false;
		}
		$('.sec-tab-links span').removeClass('active');
		$(this).addClass('active');
		tabs.removeClass('active');
		tabs.eq(index).addClass('active');
	});

	$('.basket .basket-control .one-click, #buy-one-click-auth').click(function (e) {
		//e.preventDefault();
		console.log('быстрей');

		$('#overlay').fadeIn(400,
				function () {
					$('#buy-one-click')
							.css('display', 'block')
							.animate({opacity: 1}, 200);
				});
		return false;
	});

	$('.close, #overlay').click(function () {
		$('.popup, .added-basket')
				.animate({opacity: 0}, 200,
						function () {
							if ($(this).css('display') != 'none') {
								console.log(this.id);
								if (this.id == 'thank')
									$('#thank_close').click();
								//window.location.href='/catalog/';
								$(this).css('display', 'none');
								$('#overlay').fadeOut(400);
							}
						}
				);
	});
	/*
	 //перенесено в шаблон 
	 $('.brands .view').click(function(){
	 if($(this).hasClass('open')) {
	 $(this).removeClass('open');
	 $(this).parents('.brands').find('.add').css('display', 'none');
	 $(this).text('Показать все');
	 } else {
	 $(this).addClass('open');
	 $(this).parents('.brands').find('.add').css('display', 'inline');
	 $(this).text('Популярные');
	 }
	 });
	 
	 $('.view-all-ftr').on('click', function(){
	 if($(this).hasClass('open')) {
	 $(this).removeClass('open');
	 $('.filter .rage').css('display', 'none');
	 $('.filter .protection').css('display', 'none');
	 $(this).find('span').text('все параметры');
	 $(this).find('i').css('background-position', '0 0');
	 } else {
	 $(this).addClass('open');
	 $('.filter .rage').css('display', 'inline-block');
	 $('.filter .protection').css('display', 'inline-block');
	 $(this).find('span').text('Показаны все параметры');
	 $(this).find('i').css('display', 'none');
	 $('.view-all-ftr').off('click')
	 }
	 });
	 */

	$('.registration .address .act').click(function (e) {
		e.preventDefault();

		$(this).parent().css('display', 'none');
		$('.registration .address .ent').css('display', 'block');
	});

	//форма регистрации организации (ИП или Юр.лица)
	$('.confirm').click(function (e) {
		e.preventDefault();
		var block = $(this).parent(),
				labels = block.find('label:not(.optional)');

		for (var i = 0; i < labels.length; i++) {
			if (labels.eq(i).hasClass('checkbox') || labels.eq(i).hasClass('radio')) {
				continue;
			}
			//if(labels.eq(i).find('.error-mess').index() != -1) {  continue; }
			if (labels.eq(i).is(":visible")) {
				if (labels.eq(i).parent().hasClass('err-text')) {
					//поля доставки
					if (!labels.eq(i).find('input').val()) {
						labels.eq(i).addClass('error');
					} else {
						labels.eq(i).removeClass('error');
					}
					continue;
				}

				if (!labels.eq(i).find('input').val()) {
					if (labels.eq(i).find('.error-mess').index() != -1) {
						continue;
					}
					labels.eq(i).prepend('<span class="error-mess">поле обязательно для заполнения</span>');
					labels.eq(i).find('.error-mess').css({'display': 'block', 'height': '0'}).animate({'height': '15px'});
				} else {
					labels.eq(i).find('.error-mess').remove();
				}
			}
		}
		if (labels.find('.error-mess').length == 0) {
			console.log('sended');
			$(this).closest('form').submit();
		}
	});

	//форма регистрации общая
	$('.next').click(function (e) {
		e.preventDefault();

		var block = $(this).parent(),
				labels = block.find('label:not(.optional)');

		for (var i = 0; i < labels.length; i++) {
			if (labels.eq(i).hasClass('checkbox') || labels.eq(i).hasClass('radio')) {
				continue;
			}
			//if(labels.eq(i).find('.error-mess').index() != -1) {  continue; }
			if (labels.eq(i).is(":visible")) {
				if (labels.eq(i).parent().hasClass('err-text')) {
					//поля доставки
					if (!labels.eq(i).find('input').val()) {
						labels.eq(i).addClass('error');
					} else {
						labels.eq(i).removeClass('error');
					}
					continue;
				}

				if (!labels.eq(i).find('input').val()) {
					if (labels.eq(i).find('.error-mess').index() != -1) {
						continue;
					}
					labels.eq(i).prepend('<span class="error-mess">поле обязательно для заполнения</span>');
					labels.eq(i).find('.error-mess').css({'display': 'block', 'height': '0'}).animate({'height': '15px'});
				} else {
					labels.eq(i).find('.error-mess').remove();
				}
			}
		}

		//block.find('.error-mess').css({'display': 'block', 'height': '0'});
		//block.find('.error-mess').animate({'height': '15px'});
		if (this.id == 'send_form_register') {

			var cap = $("#captcha_word").closest('label');
			if (!$("#captcha_word").val()) {
				cap.find('.error-mess').remove();
				cap.prepend('<span class="error-mess">поле обязательно для заполнения</span>');
				cap.find('.error-mess').css({'display': 'block', 'height': '0'}).animate({'height': '15px'});
			} else {
				cap.find('.error-mess').remove();
			}

			var agr = $("#reg_agreement").closest('.agreement');
			if (!$("#reg_agreement").prop("checked")) {
				agr.find('.error-mess').remove();
				agr.prepend('<span class="error-mess">поле обязательно для заполнения</span>');
				agr.find('.error-mess').css({'display': 'block', 'height': '0'}).animate({'height': '15px'});
				return false;
			} else {
				agr.find('.error-mess').remove();
			}

			if ((block.find('.error-mess').length == 0) && (block.find('.error').length == 0)) {
				console.log('sended');
				$('#reg_login').val($('#reg_email').val());
				$(this).closest('form').submit();
			}
		}
	});

	$('#send_form_register').click(function () {
		return false;
	});

	$('.entity, .entity .jq-checkbox').click(function () {
		var block = $(this).parents('.authorization');
		if ($(this).parents('.entity').find('.jq-checkbox').hasClass('checked') ||
				$(this).find('.jq-checkbox').hasClass('checked')) {
			block.find('.inn').css('display', 'block');
			block.find('.kpp').css('display', 'block');
		} else {
			block.find('.inn').css('display', 'none');
			block.find('.kpp').css('display', 'none');
		}

	});

	$('.personal .add-address').click(function () {
		$(this).css('display', 'none');
		$('.personal .address').css('display', 'block');
	});

	$('.personal .address input').on('keyup', function () {
		var dat = $(this).closest('form').serializeObject(); //функция из плагина
		var btn = $('.personal .address button'); //$('#personal_add_adres');
		if (dat.city != "" && dat.street != "" && dat.flat != "" && dat.house != "") {
			btn.removeClass('grey-btn');
			btn.addClass('orange-btn');
			btn.attr('disabled', false);
			//$('.personal .address input').off('keyup');
		} else {
			btn.removeClass('orange-btn');
			btn.addClass('grey-btn');
			btn.attr('disabled', true);
			return false;
		}

	});



	
	
	/*
	 $('.tocart').click(function(e){
	 e.preventDefault();
	 
	 $('#overlay').fadeIn(200,
	 function () {
	 $('.added-basket')
	 .css('display', 'block')
	 .animate({opacity: 1}, 200);
	 });
	 return false;
	 });
	 */

	function setValues(e, elem) {
		var index = $(elem || this).parents('.item').index(),
				values = $('.reg-entity .address .tabs .tab').eq(0).find('input'),
				oldValues = $('.reg-entity .address .tabs .tab').eq(index).find('input');


		for (var i = 0; i < values.length; i++) {
			oldValues.eq(i).val(values.eq(i).val());
		}
	}

	$('.reg-entity .address .matches, .reg-entity .address .matches .jq-checkbox')
			.on('click', setValues);

	$('.reg-entity .address .tabs .tab').eq(0).find('input').focusout(function () {
		var elems = $('.reg-entity .address .jq-checkbox.checked');

		for (var i = 0; i < elems.length; i++) {
			setValues('', elems.eq(i));
		}
	});



	$('.showall a').click(function () {
		$('#vk1 table tr.hiden_row').slideDown(200);
		$(this).hide();
		return false;
	});

	//ОФОРМЛЕНИЕ

	//клик по радио-кнопке (выбор адреса из профайлов - заполнение контактных данных формы из профайла)  
	$('.oform_prof_adrs input[type="radio"]').change(function () {
		if ($(this).is(':checked')) {
			var data = $(this).data();
			$('input[name="profile_id"]').val(data.id);
			$('.set-contacts input[name="fio"]').val(data.fio);
			$('.set-contacts input[name="phone"]').val(data.phone);
			$('.set-contacts input[name="email"]').val(data.email);
		}
	});

	$('.set-shipping .close-add-address').click(function () {
		$('.oform_prof_adrs').show();
		$('.set-shipping .close-add-address').hide();
		$('.set-shipping .set-address').hide();
		$('.set-shipping .add-address').show();
		return false;
	});

	$('.set-shipping .add-address').click(function () {
		//$(this).hide(); //css('display', 'none');
		$('.set-shipping .add-address').hide();
		$('.set-shipping .close-add-address').show();
		$('input[name="profile_id"]').val(0);
		$('.oform_prof_adrs').hide();
		$('.set-shipping .radio .jq-radio').removeClass('checked');
		$('.set-shipping .set-address').show(); //css('display', 'block');
		return false;
	});

	$('.tab-links.dost_samovyvoz span').click(function () {
		var t = $(this).data('is_samovyvoz');
		$('input[name="is_samovyvoz"]').val(t);
		if (t > 0)
			$('input[name="profile_id"]').val(0);
	});

	$('.set-shipping .calculate a').click(function () {
		$('.set-shipping .calculate .result').css('display', 'block');
		return false;
	});


	//форма оформления заказа страница №1
	$('.next-oform1').click(function (e) {
		e.preventDefault();


		$('.errors-place').text('').hide();

		var block = $(this).parent(),
				labels = block.find('label:not(.optional)');

		for (var i = 0; i < labels.length; i++) {
			if (labels.eq(i).hasClass('checkbox') || labels.eq(i).hasClass('radio')) {
				continue;
			}
			//if(labels.eq(i).find('.error-mess').index() != -1) {  continue; }
			if (labels.eq(i).is(":visible")) {
				if (labels.eq(i).parent().hasClass('err-text')) {
					//поля доставки
					if (!labels.eq(i).find('input').val()) {
						labels.eq(i).addClass('error');
					} else {
						labels.eq(i).removeClass('error');
					}
					continue;
				}

				if (!labels.eq(i).find('input').val()) {
					if (labels.eq(i).find('.error-mess').index() != -1) {
						continue;
					}
					labels.eq(i).prepend('<span class="error-mess">поле обязательно для заполнения</span>');
					labels.eq(i).find('.error-mess').css({'display': 'block', 'height': '0'}).animate({'height': '15px'});
				} else {
					labels.eq(i).find('.error-mess').remove();
				}
			}
		}

		console.log('error-mes:' + block.find('.error-mess').length);
		console.log('error:' + block.find('.error').length);


		if ((block.find('.error-mess').length == 0) && (block.find('.error').length == 0)) {
			//если адрес не выбран в списке и не указан в ручную и это не самовывоз
			/*            
			 var is_s = parseInt( $('input[name="is_samovyvoz"]').val() ); 
			 console.log('is_s=',is_s);
			 if (!is_s) {
			 $('.errors-place').text('Выберите адрес доставки из списка, или введите новый').slideDown(200);
			 } else
			 */
			$(this).closest('form').submit();
			console.log('sended');
		}
		return false;
	});

	//клик по кнопке "НАЗАД", на странице подтверждения оформления заказа
	$('.oform_back_to_s1').click(function () {
		$('input[name="CurrentStep"]').val(1);
		$(this).closest('form').submit();
	});

	$('.confirm-oform').click(function () {
		$(this).closest('form').submit();
	});


	//личный кабинет - выбор организации в селекте
	$('select[name="selected_prof_org_id"]').change(function () {
		var pid = $(this).val();
		console.log(pid);
		$('.profile_org').removeClass('active');
		$('.profile_org.profile_' + pid).addClass('active');
	});


});