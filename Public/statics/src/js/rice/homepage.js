$(function(){
  var scope = {

  };
  var page = {
    init: function(){
      $(".join").on("click",page.join)
    },
    join: function(){
      $.ajax({
        type: "get",
        url: "./index.php?m=Home&c=Rice&a=joinfans",
        data: {
          fansid: $(".webmain").data("id")
        },
        dataType: "json"
        success: function(json){
          if(json.status === 0){
            cosole.log(json.msg)
            $(".join").html("已入团")
          }else if(json.status === 101){
            cosole.log(json.msg)
            alert("加入失败，请稍后再试")
          }else if(json.status === 102){
            cosole.log(json.msg)
            alert("加入失败，请稍后再试")
          }else if(json.status === 103){
            cosole.log(json.msg)
            alert("加入失败，请稍后再试")
          }else if(json.status === 104){
            cosole.log(json.msg)
          }
        },
        error: function(){

        }
      })
    }
  };
  page.init();
})
