$(function () {
  // 轮播图
  $('#owl-banner').owlCarousel({
    singleItem: true
  });

  // 页面滚动时，头部样式调整
  $(window).scroll(function () {
    var scrollTop = $(this).scrollTop();
    if (scrollTop > 0) {
      $(".header").addClass("header-scroll");
    } else {
      $(".header").removeClass("header-scroll");
    }
  });

  // 移动端菜单展示关闭切换
  $("#navbar-toggle").click(function () {
    $(this).toggleClass("open");
    $("#menu").slideToggle(300);
  });


  // 页面右侧固定联系方式、滚动到顶部
  $(document).on("click", "#goTop", function(){
    $("html,body").animate({scrollTop: 0});
  });
  $(window).scroll(function(){
    var st = $(document).scrollTop();
    var $top = $("#goTop");
    if(st > 400){
      $top.css({display: 'block'});
    }else{
      if ($top.is(":visible")) {
        $top.hide();
      }
    }
  });
});