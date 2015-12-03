$(function(){
  var scope = {
    type: "fanssum",
    pageNum: 1,
    imgUrl: ""
  };
  var page = {
    init: function(){
      $(".group").on("click","li",page.tab);
      $(".creatRice").on("click",page.showCreateRice);
      $(".create-mask form").on("submit",page.formSubmit);
      $(".close").on("click",page.closeCreateRice);
      $(".confirm").on("click",page.closeConfirm);
      $('#file_upload').uploadify({
       'swf'      : './Public/statics/js/uploadify/uploadify.swf',
       'uploader' : './Public/statics/js/uploadify/uploadify.php',
       'buttonText' : '上传粉丝团封面图',
       'onUploadSuccess' : function(file, data, response) {
            console.log(file,data,response);
            var str = data.match(/\.\\\/Uploads.+"/)[0];
            scope.imgUrl = str.substring(0,str.length-1);
        }
       // Put your options here
      });
      page.getPage(function(){
        pageInit(scope.pageNum,10,function(index){
          page.getData(index);
        });
      });
      page.getActor();
    },
    getActor: function(){
      $.ajax({
        type: "post",
        url: "./index.php?m=Home&c=Rice&a=actors",
        dataType: "json",
        success: function(json){
          console.log(json)
          var _html = "";
          var _arr = json.data;
          for(var i = 0, len = _arr.length; i < len ; i++){
            _html　+= '<option value="'+ _arr[i].id +'">'+ _arr[i].name +'</option>'
          }
          $(".actorname").html(_html);
        },
        error: function(e){
          console.log(e)
        }
      })
    },
    closeConfirm: function(){
      $(this).parent().parent().hide();
    },
    closeCreateRice: function(){
      $(this).parent().parent().hide();
    },
    formSubmit: function(){
      console.log($(":text[name=name]").val())
      if(!/^.+$/.test($(":text[name=ricename]").val())){
        alert("请填入饭团名字");
        return false;
      }else if(!scope.imgUrl){
        alert("请上传饭团图片")
      }
      $.ajax({
        type: "post",
        url: "./index.php?m=Home&c=Rice&a=createfans",
        data: {
          actorid: $(".actorname").find("option:selected").val(),
          name: $(":text[name=ricename]").val(),
          img: scope.imgUrl
        },
        dataType: "json",
        success: function(json){
          console.log(json.msg)
          if(json.status === 0){
            $(".create-mask").hide();
            $(".done-mask").show();
          }else if(json.status === 103){
            alert("未登录，请登陆后尝试");
          }else{
            alert("服务器错误，请稍后再试");
          }
        },
        error: function(){

        }
      })
      return false;
    },
    showCreateRice: function(){
      $(".create-mask").show();
    },
    tab: function(){
      scope.type = $(this).data("type");
      $(".group li").removeClass("active");
      $(this).addClass("active");
      page.getPage(function(){
        pageInit(scope.pageNum,10,function(index){
          page.getData(index);
        });
      });

    },
    getPage: function(fn){
      $.ajax({
        type: "get",
        url: "./index.php?m=Home&c=Rice&a=morefans",
        data: {
          condition: scope.type,
          p: 1
        },
        dataType: "json",
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
        url: "./index.php?m=Home&c=Rice&a=morefans",
        type: "get",
        dataType: "json",
        data: {
          condition: scope.type,
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
            _html += '<div class="item">'+
              '<img src="'+ _arr[i].img +'" alt="" />'+
              '<p>'+ _arr[i].name +'</p>'+
              '<div class="cover">'+
                '<div class="info">'+
                  '粉丝数 '+ _arr[i].fanssum +'&nbsp;&nbsp;贴&nbsp;量 '+ _arr[i].posts +
                '</div>'+
                '<br/>'+
                '<a href="./zyw/index.php?m=Home&c=Rice&a=homepage&id='+ _arr[i].id +'">我要报名</a>'+
              '</div>'+
            '</div>'
          }
          $("#itemlist").html(_html);
        },
        error: function(){
        }
      })
    }
  }
  page.init();
})
