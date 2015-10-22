$(function() {


  var page = {
    init: function() {

      $("#J_conTopStars .select a").click(page.clickTopStars);
    },

    clickTopStars: function() {
      $(this).parent().find("a").removeClass("c");
      $(this).addClass("c");
      $(this).parents(".group").find(".list").hide();
      $(this).parents(".group").find(".list").eq($(this).index()).show()
    }
  };
  page.init();
});
