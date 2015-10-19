$(function() {

  var scope = {
    now: 0,
    timer: null
  }

  var page = {
    init: function() {
      $("#banner").on("click", ".dotlist li", page.tabBanner);
      page.autoTab();

    },
    tabBanner: function() {
      scope.now = $(this).index();
      $(".dotlist li").removeClass("active");
      $(this).addClass("active");
      $(".imglist").stop().animate({
        "left": -(scopte.now * $(".imglist li").width()) + "px"
      }, 2000);

    },
    autoTab: function(){
      scope.timer = setInterval(function(){
        scope.now++;
        if(scope.now == $(".imglist").find("li").length)scope.now = 0;
        $(".imglist").stop().animate({
          "left": -(scope.now * $(".imglist li").width()) + "px"
        }, 2000);
        $(".dotlist").find("li").removeClass("active")
        $(".dotlist").find("li").eq(scope.now).addClass("active")
      },5000)
    }
  }
  page.init();
})
