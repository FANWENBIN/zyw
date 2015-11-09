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
      var _content = $("#J_CommentSendbox textarea").val();
      $.ajax({
        type: "post",
        dataType: "json",
        url: "./index.php?m=Home&c=Comment&a=addcomment",
        data: {
          content: _content,
          href: window.location.href,
          pagename: $(".videoname").html(),
        },
        success: function(json){
          if(json.status === 0){
            $("#J_CommentList").prepend('<div class="item clearFix">'+
              '<div class="head">'+
                '<img src="./Uploads'+ scope.userdata.headpic +'">'+
              '</div>'+
              '<div class="info">'+
                '<span>'+ scope.userdata.nickname +' 发表日期：'+ new Date().toLocaleString() +'</span>'+
                '<p>'+ _content +'</p>'+
              '</div>'+
            '</div>')
            $("#J_CommentList .item").eq(5).remove();
          }else if(json.status === 101){
            console.log(json.msg)
          }else if(json.status === 102){
            console.log(json.msg)
          }else if(json.status === 105){
            console.log(json.msg)
          }
        },
        error: function(){
        }
      })

      // 提交内容
    }
  }
  page.init()

})
