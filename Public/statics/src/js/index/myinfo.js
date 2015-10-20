$(function(){
  var scope = {

  };
  var page = {
    init: function(){
      $(".sublist").on("click","li",page.leftClick)
    },
    leftClick: function(){
      $(this).parent().find("li").removeClass("active");
      $(this).addClass("active");
      $(this).parents(".bottomitem").find(".item").removeClass("active");
      $(this).parents(".bottomitem").find(".item").eq($(this).index()).addClass("active");
    }
  };
  page.init();
})
