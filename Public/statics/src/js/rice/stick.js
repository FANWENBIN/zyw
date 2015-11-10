$(function(){
  var scope = {

  };
  var page = {
    init: function(){
      $(".comment").on("click",".itemreplybutton",page.reply);
      $(".comment").on("click",".replybutton",page.subreply)
    },
    reply: function(){
      $(this).parents(".outeritem").find(".inputsection").show().find(".replytext")[0].focus();
    },
    subreply: function(){
      $(this).parents(".outeritem").find(".replytext").val("@"+$(this).parents(".itemreply").data("name")+" ")
      $(this).parents(".outeritem").find(".inputsection").show().find(".replytext")[0].focus();
    }

  };
  page.init();
})
