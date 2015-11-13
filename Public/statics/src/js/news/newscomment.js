$(function(){
  var scope = {
    userdata: {},
    pageNum: 1
  }
  var page = {
    init: function(){
      // 获取用户信息
      $.testLogin(function(data){
        scope.userdata = data;
      });
      // 分页
      page.getPage(function(){
        pageInit(parseInt(scope.pageNum),10,function(index){
          //回调，刷新内容页
          console.log(index,1)
          if(index == 0)return false;
          page.getData(index);
        });
      });

      $("#J_CommentSendbox .submit").on("click",page.addComments);
    },
    getPage: function(fn){
      $.ajax({
        url: "./index.php?m=Home&c=Comment&a=commentlist",
        type: "get",
        dataType: "json",
        data: {
          type: 2,
          id: $.getId(),
          p: 1
        },
        success: function(json){
          scope.pageNum = json.data.page;
          fn();
        },
        error: function(){
        }
      })
    },
    getData: function(page){
      $.ajax({
        url: "./index.php?m=Home&c=Comment&a=commentlist",
        type: "get",
        dataType: "json",
        data: {
          type: 1,
          id: $.getId(),
          p: page
        },
        success: function(json){
          var _arr = json.data.data;
          var _html = "";
          if(json.data.page == 0) {
            $("#itemlist").html(_html);
            return false;
          }
          for(var i = 0, len = _arr.length; i < len; i++ ){
            _html += '<div class="itemcomment clearFix">'+
              '<div class="head">'+
                '<img src="./Uploads'+ _arr[i].namehead +'">'+
              '</div>'+
              '<div class="info">'+
                '<span>'+ _arr[i].name +' 发表日期：'+ _arr[i].instime +'</span>'+
                '<p>'+ _arr[i].content +'</p>'+
              '</div>'+
            '</div>'
          }
          $("#J_CommentList").html(_html);
        },
        error: function(){
        }
      })
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
            $("#J_CommentList").prepend('<div class="itemcomment clearFix">'+
              '<div class="head">'+
                '<img src="./Uploads'+ scope.userdata.headpic +'">'+
              '</div>'+
              '<div class="info">'+
                '<span>'+ scope.userdata.nickname +' 发表日期：'+ new Date().toLocaleString() +'</span>'+
                '<p>'+ _content +'</p>'+
              '</div>'+
            '</div>')
            $("#J_CommentList .itemcomment").eq(5).remove();
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
