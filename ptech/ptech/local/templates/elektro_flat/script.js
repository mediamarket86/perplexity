function openFormCallback() {
	var action = "callback",
		visualId = "callback_" + BX.message("SITE_ID");
	BX.PopupForm =
	{						
		arParams: {}
	};
	BX.PopupForm.popup = BX.PopupWindowManager.create(visualId, null, {
		autoHide: true,
		offsetLeft: 0,
		offsetTop: 0,			
		overlay: {
			opacity: 100
		},
		draggable: false,
		closeByEsc: false,
		className: "pop-up forms short",
		closeIcon: { right : "-10px", top : "-10px"},			
		titleBar: true,
		content: "<div class='popup-window-wait'><i class='fa fa-spinner fa-pulse'></i></div>",			
		events: {
			onAfterPopupShow: function()
			{
				if(!BX(visualId + "_form")) {
					BX.ajax.post(
						BX.message("SITE_DIR") + "ajax/popup.php",
						{							
							sessid: BX.bitrix_sessid(),
							action: action
						},
						BX.delegate(function(result)
						{
							this.setContent(result);
							var windowSize =  BX.GetWindowInnerSize(),
							windowScroll = BX.GetWindowScrollPos(),
							popupHeight = BX(visualId).offsetHeight;
							BX(visualId).style.top = windowSize.innerHeight/2 - popupHeight/2 + windowScroll.scrollTop + "px";
						},
						this)
					);
				}					
			}
		}			
	});
	
	var close = BX.findChild(BX(visualId), {className: "popup-window-close-icon"}, true, false);
	if(!!close)
		close.innerHTML = "<i class='fa fa-times'></i>";

	BX.PopupForm.popup.show();
}

function adjustItemHeight(item) {
	var maxHeight = 0;
	item.css("height", "auto");
	item.each(function() {
		if($(this).height() > maxHeight) {
			maxHeight = $(this).height();
		}
	});
	item.height(maxHeight);
}

$(function(){
	$(".close").on("click", function() {
		CloseModalWindow("#addItemInCart")
	});
	$(document).keyup(function(event){
		if(event.keyCode == 27) {
			CloseModalWindow("#addItemInCart")
		}
	});
});

function CentriredModalWindow(ModalName){
	$(window).resize(function () {
		modalHeight = ($(window).height() - $(ModalName).height()) / 2;
		$(ModalName).css({
			'top': modalHeight + 'px'
		});
	});
	$(window).resize();
}

function OpenModalWindow(ModalName){
	$(ModalName).fadeIn(300);	
	$("#bgmod").fadeIn(300);
}

function CloseModalWindow(ModalName){
	$("#bgmod").fadeOut(300);
	$(ModalName).fadeOut(300);	
}

function refreshCartLine(result, disabled) {
	disabled = disabled || false;	

	var basketCont, sumOld, sumCurr;
	
	basketCont = $(".cart_line");
	
	basketCont.find(".qnt").text($(result).find(".qnt").text());
	
	basketCont.find(".sum").data("decimal", $(result).find(".sum").data("decimal"));
	
	sumOld = basketCont.find(".sum").data("sum");						
	basketCont.find(".sum").data("sum", $(result).find(".sum").data("sum"));
	sumCurr = basketCont.find(".sum").data("sum");
	
	if(sumCurr != sumOld) {
		var options = {
			useEasing: false,
			useGrouping: true,
			separator: basketCont.find(".sum").data("separator"),
			decimal: basketCont.find(".sum").data("dec-point")
		}
		var counter = new countUp("cartCounter", sumOld, sumCurr, basketCont.find(".sum").data("decimal"), 0.5, options);
		counter.start();
	}
	
	if(disabled != true)
		basketCont.find(".oformit_cont").html($(result).find(".oformit_cont").html());	
}

function addToCompare(href, btn, site_dir) {
	$.ajax({
		type: "POST",
		url: href,
		success: function(html){			
			$.post(site_dir + "ajax/compare_line.php", function(data) {
				$(".compare_line").replaceWith(data);
			});
			$("#" + btn).removeClass("catalog-item-compare").addClass("catalog-item-compared").removeAttr("onclick").css({"cursor": "default"});
		}
	});
	return false;
}

function addToDelay(id, qnt_cont, props, select_props, btn, site_dir, dsbl) {
	dsbl = dsbl || false;
	$.ajax({
		type: "POST",
		url: site_dir + "ajax/add2delay.php",
		data: "id=" + id + "&qnt=" + $("#" + qnt_cont).val() + "&props=" + props + "&select_props=" +select_props,
		success: function(html){
			$.post(site_dir + "ajax/delay_line.php", function(data) {
				$(".delay_line").replaceWith(data);
			});
			$.post(site_dir + "ajax/basket_line.php", function(data) {
				refreshCartLine(data, dsbl);				
			});			
			$("#" + btn).removeClass("catalog-item-delay").addClass("catalog-item-delayed").removeAttr("onclick").css({"cursor": "default"});
		}
	});
	return false;
}

function urlInit() {
	var data = {};
	if(location.search) {
		var paramsUrl = (location.search.substr(1)).split('&');
		for(var i = 0; i < paramsUrl.length; i ++) {
			var param = paramsUrl[i].split('=');
			data[param[0]] = param[1];
		}
	}
	return data;
}

function openbtn() {
    var btnId = BX("btnOformitAction");
    if(btnId!=null) {
        var duration = 0.3,
            delay = 0.08;
        $("#btnOformitAction").addClass("btnOformit");
        TweenMax.to(btnId, duration, {scaleY: 1.6, ease: Expo.easeOut});
        TweenMax.to(btnId, duration, {scaleX: 1.2, scaleY: 1, ease: Back.easeOut, easeParams: [3], delay: delay});
        TweenMax.to(btnId, duration * 1.25, {
            scaleX: 1,
            scaleY: 1,
            ease: Back.easeOut,
            easeParams: [6],
            delay: delay * 3
        });
    }

}


const backShadow = {
	shadow: $('.mobile-body.mobile-catalog.back-shadow'),

	show: function () {
		$(this.shadow).addClass('active');
	},

	hide: function() {
		$(this.shadow).removeClass('active');
	},

	init: function () {
		let body = $('body');
		body.on('click', this.shadow.selector, function () {
			$('.active .back-shadow__link').click();
			backShadow.hide()
		});

		body.on('click', '.top_panel .panel_3 ul.contacts-vertical li a.showcontacts', function() {
			backShadow.hide();
		});
		body.on('click', '.top_panel .panel_4 ul.search-vertical li a.showsearch', function() {
			backShadow.hide();
		});
	},
}

backShadow.init();

/* SCROLLABLE ANIMATION */
function scrollableCounterElementCount(elm) {
	let current = parseInt(elm.dataset.current);
	let end = parseInt(elm.dataset.end);

	if (current < end) {
		let step = 1;

		if (typeof elm.dataset.step !== 'undefined') {
			step = parseInt(elm.dataset.step);
		}

		//Эффект быстрого просчета???
		for (let i = 1; i <= step; i++) {
			current++;

			if (current <= end) {
				elm.dataset.current = current;
				$(elm).html(elm.dataset.current);
			}
		}

		let speed = parseInt(elm.dataset.speed);

		if (speed < 1) {
			speed = 1;
		}

		setTimeout(scrollableCounterElementCount.bind(null, elm), speed)
	}
}

BX.ready(function () {
	$('.scrollable-counter-element').each(function () {
		let options = {
			root: null,
			threshold: 0.5,
		}

		let callback = function(entries, observer) {
			for (let i = 0; i < entries.length; i++) {
				if (entries[i].isIntersecting) {
					scrollableCounterElementCount(entries[i].target);
					observer.unobserve(entries[i].target)
				}
			}
		}

		let observer = new IntersectionObserver(callback, options);

		observer.observe(this)
	});
})
/* ------------------------------------------------------------------------------ */
