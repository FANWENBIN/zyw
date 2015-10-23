$(function(){
  var scope = {

  };
  var page = {
    init: function(){
      tabinit();
      $("#judgelist").on("click","li",page.tabJudge);
    },
    tabJudge: function(){
      $("#judgelist").find("li").removeClass("active");
      $(this).addClass("active");
      $("#judgegroup").find("li").removeClass("active");
      $("#judgegroup").find("li").eq($(this).index()).addClass("active");
    }
  };
  page.init()

})
