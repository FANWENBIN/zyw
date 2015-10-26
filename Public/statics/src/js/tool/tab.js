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
    initUlList()
    autoTab();
  };
  function tabBanner(){
    clearTimeout(scope.timer)
    scope.now = $(this).index();
    $(".dotlist li").removeClass("active");
    $(this).addClass("active");
    $(".imglist").stop().animate({
      "left": -(scope.now * $(".imglist li").width()) + "px"
    }, 2000,function(){
      autoTab();
    });

  }
  function autoTab(){
    scope.timer = setInterval(function(){
      scope.now++;

      $(".imglist").stop().animate({
        "left": -(scope.now * $(".imglist li").width()) + "px"
      }, 2000,function(){
          if(scope.now == 0){
            $(".imglist").css({"left": 0});
          }
      });
      if(scope.now == $(".imglist").find("li").length/2)scope.now = 0;
      $(".dotlist").find("li").removeClass("active")
      $(".dotlist").find("li").eq(scope.now).addClass("active")
    },5000)
  }
  function initUlList() {
    var _html = $(".imglist").html();
    $(".imglist").html( _html + _html );
    $(".imglist").width($(".imglist").width()*$(".imglist li").length + "px");

  }



})
