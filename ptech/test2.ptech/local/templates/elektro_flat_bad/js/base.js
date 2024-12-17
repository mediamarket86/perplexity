$(document).ready(function(){

	$("[name='phone'], .phone input, [name='REGISTER[PERSONAL_PHONE]']").mask("+7 (999) 999-99-99");
		
	$("[name='REGISTER[EMAIL]']").blur(function(){
		var kk = $(this).val();
		$("[name='REGISTER[LOGIN]']").val(kk);
	})
	$("[name='register_submit_button']").click(function(){
		var kk = $("[name='REGISTER[EMAIL]']").val();
		$("[name='REGISTER[LOGIN]']").val(kk);
	})
	
	
	
	$("#select_client").change(function(){

		if($(this).val()=="N"){
			$("#kart_klient").hide();
		}
		else 	
			$("#kart_klient").show();
	});
	
	$("[name='phone'], .phone input").mask("+7 (999) 999-99-99");
	
	$(".head_cart a, .head_auth a, .tocompare, .tofavor").wrapInner("<span></span>");
	
	$(".rate .star").click(function(){
        $(".rate .star.active").removeClass('active');
		$(this).addClass("active");
        var elem = $(this).prev();
        while(elem.hasClass('star')){
            elem.addClass("active");
            elem = elem.prev();
        }
        
        $(".rate input").val($(".rate .star.active").length);
        $("#review_ocenka").val($(".rate .star.active").length);
        
		return false;
	})


	$(".leftMenu a").click(function(){
		if(!$(this).parent().hasClass("selected")){
			$(this).parent().addClass("selected");
			$(".leftMenu .selected > ul").show();
		}
		else{
			$(this).parent(".selected").children("ul").hide();
			$(this).parent().removeClass("selected");
		}
		if($(this).parent().hasClass("parent")) return false;
	})

/*
	$(".leftMenu > ul > li > a").click(function(){
		if(!$(this).parent().hasClass("selected")){
			$(this).parent().addClass("selected");
			$(".leftMenu > ul > li.selected > ul").show();
		}
		else{
			$(this).parent(".selected").children("ul").hide();
			$(this).parent().removeClass("selected");
		}
		if($(this).parent().hasClass("parent")) return false;
	})

	$(".leftMenu > ul > li > ul >li a").click(function(){
		if(!$(this).parent().hasClass("selected")){
			$(this).parent().addClass("selected");
			$(".leftMenu > ul > li> ul > li.selected > ul").show();
		}
		else{
			$(this).parent(".selected").children("ul").hide();
			$(this).parent().removeClass("selected");
		}
		if($(this).parent().hasClass("parent")) return false;
	})



*/
/*
	$(".vkladki a").click(function(){
		$(".vklada").hide();
		$(".vkladki a").removeClass("active");
		$($(this).attr("href")).show();
		$(this).addClass("active");
        $('html, body').animate({ scrollTop: $('.vkladki').eq(0).offset().top }, 500); 
        //$('html, body').animate({ scrollTop: $($(this).attr('href')).offset().top }, 500); 
		return false;
	})
	*/

	$(".vkladki a").click(function(){
		$(".vklada").hide();
		$(".vkladki a").parent().removeClass("tab-menu__item_active");
		$(this).parent().removeClass('tab-menu__item_reg-hover')
                .removeClass('tab-menu__item_reg-hover-clean-l')
                .removeClass('tab-menu__item_reg-hover-clean-r');
		$($(this).attr("href")).show();
		$(this).parent().addClass("tab-menu__item_active");
        $('html, body').animate({ scrollTop: $('.vkladki').eq(0).offset().top }, 500); 
		jumping_bugfix();
		return false;
	})
    // jumping tabs bugfix
    function jumping_bugfix() {
        var active = $('.tab-menu__item_active');
        console.log('active tab', active.index() + 1);
        $('.tab-menu__item').removeAttr('style');
        active.index() == 2 || active.index() == 3? active.prev().css('margin-left','-68px') : false;
        active.index() == 3 ? active.prev().prev().css('margin-left','-68px') : false;
    }
    $('.tab-menu__item').ready(jumping_bugfix());
    	
    //клик по ссылке, рядом с картинкой, кол-во отзывов
    $('.stars a[href="#vk2"], .addresp a[href="#vk2"]').click(function(){
        $(".vkladki a[href='#vk2']").click();
        return false;
    });

	$(".username").click(function(){
		$(".dropmenu").slideToggle();
		return false
	});
	
	$(".example-pager span").html(" ");
	  
	$("#back-top").hide();
	
	$(window).scroll(function() {
	     if ($(this).scrollTop() > 100) {
	         $('#back-top').fadeIn();
	     } else {
	         $('#back-top').fadeOut();
	     }
	 });
	
	 // scroll body to 0px on click
	 $('#back-top a').click(function() {
	     $('body,html').animate({
	         scrollTop: 0
	     }, 800);
	     return false;
	 });
 
	
	$("#fixs").click(function(){
		$(this).parent().toggleClass("spiskom");
	})
	
    //, 
	$('input[type="radio"], input[type="checkbox"], select').styler({
		selectSearch: true
	});	
       
});	