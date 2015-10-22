$(function() {


  var page = {
    init: function() {

      $("#J_conTopStars .select a").click(page.clickTopStars);
			$("#eventlist").on("click","li",page.tabEvent);
			$("#videolist").on("click","li",page.tabVideo);
    },

    clickTopStars: function() {
      $(this).parent().find("a").removeClass("c");
      $(this).addClass("c");
      $(this).parents(".group").find(".list").hide();
      $(this).parents(".group").find(".list").eq($(this).index()).show()
    },
		tabEvent: function(){
			$("#eventlist").find("li").removeClass("c");
			$(this).addClass("c");
			$("#eventgroup").find("li").removeClass("active");
			$("#eventgroup").find("li").eq($(this).index()).addClass("active");
		},
		tabVideo: function(){
			$("#videolist").find("li").removeClass("c");
			$(this).addClass("c");
			$("#videogroup").find(".list").removeClass("active");
			$("#videogroup").find(".list").eq($(this).index()).addClass("active");
		}
  };
  page.init();
});
