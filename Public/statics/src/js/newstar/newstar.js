$(function() {
  var scope = {

  };
  var page = {
    init: function() {
      tabinit();
      $("#hot").on("click",page.showHot);
      $("#new").on("click",page.showNew);
      $("#hotcouse").on("click","li",page.tabHotCouse)
    },
    showHot: function(){
      $(this).addClass("active");
      $("#hotlist").addClass("active");
      $("#new").removeClass("active");
      $("#newlist").removeClass("active");
    },
    showNew: function(){
      $(this).addClass("active");
      $("#newlist").addClass("active");
      $("#hot").removeClass("active");
      $("#hotlist").removeClass("active");
    },
    tabHotCouse: function(){
      $("#hotcouse").find("li").removeClass("active");
      $(this).addClass("active");
      $("#hotcousegroup").find("li").removeClass("active");
      $("#hotcousegroup").find("li").eq($(this).index()).addClass("active");
    }

  };
  page.init();
})
