$(function(){
  var scope = {

  };
  var page = {
    init: function(){
      initRadio("sex");
      $(".sublist").on("click","li",page.leftClick);
      $(".group").on("click","li",page.upClick);
    },
    leftClick: function(){
      $(this).parent().find("li").removeClass("active");
      $(this).addClass("active");
      $(this).parents(".bottomitem").find(".item").removeClass("active");
      $(this).parents(".bottomitem").find(".item").eq($(this).index()).addClass("active");
    },
    upClick: function(){
      $(this).parent().find("li").removeClass("active");
      $(this).addClass("active");
      $(".list").find(".bottomitem").removeClass("active");
      $(".list").find(".bottomitem").eq($(this).index()).addClass("active");
    }
  };
  page.init(); 
})
