$(function(){
  var scope = {

  }
  var page = {
    init: function(){
      tabinit();
      $("#group").on("click","li",page.changeGroup);
    },
    changeGroup: function(){
      $("#group").find("li").removeClass("active");
      $(this).addClass("active");
      $(".video .innergroup").removeClass("active");
      $(".video .innergroup").eq($(this).index()).addClass("active");
    }
  };
  page.init()
})
