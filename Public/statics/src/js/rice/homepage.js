$(function() {
  var scope = {
    isJoin: false
  };
  var page = {
    init: function() {
      $(".join").on("click", page.join);
      $(".join").on("mouseenter", page.joinEnter);
      $(".join").on("mouseout", page.joinOut);
      $(".submitmessage").on("click", page.submit);
      page.testJoin()
    },
    submit: function() {
      // 取得HTML内容
      var html = editor.html();

      // 同步数据后可以直接取得textarea的value
      editor.sync();
      html = $('#editor_id').val(); // jQuery
      var title = $(".articletitle").val();
      console.log(title, html);
      // if(scope.isJoin){
      if (!html || !title) {
        alert("请确认该贴标题或内容是否填写！");
        return false;
      }
      $.ajax({
          type: "post",
          url: "./index.php?m=Home&c=Rice&a=postini",
          data: {
            fansclubid: $(".webmain").data("id"),
            title: title,
            content: html
          },
          dataType: "json",
          success: function(json) {
            if (json.status === 0) {
              console.log(json.msg);
              window.location.reload();
            } else if (json.status === 101) {
              console.log(json.msg);
              alert("发送失败，请稍后再试");
            } else if (json.status === 102) {
              console.log(json.msg);
              alert("请先登录后再发帖");
            }
          },
          error: function() {

          }

        })
        // }else{
        //   alert("请先登陆并加入饭团");
        // }
    },
    joinEnter: function() {
      if (scope.isJoin) {
        $(this).html("退团")
      }else{
      }
    },
    joinOut: function(){
      if(scope.isJoin){
        $(this).html("已入团")
      }else{
      }
    },
    release: function(){
      $.ajax({
        type: "get",
        url: "./index.php?m=Home&c=Rice&a=quitfans",
        data: {
          fansid: $(".webmain").data("id")
        },
        dataType: "json",
        success: function(json){
            console.log(json.msg);
            if(json.status === 0){
              scope.isJoin = false;
              $(".join").html("+入团");
            }else if(json.status === 101){
              alert("系统错误，请稍后再试")
            }else if(json.status === 102 ){
              alert("饭团未关注")
            }
        },
        error: function(){
        }
      })
    },
    join: function() {
      if (scope.isJoin) {
        page.release()
      } else {
        $.ajax({
          type: "get",
          url: "./index.php?m=Home&c=Rice&a=joinfans",
          data: {
            fansid: $(".webmain").data("id")
          },
          dataType: "json",
          success: function(json) {
            if (json.status === 0) {
              console.log(json.msg);
              $(".join").html("已入团");
            } else if (json.status === 101) {
              console.log(json.msg)
              alert("加入失败，请稍后再试")
            } else if (json.status === 102) {
              console.log(json.msg)
              alert("请先登陆后再尝试")
            } else if (json.status === 103) {
              console.log(json.msg)
              alert("加入失败，请稍后再试")
            } else if (json.status === 104) {
              console.log(json.msg);
              $(".join").html("已入团");
            }
          },
          error: function() {
          }
        })
      }
    },
    testJoin: function() {
      $.ajax({
        type: "get",
        url: "./index.php?m=Home&c=Rice&a=checkjoin",
        data: {
          fansid: $(".webmain").data("id")
        },
        dataType: "json",
        success: function(json) {
          console.log(json.msg);
          if (json.status === 1) {
            scope.isJoin = true;
            $(".join").html("已入团");
          } else if (json.status === 0) {
            scope.isJoin = true;
            $(".join").html("+ 入团");
          }
        },
        error: function() {

        }
      })
    }
  }
  page.init();
})
