$(function(){
  var scope = {
    followId : 0
  };
  var page = {
    init: function(){
      $(".comment").on("click",".itemreplybutton",page.replycomment);
      $(".comment").on("click",".replybutton",page.subreply);
      $("#mainreply").on("click",page.submit);
      $(".inputsection form").on("submit",page.subSubmit)
    },
    subSubmit: function(){
      var _this = this;
      $.ajax({
        type: "post",
        url: "./index.php?m=Home&c=Rice&a=comment",
        data: {
          fid: $(_this).data("id"),
          postid: $(".webmain").data("id"),
          content: $(".replytext").val()
        },
        dataType: "json",
        success: function(json){
          if(json.status === 0){
            console.log(json.msg);
            window.location.reload()
          }else if(json.status === 101){
            console.log(json.msg);
            alert("发布失败，请稍后再试")
          }else if(json.status === 102){
            console.log(json.msg);
            alert("请登录或稍后再试")
          }
        },
        error: function(){

        }
      })
      return false;
    },
    submit: function(){
      $.ajax({
        type: "post",
        url: "./index.php?m=Home&c=Rice&a=comment",
        data: {
          fid: 0,
          postid: $(".webmain").data("id"),
          content: $("#mainreplycontent").val()
        },
        dataType: "json",
        success: function(json){
          if(json.status === 0){
            console.log(json.msg);
            window.location.reload()
          }else if(json.status === 101){
            console.log(json.msg);
            alert("发布失败，请稍后再试")
          }else if(json.status === 102){
            console.log(json.msg);
            alert("请登录或稍后再试");
          }
        },
        error: function(){

        }
      })
    },
    replycomment: function(){
      if($(this).parents(".outeritem").find(".inputsection").css("display") === "none"){
      $(this).parents(".outeritem").find(".inputsection").show().find(".replytext")[0].focus();
      $(this).parents(".outeritem").find(".replytext").val("");
    }else{
      $(this).parents(".outeritem").find(".inputsection").hide();
      $(this).parents(".outeritem").find(".replytext").val("");
    }
    },
    subreply: function(){
      $(this).parents(".outeritem").find(".replytext").val("@"+$(this).parents(".itemreply").data("name")+" ")
      $(this).parents(".outeritem").find(".inputsection").show().find(".replytext")[0].focus();

    }

  };
  page.init();
})
