/**
 * Created by admin on 2015/10/10.
 */

$(function(){
  var page = {
    init:function(){
      $("#group").on("click","li",page.tabGroup);
    },
    tabGroup: function(){
      $("#group").find("li").removeClass("active");
      $(this).addClass("active");
    }
  };
  page.init();
})
