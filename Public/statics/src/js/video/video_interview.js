$(function(){
  var scope = {
    group: "new",
    pageNum: "1"
  }
  var page = {
    init: function(){
      $("#group").on("click","li",page.changeGroup);
      //分页
      page.getPage(function(){
        pageInit(parseInt(scope.pageNum),10,function(index){
          //回调，刷新内容页
          if(index == 0)return false;
          page.getData(index);
        });
      });
    },
    changeGroup: function(){
      $("#group").find("li").removeClass("active");
      $(this).addClass("active");
      scope.group = $(this).data("group");
      page.getPage(function(){
        pageInit(parseInt(scope.pageNum),10,function(index){
          //回调，刷新内容页
          if(index == 0)return false;
          page.getData(index);
        });
      });

    },
    getPage: function(fn){
      $.ajax({
        url: "./index.php?m=Home&c=Video&a=vial",
        type: "get",
        dataType: "json",
        data: {
          type: 7,
          p: 1,
          condition: scope.group
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
        url: "./index.php?m=Home&c=Video&a=vial",
        type: "get",
        dataType: "json",
        data: {
          type: 7,
          p: 1,
          condition: scope.group
        },
        success: function(json){
          var _arr = json.data.data;
          var _html = "";
          if(json.data.page == 0) {
            $("#itemgroup").html(_html);
            return false;
          }
          for(var i = 0, len = _arr.length; i < len; i++ ){
            _html += '<a href="./index.php?m=Home&c=Video&a=video_details&id='+  _arr[i].id +'" class="item">'+
                '<img src="./Uploads'+ _arr[i].bigimg +'" alt="" />'+
                '<div class="text">'+
                  '<h1>' + _arr[i].title + '</h1>'+
                  '<h3>' + _arr[i].instime + '</h3>'+
                '</div>'+
                '<sub></sub>'+
            '</a>'
          }
          $("#itemgroup").html(_html);
        },
        error: function(){
        }

      })
    }
  };
  page.init()
})
