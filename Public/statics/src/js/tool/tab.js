$(function(){
  var scope = {
    now: 0,
    timer: null
  };


/*

<div class="banner" id="banner">
  <ul class="imglist">
    <li class="active"><a href="#"><img src="#" alt="1" /></a></li>
    <li><a href="#"><img src="#" alt="2" /></a></li>
    <li><a href="#"><img src="#" alt="3"  /></a></li>
    <li><a href="#"><img src="#" alt="4"  /></a></li>
  </ul>
  <ul class="dotlist">
    <li class="active"></li>
    <li></li>
    <li></li>
    <li></li>
  </ul>
</div>

*/
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
