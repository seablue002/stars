$(function () {
  // 客户案例
  $('#case-list-banner').owlCarousel({
    margin: 20,
    items:3,
    itemsDesktop: [1199,2],
    itemsDesktopSmall: [980,2],
    itemsMobile: [600,1],
    pagination: false,
    navigationText: false,
    autoPlay: false,
    navigation: true,
    navigationText: ['上一页', '下一页'],
    stopOnHover: true,
    autoHeight:true
  });

  // 客户案例case-info动效
  $('#case-list-banner .item').hover(function () {
    $(this).find('.case-info').css({ bottom: 0 })
    $(this).find('.case-info').addClass('animate__animated animate__fadeInUp')
  }, function () {
    $(this).find('.case-info').removeClass('animate__fadeInUp')
    $(this).find('.case-info').animate({ bottom: -50 }, 350, 'linear')
  })

  // 资讯动态tab切换
  $('#news-list-banner').owlCarousel({
    margin:0,
    lazyLoad: true,
    items: 1,
    itemsDesktop: [1199, 1],
    itemsDesktopSmall: [980, 1],
    itemsMobile: [600, 1],
    pagination: false,
    navigationText: false,
    autoPlay: false,
    navigation: false,
    stopOnHover: true,
    autoHeight:true,
    addClassActive: true,
    afterMove: function () {
      addActiveClassToTabNav()
    }
  });
  var $newsBanner = $('#news-list-banner').data('owlCarousel')

  function switchTabNav () {
    $('#news-tab .news-tab-header li').click(function () {
      var curIdx = $(this).index()
      $newsBanner.goTo(curIdx)
    })
  }
  switchTabNav()

  function addActiveClassToTabNav () {
    var curIdx = $('#news-list-banner .owl-item.active').index()
    
    $('#news-tab .news-tab-header li').eq(curIdx).addClass('active').siblings().removeClass('active')
  }
});