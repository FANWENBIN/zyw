$(function(){
  var scope = {

  };
  var page = {
    init: function(){
      // initRadio("sex");
      $(".sublist").on("click","li",page.leftClick);
      $(".group").on("click","li",page.upClick);
      // $("#changemb").on("click",".");

      // 删除消息
      $(document).on("mouseenter",".date",function(){
        $(this).data("content",$(this).html())
        $(this).html("删除");
      });
      $(document).on("mouseout",".date",function(){
        $(this).html($(this).data("content"));
      })
      $(".date").on("click",page.delMessage);
    },
    delMessage: function(){
      var _this = this;
      $.ajax({
        type: "get",
        url: "./index.php?m=Home&c=User&a=delmsg",
        data: {
          id: $(_this).data("id")
        }
        dataType: "json",
        success: function(json){
          console.log(json.msg);
          if(json.status === 0){
            $(this).parents(".subitem").remove();
          }else{
            alert("删除失败，请稍后再试")
          }
        },
        error: function(){
        }
      });
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
