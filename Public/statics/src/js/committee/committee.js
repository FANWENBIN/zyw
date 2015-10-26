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
      $("#typelist").on("click","li",page.tabGroup);
      // page.getTotalPage(function(){
      //   initPage(parseInt(scope.totalpage), function(index){
      //     page.getData(index);
      //   })
      // });
    },
    tabGroup: function(){
      $("#typelist").find("li").removeClass("active");
      $(this).addClass("active");
      scope.type = $(this).data("type");
      // page.getTotalPage(function(){
      //   initPage(parseInt(scope.totalpage), function(index){
      //     page.getData(index);
      //   })
      // });
    },
    getData: function(index){
      $.ajax({
        url: "./index.php?m=Home&c=Committee",
        type: "get",
        dataType: "json",
        data: {
          a: scope.type,
          p: page
        },
        success: function(json){
          //写数据
          var _arr = json.data.data;
          for(var i= 0,len = _arr.length ; i < len; i++){
            
          }
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
          scope.totalpage = json.data.page;
          fn();
        },
        error: function(){

        }
      })
    }
  };
  page.init();
})
