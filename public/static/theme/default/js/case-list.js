$(function () {
  // 客户案例case-info动效
  $('#case-list-banner .item').hover(function () {
    $(this).find('.case-info').css({ bottom: 0 })
    $(this).find('.case-info').addClass('animate__animated animate__fadeInUp')
  }, function () {
    $(this).find('.case-info').removeClass('animate__fadeInUp')
    $(this).find('.case-info').animate({ bottom: -52 }, 350, 'linear')
  })
})
