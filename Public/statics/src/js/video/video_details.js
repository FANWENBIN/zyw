$(function(){
  var scope = {
    userdata: {}
  }
  var page = {
    init: function(){
      $.testLogin(function(data){
        scope.userdata = data;
      });
      // 提交时
      $("#J_CommentSendbox .submit").on("click",page.addComments);
    },
    addComments: function(){
      console.log(scope.userdata);
      var _content = $("#J_CommentSendbox textarea").val();
      $("#J_CommentList").prepend('<div class="item clearFix">'+
        '<div class="head">'+
          '<img src="'+ scope.userdata.headpic +'">'+
        '</div>'+
        '<div class="info">'+
          '<span>'+ scope.userdata.nickname +' 发表日期：'+ new Date().toLocaleString() +'</span>'+
          '<p>'+ _content +'</p>'+
        '</div>'+
      '</div>')
      $("#J_CommentList .item").eq(5).remove();
      // 提交内容
    }
  }
  page.init()

})
