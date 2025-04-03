/*首页产品目录第一个默认展开*/
$(function(){ $(".cp_type .bd ul li").first().children('div').show();$(".cp_type .bd ul li").first().addClass('on');});
$(".cp_type .bd ul li p span").click(function () {
	if($(this).parents('li').hasClass('on')){
		$(this).parents('li').removeClass('on').find('div').stop().slideUp();
	}else{
		$(this).parents('li').find('div').removeAttr("style");
		$(this).parents('li').addClass('on').find('div').stop().slideDown();
	}
});
if(document.body.clientWidth <= 1079){  
	$(".cp_type .hd").click(function () {
		if($(this).hasClass('on')){
			$(this).next('div').removeAttr("style");
			$(this).removeClass('on').next('div').stop().slideUp();
		}else{
			$(this).next('div').removeAttr("style");
			$(this).addClass('on').next('div').stop().slideDown();
		}
	});
}
