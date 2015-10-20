$(function(){
  var scope = {
    now: 0,
    timer: null
  };

window.tabinit = function() {
    $("#banner").on("click", ".dotlist li", tabBanner);
    autoTab();
  };
  function tabBanner(){
    scope.now = $(this).index();
    $(".dotlist li").removeClass("active");
    $(this).addClass("active");
    $(".imglist").stop().animate({
      "left": -(scope.now * $(".imglist li").width()) + "px"
    }, 2000);

  }
  function autoTab(){
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



})
