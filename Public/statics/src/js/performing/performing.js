$(function(){
  var scope = {

  };
  var page = {
    init: function(){
      $("#sorting").on("click","li",page.tabGroup)
    },
    tabGroup: function(){
      $("#sorting").find("li").removeClass("active");
      $(this).addClass("active");
      //回调 刷新列表
      console.log($(this).data("group"));
    }
  };
  page.init();
})
