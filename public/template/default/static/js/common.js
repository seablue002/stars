$(function() {
	//返回顶部
	$(window).scroll(function() {
		var scrollTop = document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop;
		var eltop = $(".f_pf1").find(".totop");
		if (scrollTop > 0) {
			eltop.show();
		} else {
			eltop.hide();
		}
	});
	$(".f_pf1").find(".totop").click(function() {
		var scrollTop = document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop;
		if (scrollTop > 0) {
			$("html,body").animate({
				scrollTop: 0
			}, "slow");
		}
	});
	$(".gotop").find(".totop").click(function() {
		var scrollTop = document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop;
		if (scrollTop > 0) {
			$("html,body").animate({
				scrollTop: 0
			}, "slow");
		}
	});
});