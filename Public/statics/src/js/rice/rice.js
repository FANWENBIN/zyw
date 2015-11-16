$(function(){
  var scope = {

  };
  var page = {
    init:function(){
      $("#community .group").on("click","li",page.tabCommunity);

      tabinit();
    },
    
    tabCommunity: function(){
      var _index = $(this).index();
      $("#community .group").find("li").removeClass("active")
      $(this).addClass("active");
      $("#community .inner").find("li").hide();
      $("#community .inner").find("li").eq(_index).show();
    }
  };
  page.init();

})
