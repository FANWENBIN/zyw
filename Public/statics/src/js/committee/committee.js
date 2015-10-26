/**
 * Created by admin on 2015/10/10.
 */

$(function(){
  var scope = {
    type: "redcom",
    totalpage: 0
  }
  var page = {
    init:function(){
      $("#typelist").off().on("click","li",page.tabGroup);
      page.getTotalPage(function(){
        pageInit(scope.totalpage, 12,function(index){
          page.getData(index);
        })
      });
    },
    tabGroup: function(){
      $("#typelist").find("li").removeClass("active");
      $(this).addClass("active");
      scope.type = $(this).data("type");
      $("#typelist").on("click","li",page.tabGroup);
      page.getTotalPage(function(){
        pageInit(scope.totalpage, 12,function(index){
          page.getData(index);
        })
      });
    },
    getData: function(index){
      $.ajax({
        url: "./index.php?m=Home&c=Committee",
        type: "get",
        dataType: "json",
        data: {
          a: scope.type,
          p: index
        },
        success: function(json){
          //写数据
          var _arr = json.data.data;
          var _html = '';
          for(var i= 0,len = _arr.length ; i < len; i++){
            _html += '<div class="item">'
              +'<a href="#" class="pic">'
                +'<img src="./Uploads'+ _arr[i].img +'" alt="" />'
                +'<div class="text">'
                  +'<h3>'+ _arr[i].title +'</h3>'
                  +'<p>'+ _arr[i].instime +'</p>'
                +'</div>'
                +'<sub></sub>'
              +'</a>'
            +'</div>'
          }
          $("#typegroup").html(_html);
        },
        error: function(){

        }
      })
    },
    getTotalPage: function(fn){
      $.ajax({
        url: "./index.php?m=Home&c=Committee",
        type: "get",
        dataType: "json",
        data: {
          a: scope.type,
          p: 1
        },
        success: function(json){
          scope.totalpage = parseInt(json.data.page);
          fn();
        },
        error: function(){

        }
      })
    }
  };
  page.init();
})
