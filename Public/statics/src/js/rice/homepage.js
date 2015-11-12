$(function(){
  var scope = {

  };
  var page = {
    init: function(){
      $(".join").on("click",page.join);
      $(".submitmessage").on("click",page.submit);
      page.testJoin()
    },
    submit: function(){
      // 取得HTML内容
      var html = editor.html();

      // 同步数据后可以直接取得textarea的value
      editor.sync();
      html = $('#editor_id').val(); // jQuery
      var title = $(".articletitle").val();
      console.log(title,html);
      $.ajax({
        type: "post",
        url: "./index.php?m=Home&c=Rice&a=postini",
        data: {
          fansclubid: $(".webmain").data("id"),
          title: title,
          content: html
        },
        dataType: "json",
        success: function(json){
          if(json.status === 0){
            console.log(json.msg);
            window.location.reload();
          }else if(json.status === 101){
            console.log(json.msg);
            alert("发送失败，请稍后再试");
          }else if(json.status === 102){
            console.log(json.msg);
            alert("请填写全相关信息");
          }
        },
        error: function(){

        }

      })
    },
    join: function(){
      $.ajax({
        type: "get",
        url: "./index.php?m=Home&c=Rice&a=joinfans",
        data: {
          fansid: $(".webmain").data("id")
        },
        dataType: "json",
        success: function(json){
          if(json.status === 0){
            console.log(json.msg)
            $(".join").html("已入团")
          }else if(json.status === 101){
            console.log(json.msg)
            alert("加入失败，请稍后再试")
          }else if(json.status === 102){
            console.log(json.msg)
            alert("加入失败，请稍后再试")
          }else if(json.status === 103){
            console.log(json.msg)
            alert("加入失败，请稍后再试")
          }else if(json.status === 104){
            console.log(json.msg)
          }
        },
        error: function(){

        }
      })
    },
    testJoin: function(){
      $.ajax({
        type: "get",
        url: "./index.php?m=Home&c=Rice&a=checkjoin",
        data: {
          fansid: $(".webmain").data("id")
        },
        dataType: "json",
        success: function(json){
          if(json.status === 0){
            $(".join").html("已入团")
            console.log(json.msg)
          }else if(json.status === 1){
            $(".join").html("+ 入团")
            console.log(json.msg)
          }
        },
        error: function(){

        }
      })
    }
  }
  page.init();
})
