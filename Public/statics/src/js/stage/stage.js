$(function(){
  var scope = {

  };
  var page = {
    init: function(){
      tabinit();
      $("#sortinglist").on("click","li",page.tabSorting)
    },
    tabSorting: function(){
      $("#sortinglist").find("li").removeClass("active");
      $(this).addClass("active");
      $("#sortinggroup").find("li").removeClass("active");
      $("#sortinggroup").find("li").eq($(this).index()).addClass("active");
    }
  };
  page.init();
})
