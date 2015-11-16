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
       'swf'      : 'public/statics/js/uploadify/uploadify.swf',
       'uploader' : 'public/statics/js/uploadify/uploadify.php',
       'buttonText' : '上传粉丝团封面图',
       'onUploadSuccess' : function(file) {
            console.log(file);
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
        url: "./index.php?m=Home&c=Rice&a=actors"
        dataType: "json",
        success: function(json){
          var _html = "";
          var _arr = json.data;
          for(var i = 0; len = _arr.length; i < len ; i++){
            _html　+= '<options value="'+ _arr[i].id +'">'+ _arr[i].name +'</options>'
          }
          $(".name").html(_html);
        },
        error: function(e){
          consolo.log(e)
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
      if(!/^.+$/.test($(":text[name=name]").val())){
        alert("请填入明星名字");
        return false;
      }
      $.ajax({
        type: "get",
        url: "",
        data: {

        },
        dataType: "json",
        success: function(){

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
                '<a href="/zyw/index.php?m=Home&c=Rice&a=homepage&id='+ _arr[i].id +'">我要报名</a>'+
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
